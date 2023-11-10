<?php

require_once "Arret.php";

class Trips
{
    private string $tripId;

    private string $routeId;

    private string $tripHeadsign;

    private Array $listeArrets;

    function __construct(string $parTripId, string $parRouteId, string $parTripheadsign)
    {
        $this->tripId = $parTripId;
        $this->routeId = $parRouteId;
        $this->tripHeadsign = $parTripheadsign;
        $this->listeArrets = array();
    }

    function addArret(Arret $arret): void
    {
        $this->listeArrets[] = $arret;
    }

    function serialize(): string
    {
        $tripSerialized = "{";

        //on parcourt les champs de l'objet
        foreach ($this as $nameField => $valueField){
            $value = $valueField;

            //si le champ est une liste d'arrets, on convertit chaque objet
            if (is_array($valueField)){
                $tripSerialized .= "\"$nameField\" : [";

                foreach ($valueField as $arret)
                    $tripSerialized .= $arret->serialize() . ",";

                //on enlève la virgule en trop
                $tripSerialized = rtrim($tripSerialized, ",");

                $tripSerialized .= "],";

            }
            else{
                $tripSerialized .= "\"$nameField\" : \"$value\",";
            }

        }
        //on enlève la virgule en trop
        $tripSerialized = rtrim($tripSerialized, ",");

        return $tripSerialized . "}";
    }

    public function getTripId(): string
    {
        return $this->tripId;
    }

    public function setTripId(string $tripId): void
    {
        $this->tripId = $tripId;
    }

    public function getRouteId(): string
    {
        return $this->routeId;
    }

    public function setRouteId(string $routeId): void
    {
        $this->routeId = $routeId;
    }

    public function getTripHeadsign(): string
    {
        return $this->tripHeadsign;
    }

    public function setTripHeadsign(string $tripHeadsign): void
    {
        $this->tripHeadsign = $tripHeadsign;
    }

    public function getListeArrets(): array
    {
        return $this->listeArrets;
    }

    public function setListeArrets(array $listeArrets): void
    {
        $this->listeArrets = $listeArrets;
    }
}