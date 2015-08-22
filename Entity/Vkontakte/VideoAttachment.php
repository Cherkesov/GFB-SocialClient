<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 12.04.2015
 * Time: 2:56
 */

namespace GFB\SocialClientBundle\Entity\Vkontakte;

/**
 * Class VideoAttachment
 * @package GFB\SocialClientBundle\Entity\Vkontakte
 */
class VideoAttachment extends AbstractAttachment
{
    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var \DateTime */
    private $date;

    /** @var int */
    private $views;

    /** @var string */
    private $image;

    /** @var string */
    private $imageBig;

    /** @var string */
    private $imageSmall;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->setType(self::TYPE_VIDEO);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return int
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param int $views
     * @return $this
     */
    public function setViews($views)
    {
        $this->views = $views;
        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageBig()
    {
        return $this->imageBig;
    }

    /**
     * @param string $imageBig
     * @return $this
     */
    public function setImageBig($imageBig)
    {
        $this->imageBig = $imageBig;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageSmall()
    {
        return $this->imageSmall;
    }

    /**
     * @param string $imageSmall
     * @return $this
     */
    public function setImageSmall($imageSmall)
    {
        $this->imageSmall = $imageSmall;
        return $this;
    }

    /**
     * @return string
     */
    public function getRichText()
    {
        return '<div class="video"><img src="' . $this->getImageBig() . '"/><br/>' . $this->getTitle() . '</div>';
    }
}