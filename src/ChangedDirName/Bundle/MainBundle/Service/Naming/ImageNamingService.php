<?php

/* All inner names have been changed for security reasons. */

namespace ChangedDirName\Bundle\MainBundle\Service\Naming;

use ChangedDirName\Bundle\MainBundle\Entity\Image;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;

/**
 * Class ImageNamingService
 *
 * @package ChangedDirName\Bundle\MainBundle\Service\Naming
 */
class ImageNamingService
{
    /**
     * @var PropertyMappingFactory
     */
    private $mappingFactory;

    /**
     * Constructor
     *
     * @param PropertyMappingFactory $mappingFactory
     */
    public function __construct(PropertyMappingFactory $mappingFactory)
    {
        $this->mappingFactory = $mappingFactory;
    }

    /**
     * Generate folder name for upload
     *
     * @param Image $image
     * @return string
     */
    public function directoryName($image)
    {
        return !empty($image->getPath()) ? $image->getPath() : date("/Y/F/j(l)/");
    }

    /**
     * Check and return a path for image
     *
     * @param Image $image
     * @return string
     */
    public function asset($image)
    {
        $mapping = $this->mappingFactory->fromField($image, 'image');
        $asset = $mapping->getUriPrefix().$image->getPath().$image->getSize().'/'.$image->getName();
        $file = $mapping->getUploadDestination().$image->getPath().$image->getName();

        return ((!$this->checkFileExists($file) || empty($image->getPath())) ? $this->checkPathField($image) : $asset);
    }

    /**
     * Check if Image Path is actual and try to restore it if not
     *
     * @param Image $image
     * @return string
     */
    protected function checkPathField($image)
    {
        $mapping = $this->mappingFactory->fromField($image, 'image');
        $dirName = date_format($image->getCreatedAt(), "/Y/F/j(l)/");
        $checkPath = $mapping->getUploadDestination().$dirName.$image->getName();
        if (!$this->checkFileExists($checkPath)) {
            return false;
        }
        $newAsset = $mapping->getUriPrefix().$dirName.$image->getSize().'/'.$image->getName();

        return $newAsset;
    }

    /**
     * Check if file exists
     *
     * @param string $fullPath
     * @return bool
     */
    private function checkFileExists($fullPath)
    {
        return file_exists($fullPath);
    }
}
