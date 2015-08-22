<?php

namespace GFB\SocialClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $vkontakte = $this->get("vk_client");
        $instagram = $this->get("ig_client");
        $facebook = $this->get("fb_client");

//        $friends = $facebook->getUser();
        /*$friends = $facebook->getFriend();
        echo "<pre>";
        print_r($friends);
        echo "</pre>";
        die;*/

//        $facebook->sendMessage();
//        die;

        $mediaList = array();
        $igTag = $request->query->get("ig_tag");
        if (!empty($igTag)) {
            $mediaList = $instagram->getMediaListByTag($igTag);
        }

        $userWallPosts = array();
        $vkUserId = $request->query->get("vk_user_id");
        if (!empty($vkUserId)) {
            $userWallPosts = $vkontakte->wallGet($vkUserId);
        }

        $tagPosts = array();
        $vkTag = $request->query->get("vk_tag");
        if (!empty($vkTag)) {
            $tagPosts = $vkontakte->newsFeedSearchByTag($vkTag);
        }

        $groupMembers = array();
        $vkGroupId = $request->query->get("vk_group_id");
        if (!empty($vkGroupId)) {
            $groupMembers = $vkontakte->groupsGetMembers($vkGroupId);
        }

        return $this->render(
            "GFBSocialClientBundle:Default:index.html.twig",
            array(
                "vkUsers" => $groupMembers,
                "userWallPosts" => $userWallPosts,
                "tagPosts" => $tagPosts,
                "mediaList" => $mediaList,
            )
        );
    }
}
