<?php

use Grigorov\FileSystem\Entities\Abstract\Interfaces\IDirectory;
use Grigorov\FileSystem\Entities\Abstract\Interfaces\IFile;
use Grigorov\FileSystem\Entities\File;
use Grigorov\FileSystem\Entities\RootDirectory;
use PHPUnit\Framework\TestCase;

final class FileTest extends TestCase {



    private static IDirectory $direcory;
    private static IFile $file;

    /**
     * @beforeClass
     */
    public static function prepareDirectoryForTest(): void
    {
        // Arrange
        FileTest::$direcory = new RootDirectory("FileTest", 'C:\\Users\\jhonn\\Desktop');

        // Act
        FileTest::$direcory->create();

        // Arrange
        FileTest::$file = new File("Test.php", FileTest::$direcory);

        // Act
        FileTest::$file->create("Test");

        // Arrange
        FileTest::$file = new File("Test.php", FileTest::$direcory);

        // Act
        FileTest::$file->create("Test");
    }

    /**
     * @afterClass
     */
    public static function tearDownDirectory(): void
    {
        rmdir('C:\\Users\\jhonn\\Desktop\\FileTest');
    }


    public function testFileExistsAfterCreation(): void
    {
        // Assert
        $this->assertTrue(file_exists(FileTest::$file->getFullPath()));
    }

    public function testDirectoryHasCorrectFileCount(): void
    {
        // Assert
        $this->assertEquals(1, FileTest::$direcory->getChildrenCount());
    }

    public function testFileDoesNotExistAfterDeletion(): void
    {
        // Act
        FileTest::$file->delete();

        // Assert
        $this->assertEquals(0, FileTest::$direcory->getChildrenCount());
        $this->assertFalse(file_exists(FileTest::$file->getFullPath()));
    }
}