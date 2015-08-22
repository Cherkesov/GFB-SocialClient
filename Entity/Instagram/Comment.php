<?php
/**
 * Created by PhpStorm.
 * User: scherk01
 * Date: 03.08.2015
 * Time: 18:52
 */

namespace GFB\SocialClientBundle\Entity\Instagram;


class Comment
{
    /** @var string */
    private $id;

    /** @var \DateTime */
    private $createdAt;

    /** @var string */
    private $text;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
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
}