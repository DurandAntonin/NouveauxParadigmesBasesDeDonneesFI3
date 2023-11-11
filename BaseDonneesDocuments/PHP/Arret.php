<?php

class Arret
{
    private string $stopId;

    private string $stopName;

    private string $stopLon;

    private string $stopLat;

    private string $operatorName;

    private string $nomCommune;

    private string $codeInsee;

    private string $stopSequence;

    private Array $listArrivalsTime;

    function __construct(string $parStopId, string $parStopName, string $parStopLon, string $parStopLat, string $parOperatorName, string $parNomCommune, string $parCodeInsee, string $parStopSequence)
    {
        $this->stopId = $parStopId;
        $this->stopName = $parStopName;
        $this->stopLon = $parStopLon;
        $this->stopLat = $parStopLat;
        $this->operatorName = $parOperatorName;
        $this->nomCommune = $parNomCommune;
        $this->codeInsee = $parCodeInsee;
        $this->stopSequence = $parStopSequence;
        $this->listArrivalsTime = array();
    }

    function addArrivalTime(ArrivalsTime $arrivalTime): void
    {
        $this->listArrivalsTime[] = $arrivalTime;
    }

    function serialize(): string
    {
        $arretSerielized = "{";

        //on parcourt les champs de l'objet
        foreach ($this as $nameField => $valueField){
            //si la valeur est une liste, on sérialize chaque objet
            //si la valeur est une liste, on sérialize chaque objet
            if (is_array($valueField)){
                $arretSerielized .= "\"$nameField\" : [";

                foreach ($valueField as $arrivalTime)
                    $arretSerielized .= $arrivalTime->serialize() . ",";

                //on enlève la virgule en trop
                $arretSerielized = rtrim($arretSerielized, ",");

                $arretSerielized .= "],";

            }
            else{
                $arretSerielized .= "\"$nameField\" : \"$valueField\",";
            }
        }
        //on enlève la virgule en trop
        $arretSerielized = rtrim($arretSerielized, ",") . "}";
        //echo $arretSerielized . "\n\n";
        return $arretSerielized;
    }

    public function getStopId(): string
    {
        return $this->stopId;
    }

    public function setStopId(string $stopId): void
    {
        $this->stopId = $stopId;
    }

    public function getStopName(): string
    {
        return $this->stopName;
    }

    public function setStopName(string $stopName): void
    {
        $this->stopName = $stopName;
    }

    public function getStopLon(): string
    {
        return $this->stopLon;
    }

    public function setStopLon(string $stopLon): void
    {
        $this->stopLon = $stopLon;
    }

    public function getStopLat(): string
    {
        return $this->stopLat;
    }

    public function setStopLat(string $stopLat): void
    {
        $this->stopLat = $stopLat;
    }

    public function getOperatorName(): string
    {
        return $this->operatorName;
    }

    public function setOperatorName(string $operatorName): void
    {
        $this->operatorName = $operatorName;
    }

    public function getNomCommune(): string
    {
        return $this->nomCommune;
    }

    public function setNomCommune(string $nomCommune): void
    {
        $this->nomCommune = $nomCommune;
    }

    public function getCodeInsee(): string
    {
        return $this->codeInsee;
    }

    public function setCodeInsee(string $codeInsee): void
    {
        $this->codeInsee = $codeInsee;
    }

    public function getArrivalTime(): string
    {
        return $this->arrivalTime;
    }

    public function setArrivalTime(string $arrivalTime): void
    {
        $this->arrivalTime = $arrivalTime;
    }

    public function getDepartureTime(): string
    {
        return $this->departureTime;
    }

    public function setDepartureTime(string $departureTime): void
    {
        $this->departureTime = $departureTime;
    }

    /**
     * @return string
     */
    public function getStopSequence(): string
    {
        return $this->stopSequence;
    }

    /**
     * @param string $stopSequence
     */
    public function setStopSequence(string $stopSequence): void
    {
        $this->stopSequence = $stopSequence;
    }

    /**
     * @return array
     */
    public function getListArrivalsTime(): array
    {
        return $this->listArrivalsTime;
    }

    /**
     * @param array $listArrivalsTime
     */
    public function setListArrivalsTime(array $listArrivalsTime): void
    {
        $this->listArrivalsTime = $listArrivalsTime;
    }
}