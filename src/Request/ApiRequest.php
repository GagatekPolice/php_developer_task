<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Request;

use Shoper\Recruitment\Task\Constants\ApiConstants;
use Shoper\Recruitment\Task\Controller\AbstractController;
use Shoper\Recruitment\Task\Request\JsonResponse;

class ApiRequest
{
    /**
     * @var array dozwolone przez api typy przyjmowany danych w ciele żądania
     */
    const AVAILABLE_CONTENT_TYPES = ['application/json'];

    const CONTROLLER_NAMESPACE = "Shoper\Recruitment\Task\Controller\\";

    /**
     * @var string wyrażenie regularne slużące do walidacji ścieżki uri
     */
    const REQUEST_PATH_REGEX = "/^[A-Za-z1-9-_]{0,64}$/";

    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $uri;

    public function __construct()
    {
        $this->uri = explode( '/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->body = file_get_contents('php://input');

        // ToDo: ustawiać tylko podczas trybu debug 
        //       ustawiać pierwotnie tylko w ramach setDefaultHeaders w JsonResponse
        header('Content-type: application/json; charset=utf-8', true);
    }

    /**
     * Metoda zwraca ciało dopowiedzi w formacie JSON
     */
    public function getJsonBody(): array
    {
        return json_decode($this->body, true);
    }

    /**
     * Metoda przetwarza przekazane żądaniem dane i przekazuje je do odpowiedniego kontrolera
     */
    public function processControllerAction(): JsonResponse
    {
        $controller = $this->createController();
        $productId = $this->getRequestedProductId();

        if ($productId && $this->body) {
            $functionArguments = [
                $this->getJsonBody(),
                $productId
            ];
        }
        else if ($productId && !$this->body) {
            $functionArguments = [
                $productId
            ];
        }
        else if (!$productId && $this->body) {
            $functionArguments = [
                $this->getJsonBody(),
            ];
        } else {
            $functionArguments = [];
        }

        try {
            $controllerResponse = call_user_func_array(
                [
                    $controller,
                    $this->getControllerMethodName($controller)
                ],
                $functionArguments
            );
        } catch (\ArgumentCountError $expection) {
            throw new \Exception($expection->getMessage(), ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $expection) {
            throw new \Exception($expection->getMessage(), ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $controllerResponse ?? new JsonResponse();
    }

    /**
     * Metoda waliduje podstawowe dane przekazane w żądaniu. W tym typ danych oraz przekazaną scieżkę (path).
     */
    public function validate(): self
    {
        if (isset($_SERVER["CONTENT_TYPE"]) && !in_array($_SERVER["CONTENT_TYPE"], self::AVAILABLE_CONTENT_TYPES, true)) {
            throw new \Exception('Invalid content type, expected: ' . implode("|", self::AVAILABLE_CONTENT_TYPES), ApiConstants::HTTP_BAD_REQUEST);
        }

        foreach ($this->uri as $value) {
            if($value && !preg_match(self::REQUEST_PATH_REGEX, $value)) {
                throw new \Exception('Invalid route name: \'' . $value . '\'', ApiConstants::HTTP_BAD_REQUEST);
            }
        }
        return $this;
    }

    /**
     * Metoda formuje, tworzy i zwraca klase kontrolera na podstawie uri
     */
    private function createController(): AbstractController
    {
        $controllerName = self::CONTROLLER_NAMESPACE . ucfirst($this->uri[1]) . "Controller";

        if (!class_exists($controllerName)) {
            throw new \Exception('Route path not found: \'' . ucfirst($this->uri[1]) . '\'', ApiConstants::HTTP_NOT_FOUND);
        }

        return new $controllerName();
    }

    /**
     * Metoda formuje i zwraca metodę wykonywaną w ramach kontrolera na podstawie uri
     */
    private function getControllerMethodName(AbstractController $controller): string
    {
        $controllerMethodSegment = !empty($this->uri[2]) 
         ? (
            is_numeric($this->uri[2])
                ? ucfirst($this->uri[1])
                : ucfirst($this->uri[2])
            )
         : ucfirst($this->uri[1]);

        $method = $this->method . $controllerMethodSegment . "Action";

        if (!method_exists($controller, $method) && !is_numeric($controllerMethodSegment)) {
            throw new \Exception('Route path method not found: \'' . $controllerMethodSegment . '\'', ApiConstants::HTTP_NOT_FOUND);
        }

        return $method;
    }

    /**
     * Metoda zwraca id encji przekazanej w ramach uri
     */
    private function getRequestedProductId(): ?string
    {
        $productId = end($this->uri);

        //ToDo: jeżeli id będzie UUID, to isNumeric zastąpić metodą typu isValidUuid()
        return (!empty($productId) && is_numeric($productId))
            ? $productId
            : null
        ;
    }
}