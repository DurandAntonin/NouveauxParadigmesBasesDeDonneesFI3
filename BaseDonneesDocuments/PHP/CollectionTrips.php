<?php

class CollectionTrips
{
    private Array $listeTrips;

    function __construct()
    {
        $this->listeTrips = Array();
    }

    function addTrip(TrainHeadsign $trip): void
    {
        $this->listeTrips[] = $trip;
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

    /**
     * @return array
     */
    public function getListeTrips(): array
    {
        return $this->listeTrips;
    }

    /**
     * @param array $listeTrips
     */
    public function setListeTrips(array $listeTrips): void
    {
        $this->listeTrips = $listeTrips;
    }
}