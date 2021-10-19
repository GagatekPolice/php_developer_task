<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Entity;

class Headquarter extends AbstractProduct
{
    const CLASS_NAME = 'Headquarter'; 

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
}