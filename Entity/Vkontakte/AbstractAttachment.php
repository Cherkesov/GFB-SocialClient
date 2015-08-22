<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 04.03.2015
 * Time: 0:46
 */

namespace GFB\SocialClientBundle\Entity\Vkontakte;

/**
 * Class AbstractAttachment
 * @package GFB\SocialClientBundle\Entity\Vkontakte
 */
abstract class AbstractAttachment
{
    const TYPE_AUDIO = "audio";
    const TYPE_VIDEO = "video";
    const TYPE_PHOTO = "photo";
    const TYPE_DOC = "doc";

    /** @var int */
    private $id;

    /** @var User */
    private $owner;

    /** @var string */
    private $type;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     * @return $this
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public abstract function getRichText();
}