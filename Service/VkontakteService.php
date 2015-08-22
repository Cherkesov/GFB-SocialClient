<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 02.03.2015
 * Time: 22:08
 */

namespace GFB\SocialClientBundle\Service;

use GFB\SocialClientBundle\Entity\Vkontakte\Hydrator;
use GFB\SocialClientBundle\Entity\Vkontakte\Post;
use GFB\SocialClientBundle\Entity\Vkontakte\User;

class VkontakteService extends AbstractRestClient
{
    const VK_AUTHORIZE_URL = "https://oauth.vk.com/authorize";
    const VK_ACCESS_TOKEN_URL = "https://oauth.vk.com/access_token";

    const METHOD_USERS_GET = "users.get";
    const METHOD_USERS_SEARCH = "users.search";
    const METHOD_FRIENDS_GET = "friends.get";
    const METHOD_GROUPS_GET_MEMBERS = "groups.getMembers";
    const METHOD_WALL_GET = "wall.get";
    const METHOD_NEWSFEED_SEARCH = "newsfeed.search";

    private $userFieldsArr = array(
        "uid",
        "first_name", "last_name", "nickname", "screen_name",
        "sex", "birthdate",
        "city", "country", "timezone",
        "photo", "photo_medium", "photo_big",
        "has_mobile", "rate", "contacts",
        "education", "online", "counters"
    );

    /** @var Hydrator */
    private $hydrator;

    /**
     * Default constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->hydrator = new Hydrator($this);
    }

    /**
     * @return string
     */
    protected function getApiUrl()
    {
        return "https://api.vk.com/method/";
    }

    /**
     * Получение данных пользователей по массиву идентификаторов
     *
     * @param array $usersIdsArr
     * @return \GFB\SocialClientBundle\Entity\Vkontakte\User[]
     */
    public function usersGet($usersIdsArr = array())
    {
        $context = array(
            "uids" => implode(",", $usersIdsArr),
            "fields" => implode(",", $this->userFieldsArr),
        );
        $response = $this->prepareRequest(self::METHOD_USERS_GET, $context)->send();
        $data = $this->prepareResponse($response->getBody());
        return $this->hydrator->getUsers($data);
    }

    /**
     * Получение данных пользователя по идентификатору
     *
     * @param string $userId
     * @return \GFB\SocialClientBundle\Entity\Vkontakte\User
     */
    public function userGet($userId)
    {
        $result = $this->usersGet([$userId]);
        return (count($result) > 0) ? $result[0] : null;
    }

    /**
     * Поиск пользователей
     *
     * @param string $query
     * @param int $count
     * @return \GFB\SocialClientBundle\Entity\Vkontakte\User[]
     */
    public function usersSearch($query, $count = 100)
    {
        // https://api.vkontakte.ru/method/users.search?q=Вася+Бабич&fields=nickname,screen_name,sex,bdate,city,country&client_id=4809172&client_secret=Htk21jHYGLVjXeBrVbcm

        $context = array(
            "q" => $query,
            "fields" => implode(",", $this->userFieldsArr),
            "count" => $count,
        );
        $response = $this->prepareRequest(self::METHOD_USERS_SEARCH, $context)->send();
        $data = $this->prepareResponse($response->getBody());
        return $this->hydrator->getUsers($data);
    }

    /**
     * Возвращает список друзей пользователя
     * @param string $userId
     * @return \GFB\SocialClientBundle\Entity\Vkontakte\User[]
     */
    public function friendsGet($userId)
    {
        $context = array("uid" => $userId);
        $response = $this->prepareRequest(self::METHOD_FRIENDS_GET, $context)->send();
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
        $user = $this->userGet($ownerId);

        $context = array(
            "owner_id" => $user->getId(),
            "count" => $count,
            "offset" => $offset,
            "filter" => $filter,
        );
        $response = $this->prepareRequest(self::METHOD_WALL_GET, $context)->send();
        $data = $this->prepareResponse($response->getBody());
        array_shift($data);

        $posts = $this->hydrator->getPosts($data);
        return $posts;
    }

    /**
     * Поиск новостей
     * @param string $query
     * @param int $offset
     * @param int $count
     * @return \GFB\SocialClientBundle\Entity\Vkontakte\Post[]
     */
    public function newsFeedSearch($query = "", $offset = 0, $count = 200)
    {
        // https://api.vk.com/method/newsfeed.search?q=hello&offset=0&count=200

        $context = array(
            "q" => $query,
            "offset" => $offset,
            "count" => $count,
        );
        $response = parent::prepareRequest(self::METHOD_NEWSFEED_SEARCH, $context)->send();
        $data = $this->prepareResponse($response->getBody());

        $posts = $this->hydrator->getPosts($data);
        return $posts;
    }

    /**
     * @param string $tag
     * @return \GFB\SocialClientBundle\Entity\Vkontakte\Post[]
     */
    public function newsFeedSearchByTag($tag)
    {
        // https://api.vk.com/method/newsfeed.search?q=#hello&offset=0&count=200

        return $this->newsFeedSearch("#{$tag}");
    }

    /**
     * @param string $data
     * @return array
     */
    protected function prepareResponse($data)
    {
        $arr = json_decode($data, true);
        return isset($arr["response"]) ? $arr["response"] : array();
    }
}