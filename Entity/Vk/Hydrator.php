<?php
/**
 * Created by PhpStorm.
 * User: scherk01
 * Date: 10.07.2015
 * Time: 13:17
 */

namespace GFB\SocialClientBundle\Entity\Vk;


use GFB\SocialClientBundle\Service\VkontakteService;

class Hydrator
{
    /** @var VkontakteService */
    private $vkService;

    /**
     * @param $vkService
     */
    public function __construct($vkService)
    {
        $this->vkService = $vkService;
    }

    /**
     * @param array $arr
     * @return User[]
     */
    public function getUsers($arr)
    {
        $users = array();
        foreach ($arr as $row) {
            $users[] = $this->getUser($row);
        }
        return $users;
    }

    /**
     * @param array $row
     * @return User
     */
    public function getUser($row)
    {
        $user = new User();
        $user
            ->setId($row["uid"])
            ->setFirstName($row["first_name"])
            ->setLastName($row["last_name"])
            ->setSex($row["sex"])
            ->setNickName(isset($row["nickname"]) ? $row["nickname"] : "")
            ->setScreenName(isset($row["screen_name"]) ? $row["screen_name"] : "")
            ->setCityId(isset($row["city"]) ? $row["city"] : 0)
            ->setCountryId(isset($row["country"]) ? $row["country"] : "")
            ->setPhotoUrl($row["photo"])
            ->setPhotoMediumUrl($row["photo_medium"])
            ->setPhotoBigUrl($row["photo_big"])
            ->setHasMobile(isset($row["has_mobile"]) ? $row["has_mobile"] : false)
            ->setOnline($row["online"])
            ->setUniversity(isset($row["university"]) ? $row["university"] : "")
            ->setUniversityName(isset($row["university_name"]) ? $row["university_name"] : "")
            ->setFaculty(isset($row["faculty"]) ? $row["faculty"] : "")
            ->setFacultyName(isset($row["faculty_name"]) ? $row["faculty_name"] : "")
            ->setGraduationYear(isset($row["graduation"]) ? $row["graduation"] : "")
            ->setEducationForm(isset($row["education_form"]) ? $row["education_form"] : "")
            ->setEducationStatus(isset($row["education_status"]) ? $row["education_status"] : "");
        return $user;
    }

    /**
     * @param $arr
     * @return Post[]
     */
    public function getPosts($arr)
    {
        $posts = array();
        foreach ($arr as $row) {
            if (!isset($row["id"])) continue;

            $posts[] = $this->getPost($row);
        }
        return $posts;
    }

    /**
     * @param array $row
     * @return Post
     */
    public function getPost($row = array())
    {
        $attachments = [];
        if (isset($row["attachments"])) {
            foreach ($row["attachments"] as $data)
                $attachments[] = $this->getAttachment($data);
        }

        $post = new Post();
        $post
            ->setId(intval($row["id"]))
            ->setOwner(isset($row["to_id"]) ? $this->vkService->userGet($row["to_id"]) : null)
            ->setAuthor(isset($row["from_id"]) ? $this->vkService->userGet($row["from_id"]) : null)
            ->setCreatedAt((new \DateTime())->setTimestamp($row["date"]))
            ->setText($row["text"])
            ->setComments($row["comments"])
            ->setLikesCount(intval($row["likes"]))
            ->setRepostsCount(intval($row["reposts"]))
            ->setAttachments($attachments)
            ->setGeo(isset($row["geo"]) ? $row["geo"] : "")
            ->setSigner(isset($row["signer_id"]) ? $this->vkService->userGet($row["signer_id"]) : null)
            ->setCopyOwner(isset($row["copy_owner_id"]) ? $this->vkService->userGet($row["copy_owner_id"]) : null)
            ->setCopyText(isset($row["copy_text"]) ? $row["copy_text"] : "");
        return $post;
    }

    /**
     * @param array $data
     * @return null
     */
    public function getAttachment($data)
    {
        /*if ($data["type"] == "doc" && $data["doc"]["title"] == "file.gif") {
            echo "<pre>";
            print_r($data);
            echo "</pre>";
            die;
        }*/

        switch ($data["type"]) {
            case "photo":
                $photo = $data["photo"];
                $vkPhotoAttachment = new PhotoAttachment();
                $vkPhotoAttachment
                    ->setId($photo["pid"])
                    ->setOwner($photo["owner_id"])
                    ->setSrc($photo["src"])
                    ->setSrcBig($photo["src_big"])
                    ->setSrcSmall($photo["src_small"])
                    ->setWidth(isset($photo["width"]) ? intval($photo["width"]) : 0)
                    ->setHeight(isset($photo["height"]) ? intval($photo["height"]) : 0);
                return $vkPhotoAttachment;
            case "audio":
                $audio = $data["audio"];
                $vkAudioAttachment = new AudioAttachment();
                $vkAudioAttachment
                    ->setId($audio["aid"])
                    ->setTitle($audio["title"])
                    ->setArtist($audio["artist"])
                    ->setUrl($audio["url"])
                    ->setGenre(isset($audio["genre"]) ? intval($audio["genre"]) : 0);
                return $vkAudioAttachment;
            case "video":
                $video = $data["video"];
                $vkVideoAttachment = new VideoAttachment();
                $vkVideoAttachment
                    ->setId($video["vid"])
                    ->setOwner($this->vkService->userGet($video["owner_id"]))
                    ->setTitle($video["title"])
                    ->setDescription($video["description"])
                    ->setDate($video["date"])
                    ->setViews($video["views"])
                    ->setImage($video["image"])
                    ->setImageBig($video["image_big"])
                    ->setImageSmall($video["image_small"]);
                return $vkVideoAttachment;
            case "doc":
                $document = $data["doc"];
                $docAttachment = new DocAttachment();
                $docAttachment
                    ->setId($document["did"])
                    ->setOwner($this->vkService->userGet($document["owner_id"]))
                    ->setTitle($document["title"])
                    ->setSize($document["size"])
                    ->setExtension($document["ext"])
                    ->setUrl($document["url"])
                    ->setThumb($document["thumb"])
                    ->setThumbSmall($document["thumb_s"]);
                return $docAttachment;
            case "link":
                return new AudioAttachment();
            default:
                echo "Undefined type attachment - " . $data["type"];
        }
        return null;
    }
}