<?php

namespace GFB\SocialClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $vk = $this->get('vk_client');
//        $data = $vk->usersGet(['nkmelanj', 'stemmuel', 'will_grymm', 'holodnyi_veter', 'dandrumkiller']);
//        $data = $vk->friendsGet('199177108');
        $groupMembers = $vk->groupsGetMembers('anastacia_koptiaeva');
//        $data = $vk->usersSearch('Строгин Артем');

        $posts = $vk->wallGet('199177108');

//        echo '<pre>';
//        print_r($data);
//        echo '</pre>';
//        die;

        return $this->render(
            'GFBSocialClientBundle:Default:index.html.twig',
            array(
                'vkUsers' => $groupMembers,
                'posts' => $posts
            )
        );
    }

    /*public function vkAction()
    {
        $catchCode = $this->generateUrl("gfb_social_client_vk_catch_code");

        $clientId = $this->container->getParameter('gfb_social_client.vk.client_id');
        $scope = $this->container->getParameter('gfb_social_client.vk.scope');
        $apiVer = $this->container->getParameter('gfb_social_client.vk.api_version');

        $getCodeUrl = "https://oauth.vk.com/authorize?"
            . "client_id={$clientId}&scope={$scope}&"
            . "redirect_uri=http://bms.dev{$catchCode}&response_type=code&v={$apiVer}";

        return $this->redirect($getCodeUrl);
    }*/

    public function vkCatchCodeAction(Request $request)
    {
        $code = $request->query->get("code");
        $this->get("vk_client")->getTokenByCodeAndSaveToCookies($code);

        return $this->redirect(
            $this->generateUrl("gfb_social_client_index")
        );
    }
}
