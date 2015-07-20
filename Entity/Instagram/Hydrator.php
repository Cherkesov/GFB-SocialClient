<?php
/**
 * Created by PhpStorm.
 * User: scherk01
 * Date: 15.07.2015
 * Time: 17:16
 */

namespace GFB\SocialClientBundle\Entity\Instagram;


class Hydrator
{
    /**
     * @param array $data
     * @return Media[]
     */
    public function getMediaList($data = array())
    {
        $result = array();
        foreach ($data as $item) {
            $result[] = $this->getMedia($item);
        }
        return $result;
    }

    /**
     * @param array $data
     * @return Media
     */
    public function getMedia($data = array())
    {
        $media = new Media();
        $media
            ->setId($data["id"])
            ->setLink($data["link"]);

        if ($data["type"] == "image") {
            $media->setType(Media::TYPE_IMAGE);
            $media->setSource($data["images"]["standard_resolution"]["url"]);
        } elseif ($data["type"] == "video") {
            $media->setType(Media::TYPE_VIDEO);
            $media->setSource($data["videos"]["standard_resolution"]["url"]);
        }

        return $media;
    }
}