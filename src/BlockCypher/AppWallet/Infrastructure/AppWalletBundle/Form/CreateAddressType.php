<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form;

use BlockCypher\AppWallet\App\Command\CreateAddressCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateAddressType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: use select for accountId

        $builder
            ->add('accountId', 'text')
            ->add('tag', 'text', array('required' => false))
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