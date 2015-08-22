<?php
/**
 * Created by PhpStorm.
 * User: scherk01
 * Date: 15.07.2015
 * Time: 16:48
 */

namespace GFB\SocialClientBundle\Service;

use GFB\SocialClientBundle\Entity\Instagram\Hydrator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class InstagramService
 * @package GFB\SocialClientBundle\Service
 */
class InstagramService extends AbstractRestClient
{
    const INSTAGRAM_API_AUTHORIZE = "https://api.instagram.com/oauth/authorize/";
    const INSTAGRAM_API_ACCESS_TOKEN = "https://api.instagram.com/oauth/access_token";

    /** @var ContainerInterface */
    private $container;

    /**
     * Default constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->hydrator = new Hydrator();
    }

    /**
     * @return string
     */
    protected function getApiUrl()
    {
        return "https://api.instagram.com/v1/";
    }

    /**
     * @param string $method
     * @param array $context
     * @return \Guzzle\Http\Message\RequestInterface
     */
    protected function prepareRequest($method, $context = array())
    {
        $context["client_id"] = $this->container
            ->getParameter("gfb_social_client.instagram.client_id");
        $context["client_secret"] = $this->container
            ->getParameter("gfb_social_client.instagram.client_secret");
        return parent::prepareRequest($method, $context);
    }

    /**
     * @param string $data
     * @return array
     */
    protected function prepareResponse($data)
    {
        return parent::prepareResponse($data)["data"];
    }

    /**
     * @param int $userId
     * @return \GFB\SocialClientBundle\Entity\Instagram\User
     */
    public function getUserInfo($userId)
    {
        $method = "/v1/users/{$userId}/";
        $response = $this->prepareRequest($method, array(
            "count" => 1000,
        ))->send();
        $data = $this->prepareResponse($response->getBody());
        $user = $this->hydrator->getUserInfo($data);
        return $user;
    }

    public function getUserMediaRecent($userId)
    {
        $method = "/v1/users/{$userId}/media/recent/";
        $response = $this->prepareRequest($method, array(
            "count" => 1000,
        ))->send();
        $data = $this->prepareResponse($response->getBody());
        $media = $this->hydrator->getMediaList($data);
        return $media;
    }

    /**
     * @param string $tagName
     * @return \GFB\SocialClientBundle\Entity\Instagram\Media[]
     */
    public function getMediaListByTag($tagName)
    {
        $method = "/v1/tags/{$tagName}/media/recent";
        $response = $this->prepareRequest($method, array(
            "count" => 1000,
        ))->send();
        $data = $this->prepareResponse($response->getBody());
        $mediaList = $this->hydrator->getMediaList($data);
        return $mediaList;
    }

    /**
     * @param string $id
     * @return \GFB\SocialClientBundle\Entity\Instagram\Media
     */
    public function getMediaById($id)
    {
        $method = "/v1/media/{$id}";
        try {
            $response = $this->prepareRequest($method, array())->send();
        } catch(\Exception $ex) {
            return null;
        }
        $data = $this->prepareResponse($response->getBody());
        $media = $this->hydrator->getMedia($data);
        return $media;
    }

    /**
     * @param string $query
     * @return \GFB\SocialClientBundle\Entity\Instagram\User[]
     */
    public function search($query)
    {
        $method = "/v1/users/search?q={$query}";
        $response = $this->prepareRequest($method, array(
            "count" => 1000,
        ))->send();
        $data = $this->prepareResponse($response->getBody());
        $results = $this->hydrator->getSearchResults($data);
        return $results;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /* *
     *
     */
    /*protected function sendCodeRequest()
    {
        $clientId = $this->container->getParameter("gfb_social_client.instagram.client_id");
        $redirect = $this->getRedirectUrl();
        $state = $this->request->getRequestUri();

        $url = self::INSTAGRAM_API_AUTHORIZE
            . "?client_id={$clientId}&"
            . "redirect_uri={$redirect}&"
            . "response_type=code&"
            . "state={$state}";
        header("Location: {$url}");
        exit;
    }*/

    /* *
     * @param string $code
     */
    /*public function getTokenByCodeAndSaveToCookies($code)
    {
        $clientId = $this->container->getParameter("gfb_social_client.instagram.client_id");
        $clientSecret = $this->container->getParameter("gfb_social_client.instagram.client_secret");
        $redirect = $this->getRedirectUrl();

        $url = self::INSTAGRAM_API_ACCESS_TOKEN
            . "?client_id={$clientId}&"
            . "client_secret={$clientSecret}&"
            . "grant_type=authorization_code&"
            . "redirect_uri={$redirect}&"
            . "code={$code}";
        $postFields = "client_id={$clientId}&"
            . "client_secret={$clientSecret}&"
            . "grant_type=authorization_code&"
            . "redirect_uri={$redirect}&"
            . "code={$code}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        $codeData = curl_exec($ch);
        curl_close($ch);

        echo "Get code {$url} -> {$codeData}";

        $data = json_decode($codeData, true);
        if (isset($data["access_token"])) {
            $token = $data["access_token"];
            setcookie(self::IG_TOKEN_COOKIE, $token, time() + 60 * 60 * 24 * 90, "/");
        } else {
            print_r($data);
            die;
        }
    }*/
}