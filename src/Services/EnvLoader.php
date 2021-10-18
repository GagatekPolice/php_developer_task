<?php
declare(strict_types=1);

namespace Shoper\Recruitment\Task\Services;

class EnvLoader
{
    /**
     * Katalog, w którym znajduje się plika ze zmiennymi środowiskowymi
     *
     * @var string
     */
    protected $path;

    public function __construct(string $path)
    {
        if(!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('Path \'%s\' does not exist', $path));
        }
        $this->path = $path;
    }

    public function load() :void
    {
        if (!is_readable($this->path)) {
            throw new \RuntimeException(sprintf('File \'%s\',  is not readable', $this->path));
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Pomijamy komentarze w pliku
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line);
            $name = trim($name);
            $value = trim($value);

            putenv(sprintf('%s=%s', $name, $value));
        }
    }
}

