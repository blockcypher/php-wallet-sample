<?php

namespace BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SecurityController
 * @package BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $user = new \stdClass();
        $user->is_authenticated = false;

        return $this->render(
            'BlockCypherAppCommonInfrastructureAppCommonBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'is_home' => true,
                'coin_symbol' => 'btc',
                'user' => $user,
                'messages' => array(),
                'last_username' => $lastUsername,
                'error' => $error
            )
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginCheckWithBlockCypherTokenAction(Request $request)
    {
        return $this->redirectToRoute('bc_app_wallet_wallet.index');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function terminateAction()
    {
        // Logging user out.
        $this->get('security.token_storage')->setToken(null);

//        // Invalidating the session.
//        $session = $this->get('request')->getSession();
//        $session->invalidate();

        // Redirecting user to login page in the end.
        $response = $this->redirectToRoute('bc_homepage');

        // Clearing the cookies.
//        $cookieNames = [
//            $this->container->getParameter('session.name'),
//            $this->container->getParameter('session.remember_me.name'),
//        ];
//        foreach ($cookieNames as $cookieName) {
//            $response->headers->clearCookie($cookieName);
//        }

        return $response;
    }
}