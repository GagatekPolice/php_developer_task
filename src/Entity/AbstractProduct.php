<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Entity;

use Shoper\Recruitment\Task\Model\ProductInterface;

/**
 * Klasa opisująca abstrakcje dowolnych produktów api
 */
abstract class AbstractProduct implements ProductInterface
{
    /**
     * Identyfikator obiektu zbudowany na podstawie 
     * @see Shoper\Recruitment\Task\Services\Uuid
     * 
     * @var string
     */
    protected $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
