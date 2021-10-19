<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Controller;

use Shoper\Recruitment\Task\Constants\ApiConstants;
use Shoper\Recruitment\Task\Entity\DatabaseHandler\DatabaseHandler;
use Shoper\Recruitment\Task\Entity\Headquarter;
use Shoper\Recruitment\Task\Request\JsonResponse;
use Shoper\Recruitment\Task\Services\Uuid;

class HeadquarterController extends AbstractController
{
    /**
     * @var DatabaseHandler
     */
    private $databaseHandler;

    public function __construct()
    {
        parent::__construct();
        $this->databaseHandler = new DatabaseHandler();
    }

    public function deleteHeadquarterAction(string $productId): JsonResponse
    {
        $headquarter = $this->databaseHandler->findById(Headquarter::class, $productId);

        if (!$headquarter) {
         throw new \Exception("Headquarter not found", ApiConstants::HTTP_NOT_FOUND);
        }

        $this->databaseHandler->deleteById($headquarter);

        return new JsonResponse(null, ApiConstants::HTTP_OK );
    }

    public function getAllAction(): JsonResponse
    {
       $headquarters = $this->databaseHandler->findAll(Headquarter::class);

       if (!$headquarters) {
        throw new \Exception("No headquarters found", ApiConstants::HTTP_NOT_FOUND);
       }

       foreach ($headquarters as $headquarter) {
           $headquarterAsJson[] = $headquarter->asJson();
       }

       return new JsonResponse($headquarterAsJson, ApiConstants::HTTP_OK );
    }

    public function getHeadquarterAction(string $productId): JsonResponse
    {
        $headquarter = $this->databaseHandler->findById(Headquarter::class, $productId);

        if (!$headquarter) {
         throw new \Exception("Headquarter not found", ApiConstants::HTTP_NOT_FOUND);
        }

        return new JsonResponse($headquarter->asJson(), ApiConstants::HTTP_OK );
    }

    public function postHeadquarterAction(array $parameters): JsonResponse
    {
        $headquarter = new Headquarter(
            $parameters['city'],
            Uuid::uuid4(), 
            $parameters['latitude'],
            $parameters['longitude'],
            $parameters['street'],
        );

        //ToDo: dodać walidator requestow
        $this->databaseHandler->insert($headquarter);

        return new JsonResponse($headquarter->asJson(), ApiConstants::HTTP_CREATED);
    }

    public function putHeadquarterAction(array $parameters, string $productId): JsonResponse
    {
        $headquarter = $this->databaseHandler->findById(Headquarter::class, $productId);

        if (!$headquarter) {
         throw new \Exception("Headquarter not found", ApiConstants::HTTP_NOT_FOUND);
        }

        //ToDo: dodać walidator requestow
        $headquarter = $this->databaseHandler->update($headquarter, $parameters);

        return new JsonResponse($headquarter->asJson(), ApiConstants::HTTP_OK);
    }
}