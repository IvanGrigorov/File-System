<?php

namespace Grigorov\FileSystem\Entities;

use Exception;
use Grigorov\FileSystem\Entities\Abstract\Interfaces\IDirectory;
use Grigorov\FileSystem\Entities\Abstract\Interfaces\IFile;
use Grigorov\FileSystem\Entities\Abstract\Interfaces\IItem;
use Grigorov\FileSystem\Entities\Abstract\Item;
use Grigorov\FileSystem\Infrastructure\ChangeFileContentsMode;

final class File extends Item implements IItem, IFile {

    public function __construct(string $name, IDirectory $parent)
    {
        parent::__construct($name, $parent);
    }

    private function checkIfFileExists() {
        if (!file_exists($this->getFullPath())) {
            throw new Exception("File does not exists: " . $this->getFullPath());
        }
    }

    public function getParentPath() {
        return $this->parent->getFullPath();
    }

    public function delete(): void
    {
        $this->checkIfFileExists();
        $this->parent->deleteFileChild($this);
        unlink($this->getFullPath());
    }

    public function move(IDirectory $targetDirectory) : void {
        try {
            parent::move($targetDirectory);
            $targetDirectory->addFileChild($this);
            $this->parent->deleteFileChild($this);
        }
        catch (Exception $e) {
            throw new Exception("Cannot move file, because of: " . $e->getMessage());
        }
    }

    public function create(string $contents) : void {
        if (!is_dir($this->parent->getFullPath())) {
            throw new Exception("The parent is not a directory: " . $this->parent->getFullPath());
        }
        $this->parent->addFileChild($this);
        file_put_contents($this->getFullPath(), $contents);
    }

    public function getSize(): int
    {
        $this->checkIfFileExists();
        return filesize($this->getFullPath());
    }

    public function getFullPath(): string
    {
        return $this->parent->getFullPath() . '/' . $this->getName();
    }

    public function getFileInfo() : array {
        $this->checkIfFileExists();
        return pathinfo($this->getFullPath());
    }

    public function changeContents(string $contents, $mode = ChangeFileContentsMode::REPLACE, bool $shouldLock = false) : void {
        $this->checkIfFileExists();
        $flags = 0;
        if ($mode == ChangeFileContentsMode::APPEND) {
            $flags += FILE_APPEND;
        }
        if ($shouldLock) {
            $flags += LOCK_EX;
        }
        file_put_contents($this->getFullPath(), $contents, $flags);
    }
}