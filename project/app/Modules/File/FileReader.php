<?php declare(strict_types=1);

/**
 * The FileReader class is responsible for reading a file from a given file path and processing its contents
 * using a ProcessorInterface. It returns an array of objects created by the ProcessorInterface. In this particular
 * case FileReader class will be built using CarrierProcessor and TransactionProcessor, which will be responsible for
 * forming objects with data from providers.txt and input.txt. I chose to keep providers also in the file
 * to have it more flexible.
 */

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
            $line = str_replace("\n", '', $line);
            $values = explode(self::SPACE_SEPARATOR, $line);
            $object = $this->processor->processData($values);

            if ($object !== null) {
                $objects[] = $object;
            }
        }

        return $objects;
    }
}
