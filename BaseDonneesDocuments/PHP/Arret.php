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

    private string $arrivalTime;

    private string $departureTime;

    function __construct(string $parStopId, string $parStopName, string $parStopLon, string $parStopLat, string $parOperatorName, string $parNomCommune, string $parCodeInsee, string $parArrivalTime, string $parDepartureTime)
    {
        $this->stopId = $parStopId;
        $this->stopName = $parStopName;
        $this->stopLon = $parStopLon;
        $this->stopLat = $parStopLat;
        $this->operatorName = $parOperatorName;
        $this->nomCommune = $parNomCommune;
        $this->codeInsee = $parCodeInsee;
        $this->arrivalTime = $parArrivalTime;
        $this->departureTime = $parDepartureTime;
    }

    function serialize(): string
    {
        $arretSerielized = "{";

        //on parcourt les champs de l'objet
        foreach ($this as $nameField => $valueField){
            $value = $valueField;

            $arretSerielized .= "\"$nameField\" : \"$value\",";
        }
        //on enlève la virgule en trop
        $arretSerielized = rtrim($arretSerielized, ",");

        return $arretSerielized . "}";
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
}