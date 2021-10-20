<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Entity\Traits;

trait CoordinateTrait
{
    /**
     * @var string
     */
    private $latitude;

    /**
     * @var string
     */
    private $longitude;

    public function getCoordinates(): string
    {
        return "{$this->latitude},{$this->longitude}";
    }
}
