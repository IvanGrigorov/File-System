<?php

namespace Grigorov\FileSystem\Infrastructure;


final class ChangeFilePermissionsMode {

    const ALL = 0777;

    const ALL_OWNER_ALL_GROUP_ELSE_NOTHING = 0770;

    const ALL_OWNER_ELSE_NOTHING = 0700;

    const ALL_OWNER_ELSE_READ = 0744;

    const ALL_OWNER_ELSE_READ_EXECUTE = 0755;

    const ALL_OWNER_GROUP_READ_EXECUTE_ELSE_NOTHING = 0750;

    // ...
}
