<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 12.04.2015
 * Time: 17:42
 */

namespace GFB\SocialClientBundle\Service;

use GFB\SocialClientBundle\Entity\Facebook\Hydrator;
use Guzzle\Http\Client as GuzzleClient;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\FacebookApp;
use Facebook\FacebookRequest;
use Facebook\GraphNodes\GraphUser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class FacebookService extends AbstractRestClient
{
    const FB_ACCESS_TOKEN_SESSION = "fb_access_token";
    const METHOD_LINKS_GET_STATS = "links.getStats";

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
        $this->hydrator = new Hydrator();
    }

    /**
     * @param int $userId
     * @return \Facebook\GraphNodes\GraphObject
     */
    public function getUser($userId = 100003261022760)
    {
        //https://graph.facebook.com/100003261022760/?access_token=381700235355893|n_3eHk4foB-UMWgW6DvJgjyTS6k
        $this->client = new GuzzleClient("https://graph.facebook.com/");

        $appId = $this->container->getParameter("gfb_social_client.facebook.app_id");
        $appSecret = $this->container->getParameter("gfb_social_client.facebook.app_secret");
        $version = $this->container->getParameter("gfb_social_client.facebook.version");

        $tokenResponse = $this->prepareRequest("/oauth/access_token", array(
            "grant_type" => "client_credentials",
            "client_id" => $appId, // "381700235355893",
            "client_secret" => $appSecret, //"ba0317c19b91b1d47197218298ed6a23",
        ))->send();
        $token = str_replace("access_token=", "", $tokenResponse->getBody());

        $userResponse = $this->prepareRequest("/{$userId}/", array(
            "access_token" => $token,
        ))->send();
        return $this->hydrator->getUser($userResponse->getBody());


        /*$facebook = new Facebook(array(
            "app_id" => $appId,
            "app_secret" => $appSecret,
            "default_graph_version" => $version,
        ));

        $response = $facebook->get(
            "/{$userId}",
            $_SESSION[self::FB_ACCESS_TOKEN_SESSION],
            null,
            $version
        );

        return $response->getGraphObject();*/
    }

    public function getFriend()
    {
        try {
            $session = new Session();
            $session->start();
        } catch (\Exception $ex) {

        }

        $appId = $this->container->getParameter("gfb_social_client.facebook.app_id");
        $appSecret = $this->container->getParameter("gfb_social_client.facebook.app_secret");
        $version = $this->container->getParameter("gfb_social_client.facebook.version");

        $facebook = new Facebook(array(
            "app_id" => $appId,
            "app_secret" => $appSecret,
            "default_graph_version" => $version,
        ));

        if (!isset($_SESSION[self::FB_ACCESS_TOKEN_SESSION])) {
            $url = $this->router->generate("gfb_social_client_facebook_login");
            header("Location: {$url}");
            exit;
        }

        $response = $facebook->get(
            "/me/friends",
            $_SESSION[self::FB_ACCESS_TOKEN_SESSION],
            null,
            $version
        );
        return $response->getGraphEdge()->asArray();

        $response = $facebook->get(
            "/me/taggable_friends",
            $_SESSION[self::FB_ACCESS_TOKEN_SESSION],
            null,
            $version
        );
        $graphNode = $response->getGraphEdge();
        return $graphNode->all();
    }

    public function sendPostToWall()
    {
        $session = new Session();
        $session->start();

        $appId = $this->container->getParameter("gfb_social_client.facebook.app_id");
        $appSecret = $this->container->getParameter("gfb_social_client.facebook.app_secret");
        $version = $this->container->getParameter("gfb_social_client.facebook.version");

        $facebook = new Facebook(array(
            "app_id" => $appId,
            "app_secret" => $appSecret,
            "default_graph_version" => $version,
        ));

        $linkData = [
            "link" => "http://www.example.com",
            "message" => "Test message",
        ];
        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $facebook->post("/me/feed", $linkData, $_SESSION[self::FB_ACCESS_TOKEN_SESSION]);
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $graphNode = $response->getGraphNode();
        echo "<pre>";
        print_r($graphNode);
        echo "</pre><br/><br/><br/>";
        die;
    }

    public function sendMessage()
    {
        $session = new Session();
        $session->start();

        $appId = $this->container->getParameter("gfb_social_client.facebook.app_id");
        $appSecret = $this->container->getParameter("gfb_social_client.facebook.app_secret");
        $version = $this->container->getParameter("gfb_social_client.facebook.version");

        $facebook = new Facebook(array(
            "app_id" => $appId,
            "app_secret" => $appSecret,
            "default_graph_version" => $version,
        ));

        //100003261022760
        //100007644586543

        $linkData = array(
//            "link" => "http://www.example.com",
            "message" => "Test message",
            "name" => "sdfds jj jjjsdj j j ",
            "link" => "https://apps.facebook.com/xxxxxxxaxsa",
//            "to" => "AaJCET_LPQAxniBSfzhExnJJBkUXRlHIdQ-AS16_Z8hZiMhr210BE6HQxERY7BXR7ETQyAi0jlHJdoT9wRzQeuNAYHjNJbKGPD9hfrNUey2o2Q",
            "to" => "100008162627320",
        );
        $response = $facebook->post(
            "/send",
            $linkData,
            $_SESSION[self::FB_ACCESS_TOKEN_SESSION],
            null,
            $version
        );
        $graphNode = $response->getGraphEdge();
        echo "<pre>";
        print_r($graphNode->asArray());
        echo "</pre><br/><br/><br/>";
        die;


        // ============================================================================

        /*$linkData = [
            "link" => "http://www.example.com",
            "message" => "Test message",
        ];
        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $facebook->post("/me/feed", $linkData, $_SESSION["fb_access_token"]);
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $graphNode = $response->getGraphNode();
        echo "<pre>";
        print_r($graphNode);
        echo "</pre><br/><br/><br/>";
        die;*/
    }

    private function getLinkStat($url, $format = "json")
    {
        // https://api.facebook.com/method/links.getStats?urls=http://hillsclub.ru/cat/&format=json

        $context = array(
            "urls" => $url,
            "format" => $format,
        );
        $response = $this->prepareRequest(self::METHOD_LINKS_GET_STATS, $context)->send();
        $data = $this->prepareResponse($response->getBody());
        return $data;
    }

    /**
     * Sharing count
     * @param string $url
     * @param string $format
     * @return int
     */
    public function getSharingCount($url, $format = "json")
    {
        $data = $this->getLinkStat($url, $format);
        return isset($data[0]) ? intval($data[0]["share_count"]) : 0;
    }

    /**
     * Likes count
     * @param string $url
     * @param string $format
     * @return int
     */
    public function getLikesCount($url, $format = "json")
    {
        $data = $this->getLinkStat($url, $format);
        return isset($data[0]) ? intval($data[0]["like_count"]) : 0;
    }

    /**
     * Comments count
     * @param string $url
     * @param string $format
     * @return int
     */
    public function getCommentsCount($url, $format = "json")
    {
        $data = $this->getLinkStat($url, $format);
        return isset($data[0]) ? intval($data[0]["comment_count"]) : 0;
    }

    /**
     * @return string
     */
    protected function getApiUrl()
    {
        return "https://api.facebook.com/method/";
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
}