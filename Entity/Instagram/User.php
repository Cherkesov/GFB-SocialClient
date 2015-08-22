<?php
/**
 * Created by PhpStorm.
 * User: scherk01
 * Date: 05.08.2015
 * Time: 11:55
 */

namespace GFB\SocialClientBundle\Entity\Instagram;


class User
{
    /** @var int */
    private $id;

    /** @var string */
    private $username;

    /** @var string */
    private $fullName;

    /** @var string */
    private $pictureUrl;

    /** @var string */
    private $biography;

    /** @var string */
    private $webSite;

    /** @var int */
    private $countOfMedia;

    /** @var int */
    private $countOfFollowedBy;

    /** @var int */
    private $countOfFollows;

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
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     * @return $this
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPictureUrl()
    {
        return $this->pictureUrl;
    }

    /**
     * @param string $pictureUrl
     * @return $this
     */
    public function setPictureUrl($pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * @param string $biography
     * @return $this
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;
        return $this;
    }

    /**
     * @return string
     */
    public function getWebSite()
    {
        return $this->webSite;
    }

    /**
     * @param string $webSite
     * @return $this
     */
    public function setWebSite($webSite)
    {
        $this->webSite = $webSite;
        return $this;
    }

    /**
     * @return int
     */
    public function getCountOfMedia()
    {
        return $this->countOfMedia;
    }

    /**
     * @param int $countOfMedia
     * @return $this
     */
    public function setCountOfMedia($countOfMedia)
    {
        $this->countOfMedia = $countOfMedia;
        return $this;
    }

    /**
     * @return int
     */
    public function getCountOfFollowedBy()
    {
        return $this->countOfFollowedBy;
    }

    /**
     * @param int $countOfFollowedBy
     * @return $this
     */
    public function setCountOfFollowedBy($countOfFollowedBy)
    {
        $this->countOfFollowedBy = $countOfFollowedBy;
        return $this;
    }

    /**
     * @return int
     */
    public function getCountOfFollows()
    {
        return $this->countOfFollows;
    }

    /**
     * @param int $countOfFollows
     * @return $this
     */
    public function setCountOfFollows($countOfFollows)
    {
        $this->countOfFollows = $countOfFollows;
        return $this;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return "{$this->id} {$this->fullName}";
    }
}