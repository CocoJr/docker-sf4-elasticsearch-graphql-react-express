<?php

namespace App\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Overblog\GraphQLBundle\Annotation\GraphQLColumn;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @Gedmo\Uploadable(filenameGenerator="SHA1", allowOverwrite=true, appendNumber=true)
 */
class File
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Serializer\Groups({"elastica"})
     * @Serializer\Expose()
     */
    protected $id;

    /**
     * @ORM\Column(name="path", type="string")
     * @Gedmo\UploadableFilePath
     *
     * @Serializer\Groups({"elastica"})
     * @Serializer\Expose()
     */
    protected $path;

    /**
     * @ORM\Column(name="name", type="string")
     * @Gedmo\UploadableFileName
     */
    protected $name;

    /**
     * @ORM\Column(name="mime_type", type="string")
     * @Gedmo\UploadableFileMimeType
     */
    protected $mimeType;

    /**
     * @ORM\Column(name="size", type="decimal")
     * @Gedmo\UploadableFileSize
     */
    protected $size;

    /**
     * @GraphQLColumn(type="String")
     */
    protected $publicPath;

    /**
     * @Assert\Image(groups={"upload_img"})
     */
    public $file;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Serializer\Groups({"elastica"})
     * @Serializer\Expose()
     */
    private $createdAt;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }


    /**
     * @return string
     */
    public function getPublicPath()
    {
        return '/uploads/'.$this->name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     *
     * @return File
     *
     * @codeCoverageIgnore
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return File
     *
     * @codeCoverageIgnore
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param mixed $mimeType
     *
     * @return File
     *
     * @codeCoverageIgnore
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     *
     * @return File
     *
     * @codeCoverageIgnore
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return File
     *
     * @codeCoverageIgnore
     */
    public function setCreatedAt(\DateTime $createdAt): File
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}