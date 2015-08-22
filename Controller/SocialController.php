<?php

namespace GFB\SocialClientBundle\Controller;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use GFB\SocialClientBundle\Service\FacebookService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class SocialController extends Controller
{
    /* *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    /*public function vkontakteCodeAction(Request $request)
    {
        $code = $request->query->get("code");
        $state = $request->query->get("state");
        $this->get("vk_client")->getTokenByCodeAndSaveToCookies($code);
        return $this->redirect($state);
    }*/

    /* *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    /*public function instagramCodeAction(Request $request)
    {
        $code = $request->query->get("code");
        $state = $request->query->get("state");
//        $this->get("ig_client")->getTokenByCodeAndSaveToCookies($code);
        return $this->redirect($state);
    }*/

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function facebookLoginAction(Request $request)
    {
        try {
            $session = new Session();
            $session->start();
        } catch (\Exception $ex) {

        }

        $appId = $this->getParameter("gfb_social_client.facebook.app_id");
        $appSecret = $this->getParameter("gfb_social_client.facebook.app_secret");
        $version = $this->getParameter("gfb_social_client.facebook.version");
        $fb = new Facebook(array(
            'app_id' => $appId,
            'app_secret' => $appSecret,
            'default_graph_version' => $version,
        ));

        $helper = $fb->getRedirectLoginHelper();

        $redirect = "http://" . $request->getHost() . $this->generateUrl("gfb_social_client_facebook_code");

        $permissions = $this->getParameter("gfb_social_client.facebook.permissions"); // Optional permissions
        $permissions = explode(",", $permissions);
        $loginUrl = $helper->getLoginUrl($redirect, $permissions);

        return $this->render(
            "GFBSocialClientBundle:Social:facebook-login.html.twig",
            array("loginUrl" => $loginUrl)
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function facebookCodeAction(Request $request)
    {
        try {
            $session = new Session();
            $session->start();
        } catch (\Exception $ex) {

        }

        $appId = $this->getParameter("gfb_social_client.facebook.app_id");
        $appSecret = $this->getParameter("gfb_social_client.facebook.app_secret");
        $version = $this->getParameter("gfb_social_client.facebook.version");

        $fb = new Facebook(array(
            "app_id" => $appId,
            "app_secret" => $appSecret,
            "default_graph_version" => $version,
        ));

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            echo "Graph returned an error: " . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo "Facebook SDK returned an error: " . $e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header("HTTP/1.0 401 Unauthorized");
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header("HTTP/1.0 400 Bad Request");
                echo "Bad request";
            }
            exit;
        }

        // Logged in
        echo '<h3>Access Token</h3>';
        var_dump($accessToken->getValue());

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        echo '<h3>Metadata</h3>';
        var_dump($tokenMetadata);

        // Validation (these will throw FacebookSDKException's when they fail)
//        $tokenMetadata->validateAppId($appId);
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (FacebookSDKException $e) {
//                echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                exit;
            }

            echo '<h3>Long-lived</h3>';
            var_dump($accessToken->getValue());
        }

        $_SESSION[FacebookService::FB_ACCESS_TOKEN_SESSION] = (string)$accessToken;

        // User is logged in with a long-lived access token.
        // You can redirect them to a members-only page.
        //header('Location: https://example.com/members.php');
        return null;
    }
}
