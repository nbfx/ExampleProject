<?php

/* All inner names have been changed for security reasons. */

namespace ChangedDirName\Bundle\MainBundle\Tests\Unit\Service\Naming;

use ChangedDirName\Bundle\MainBundle\Entity\Image;
use ChangedDirName\Bundle\MainBundle\Service\Naming\PageImageDirectoryNamerService;
use Vich\UploaderBundle\Mapping\PropertyMapping;

/**
 * Class PageImageDirectoryNamingServiceTest
 *
 * @package ChangedDirName\Bundle\MainBundle\Tests\Unit\Service\Naming
 */
class PageImageDirectoryNamingServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PageImageDirectoryNamingService
     */
    private $service;

    /**
     * @var PropertyMapping
     */
    private $propertyMappingMock;

    /**
     * @var Image
     */
    private $image;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->propertyMappingMock = $this->getMockBuilder('Vich\UploaderBundle\Mapping\PropertyMapping')
            ->disableOriginalConstructor()
            ->getMock();
        $this->service = new PageImageDirectoryNamingService();
        $this->image = new Image();
    }

    /**
     * Check if the path from entity will be used when it exists
     *
     * @runInSeparateProcess
     */
    public function testDirectoryNameWithPathReturnPath()
    {
        $this->image->setPath('path/to/image.jpg');
        $this->assertEquals('path/to/image.jpg', $this->service->directoryName($this->image, $this->propertyMappingMock));
    }

    /**
     * Check if the path will be generated according to current date when path in entity is empty
     *
     * @runInSeparateProcess
     */
    public function testDirectoryNameWithoutPathReturnCurrentDate()
    {
        $this->image->setCreatedAt(new \DateTime('2016-01-01 12:00:00.0'));
        $this->assertEquals('/2016/January/1(Friday)/', $this->service->directoryName($this->image, $this->propertyMappingMock));
    }
}
