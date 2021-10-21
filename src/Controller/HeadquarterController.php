<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Controller;

use Shoper\Recruitment\Task\Constants\ApiConstants;
use Shoper\Recruitment\Task\Entity\Headquarter;
use Shoper\Recruitment\Task\Request\JsonResponse;
use Shoper\Recruitment\Task\Services\Uuid;

/**
 * Klasa odpowiedzialna za operacje na danych siedziby firmy
*/
class HeadquarterController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
    }

     /**
     * Metoda usuwa z bazy danych siedzibę firmy o podanym id i ją zwraca
     */
    public function deleteHeadquarterAction(string $productId): JsonResponse
    {
        $headquarter = $this->databaseHandler->findById(Headquarter::class, $productId);

        if (!$headquarter) {
         throw new \Exception("Headquarter not found", ApiConstants::HTTP_NOT_FOUND);
        }

        $this->databaseHandler->deleteById($headquarter);

        return new JsonResponse(null, ApiConstants::HTTP_OK );
    }

    /**
     * Metoda pobiera z bazy danych wszystkie siedziby firmy i je zwraca
     */
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

     /**
     * Metoda pobiera z bazy danych siedzibę firmy o podanym id
     */
    public function getHeadquarterAction(string $productId): JsonResponse
    {
        $headquarter = $this->databaseHandler->findById(Headquarter::class, $productId);

        if (!$headquarter) {
         throw new \Exception("Headquarter not found", ApiConstants::HTTP_NOT_FOUND);
        }

        return new JsonResponse($headquarter->asJson(), ApiConstants::HTTP_OK );
    }

    /**
     * Metoda odpowiedzialna za tworzenie obiektu siedziby firmy w bazie danych
     */
    public function postHeadquarterAction(array $parameters): JsonResponse
    {
        $this->validateParameters(
            $parameters,
            [
                'city' => true,
                'latitude' => true,
                'longitude' => true,
                'street' => true
            ]
        );

        $headquarter = new Headquarter(
            $parameters['city'],
            Uuid::uuid4(), 
            $parameters['latitude'],
            $parameters['longitude'],
            $parameters['street'],
        );

        $this->validateEntity($headquarter);
        $this->databaseHandler->insert($headquarter);

        return new JsonResponse($headquarter->asJson(), ApiConstants::HTTP_CREATED);
    }

    /**
     * Metoda odpowiedzialna za aktualizację obiektu siedziby firmy w bazie danych
     */
    public function putHeadquarterAction(array $parameters, string $productId): JsonResponse
    {
        $headquarter = $this->databaseHandler->findById(Headquarter::class, $productId);

        if (!$headquarter) {
         throw new \Exception("Headquarter not found", ApiConstants::HTTP_NOT_FOUND);
        }

        $this->validateParameters(
            $parameters,
            [
                'city' => false,
                'latitude' => false,
                'longitude' => false,
                'street' => false
            ]
        );

        foreach ($parameters as $key => $value) {
            if(!property_exists($headquarter, $key)) {
                throw new \Exception('Invalid key: \'' . $key . '\'', ApiConstants::HTTP_BAD_REQUEST);
            }

            call_user_func([$headquarter, 'set' . ucfirst(strtolower($key))], $value);
        }

        $this->validateEntity($headquarter);
        $headquarter = $this->databaseHandler->update($headquarter);

        return new JsonResponse($headquarter->asJson(), ApiConstants::HTTP_OK);
    }
}