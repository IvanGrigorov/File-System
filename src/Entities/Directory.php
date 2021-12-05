<?php

namespace Grigorov\FileSystem\Entities;

use Exception;
use Grigorov\FileSystem\Entities\Abstract\Interfaces\IDirectory;
use Grigorov\FileSystem\Entities\Abstract\Interfaces\IFile;
use Grigorov\FileSystem\Entities\Abstract\Interfaces\IItem;
use Grigorov\FileSystem\Entities\Abstract\Item;

final class Directory extends Item implements IItem, IDirectory {

    /**
     * @var IFile[]
     */
    private array $childFiles;

    /**
     * @var IDirectory[]
     */
    private array $childDirectories;

    public function __construct(string $name, IDirectory $parent)
    {
        parent::__construct($name, $parent);
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
        return $this->parent->getFullPath() . '/' . $this->getName();
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
        if ($this->childFiles[$file->getName()]) {
            throw new Exception("This file already exists: " . $file->getName());
        }
        $this->childFiles[$file->getName()] = $file;
    }

    public function deleteFileChild(IFile $file) : void {
        if ($this->childFiles[$file->getName()]) {
            unset($this->childFiles[$file->getName()]);
        }
    }

    public function addDirectoryChild(IDirectory $directory) : void {
        if ($this->childFiles[$directory->getName()]) {
            throw new Exception("This directory already exists: " . $directory->getName());
        }
        $this->childFiles[$directory->getName()] = $directory;
    }

    public function deleteDirectoryChild(IDirectory $directory) : void {
        if ($this->childFiles[$directory->getName()]) {
            unset($this->childFiles[$directory->getName()]);
        }
    }

    public function create(int $permissions = 0777) : void {
        if (!is_dir($this->parent->getFullPath())) {
            throw new Exception("The parent is not a directory: " . $this->parent->getFullPath());
        }
        $this->parent->addDirectoryChild($this);
        mkdir($this->getFullPath(), $permissions);
    }

    public function move(IDirectory $targetDirectory) : void {
        try {
            parent::move($targetDirectory);
            $targetDirectory->addDirectoryChild($this);
            $this->parent->deleteDirectoryChild($this);
        }
        catch (Exception $e) {
            throw new Exception("Cannot move file, because of: " . $e->getMessage());
        }
    }

    public function isEmpty(): bool
    {
        return count($this->childFiles) + count($this->childDirectories) == 0;

    }

    public function delete() : void {
        if (is_dir($this->getFullPath()) && !$this->isEmpty()) {
            throw new Exception("Directory is not empty or does not exists. Cannot be deleted");
        }
        rmdir($this->getFullPath());
    }

     public function getChildren(): array
     {
         return array_merge($this->childFiles, $this->childDirectories);
     }
}