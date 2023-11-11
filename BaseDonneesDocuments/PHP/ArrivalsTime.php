<?php

class ArrivalsTime
{
    private string $tripId;

    private string $arrivalTime;

    private string $departureTime;

    function __construct(string $parTripId, string $parArrivalTime, string $parDepartureTime){
        $this->tripId = $parTripId;
        $this->arrivalTime = $parArrivalTime;
        $this->departureTime = $parDepartureTime;
    }

    function serialize(): string
    {
        $arrivalTimeSerielized = "{";

        //on parcourt les champs de l'objet
        foreach ($this as $nameField => $valueField){

            $arrivalTimeSerielized .= "\"$nameField\" : \"$valueField\",";
        }
        //on enlève la virgule en trop
        $arrivalTimeSerielized = rtrim($arrivalTimeSerielized, ",") . "}";


        return $arrivalTimeSerielized;
    }

    /**
     * @return string
     */
    public function getTripId(): string
    {
        return $this->tripId;
    }

    /**
     * @param string $tripId
     */
    public function setTripId(string $tripId): void
    {
        $this->tripId = $tripId;
    }

    /**
     * @return string
     */
    public function getArrivalTime(): string
    {
        return $this->arrivalTime;
    }

    /**
     * @param string $arrivalTime
     */
    public function setArrivalTime(string $arrivalTime): void
    {
        $this->arrivalTime = $arrivalTime;
    }

    /**
     * @return string
     */
    public function getDepartureTime(): string
    {
        return $this->departureTime;
    }

    /**
     * @param string $departureTime
     */
    public function setDepartureTime(string $departureTime): void
    {
        $this->departureTime = $departureTime;
    }
}