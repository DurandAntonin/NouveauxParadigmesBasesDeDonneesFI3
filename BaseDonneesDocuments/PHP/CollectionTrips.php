<?php

class CollectionTrips
{
    private Array $listeTrips;

    function __construct()
    {
        $this->listeTrips = Array();
    }

    function addTrip(Trips $trips): void
    {
        $this->listeTrips[] = $trips;
    }

    function serialize(): string
    {
        $collectionTripsSerialized = "[";

        //on parcourt chaque objet Ligne de la liste
        foreach ($this->listeTrips as $ligne){
            $collectionTripsSerialized .= $ligne->serialize() . ",";
        }

        //on enlève la virgule en trop
        $collectionTripsSerialized = rtrim($collectionTripsSerialized, ",") . "]";

        return $collectionTripsSerialized;
    }
}