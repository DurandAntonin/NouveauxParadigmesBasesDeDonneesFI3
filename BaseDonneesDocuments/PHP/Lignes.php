<?php

class Lignes
{
    private string $routeId;

    private string $routeShortName;

    private string $routeLongName;

    function __construct(string $parRouteId, string $parRouteShortName, string $parRouteLongName)
    {
        $this->routeId = $parRouteId;
        $this->routeShortName = $parRouteShortName;
        $this->routeLongName = $parRouteLongName;
    }

    function serialize(): string
    {
        $ligneSerialized = "{";

        //on parcourt les champs de l'objet
        foreach ($this as $nameField => $valueField){
            $value = $valueField;

            $ligneSerialized .= "\"$nameField\" : \"$value\",";
        }
        //on enlève la virgule en trop
        $ligneSerialized = rtrim($ligneSerialized, ",");

        return $ligneSerialized . "}";
    }

    public function getRouteId(): string
    {
        return $this->routeId;
    }

    public function setRouteId(string $routeId): void
    {
        $this->routeId = $routeId;
    }

    public function getRouteShortName(): string
    {
        return $this->routeShortName;
    }

    public function setRouteShortName(string $routeShortName): void
    {
        $this->routeShortName = $routeShortName;
    }

    public function getRouteLongName(): string
    {
        return $this->routeLongName;
    }

    public function setRouteLongName(string $routeLongName): void
    {
        $this->routeLongName = $routeLongName;
    }
}