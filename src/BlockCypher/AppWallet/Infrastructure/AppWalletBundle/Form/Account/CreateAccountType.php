<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Account;

use BlockCypher\AppWallet\App\Command\CreateAccountCommand;
use BlockCypher\AppWallet\Domain\Account\AccountType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateAccountType
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Account
 */
class CreateAccountType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array(
                'choices' => AccountType::flippedValues(),
                'required' => false,
                'placeholder' => 'Choose an option'
            ))
            ->add('tag', 'text', array(
                'required' => true
            ));
    }

    public function configureOptions(/** @noinspection PhpUndefinedClassInspection */
        OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlockCypher\AppWallet\App\Command\CreateAccountCommand',
            'empty_data' => function (FormInterface $form) {
                $createAccountCommand = new CreateAccountCommand(
                    $form->get('type')->getData(),
                    $form->get('tag')->getData()
                );
                return $createAccountCommand;
            }
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bc_app_wallet_account_create_account';
    }
}