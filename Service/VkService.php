<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 02.03.2015
 * Time: 22:08
 */

namespace GFB\SocialClientBundle\Service;

use GFB\SocialClientBundle\Entity\Vk\DocAttachment;
use GFB\SocialClientBundle\Entity\Vk\Post;
use GFB\SocialClientBundle\Entity\Vk\AudioAttachment;
use GFB\SocialClientBundle\Entity\Vk\PhotoAttachment;
use GFB\SocialClientBundle\Entity\Vk\User;
use GFB\SocialClientBundle\Entity\Vk\VideoAttachment;
use Guzzle\Http\Client as GuzzleClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

class VkService
{
    const VK_API_URL = "https://api.vk.com/method/";

    /** @var GuzzleClient */
    protected $client;

    const VK_TOKEN_COOKIE = "vk_token";

    const METHOD_USERS_GET = "users.get";
    const METHOD_USERS_SEARCH = "users.search";
    const METHOD_FRIENDS_GET = "friends.get";
    const METHOD_FRIENDS_GET_ONLINE = "friends.getOnline";
    const METHOD_FRIENDS_GET_MUTUAL = "friends.getMutual";
    const METHOD_GROUPS_GET_MEMBERS = "groups.getMembers";
    const METHOD_WALL_GET = "wall.get";
    const METHOD_WALL_POST = "wall.post";

    private $userFieldsArr = array(
        "uid",
        "first_name", "last_name", "nickname", "screen_name",
        "sex", "birthdate",
        "city", "country", "timezone",
        "photo", "photo_medium", "photo_big",
        "has_mobile", "rate", "contacts",
        "education", "online", "counters"
    );

    /**
     * @var ContainerInterface
     */
    private $container;

    /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router */
    private $router;

    /** @var \Symfony\Component\HttpFoundation\Request */
    private $request;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->client = new GuzzleClient(self::VK_API_URL);

