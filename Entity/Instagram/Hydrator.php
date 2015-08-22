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
     * @param array $row
     * @return User
     */
    public function getUserInfo($row)
    {
        $user = new User();
        $user
            ->setId($row["id"])
            ->setUsername($row["username"])
            ->setFullName($row["full_name"])
            ->setPictureUrl($row["profile_picture"])
            ->setBiography(isset($row["bio"]) ? $row["bio"] : "")
            ->setWebSite(isset($row["website"]) ? $row["website"] : "")
            ->setCountOfMedia(
                isset($row["counts"]) ? intval($row["counts"]["media"]) : ""
            )
            ->setCountOfFollowedBy(
                isset($row["counts"]) ? intval($row["counts"]["followed_by"]) : ""
            )
            ->setCountOfFollows(
                isset($row["counts"]) ? intval($row["counts"]["follows"]) : ""
            );

        return $user;
    }

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
            ->setLink($data["link"])
            ->setCaptionText(
                (isset($data["caption"]) && isset($data["caption"]["text"])) ?
                    $data["caption"]["text"] : ""
            );

        if ($data["type"] == "image") {
            $media->setType(Media::TYPE_IMAGE);
            $media->setSource($data["images"]["standard_resolution"]["url"]);
        } elseif ($data["type"] == "video") {
            $media->setType(Media::TYPE_VIDEO);
            $media->setSource($data["videos"]["standard_resolution"]["url"]);
        }

        $media->setUser(
            $this->getUserInfo($data["user"])
        );

        if (intval($data["comments"]["count"]) > 0) {
            foreach ($data["comments"]["data"] as $comment) {
                $createdAt = new \DateTime();
                $createdAt->setTimestamp(intval($comment["created_time"]));

                $commentObj = new Comment();
                $commentObj
                    ->setId($comment["id"])
                    ->setCreatedAt($createdAt)
                    ->setText($comment["text"]);
                $media->addComment($commentObj);
            }
        }

        return $media;
    }

    /**
     * @param array $data
     * @return User[]
     */
    public function getSearchResults($data)
    {
        $users = array();
        foreach ($data as $row) {
            $users[] = $this->getUserInfo($row);
        }

        return $users;
    }
}