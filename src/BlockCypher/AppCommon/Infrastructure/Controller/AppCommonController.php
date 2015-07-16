<?php

namespace BlockCypher\AppCommon\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AppCommonController
 * @package BlockCypher\AppCommon\Infrastructure\Controller
 */
class AppCommonController extends Controller
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var MessageBag
     */
    protected $messageBag;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param Session $session
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->session = $session;
        $this->messageBag = new MessageBag();
    }

    /**
     * @return string
     */
    protected function getEngine()
    {
        return 'twig';
    }

    /**
     * Shortcut to trans. Consider to put it in some common parent controller.
     * @param $id
     * @param array $parameters
     * @param string $domain
     * @param null $locale
     * @return string
     */
    protected function trans(
        $id,
        $parameters = array(),
        $domain = 'BlockCypherAppCommonInfrastructureAppCommonBundle',
        $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    /**
     * Adds a message to the current response.
     *
     * @param string $type The type
     * @param string $message The message
     */
    protected function addMessage($type, $message)
    {
        $this->getMessageBag()->add($type, $message);
    }

    /**
     * @return MessageBag
     */
    public function getMessageBag()
    {
        return $this->messageBag;
    }

    /**
     * Adds a flash message to the current session for type.
     *
     * @param string $type The type
     * @param string $message The message
     *
     * @throws \LogicException
     */
    protected function addFlash($type, $message)
    {
        $this->session->getFlashBag()->add($type, $message);
    }

    /**
     * @param $form
     * @return string
     */
    protected function getAllFormErrorMessagesAsString($form)
    {
        $msgArr = $this->getAllFormErrorMessages($form);

        // DEBUG
        //var_dump($msgArr);
        //die();

        $message = '';
        if (is_array($msgArr) && count($msgArr) > 0) {
            foreach ($msgArr as $childName => $messages) {
                $message .= 'Error in field ' . $childName . '. ';
                if (is_array($messages)) {
                    foreach ($messages as $msg) {
                        $message .= $msg . ' ';
                    }
                } else {
                    $message .= $messages . ' ';
                }
                $message .= PHP_EOL;
            }
        }
        return $message;
    }

    /**
     * Get form validation errors.
     * @param Form| $form
     * @return array
     */
    protected function getAllFormErrorMessages($form)
    {
        // DEBUG
        //var_dump($form->getErrors($deep));
        //die();

        $messagesArray = array();
        foreach ($form->getErrors() as $key => $error) {
            if ($error->getMessagePluralization() !== null) {
                $messagesArray['message'] = $this->translator->transChoice(
                    $error->getMessage(),
                    $error->getMessagePluralization(),
                    $error->getMessageParameters(),
                    'validators'
                );
            } else {
                $messagesArray['message'] = $this->translator->trans($error->getMessage(), array(), 'validators');
            }
        }

        // Children errors
        foreach ($form->all() as $name => $child) {
            $errors = $this->getAllFormErrorMessages($child);
            if (!empty($errors)) {
                $messagesArray[$name] = $errors;
            }
        }

        return $messagesArray;
    }
}