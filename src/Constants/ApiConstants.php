<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Constants;

/**
 * Klasa definiująca możliwe statusy HTTP.
 */
class ApiConstants
{
    const HTTP_ACCEPTED = 202;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_CONFLICT = 409;
    const HTTP_CREATED = 201;
    const HTTP_FORBIDDEN = 403;
    const HTTP_GONE = 410;
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    const HTTP_LOCKED = 423;
    const HTTP_NO_CONTENT = 204;
    const HTTP_NOT_ACCEPTABLE = 406;
    const HTTP_NOT_FOUND = 404;
    const HTTP_NOT_IMPLEMENTED = 501;
    const HTTP_OK = 200;
    const HTTP_SEE_OTHER = 303;
    const HTTP_SERVICE_UNAVAILABLE = 503;
    const HTTP_UNPROCESSABLE_ENTITY = 422;
}
