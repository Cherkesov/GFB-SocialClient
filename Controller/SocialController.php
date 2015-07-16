<?php

namespace GFB\SocialClientBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SocialController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function vkontakteCodeAction(Request $request)
    {
        $code = $request->query->get("code");
        $state = $request->query->get("state");
        $this->get("vk_client")->getTokenByCodeAndSaveToCookies($code);
        return $this->redirect($state);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function instagramCodeAction(Request $request)
    {
        $code = $request->query->get("code");
        $state = $request->query->get("state");
        $this->get("ig_client")->getTokenByCodeAndSaveToCookies($code);
        return $this->redirect($state);
    }
}
