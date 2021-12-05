<?php

use Grigorov\FileSystem\Entities\RootDirectory;
use PHPUnit\Framework\TestCase;

final class DirectoryTest extends TestCase {


    /**
     * @afterClass
     */
    public static function tearDownDirectory(): void
    {
        rmdir('C:\\Users\\jhonn\\Desktop\\DirectoryTest');
    }


    public function testDirectoryExistsAfterCreation(): void
    {
        // Arrange
        $direcory = new RootDirectory("DirectoryTest", 'C:\\Users\\jhonn\\Desktop');

        // Act
        $direcory->create();

        // Assert
        $this->assertTrue(is_dir($direcory->getFullPath()));
    }
}