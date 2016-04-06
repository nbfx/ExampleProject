<?php

/* All inner names have been changed for security reasons. */

namespace ChangedDirName\Bundle\MainBundle\Tests\Unit\Service\Naming;

use ChangedDirName\Bundle\MainBundle\Entity\Image;
use ChangedDirName\Bundle\MainBundle\Service\Naming\ImageNamingService;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;
use Vich\UploaderBundle\Mapping\PropertyMapping;

/**
 * Class ImageNamingServiceTest
 *
 * @package ChangedDirName\Bundle\MainBundle\Tests\Unit\Service\Naming
 */
class ImageNamingServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ImageNamingService
     */
    private $service;

    /**
     * @var PropertyMappingFactory
     */
    private $propertyMappingFactoryMock;

    /**
     * @var PropertyMapping
     */
    private $propertyMappingMock;

    /**
     * @var Image
     */
    private $image;

    /**
     * @var string
     */
    private $expectedRealPath;

    /**
     * @var string
     */
    private $uploadDestination;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->image = new Image();
        $this->image
            ->setName('test-slide-1-fxt-(12-00-00.00).jpg')
            ->setCreatedAt(new \DateTime("2016-01-01 12:00:00.0"))
            ->setPath('/2016/January/1(Friday)/');
        $this->expectedRealPath = '/static/uploads/pages/2016/January/1(Friday)/test-slide-1-fxt-(12-00-00.00).jpg';
        $this->uploadDestination = __DIR__.'/../../../../../../../../web/static/uploads/pages';
        $this->propertyMappingFactoryMock = $this->getMockBuilder('Vich\UploaderBundle\Mapping\PropertyMappingFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $this->propertyMappingMock = $this->getMockBuilder('Vich\UploaderBundle\Mapping\PropertyMapping')
            ->disableOriginalConstructor()
            ->getMock();
        $this->propertyMappingMock->expects($this->any())
            ->method('getUploadDestination')
            ->will($this->returnValue($this->uploadDestination));
        $this->propertyMappingMock->expects($this->any())
            ->method('getUriPrefix')
            ->will($this->returnValue('/static/uploads/pages'));
        $this->propertyMappingFactoryMock->expects($this->any())
            ->method('fromField')
            ->with($this->equalTo($this->image), $this->equalTo('image'))
            ->will($this->returnValue($this->propertyMappingMock));
        $this->service = new ImageNamingService($this->propertyMappingFactoryMock);
    }

    /**
     * Check if the path will be generated according to current date when path in entity is empty
     *
     * @runInSeparateProcess
     */
    public function testDirectoryNameWithoutPathReturnCurrentDate()
    {
        $this->image->setPath(null);

        $this->assertEquals(date("/Y/F/j(l)/"), $this->service->directoryName($this->image));
    }

    /**
     * Check if the path from entity will be used when it exists
     *
     * @runInSeparateProcess
     */
    public function testDirectoryNameWithPathReturnPath()
    {
        $this->image->setPath('path/to/image.jpg');

        $this->assertEquals('path/to/image.jpg', $this->service->directoryName($this->image));
    }

    /**
     * Check if the image will be found by real path from entity
     *
     * @runInSeparateProcess
     */
    public function testAssetWithRealPathReturnPath()
    {
        $this->assertEquals($this->expectedRealPath, $this->service->asset($this->image));
    }

    /**
     * Check if a new path will be generated when path in entity is empty
     *
     * @runInSeparateProcess
     */
    public function testAssetWithoutPathFileFoundReturnNewPath()
    {
        $this->assertEquals($this->expectedRealPath, $this->service->asset($this->image));
    }

    /**
     * Check if a new path will be generated when path in entity is wrong
     *
     * @runInSeparateProcess
     */
    public function testAssetWithWrongPathReturnNewPath()
    {
        $this->image->setPath('/2016/January/1(Saturday)/');

        $this->assertEquals($this->expectedRealPath, $this->service->asset($this->image));
    }

    /**
     * Check if asset() method will return false when the path and createdAt value in entity are wrong
     *
     * @runInSeparateProcess
     */
    public function testAssetWithWrongPathWrongCreatedAtReturnFalse()
    {
        $this->image->setName('test-slide-1-fxt-(12-15-00.00).jpg')
            ->setCreatedAt(new \DateTime("2016-03-25 12:15:00.0"));

        $this->assertEquals(false, $this->service->asset($this->image));
    }
}
