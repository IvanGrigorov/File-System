<?php

namespace Grigorov\FileSystem\Entities\Abstract\Interfaces;

use Grigorov\FileSystem\Infrastructure\ChangeFileContentsMode;
use Grigorov\FileSystem\Infrastructure\ChangeFilePermissionsMode;

interface IFile {


    public function getFullPath() : string;

    public function getName() : string;

    public function getSize() : int;

    public function create(string $content) : void;

    public function getFileInfo() : array;

    public function changeContents(string $contents, $mode = ChangeFileContentsMode::REPLACE,  bool $shouldLock = false) : void;

    public function move(IDirectory $moveTo) : void;

    public function delete() : void;

    public function changePermissions($mode = ChangeFilePermissionsMode::ALL_OWNER_ALL_GROUP_ELSE_NOTHING) : void;
}