<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Request;

use Shoper\Recruitment\Task\Constants\ApiConstants;

/**
* Klasa odpowiedzialna za odpowiedź na żądanie zwracane przez API
*/
class JsonResponse
{
    /**
     * @var int kod odpowiedzi na żądanie
     */
    private $code;

    /**
     * @var mixed treść ciała odpowiedzi na żądanie
     */
    private $message;

    public function __construct($message = '', int $code = ApiConstants::HTTP_NO_CONTENT)
    {
        if ($message && $code === ApiConstants::HTTP_NO_CONTENT) {
            throw new \Exception('Invalid response code', ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (!$message && $code === ApiConstants::HTTP_NO_CONTENT) {
            throw new \Exception('Invalid response message', ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->message = $message;
        $this->code = $code;

        $this->setDefaultHeaders();
        $this->displayMessage();

        exit;
    }

     /**
     * Metoda dodaje do ciała dane przekazane dane w formacie JSON 
     */
    private function displayMessage(): void
    {
        if (!empty($this->message)) {
            switch (gettype($this->message)) {
                case 'string':
                    echo json_encode([$this->message]);
                    break;
                default:
                    echo json_encode($this->message);
                }
        }
    }

    /**
     * Metoda ustawia nagłówki odpowiedzi na żądanie klienta
     */
    private function setDefaultHeaders(): void
    {
        http_response_code($this->code);
    }
}