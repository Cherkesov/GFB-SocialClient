<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 02.03.2015
 * Time: 22:08
 */

namespace GFB\SocialClientBundle\Service;


use GFB\SocialClientBundle\Entity\Vk\Hydrator;
use GFB\SocialClientBundle\Entity\Vk\Post;
use GFB\SocialClientBundle\Entity\Vk\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class VkService extends AbstractRestClient
{
    const VK_AUTHORIZE_URL = "https://oauth.vk.com/authorize";
    const VK_ACCESS_TOKEN_URL = "https://oauth.vk.com/access_token";
    /** @var Hydrator */
    protected $hydrator;

    const VK_TOKEN_COOKIE = "vk_token";

    const METHOD_USERS_GET = "users.get";
    const METHOD_USERS_SEARCH = "users.search";
    const METHOD_FRIENDS_GET = "friends.get";
    const METHOD_FRIENDS_GET_ONLINE = "friends.getOnline";
    const METHOD_FRIENDS_GET_MUTUAL = "friends.getMutual";
    const METHOD_GROUPS_GET_MEMBERS = "groups.getMembers";
    const METHOD_WALL_GET = "wall.get";
    const METHOD_WALL_POST = "wall.post";
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

    /** @var ContainerInterface */
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
        return $this->hydrator->getUsers($data);
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
        return $this->hydrator->getUsers($data);
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
        $posts = $this->hydrator->getPosts($data);
        return $posts;
    }

    /**
     * Поиск новостей
     * @param string $query
     * @param int $offset
     * @param int $count
     * @return \GFB\SocialClientBundle\Entity\Vk\Post[]
     */
    public function newsFeedSearch($query = "", $offset = 0, $count = 200)
    {
        $context = array(
            "q" => $query,
            "offset" => $offset,
            "count" => $count,
        );
        $response = parent::prepareRequest(self::METHOD_NEWSFEED_SEARCH, $context)->send();
//        $response = $this->prepareRequest(self::METHOD_WALL_POST, $context)->send();
        $data = $this->prepareResponse($response->getBody());
        $posts = $this->hydrator->getPosts($data["items"]);
        return $posts;
    }

    /**
     * @param string $tag
     * @return \GFB\SocialClientBundle\Entity\Vk\Post[]
     */
    public function newsFeedSearchByTag($tag)
    {
        return $this->newsFeedSearch("#{$tag}");
    }

    /**
     * @param $method
     * @param $context
     * @return \Guzzle\Http\Message\RequestInterface
     */
    protected function prepareRequest($method, $context = array())
    {
        if (!isset($_COOKIE[self::VK_TOKEN_COOKIE])) {
            $this->sendCodeRequest();
        }

        $context = array_merge($context, array(
            "access_token" => $_COOKIE[self::VK_TOKEN_COOKIE]
        ));

        return parent::prepareRequest($method, $context);
    }

    /**
     * @param string $data
     * @return array
     */
    protected function prepareResponse($data)
    {
//        echo "<pre>";
//        print_r($data);
//        echo "</pre>";
//        die;

        $arr = json_decode($data, true);
        return isset($arr["response"]) ? $arr["response"] : array();
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
    protected function sendCodeRequest()
    {
        $clientId = $this->container->getParameter("gfb_social_client.vkontakte.client_id");
        $scope = $this->container->getParameter("gfb_social_client.vkontakte.scope");
        $host = $this->request->getHost();
        $catchCodeUri = $this->router->generate("gfb_social_client_vkontakte_code");
        $apiVer = $this->container->getParameter("gfb_social_client.vkontakte.api_version");

        $state = $this->request->getRequestUri();

        $getCodeUrl = self::VK_AUTHORIZE_URL
            . "?client_id={$clientId}&scope={$scope}&"
            . "redirect_uri=http://{$host}{$catchCodeUri}&"
            . "response_type=code&v={$apiVer}&state={$state}";
        header("Location: {$getCodeUrl}");
        exit;
    }

    /**
     * Get token with code
     * Получаем токен по коду и сохраняем в куку
     * @param string $code
     */
    public function getTokenByCodeAndSaveToCookies($code)
    {
        $clientId = $this->container->getParameter("gfb_social_client.vkontakte.client_id");
        $clientSecret = $this->container->getParameter("gfb_social_client.vkontakte.client_secret");
        $host = $this->request->getHost();
        $redirect = $this->router->generate("gfb_social_client_vkontakte_code");

        $getCodeUrl = self::VK_ACCESS_TOKEN_URL
            . "?client_id={$clientId}&client_secret={$clientSecret}&"
            . "code={$code}&redirect_uri=http://{$host}{$redirect}";

        $ch = curl_init($getCodeUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $codeData = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($codeData, true);
        if (isset($data["access_token"])) {
            $token = $data["access_token"];
            setcookie(self::VK_TOKEN_COOKIE, $token, time() + 60 * 60 * 24 * 90, "/"); // TODO:
            setcookie("vk_token_date", date("Y-m-d H:i"), time() + 60 * 60 * 24 * 90, "/"); // TODO:
        } else {
            print_r($data);
            die; // TODO:
        }
    }
}