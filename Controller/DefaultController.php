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

        $posts = array();// $vk->wallGet('199177108');

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

    /*public function vkCatchCodeAction(Request $request)
    {
        $code = $request->query->get("code");
        $this->get("vk_client")->getTokenByCodeAndSaveToCookies($code);

        return $this->redirect(
            $this->generateUrl("gfb_social_client_index")
        );
    }*/
}
