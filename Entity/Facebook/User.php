<?php
/**
 * Created by PhpStorm.
 * User: scherk01
 * Date: 06.08.2015
 * Time: 18:53
 */

namespace GFB\SocialClientBundle\Entity\Facebook;


class User
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}