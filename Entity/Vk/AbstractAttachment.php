<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 04.03.2015
 * Time: 0:46
 */

namespace GFB\SocialClientBundle\Entity\Vk;

/**
 * Class AbstractAttachment
 * @package GFB\SocialClientBundle\Entity\Vk
 */
abstract class AbstractAttachment
{
    /** @var int */
    private $id;

    /** @var User */
    private $owner;

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
    public abstract function toRichText();
}