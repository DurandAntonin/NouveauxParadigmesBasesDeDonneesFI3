<?php

require_once "..\..\BaseDonneesRelationnelle\PHP\PostgreSQL.php";
require_once "CollectionLignes.php";
require_once "CollectionTrips.php";
require_once "Lignes.php";
require_once "Trips.php";
require_once "Arret.php";

set_time_limit(0);

//PATH TO JSON DATA OUTPUT
$pathDataJson = "../DATA/";
$filenameCollectionLignes = "collectionLignes.json";
$filenameCollectionTrips = "collectionTrips.json";

$postgreSql = new PostgreSQL();
$postgreSql->connectToPostgreSQL("database_reseaux_transports", "user_bd_reseaux_transport", "azerty");

//on va exécuter une requete au serveur postgres pour sélectionner chaque ligne, et l'insérer dans un fichier json

//on ouvre le fichier des lignes à la fin en mode lecture et écriture
$curseur = fopen("{$pathDataJson}$filenameCollectionLignes", "w+");

$listeLignes = $postgreSql->executeQuery("select route_id, route_short_name, route_long_name from small_routes");

//on créé un objet CollectionLignes pour stocker chaque ligne
$collectionLignes = new CollectionLignes();

while ($row = pg_fetch_row($listeLignes)) {
    //on créé un objet Lignes pour chaque ligne retournée
    $ligne = new Lignes($row[0], $row[1], $row[2]);

    $collectionLignes->addLigne($ligne);
}
//on insère ce string json dans le fichier
fputs($curseur, $collectionLignes->serialize());
fclose($curseur);

$i = 1;
$j = 1;

//on exécute une premiere requete pour sélectionner chaque train (chaque train a un horaire de début différent)
$listeTrips = $postgreSql->executeQuery("select route_id, trip_id, trip_headsign from small_trips");

//on ouvre le fichier des trips à la fin en mode lecture et écriture
$curseur = fopen("{$pathDataJson}{$filenameCollectionTrips}_{$i}", "w+");

//on créé un objet CollectionLignes pour stocker chaque ligne
$collectionTrips = new CollectionTrips();

while ($row = pg_fetch_row($listeTrips)) {
    if ($i == 1000){
        //on insère ce string json dans le fichier
        fputs($curseur, $collectionTrips->serialize());
        fclose($curseur);

        $i = 1;
        $j++;

        $curseur = fopen("{$pathDataJson}{$filenameCollectionTrips}_{$j}", "w+");
        $collectionTrips = new CollectionTrips();

    }

    //on stocke les info de chaque train
    $routeId = $row[0];
    $tripId = $row[1];
    $tripHeadsign = $row[2];

    //pour chaque train, i.e tripId, on effectue une requete pour sélectionner les différents arrets desservis par ce dernier
    //echo $tripId;
    $listeArretsTrips = $postgreSql->executeQuery("select trip_id, stop_id, arrival_time, departure_time from small_stop_times where trip_id = '$tripId' order by stop_sequence asc");

    //on créé un objet trip pour chaque train
    $trips = new Trips($tripId, $routeId, $tripHeadsign);
    while ($row1 = pg_fetch_row($listeArretsTrips)) {
        //print_r($row1);
        $stopId = $row1[1];
        $arrivalTime = $row1[2];
        $departureTime = $row1[3];
        //on exécute une requete sql pour sélectionner les info de l'arret obtenu
        $infoArret = $postgreSql->executeQuery("select route_id, stop_id, stop_name, stop_lon, stop_lat, operatorname, nom_commune, code_insee from small_arrets_lignes");
        $row2 = pg_fetch_row($infoArret);
        //print_r($row2);
        $stopName = $row2[2];
        $stopLon = $row2[3];
        $stopLat = $row2[4];
        $operatorname = $row2[5];
        $nom_commune = $row2[6];
        $code_insee = $row2[7];

        $arret = new Arret($stopId, $stopName, $stopLon, $stopLat, $operatorname, $nom_commune, $code_insee, $arrivalTime, $departureTime);
        $trips->addArret($arret);

    }
    $i ++;
    $collectionTrips->addTrip($trips);

}
