<?php
/**
 * Created by PhpStorm.
 * User: scherk01
 * Date: 15.07.2015
 * Time: 17:13
 */

namespace GFB\SocialClientBundle\Entity\Instagram;


class Media
{
    const TYPE_IMAGE = 1;
    const TYPE_VIDEO = 2;

    /** @var int */
    private $id;

    /** @var string */
    private $captionText;

    /** @var User */
    private $user;

    /** @var array */
    private $tags = array();

    /** @var int */
    private $type;

    /** @var string */
    private $source;

    /** @var string */
    private $link;

    /** @var array */
    private $comments = array();

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
    public function getCaptionText()
    {
        return $this->captionText;
    }

    /**
     * @param string $captionText
     * @return $this
     */
    public function setCaptionText($captionText)
    {
        $this->captionText = $captionText;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function addTag($tag)
    {
        $this->tags[] = $tag;
        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;
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
     * @param Comment $comment
     * @return $this
     */
    public function addComment($comment)
    {
        $this->comments[] = $comment;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "{$this->id} [{$this->link}]";
    }
}