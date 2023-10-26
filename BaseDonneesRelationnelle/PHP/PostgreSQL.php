<?php
class PostgreSQL{
    private PgSql\Connection $pdo;

    private string $database;

    private string $userBd;

    private string $mdpUserBd;

    function __construct(){

    }

    /**
     * @throws Exception
     */
    function connectToPostgreSQL($dataBase, $userBd, $mdpUserBd): void
    {
        $conn_string = "host=localhost port=5432 dbname=" . $dataBase . " user=" . $userBd . " password=" . $mdpUserBd;


        $dbConn = pg_connect($conn_string);
        //echo $dbConn;

        if ($dbConn){
            $this->pdo = $dbConn;
            $this->database = $dataBase;
            $this->userBd = $userBd;
            $this->mdpUserBd = $mdpUserBd;
        }
        else{
            throw new Exception("Unable to connect to bd postgres");
        }
    }

    /**
     * @throws Exception
     */
    function switchUserConnexion($newDB, $newUserBD, $newMdpUsersBd): void
    {
        $this->connectToPostgreSQL($newDB, $newUserBD, $newMdpUsersBd);
    }

    function executeQuery($requete): \PgSql\Result
    {
        $result = pg_query($requete);

        if (!$result)
            throw new Exception(pg_last_error($this->pdo));

        return $result;
    }

    function initBd($dataBaseName, $userNameBd, $userPasswordBd): void
    {
        echo "Init user and database ... \n";

        $this->createOrInitialiseDataBase($dataBaseName, $userNameBd);

        $this->createOrInitialiseUserDataBase($userNameBd, $userPasswordBd);
    }

    private function createOrInitialiseUserDataBase($userNameBd, $userPasswordBd){
        echo "Initialisation of user for database ... \n";

        //create the user if not exists, or recreate the user if exists

        //delete database if exists
        $queryDeleteUser = "DROP USER IF EXISTS $userNameBd";
        $resultQueryDeleteUser = $this->executeQuery($queryDeleteUser);


        //create user
        $queryCreateUser = "CREATE USER $userNameBd WITH
                                LOGIN
                                SUPERUSER
                                NOCREATEROLE
                                NOINHERIT
                                PASSWORD '$userPasswordBd'";

        $resultQueryCreateUser = $this->executeQuery($queryCreateUser);
    }

    private function createOrInitialiseDataBase($dataBaseName, $userNameAssociated): void
    {
        echo "Initialisation of database ... \n";

        //create the db if not exists, or recreate the db if exists
        $queryDeleteDataBase = "DROP DATABASE IF EXISTS $dataBaseName";
        $resultQueryDeleteDataBase = $this->executeQuery($queryDeleteDataBase);

        //create database
        $queryCreateDataBase = "CREATE DATABASE $dataBaseName
                                        WITH
                                        ENCODING = 'UTF8'
                                        IS_TEMPLATE = False;";

        $resultQueryCreateDataBase = $this->executeQuery($queryCreateDataBase);

        //while ($resultArrayQueryCheckBdExists = pg_fetch_array($resultQueryCheckBdExists))
            //break;
    }

    function initTablesInDb($tables){
        echo "Initialisation of tables ... \n";

        $tablesName = array_keys($tables);
        foreach ($tablesName as $tableName){
            $colonnes = $tables[$tableName];

            //create table with tableName and parameters
            $queryCreateTable = "CREATE TABLE $tableName(";

            foreach ($colonnes as $colonne){
                $queryCreateTable .= $colonne . " varchar(255),";
            }
            //echo $queryCreateTable . " ici\n";
            $queryCreateTable = substr($queryCreateTable,0, -1);
            //echo $queryCreateTable . "la \n";
            $queryCreateTable .= ")";

            echo "Create table $tableName with query $queryCreateTable \n";
            $resultQueryCreateTable = $this->executeQuery($queryCreateTable);
        }
    }

    function insertDataToTables($tableName, $batchSize, $listeData, $debut=0){
        echo "Insert data in table $tableName ... \n";
        if ($debut >= count($listeData)){
            echo "Insertion of " . count($listeData) .  " tuples in $tableName completed \n";
            return;
        }

        if (($debut + $batchSize) >= count($listeData)){
            $fin = count($listeData);
            //echo $fin;
        }
        else{
            $fin = $debut + $batchSize;
        }

        $queryInsertTuple = "INSERT INTO $tableName values";
        for ($i=$debut;$i<$fin;$i++){
            $ligne = $listeData[$i];

            $queryInsertTuple .= "(";
            foreach ($ligne as $elem){
                $queryInsertTuple .= "'" . $elem . "'" . ",";
            }

            $queryInsertTuple = substr($queryInsertTuple, 0,-1);
            $queryInsertTuple .= "),";


        }
        $queryInsertTuple = substr($queryInsertTuple, 0,-1);
        //echo $queryInsertTuple . "\n";
        //echo "      Inserting " . ($fin-$debut) . " tuples in the table ...\n";
        $this->executeQuery($queryInsertTuple);

        $this->insertDataToTables($tableName, $batchSize, $listeData, $debut+$batchSize);
    }

    function closeConnectionToBd(): void
    {
        pg_close($this->pdo);
    }

    function getConnexionStatus(){
        return pg_consume_input($this->pdo);
    }
}


#dev_bd_reseau_transports

?>
