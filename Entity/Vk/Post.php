<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 03.03.2015
 * Time: 1:54
 */

namespace GFB\SocialClientBundle\Entity\Vk;

/**
 * Class Post
 * @package GFB\SocialClientBundle\Entity\Vk
 */
class Post
{
    /** @var int */
    private $id;

    /** @var User */
    private $owner;

    /** @var User */
    private $author;

    /** @var \DateTime */
    private $createdAt;

    /** @var string */
    private $text;

    /** @var array */
    private $comments;

    /** @var int */
    private $likesCount;

    /** @var int */
    private $repostsCount;

    /** @var array */
    private $attachments;

    /** @var string */
    private $geo;

    /** @var User */
    private $signer;

    /** @var User */
    private $copyOwner;

    /** @var Post */
    private $copyPost;

    /** @var string */
    private $copyText;

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
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;
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
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return array
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param array $comments
     * @return $this
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * @return int
     */
    public function getLikesCount()
    {
        return intval($this->likesCount);
    }

    /**
     * @param int $likesCount
     * @return $this
     */
    public function setLikesCount($likesCount)
    {
        $this->likesCount = $likesCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getRepostsCount()
    {
        return $this->repostsCount;
    }

    /**
     * @param int $repostsCount
     * @return $this
     */
    public function setRepostsCount($repostsCount)
    {
        $this->repostsCount = $repostsCount;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     * @return $this
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * @return string
     */
    public function getGeo()
    {
        return $this->geo;
    }

    /**
     * @param string $geo
     * @return $this
     */
    public function setGeo($geo)
    {
        $this->geo = $geo;
        return $this;
    }

    /**
     * @return User
     */
    public function getSigner()
    {
        return $this->signer;
    }

    /**
     * @param User $signer
     * @return $this
     */
    public function setSigner($signer)
    {
        $this->signer = $signer;
        return $this;
    }

    /**
     * @return User
     */
    public function getCopyOwner()
    {
        return $this->copyOwner;
    }

    /**
     * @param User $copyOwner
     * @return $this
     */
    public function setCopyOwner($copyOwner)
    {
        $this->copyOwner = $copyOwner;
        return $this;
    }

    /**
     * @return Post
     */
    public function getCopyPost()
    {
        return $this->copyPost;
    }

    /**
     * @param Post $copyPost
     * @return $this
     */
    public function setCopyPost($copyPost)
    {
        $this->copyPost = $copyPost;
        return $this;
    }

    /**
     * @return string
     */
    public function getCopyText()
    {
        return $this->copyText;
    }

    /**
     * @param string $copyText
     * @return $this
     */
    public function setCopyText($copyText)
    {
        $this->copyText = $copyText;
        return $this;
    }
}