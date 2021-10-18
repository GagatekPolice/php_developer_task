<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Controller;

use Shoper\Recruitment\Task\Constants\ApiConstants;
use Shoper\Recruitment\Task\Entity\DatabaseHandler\DatabaseHandler;
use Shoper\Recruitment\Task\Entity\Headquarter;
use Shoper\Recruitment\Task\Request\JsonResponse;

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
        // ['id', '1001'], ['name', 'test']]
        // var_dump($databaseHandler->getDatabase()->select('Headquarter', '*'));
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
        // var_dump($databaseHandler->getDatabase()->select('Headquarter', '*'));
       return new JsonResponse([["test" => 'test223']], ApiConstants::HTTP_OK );
    }
}