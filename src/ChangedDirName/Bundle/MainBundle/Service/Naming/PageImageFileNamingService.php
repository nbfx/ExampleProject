<?php

/* All inner names have been changed for security reasons. */

namespace ChangedDirName\Bundle\MainBundle\Service\Naming;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

/**
 * Class PageImageFileNamingService
 *
 * @package ChangedDirName\Bundle\MainBundle\Service\Naming
 */
class PageImageFileNamingService implements NamerInterface
{
    /**
     * {@inheritDoc}
     */
    public function name($image, PropertyMapping $mapping)
    {
        /** @var $file UploadedFile */
        $file = $mapping->getFile($image);

        return $this->getFileName($file)['name'].$this->getSuffix().$this->getFileName($file)['ext'];
    }

    /**
     * Get original name from unique name
     *
     * @param string $name
     * @return string
     */
    public static function normalizeName($name)
    {
        preg_match('/(\.\w{3,4})$/', $name, $ext);
        $extension = $ext[0];
        $mask = '/(\-\([0-9]{2}-[0-9]{2}-[0-9]{2}\.[0-9]{1,2}\))\S{3,5}$/';

        return preg_replace($mask, '', $name).$extension;
    }

    /**
     * Generates suffix from current time in format hh-mm-ss.uu
     *
     * @return string
     */
    protected function getSuffix()
    {
        return '-('.date("H-i-s").'.'.round(explode(" ", microtime())[0] * 100).').';
    }

    /**
     * Get filename without extension and separated extension from file name
     *
     * @param UploadedFile $file
     * @return array
     */
    protected function getFileName($file)
    {
        return [
            'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'ext' => $file->getClientOriginalExtension(),
        ];
    }
}
