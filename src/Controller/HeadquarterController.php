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
         throw new \Exception("No headquarters found", ApiConstants::HTTP_NOT_FOUND);
        }

        return new JsonResponse($headquarter->asJson(), ApiConstants::HTTP_OK );
    }

    public function postHeadquarterAction(array $parameters): JsonResponse
    {
        $headquarter = new Headquarter(
            Uuid::uuid4(), 
            ...array_values($parameters)
        );
        //ToDo: dodać walidator requestow

        $this->databaseHandler->insert($headquarter);

        return new JsonResponse($headquarter->asJson(), ApiConstants::HTTP_CREATED);
    }
}