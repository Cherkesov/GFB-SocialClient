<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 12.04.2015
 * Time: 18:03
 */

namespace GFB\SocialClientBundle\Entity\Vkontakte;

class DocAttachment extends AbstractAttachment
{
    /** @var string */
    private $title;

    /** @var int */
    private $size;

    /** @var string */
    private $extension;

    /** @var string */
    private $url;

    /** @var string */
    private $thumb;

    /** @var string */
    private $thumbSmall;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->setType(self::TYPE_DOC);
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
     * @return int
     */
    public function getSize()
    {
        return intval($this->size);
    }

    /**
     * @param int $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     * @return $this
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * @param string $thumb
     * @return $this
     */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;
        return $this;
    }

    /**
     * @return string
     */
    public function getThumbSmall()
    {
        return $this->thumbSmall;
    }

    /**
     * @param string $thumbSmall
     * @return $this
     */
    public function setThumbSmall($thumbSmall)
    {
        $this->thumbSmall = $thumbSmall;
        return $this;
    }

    /**
     * @return string
     */
    public function getRichText()
    {
        return '<div class="doc"><a href"' . $this->getUrl() . '"><img src="' . $this->getUrl() . '"/><br/>' . $this->getTitle() . '</a></div>';
    }
}