<?php

require_once "..\..\BaseDonneesRelationnelle\PHP\PostgreSQL.php";
require_once "CollectionLignes.php";
require_once "CollectionTrips.php";
require_once "Lignes.php";
require_once "TrainHeadsign.php";
require_once "Arret.php";
require_once "ArrivalsTime.php";

set_time_limit(0);

//PATH TO JSON DATA OUTPUT
$pathDataJson = "../TMP_DATA/";
$filenameCollectionLignes = "collectionLignes.json";
$filenameCollectionTrips = "collectionTrips.json";

$postgreSql = new PostgreSQL();
$postgreSql->connectToPostgreSQL("database_reseaux_transports", "user_bd_reseaux_transport", "azerty");

$routesTableName = "small_routes";
$tripsTableName = "small_trips";
$stopTimesTableName = "small_stop_times";
$arretsLignesTableName = "small_arrets_lignes";

//on va exécuter une requete au serveur postgres pour sélectionner chaque ligne, et l'insérer dans un fichier json

//on ouvre le fichier des lignes à la fin en mode lecture et écriture
$curseur = fopen("{$pathDataJson}$filenameCollectionLignes", "w+");

$listeLignes = $postgreSql->executeQuery("select route_id, route_short_name, route_long_name from $routesTableName");

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

$nbDocsPerFile = 10;
$i = 0;
$j = 1;
$nArrivalsTimeToTake = 20;

//on ouvre le fichier des trips à la fin en mode lecture et écriture
$curseur = fopen("{$pathDataJson}_{$j}{$filenameCollectionTrips}", "w+");

//on exécute une premiere requete pour sélectionner chaque tripheadsign
$resultTrips = $postgreSql->executeQuery("select distinct on (trip_headsign) trip_headsign, route_id, trip_id from $tripsTableName");

//on créé un objet CollectionLignes pour stocker chaque ligne
$collectionTrips = new CollectionTrips();
echo var_dump($resultTrips);
while ($row1 = pg_fetch_row($resultTrips)){
    echo "Trip : ";
    print_r($row1);
    if ($i == $nbDocsPerFile){
        //on insère ce string json dans le fichier
        fputs($curseur, $collectionTrips->serialize());
        fclose($curseur);

        $i = 0;
        $j++;

        $curseur = fopen("{$pathDataJson}_{$j}{$filenameCollectionTrips}", "w+");
        $collectionTrips = new CollectionTrips();
    }
    //echo "la";

    //on stocke les info de chaque train (MONA, CIME, APOR, ...)
    $routeId = $row1[1];
    $tripId = $row1[2];
    $tripHeadSign  = $row1[0];
    $listeArrets = array();

    $trainHeadsign = new TrainHeadsign($routeId, "", $tripHeadSign);

    //pour chaque train tripheadsign, on récupère la liste des arrets de manière croissante
    $resulTArrets = $postgreSql->executeQuery("select st.stop_id, stop_sequence, stop_name, stop_lon, stop_lat, OperatorName, Nom_commune, Code_insee 
                                                                    from $stopTimesTableName st, $arretsLignesTableName al 
                                                                    where st.stop_id = al.stop_id 
                                                                    and al.route_id = '$routeId'
                                                                    and st.trip_id = '$tripId'
                                                                    order by stop_sequence asc");
    $n_arrets = 0;
    while ($row2 = pg_fetch_row($resulTArrets)){
        $stopId = $row2[0];
        $stopSequence = $row2[1];
        $stopName = $row2[2];
        $stopLon = $row2[3];
        $stopLat = $row2[4];
        $operatorname = $row2[5];
        $nomCommune = $row2[6];
        $codeInsee = $row2[7];

        //echo "Arret : ";
        //print_r($row2);

        if ($n_arrets == 0)
            $trainHeadsign->setIdArretDepart($stopId);

        $arret = new Arret($stopId, $stopName, $stopLon, $stopLat, $operatorname, $nomCommune, $codeInsee, $stopSequence);

        //pour chaque arret, on obtient les horaires de ce dernier
        $resultArrivalTimesArret = $postgreSql -> executeQuery("select arrival_time, departure_time, st.trip_id
                                                                            from $stopTimesTableName st, $tripsTableName t
                                                                            where st.trip_id = t.trip_id
                                                                            and stop_id = '$stopId'
                                                                            and t.route_id = '$routeId'
                                                                            order by arrival_time asc
                                                                            limit $nArrivalsTimeToTake
                                                                            offset 0");

        while ($row3 = pg_fetch_row($resultArrivalTimesArret)){
            //echo "Arrival Time : ";
            //print_r($row3);
            $tripIdArrivalTime = $row3[2];
            $arrivalTime = $row3[0];
            $departureTime = $row3[1];

            $arrivalsTime = new ArrivalsTime($tripIdArrivalTime, $arrivalTime, $departureTime);

            $arret->addArrivalTime($arrivalsTime);
        }
        $trainHeadsign->addArret($arret);
        $n_arrets ++;
    }
    $i++;
    $collectionTrips->addTrip($trainHeadsign);
}
if ($i < $nbDocsPerFile){
    //on insère ce string json dans le fichier
    //echo var_dump($collectionTrips->getListeTrips()[0]->getListeArrets()[0]);
    fputs($curseur, $collectionTrips->serialize());
    fclose($curseur);
}