<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Address;

use BlockCypher\AppWallet\App\Command\CreateAddressCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateAddressType
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Address
 */
class CreateAddressType extends AbstractType
{
    /**
     * @var array choices for account html select
     */
    private $accountIdChoices;

    /**
     * @var string
     */
    private $defaultAccountId;

    /**
     * @param array $accountIdChoices
     * @param $defaultAccountId
     */
    function __construct($accountIdChoices, $defaultAccountId)
    {
        $this->accountIdChoices = $accountIdChoices;
        $this->defaultAccountId = $defaultAccountId;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // DEBUG
        //var_dump($this->defaultAccountId);
        //die();

        $builder
            ->add('accountId', 'choice', array(
                'choices' => $this->accountIdChoices,
                'required' => true,
                'data' => $this->defaultAccountId
            ))
            ->add('tag', 'text', array('required' => true))
            ->add('callbackUrl', 'text', array('required' => false));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlockCypher\AppWallet\App\Command\CreateAddressCommand',
            'empty_data' => function (FormInterface $form) {
                $createAddressCommand = new CreateAddressCommand(
                    $form->get('accountId')->getData(),
                    $form->get('tag')->getData(),
                    $form->get('callbackUrl')->getData()
                );
                return $createAddressCommand;
            }
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bc_app_wallet_address_create_address';
    }
}