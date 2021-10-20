<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Controller;

use Shoper\Recruitment\Task\Constants\ApiConstants;
use Shoper\Recruitment\Task\Entity\Destination;
use Shoper\Recruitment\Task\Entity\Headquarter;
use Shoper\Recruitment\Task\HereapiClient\ClientFactory;
use Shoper\Recruitment\Task\HereapiClient\HereapiClient;
use Shoper\Recruitment\Task\Request\JsonResponse;

class DistanceController extends AbstractController
{
    const DEFAULT_DISTANCE_UNIT = 'meters';

    /**
    * @var HereapiClient
    */
   private $heareapiCLient;

    public function __construct()
    {
        parent::__construct();
        $this->heareapiCLient = (new ClientFactory())->getHereapiClient();
    }

    public function getDistanceAction(array $parameters, string $productId): JsonResponse
    {
        $headquarter = $this->databaseHandler->findById(Headquarter::class, $productId);

        if (!$headquarter) {
         throw new \Exception("Headquarter not found", ApiConstants::HTTP_NOT_FOUND);
        }

        $this->validateParameters(
            $parameters,
            [
                'destinationLatitude' => true,
                'destinationtLongitude' => true,
            ]
        );

        $destination = new Destination($parameters['destinationLatitude'], $parameters['destinationtLongitude']);

        $this->validateEntity($destination);

        $distance = $this->heareapiCLient->getDistance(
            'car',
            $headquarter,
            $destination
        );

        return new JsonResponse(
            [
                "distance" => $distance,
                "unit" => self::DEFAULT_DISTANCE_UNIT
            ],
            ApiConstants::HTTP_OK);
    }
}