<?php

require_once "Arret.php";

class TrainHeadsign
{
    private string $routeId;

    private string $tripHeadsign;

    private string $idArretDepart;

    private Array $listeArrets;

    function __construct(string $parRouteId, string $parIdArretDepart, string $parTripheadsign)
    {
        $this->routeId = $parRouteId;
        $this->tripHeadsign = $parTripheadsign;
        $this->idArretDepart = $parIdArretDepart;
        $this->listeArrets = array();
    }

    function addArret(Arret $arret): void
    {
        $this->listeArrets[] = $arret;
    }

    function serialize(): string
    {
        $trainHeadsignSerialized = "{";

        //on parcourt les champs de l'objet
        foreach ($this as $nameField => $valueField){
            //si le champ est une liste d'arrets, on convertit chaque objet
            if (is_array($valueField)){
                $trainHeadsignSerialized .= "\"$nameField\" : [";

                foreach ($valueField as $arret)
                    $trainHeadsignSerialized .= $arret->serialize() . ",";

                //on enlève la virgule en trop
                $trainHeadsignSerialized = rtrim($trainHeadsignSerialized, ",");

                $trainHeadsignSerialized .= "],";

            }
            else{
                $trainHeadsignSerialized .= "\"$nameField\" : \"$valueField\",";
            }

        }
        //on enlève la virgule en trop
        $trainHeadsignSerialized = rtrim($trainHeadsignSerialized, ",");

        return $trainHeadsignSerialized . "}";
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

    /**
     * @return string
     */
    public function getIdArretDepart(): string
    {
        return $this->idArretDepart;
    }

    /**
     * @param string $idArretDepart
     */
    public function setIdArretDepart(string $idArretDepart): void
    {
        $this->idArretDepart = $idArretDepart;
    }
}