        if (!isset($_COOKIE[self::VK_TOKEN_COOKIE])) {
            $this->runTokenReceiving();
        }

//        if ($this->router->})
    }

    /**
     * @param array $usersIdsArr
     * @return \GFB\SocialClientBundle\Entity\Vk\User[]
     */
    public function usersGet($usersIdsArr = array())
    {
        $context = [
            "uids" => implode(",", $usersIdsArr),
            "fields" => implode(",", $this->userFieldsArr),
        ];
        $response = $this->prepareRequest(self::METHOD_USERS_GET, $context)->send();
        $data = $this->prepareResponse($response->getBody());
        return $this->arrayToUsers($data);
    }

    /**
     * @param string $userId
     * @return \GFB\SocialClientBundle\Entity\Vk\User
     */
    public function userGet($userId)
    {
        $result = $this->usersGet([$userId]);
        return (count($result) > 0) ? $result[0] : null;
    }

    /**
     * @param string $query
     * @param int $count
     * @return \GFB\SocialClientBundle\Entity\Vk\User[]
     */
    public function usersSearch($query, $count = 100)
    {
        $context = array(
            "q" => $query,
            "fields" => implode(",", $this->userFieldsArr),
            "count" => $count,
        );
        $response = $this->prepareRequest(self::METHOD_USERS_SEARCH, $context)->send();
        $data = $this->prepareResponse($response->getBody());
        return $this->arrayToUsers($data);
    }

    /**
     * Возвращает список друзей пользователя
     * @param string $userId
     * @return User[]
     */
    public function friendsGet($userId)
    {
        $context = array("uid" => $userId);
        $response = $this->prepareRequest(self::METHOD_FRIENDS_GET, $context)->send();
        $data = $this->prepareResponse($response->getBody());
        return $this->usersGet($data);
    }

    /**
     * Возвращает список друзей пользователя, который сейчас в онлайне
     * @param string $userId
     * @return User[]
     */
    public function friendsGetOnline($userId)
    {
        $context = array("uid" => $userId);
        $response = $this->prepareRequest(self::METHOD_FRIENDS_GET_ONLINE, $context)->send();
        $data = $this->prepareResponse($response->getBody());
        return $this->usersGet($data);
    }

    /**
     * Возвращает список общих друзей пользователей
     * @param string $targetId
     * @param string $sourceId
     * @return User[]
     */
    public function friendsGetMutual($targetId, $sourceId)
    {
        $context = array(
            "target_uid" => $targetId,
            "source_uid" => $sourceId,
        );
        $response = $this->prepareRequest(self::METHOD_FRIENDS_GET_MUTUAL, $context)->send();
        $data = $this->prepareResponse($response->getBody());
        return $this->usersGet($data);
    }

    /**
     * Возвращает список учасников группы
     * @param string $gid
     * @param int $count
     * @param int $offset
     * @return User[]
     */
    public function groupsGetMembers($gid, $count = 200, $offset = 0)
    {
        $context = array(
            "gid" => $gid,
            "count" => $count,
            "offset" => $offset,
            "sort" => "id_asc", //  id_asc, id_desc, time_asc, time_desc
        );
        $response = $this->prepareRequest(self::METHOD_GROUPS_GET_MEMBERS, $context)->send();
        $data = $this->prepareResponse($response->getBody());
        return isset($data["users"]) ? $this->usersGet($data["users"]) : null;
    }

    /**
     * Возвращает список записей со стены пользователя
     * @param string $ownerId
     * @param int $count
     * @param int $offset
     * @param string $filter = owner|others|all
     * @return Post[]
     */
    public function wallGet($ownerId, $count = 200, $offset = 0, $filter = "all")
    {
        $context = array(
            "owner_id" => $ownerId,
            "count" => $count,
            "offset" => $offset,
            "filter" => $filter,
        );
        $response = $this->prepareRequest(self::METHOD_WALL_GET, $context)->send();
        $data = $this->prepareResponse($response->getBody());
        array_shift($data);

//        echo "<pre>";
//        print_r($data);
//        echo "</pre>";
//        die;

        $posts = $this->arrayToPosts($data);
        return $posts;
    }

    /**
     * Возвращает список записей со стены пользователя
     * @param int $ownerId
     * @param string $message
     * @param array $attachments
     * @return Post[]
     */
    public function wallPost($ownerId, $message, $attachments = [])
    {
        $context = array(
            "owner_id" => $ownerId,
            "message" => $message,
            "attachments" => $attachments,
        );
        $response = $this->prepareRequest(self::METHOD_WALL_POST, $context)->send();
        $data = $this->prepareResponse($response->getBody());
        $posts = $this->arrayToPosts($data);
        return $posts;
    }

    /**
     * @param $method
     * @param $context
     * @return \Guzzle\Http\Message\RequestInterface
     */
    private function prepareRequest($method, $context)
    {
        $request = $this->client->get($method);
        $context = array_merge($context, ["access_token" => $_COOKIE[self::VK_TOKEN_COOKIE]]);
        $query = $request->getQuery();
        foreach ($context as $key => $value) {
            $query->set($key, $value);
        }
        return $request;
    }

    /**
     * @param string $data
     * @return array
     */
    private function prepareResponse($data)
    {
//        echo "<pre>";
//        print_r($data);
//        echo "</pre>";
//        die;

        $arr = json_decode($data, true);
        return isset($arr["response"]) ? $arr["response"] : [];
    }

    /**
     * @param array $row
     * @return User
     */
    private function arrayToUser($row)
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
     * @param array $arr
     * @return User[]
     */
    private function arrayToUsers($arr)
    {
        $users = array();
        foreach ($arr as $row) {
            $users[] = $this->arrayToUser($row);
        }
        return $users;
    }

    /**
     * @param $arr
     * @return Post[]
     */
    private function arrayToPosts($arr)
    {
        $posts = array();
        foreach ($arr as $row) {
            $posts[] = $this->arrayToPost($row);
        }
        return $posts;
    }

    /**
     * @param array $row
     * @return Post
     */
    private function arrayToPost($row)
    {
//        echo "<pre>";
//        print_r(isset($row["attachments"]) ? $row["attachments"] : []);
//        echo "</pre>";
//        die;

        $attachments = [];
        if (isset($row["attachments"])) {
            foreach ($row["attachments"] as $data)
                $attachments[] = $this->arrayToAttachment($data);
        }


        $post = new Post();
        $post
            ->setId(intval($row["id"]))
            ->setOwner(isset($row["to_id"]) ? $this->userGet($row["to_id"]) : null)
            ->setAuthor(isset($row["from_id"]) ? $this->userGet($row["from_id"]) : null)
            ->setCreatedAt((new \DateTime())->setTimestamp($row["date"]))
            ->setText($row["text"])
            ->setComments($row["comments"])
            ->setLikesCount(intval($row["likes"]))
            ->setRepostsCount(intval($row["reposts"]))
            ->setAttachments($attachments)
            ->setGeo(isset($row["geo"]) ? $row["geo"] : "")
            ->setSigner(isset($row["signer_id"]) ? $this->userGet($row["signer_id"]) : null)
            ->setCopyOwner(isset($row["copy_owner_id"]) ? $this->userGet($row["copy_owner_id"]) : null)
            ->setCopyText(isset($row["copy_text"]) ? $row["copy_text"] : "");
        return $post;
    }

    /**
     * @param array $data
     * @return null
     */
    private function arrayToAttachment($data)
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
                    ->setOwner($this->userGet($video["owner_id"]))
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
                    ->setOwner($this->userGet($document["owner_id"]))
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

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * Method send request to VK API for getting code which need for getting token
     * Метод для отправки запроса в ВК API для получения кода, который потребуется для получения токена
     */
    protected function runTokenReceiving()
    {
        $clientId = $this->container->getParameter("gfb_social_client.vk.client_id");
        $scope = $this->container->getParameter("gfb_social_client.vk.scope");
        $host = $this->request->getHost();
        $catchCodeUri = $this->router->generate("gfb_social_client_vk_catch_code");
        $apiVer = $this->container->getParameter("gfb_social_client.vk.api_version");

        $getCodeUrl = "https://oauth.vk.com/authorize?"
            . "client_id={$clientId}&scope={$scope}&"
            . "redirect_uri=http://{$host}{$catchCodeUri}&response_type=code&v={$apiVer}";
        header("Location: {$getCodeUrl}");
        exit;
    }

    /**
     * @param string $code
     */
    public function getTokenByCodeAndSaveToCookies($code)
    {
        $clientId = $this->container->getParameter("gfb_social_client.vk.client_id");
        $clientSecret = $this->container->getParameter("gfb_social_client.vk.client_secret");
        $host = $this->request->getHost();
        $redirect = $this->router->generate("gfb_social_client_vk_catch_code");

        $getCodeUrl = "https://oauth.vk.com/access_token?client_id={$clientId}&client_secret={$clientSecret}&code={$code}&redirect_uri=http://{$host}{$redirect}";

        $ch = curl_init($getCodeUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $codeData = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($codeData, true);
        $token = $data["access_token"];
        setcookie(self::VK_TOKEN_COOKIE, $token, time() + 60 * 60 * 24 * 30, "/"); // TODO:
    }
}