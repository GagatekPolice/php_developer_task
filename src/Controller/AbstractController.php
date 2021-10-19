<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Controller;

use Shoper\Recruitment\Task\Entity\DatabaseHandler\DatabaseHandler;

abstract class AbstractController
{
    /**
    * @var DatabaseHandler
    */
   protected $databaseHandler;

    public function __construct()
    {
        $this->databaseHandler = new DatabaseHandler();
    }
}
