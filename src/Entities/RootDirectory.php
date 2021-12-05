<?php

namespace Grigorov\FileSystem\Entities;

use Exception;
use Grigorov\FileSystem\Entities\Abstract\Interfaces\IDirectory;
use Grigorov\FileSystem\Entities\Abstract\Interfaces\IFile;
use Grigorov\FileSystem\Entities\Abstract\Interfaces\IItem;
use Grigorov\FileSystem\Entities\Abstract\RootItem;

final class RootDirectory extends RootItem implements IItem, IDirectory {

    /**
     * @var IFile[]
     */
    private array $childFiles;

    /**
     * @var IDirectory[]
     */
    private array $childDirectories;

    public function __construct(string $name, $basePath)
    {
        parent::__construct($name, $basePath);
        $this->childFiles = [];
        $this->childDirectories = [];

    }

    public function getSize(): int
    {
        $size = 0;
        foreach ($this->childDirectories as $key => $directory) {
            $size += $directory->getSize();
        }
        foreach ($this->childFiles as $key => $file) {
            $size += $file->getSize();
        }
        return $size;
    }


    public function getFullPath(): string
    {
        return $this->basePath . '/' . $this->getName();
    }

    public function getSubFiles() : array {
        return $this->childFiles;
    }

    public function getSubDirectories() : array {
        return $this->childDirectories;
    }

    public function getChildrenCount() : int {
        return count($this->childFiles) + count($this->childDirectories);
    }

    public function addFileChild(IFile $file) : void {
        foreach ($this->childFiles as $key => $childFile) {
            if ($file->getName() == $childFile->getName()) {
                throw new Exception("This file already exists" . $file->getName());
            }
        }
        $this->childFiles[] = $file;
    }

    public function deleteFileChild(IFile $file) : void {
        foreach ($this->childFiles as $key => $childFile) {
            if ($file->getName() == $childFile->getName()) {
                unset($this->childFiles[$key]);
            }
        }
    }

    public function addDirectoryChild(IDirectory $directory) : void {
        foreach ($this->childDirectories as $key => $childDirectory) {
            if ($childDirectory->getName() == $directory->getName()) {
                throw new Exception("This file already exists" . $directory->getName());
            }
        }
        $this->childDirectories[] = $directory;
    }

    public function deleteDirectoryChild(IDirectory $directory) : void {
        foreach ($this->childDirectories as $key => $childDirectory) {
            if ($childDirectory->getName() == $directory->getName()) {
                unset($this->childDirectories[$key]);
            }
        }
    }

    public function create(int $permissions = 0777) : void {
        if (!is_dir($this->basePath)) {
            throw new Exception("The parent is not a directory: " . $this->basePath);
        }
        mkdir($this->getFullPath(), $permissions);
    }


    public function isEmpty(): bool
    {
        return count($this->childFiles) + count($this->childDirectories) == 0;

    }

    public function delete() : void {
        if (is_dir($this->getFullPath()) && !$this->isEmpty()) {
            throw new Exception("Directory is not empty. Cannot be deleted");
        }
        rmdir($this->getFullPath());
    }

     public function getChildren(): array
     {
         return array_merge($this->childFiles, $this->childDirectories);
     }

}