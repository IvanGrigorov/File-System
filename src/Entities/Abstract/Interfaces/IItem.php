<?php

namespace Grigorov\FileSystem\Entities\Abstract\Interfaces;

use Grigorov\FileSystem\Infrastructure\ChangeFilePermissionsMode;

interface IItem {

    public function getName() : string;

    public function move(IDirectory $moveTo) : void;

    public function delete() : void;

    public function changePermissions($mode = ChangeFilePermissionsMode::ALL_OWNER_ALL_GROUP_ELSE_NOTHING) : void;

}