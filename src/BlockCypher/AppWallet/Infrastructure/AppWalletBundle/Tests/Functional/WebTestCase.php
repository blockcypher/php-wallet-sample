<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Tests\Functional;

use BlockCypher\AppCommon\Domain\User\User;
use BlockCypher\AppCommon\Domain\User\UserId;
use BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security\BlockCypherUserToken;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WebTestCase
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Tests\Functional
 */
class WebTestCase extends BaseWebTestCase
{
    const LOGGED_IN_USER_BC_TOKEN = 'c0afcccdde5081d6429de37d16166ead';

    /**
     * @var Client
     */
    protected $clientAuthenticated;

    /**
     * @var Client
     */
    protected $clientUnauthenticated;

    /**
     * @param Client $client
     * @param string $token
     */
    public function loginIn(Client $client, $token)
    {
        $session = $client->getContainer()->get('session');

        $user = new User(new UserId('$token'), $token);
        $token = new BlockCypherUserToken($user, $token, 'blockcypher', array('ROLE_USER'));

        $firewall = 'secured_area';
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function generateRoute($route, $params)
    {
        if (!is_array($params) || count($params) == 0)
            return $route;

        $renderedRoute = $route;
        foreach ($params as $paramKey => $paramValue) {
            $renderedRoute = str_replace('{' . $paramKey . '}', $paramValue, $renderedRoute);
        }

        return $renderedRoute;
    }

    /**
     * @param $response
     * @param int $statusCode
     * @param bool $checkValidJson
     * @param string $contentType
     */
//    public function assertJsonResponse(
//        $response,
//        $statusCode = 200,
//        $checkValidJson = true,
//        $contentType = 'application/json'
//    )
//    {
//        $this->assertEquals(
//            $statusCode, $response->getStatusCode(),
//            $response->getContent()
//        );
//        $this->assertTrue(
//            $response->headers->contains('Content-Type', $contentType),
//            $response->headers
//        );
//
//        if ($checkValidJson) {
//            $decode = json_decode($response->getContent());
//            $this->assertTrue(($decode != null && $decode != false),
//                'is response valid json: [' . $response->getContent() . ']'
//            );
//        }
//    }

    /**
     * @param Response|null $response
     */
    public function assertRedirectionToLogin($response)
    {
        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertRegExp('/\/login$/', $response->headers->get('location'));
    }
}