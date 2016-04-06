<?php

/* All inner names have been changed for security reasons. */

namespace ChangedDirName\Bundle\MainBundle\Service\Naming;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

/**
 * Class PageImageDirectoryNamingService
 *
 * @package ChangedDirName\Bundle\MainBundle\Service\Naming
 */
class PageImageDirectoryNamingService implements DirectoryNamerInterface
{
    /**
     * Returns getPath() value if it exists or generates new path if not
     * {@inheritdoc}
     */
    public function directoryName($object, PropertyMapping $mapping)
    {
        return !empty($object->getPath()) ? $object->getPath() : $object->getCreatedAt()->format("/Y/F/j(l)/");
    }
}
