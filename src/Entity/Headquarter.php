<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Entity;

class Headquarter
{
    const CLASS_NAME = 'Headquarter'; 

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $id;

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

    public function __construct(string $id, string $city, string $street, string $latitude, string $longitude)
    {
        $this->id = $id;
        $this->city = $city;
        $this->street = $street;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function asJson() {
        $objectVars = get_object_vars($this);
        $objectVars['type'] = self::CLASS_NAME;
        return json_decode(json_encode($objectVars));
    }

    public function getObjectVars(){
        return get_object_vars($this);
    }
}