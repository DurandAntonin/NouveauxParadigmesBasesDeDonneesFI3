<?php

require_once "Lignes.php";

class CollectionLignes
{
    private Array $listeLignes;

    function __construct()
    {
        $this->listeLignes = Array();
    }

    function addLigne(Lignes $ligne): void
    {
        $this->listeLignes[] = $ligne;
    }

    function serialize(): string
    {
        $collectionLigneSerialized = "[";

        //on parcourt chaque objet Ligne de la liste
        foreach ($this->listeLignes as $ligne){
            $collectionLigneSerialized .= $ligne->serialize() . ",";
        }

        //on enlève la virgule en trop
        $collectionLigneSerialized = rtrim($collectionLigneSerialized, ",") . "]";

        return $collectionLigneSerialized;
    }
}