<?php

/* All inner names have been changed for security reasons. */

namespace ChangedDirName\Bundle\MainBundle\Tests\Unit\Service\Naming;

use ChangedDirName\Bundle\MainBundle\Entity\Image;

/**
 * Class PageImageFileNamingServiceTest
 *
 * @package ChangedDirName\Bundle\MainBundle\Tests\Unit\Service\Naming
 */
class PageImageFileNamingServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Check if the unique name for a new image will be created
     */
    public function testNameReturnUniqueName()
    {
        $image = new Image();
        $mapping = $this->getMock('Vich\UploaderBundle\Mapping\PropertyMapping', [], [], '', false);
        $service = $this->getMock('ChangedDirName\Bundle\MainBundle\Service\Naming\PageImageFileNamingService', ['getSuffix', 'getFileName']);
        $service->expects($this->any())
            ->method('getSuffix')
            ->will($this->returnValue('-(12-00-00.00).'));
        $service->expects($this->any())
            ->method('getFileName')
            ->with($mapping->getFile($image))
            ->will($this->returnValue(['name' => 'test-slide-1-fxt', 'ext' => 'jpg']));

        $this->assertEquals('test-slide-1-fxt-(12-00-00.00).jpg', $service->name($image, $mapping));
    }
}
