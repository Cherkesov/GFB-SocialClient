<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 12.04.2015
 * Time: 2:22
 */

namespace GFB\SocialClientBundle\Entity\Vk;


class PhotoAttachment extends AbstractAttachment
{
    /** @var string */
    private $src;

    /** @var string */
    private $srcBig;

    /** @var string */
    private $srcSmall;

    /** @var int */
    private $width;

    /** @var int */
    private $height;

    /**
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * @param string $src
     * @return $this
     */
    public function setSrc($src)
    {
        $this->src = $src;
        return $this;
    }

    /**
     * @return string
     */
    public function getSrcBig()
    {
        return $this->srcBig;
    }

    /**
     * @param string $srcBig
     * @return $this
     */
    public function setSrcBig($srcBig)
    {
        $this->srcBig = $srcBig;
        return $this;
    }

    /**
     * @return string
     */
    public function getSrcSmall()
    {
        return $this->srcSmall;
    }

    /**
     * @param string $srcSmall
     * @return $this
     */
    public function setSrcSmall($srcSmall)
    {
        $this->srcSmall = $srcSmall;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return string
     */
    public function toRichText()
    {
        return '<div class="photo"><img src="' . $this->getSrcBig() . '"/></div>';
    }
}