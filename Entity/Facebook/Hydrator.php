<?php
/**
 * Created by PhpStorm.
 * User: scherk01
 * Date: 06.08.2015
 * Time: 19:09
 */

namespace GFB\SocialClientBundle\Entity\Facebook;

class Hydrator
{
    /**
     * @param array $data
     * @return User
     */
    public function getUser($data = array())
    {
        $user = new User();
        $user
            ->setId($data["id"])
            ->setName($data["name"]);
        return $user;
    }
}