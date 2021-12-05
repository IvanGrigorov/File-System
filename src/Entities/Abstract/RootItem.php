<?php

namespace Grigorov\FileSystem\Entities\Abstract;

use Exception;
use Grigorov\FileSystem\Entities\Abstract\Interfaces\IDirectory;
use Grigorov\FileSystem\Entities\Abstract\Interfaces\IItem;
use Grigorov\FileSystem\Infrastructure\ChangeFilePermissionsMode;

abstract class RootItem implements IItem {

    protected string $name;
    protected string $basePath;

    public function __construct(string $name, string $basePath) {
        $this->name = $name;
        $this->basePath = $basePath;

    }

    public function getName() : string {
        return $this->name;
    }

    public function getFullPath() : string {
        if (empty($this->basePath)) {
            throw new Exception("Basepath not defined");
        }
        return $this->basePath . DIRECTORY_SEPARATOR . $this->name;
    }

    public function move(IDirectory $targetDirectory) : void {
        try {
            if (!is_dir($targetDirectory->getFullPath())) {
                throw new Exception("Target is not a directory: " . $targetDirectory->getFullPath());
            }
            rename($this->getFullPath(), $targetDirectory->getFullPath() . '/' . $this->getName());
        }
        catch (Exception $e) {
            throw new Exception("Cannot move file, because of: " . $e->getMessage());
        }
    }

    public function changePermissions($mode = ChangeFilePermissionsMode::ALL_OWNER_ALL_GROUP_ELSE_NOTHING) : void {
        chmod($this->getFullPath(), $mode);
    }
}