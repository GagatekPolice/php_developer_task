<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Entity;

use Shoper\Recruitment\Task\Model\ProductInterface;

/**
* Klasa odpowiedzialna za reprezentację współrzędnych geograficznych punktu podróży
*/
class Destination implements ProductInterface
{
    use Traits\CoordinateTrait;

    const CLASS_NAME = 'Destination'; 

    const PATTERNS = [
        "latitude" => "/^-?(([1-8][0-9]{2})|([1-9][0-9])|([1-9]))\.\d{1,8}$/",
        "longitude" => "/^-?(([1-9][0-9])|([1-9]))\.\d{1,8}$/",
    ];

    public function __construct(string $latitude, string $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function asJson(): object
    {
        $objectVars = get_object_vars($this);
        $objectVars['type'] = self::CLASS_NAME;

        return json_decode(json_encode($objectVars));
    }

    public function getPatterns(): array
    {
        return self::PATTERNS;
    }
}