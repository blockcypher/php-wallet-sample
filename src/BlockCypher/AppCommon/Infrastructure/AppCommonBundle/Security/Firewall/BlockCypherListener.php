<?php

namespace BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security\Firewall;

use BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security\BlockCypherUserToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

/**
 * Class BlockCypherListener
 * @package BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security\Firewall
 */
class BlockCypherListener implements ListenerInterface
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var AuthenticationManagerInterface
     */
    protected $authenticationManager;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param AuthenticationManagerInterface $authenticationManager
     * @param Session $session
     * @param RouterInterface $router
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        Session $session,
        RouterInterface $router
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @param GetResponseEvent $event
     * @return null|void
     */
    public function handle(GetResponseEvent $event)
    {
        $token = $this->tokenStorage->getToken();

        // DEBUG
        //var_dump($token);
        //die();

        if ($token !== null && $token->isAuthenticated()) {
            return null;
        }

        $request = $event->getRequest();
        $blockCypherToken = $request->get('blockcypher_token');

//        if (!$blockCypherToken) {
//            return null;
//        }

//        if ($request->getMethod() == 'post') {
//            if (empty($blockCypherToken) || trim($blockCypherToken) == '') {
//                return $this->redirectToLogin($event, 'Empty token. Please type your BlockCypher token.');
//            }
//        }

        //DEBUG
        //var_dump($blockCypherToken);
        //die('BCToken');

        // Create token
        //$user = new User(new UserId($blockCypherToken), $blockCypherToken);
        $preAuthToken = new BlockCypherUserToken(
            'anon.',
            $blockCypherToken,
            'blockcypher'
        );
        //$this->tokenStorage->setToken($preAuthToken);

        //$user = new User(new UserId($blockCypherToken), $blockCypherToken);
        //$token->setUser($user);

        // DEBUG
        //var_dump($token);
        //die('BlockCypherListener');

        try {

            $authToken = $this->authenticationManager->authenticate($preAuthToken);
            $this->tokenStorage->setToken($authToken);

            return null;

        } catch (AuthenticationException $failed) {
            $this->redirectToLogin($event, 'Authentication is required. ' . $failed->getMessage());
            return null;
        }
    }

    /**
     * @param GetResponseEvent $event
     * @param string $message
     */
    private function redirectToLogin(GetResponseEvent $event, $message)
    {
        $this->session->getFlashBag()->add('error', $message);
        $url = $this->router->generate('login_route');
        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }


}