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
     * @var array choices for wallet html select
     */
    private $walletIdChoices;

    /**
     * @var string
     */
    private $defaultWalletId;

    /**
     * @param array $walletIdChoices
     * @param $defaultWalletId
     */
    function __construct($walletIdChoices, $defaultWalletId)
    {
        $this->walletIdChoices = $walletIdChoices;
        $this->defaultWalletId = $defaultWalletId;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // DEBUG
        //var_dump($this->defaultWalletId);
        //die();

        // TODO: walletId hidden
        $builder
            ->add('walletId', 'choice', array(
                'choices' => $this->walletIdChoices,
                'required' => true,
                'data' => $this->defaultWalletId
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
                    $form->get('walletId')->getData(),
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