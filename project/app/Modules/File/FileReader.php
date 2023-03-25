<?php declare(strict_types=1);

namespace App\Modules\File;

use App\Exceptions\InvalidPathException;
use App\Modules\File\Interfaces\FileReaderInterface;
use App\Modules\File\Interfaces\Processors\ProcessorInterface;
use SplFileObject;

class FileReader implements FileReaderInterface
{
    private const SPACE_SEPARATOR = ' ';

    public function __construct(
        private ProcessorInterface $processor,
    ) {
    }

    public function readFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new InvalidPathException('File was not found with given name and path');
        }

        $file = new SplFileObject($filePath, 'r');
        $objects = [];

        while (!$file->eof()) {
            $line = $file->fgets();
            $values = explode(self::SPACE_SEPARATOR, $line);
            $object = $this->processor->processData($values);

            if ($object !== null) {
                $objects[] = $object;
            }
        }

        return $objects;
    }
}
