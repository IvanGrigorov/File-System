<?php

namespace Grigorov\FileSystem\Entities\Abstract\Interfaces;

use Grigorov\FileSystem\Infrastructure\ChangeFilePermissionsMode;

interface IDirectory {

    public function getFullPath() : string;

    public function getName() : string;

    public function getChildren() : array;

    public function getChildrenCount() : int;

    public function addFileChild(IFile $file) : void;

    public function deleteFileChild(IFile $file) : void;

    public function addDirectoryChild(IDirectory $file) : void;

    public function deleteDirectoryChild(IDirectory $file) : void;

    public function create() : void;

    public function getSize() : int;

    public function isEmpty() : bool;

    public function move(IDirectory $moveTo) : void;

    public function delete() : void;

    public function changePermissions($mode = ChangeFilePermissionsMode::ALL_OWNER_ALL_GROUP_ELSE_NOTHING) : void;

}