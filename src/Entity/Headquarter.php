<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Entity;

class Headquarter extends AbstractProduct
{
    const CLASS_NAME = 'Headquarter'; 

    const PATTERNS = [
        "city" => "/^[a-zA-Zp{L}\p{N} -]{1,64}$/",
        "latitude" => "/^(-?\d+(\.\d+)?),\s*(-?\d+(\.\d+)?)$/",
        "longitude" => "/^(-?\d+(\.\d+)?),\s*(-?\d+(\.\d+)?)$/",
        "street" => "/^[a-zA-Zp{L}\p{N} -0-9]{1,64}$/",
    ];

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $latitude;

    /**
     * @var string
     */
    private $longitude;
    
    /**
     * @var string
     */
    private $street;

    public function __construct(string $city, string $id, string $latitude, string $longitude, string $street)
    {
        parent::__construct($id);
        $this->city = $city;
        $this->street = $street;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function asJson(): object
    {
        $objectVars = get_object_vars($this);
        $objectVars['type'] = self::CLASS_NAME;

        return json_decode(json_encode($objectVars));
    }

    public function getObjectVars(): array
    {
        return get_object_vars($this);
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }
}