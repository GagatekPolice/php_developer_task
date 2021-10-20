<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Controller;

use Shoper\Recruitment\Task\Constants\ApiConstants;
use Shoper\Recruitment\Task\Entity\Destination;
use Shoper\Recruitment\Task\Entity\Headquarter;
use Shoper\Recruitment\Task\HereapiClient\ClientFactory;
use Shoper\Recruitment\Task\HereapiClient\HereapiClient;
use Shoper\Recruitment\Task\Request\JsonResponse;

/**
 * Klasa odpowiedzialna za operacje na danych geograficznych przetwarzanych przez HereAPI
*/
class DistanceController extends AbstractController
{
    const DEFAULT_DISTANCE_UNIT = 'meters';

    const DEFAULT_TRANSPORT_TYPE = 'car';

    /**
    * @var HereapiClient
    */
   private $heareapiCLient;

    public function __construct()
    {
        parent::__construct();
        $this->heareapiCLient = (new ClientFactory())->getHereapiClient();
    }

    /**
     * Metoda na podstawie szerokości i długości geograficznej oblicza i zwraca z HereAPI
     * odległość od siedziby firmy shoper.pl do wybranego punktu
     */
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
            self::DEFAULT_TRANSPORT_TYPE,
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