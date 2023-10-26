<?php
require_once "PostgreSQL.php";
ini_set('memory_limit', '1000M');

$repoData = "../code-TP-reseau/new-dataset/";

//on se connecte au seveur postgre sur une bd de base en tant que user postgres
$postgreSql = new PostgreSQL();
$postgreSql->connectToPostgreSQL("postgres", "postgres", "azerty");

echo "Connexion status :" . $postgreSql->getConnexionStatus() . "\n";

//on créé un user et une base de données
$postgreSql->initBd("database_reseaux_transports", "user_bd_reseaux_transport", "azerty");
echo "\n";

//on se connecte a la bd créée précédemment avec le nouveau user
$postgreSql->switchUserConnexion("database_reseaux_transports", "user_bd_reseaux_transport", "azerty");
echo "\n";

//on récupère le nom de chaque fichier de données à insérer dans la bd
$listeFicForTables = scandir($repoData);
//on stocke dans un array associatif le nom du fichier comme cle, et la liste des champs, i.e entetes comme valeurs
$listeTables = array();
for ($i = 2;$i<count($listeFicForTables);$i++){
    //on enleve le _ au début du nom, et on supprime l'extension
    $tableName = substr($listeFicForTables[$i],1);
    $tableName = substr($tableName,0,-4);


    //on ouvre le fichier pour récupérer l'entete
    $curseur = fopen($repoData . $listeFicForTables[$i],"r");
    $entete = fgetcsv($curseur, 1024,"\t");
    fclose($curseur);

    //echo "<br>";
    $listeTables[$tableName] = $entete;
}


//on créé les tables sql associés à $listeTables
$postgreSql->initTablesInDb($listeTables);

$NbTuplesToSend = [500, 20, 200000, 500, 20000];
$NbTuplesPerBach = [intdiv($NbTuplesToSend[0],2), intdiv($NbTuplesToSend[1],2), intdiv($NbTuplesToSend[2],3), intdiv($NbTuplesToSend[3],2), intdiv($NbTuplesToSend[4],3)];

print_r($listeFicForTables);
//on insère les données de chaque fichier de données dans la table adéquate
for ($i = 2;$i<count($listeFicForTables);$i++){
    //on enleve le _ au début du nom, et on supprime l'extension
    $tableName = substr($listeFicForTables[$i],1);
    $tableName = substr($tableName,0,-4);

    $curseur = fopen($repoData . $listeFicForTables[$i], "r");
    $ligne = fgetcsv($curseur, 1024,"\t");
    $listeTuples = array();
    $nb_tuples = 0;
    while ($ligne = fgetcsv($curseur, 1024,"\t")){
        //on remplace les ' dans le tuples par un \'
        $ligne = str_replace("'", "", $ligne);
        $listeTuples[] = $ligne;
        $nb_tuples ++;

        if ($nb_tuples >= $NbTuplesToSend[$i-2]){
            //echo "<pre>";
            //print_r($listeTuples);
            //echo "</pre>";
            $postgreSql->insertDataToTables($tableName, $NbTuplesPerBach[$i-2], $listeTuples);
            $nb_tuples = 0;
            $listeTuples = array();
        }

    }
    fclose($curseur);

    //on regarde si on a encore un batch a envoyé, de taille inférieur à $NTuplesPerbatch[$i-2]
    if ($nb_tuples < $NbTuplesToSend[$i-2]){
        $postgreSql->insertDataToTables($tableName, $NbTuplesPerBach[$i-2]/2, $listeTuples);
    }

    //echo "<br>Nombre de tuples : " . $nb_tuples . "<br>";
    //echo "\n";
}





$postgreSql->closeConnectionToBd();

?>