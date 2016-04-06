<?php

/* All inner names have been changed for security reasons. */

namespace ChangedDirName\Bundle\MainBundle\Entity;

use ChangedDirName\Base\EntityTrait\DateTrait;
use ChangedDirName\Bundle\MainBundle\Service\Naming\PageImageFileNamingService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * File
 *
 * @package ChangedDirName\Bundle\MainBundle\Entity
 * @ORM\Table("changed_project_name_main_images")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ChangedDirName\Bundle\MainBundle\Repository\ImageRepository")
 * @Vich\Uploadable
 * @UniqueEntity(fields="name")
 */
class Image
{
    use DateTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="origin_name", type="string", length=255, nullable=true)
     */
    private $originName;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=512, nullable=true)
     */
    private $path;

    /**
     * @var File
     * @Assert\File(
     *     maxSize="3M",
     *     mimeTypes={"image/png", "image/jpeg", "image/gif"},
     *     mimeTypesMessage = "Please upload a valid file",
     *     maxSizeMessage = "Size is big"
     * )
     * @Vich\UploadableField(mapping="page_images", fileNameProperty="name")
     */
    private $image;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="ChangedDirName\Bundle\MainBundle\Entity\Page",
     *     mappedBy="images",
     *     cascade={"persist", "remove"}
     * )
     */
    private $pages;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @ORM\ManyToOne(targetEntity="ChangedDirName\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=5, nullable=true)
     */
    protected $size;


    /**
     * File constructor
     */
    public function __construct()
    {
        $this->enabled = true;
        $this->pages = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->id = $id;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->setOriginName($name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add page
     *
     * @param Page $page
     * @return $this
     */
    public function addPage(Page $page)
    {
        if (!$this->pages->contains($page)) {
            $this->pages->add($page);
        }

        return $this;
    }

    /**
     * Remove page
     *
     * @param Page $page
     * @return $this
     */
    public function removePage(Page $page)
    {
        $this->pages->removeElement($page);

        return $this;
    }

    /**
     * Get page
     *
     * @return ArrayCollection
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Image
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Is enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Get owner
     *
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set owner
     *
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File $image
     */
    public function setImage(File $image = null)
    {
        $this->image = $image;
        if (!empty($image)) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * Get file
     *
     * @return File
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set original name
     *
     * @param string $name
     * @return $this
     */
    public function setOriginName($name)
    {
        $this->originName = PageImageFileNamingService::normalizeName($name);

        return $this;
    }

    /**
     * Get original name
     *
     * @return string
     */
    public function getOriginName()
    {
        return $this->originName;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set image size if exists
     *
     * @param string $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get image size if exists
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
