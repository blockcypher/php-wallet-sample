<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Wallet;

use BlockCypher\AppWallet\App\Command\CreateWalletCommand;
use BlockCypher\AppWallet\Domain\Wallet\WalletCoinSymbol;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateWalletType
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Wallet
 */
class CreateWalletType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'required' => true
            ))
            ->add('coinSymbol', 'choice', array(
                'choices' => WalletCoinSymbol::flippedValues(),
                'required' => true,
                'placeholder' => 'Choose a blockchain'
            ));
    }

    public function configureOptions(/** @noinspection PhpUndefinedClassInspection */
        OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlockCypher\AppWallet\App\Command\CreateWalletCommand',
            'empty_data' => function (FormInterface $form) {
                $createWalletCommand = new CreateWalletCommand(
                    $form->get('name')->getData(),
                    $form->get('coinSymbol')->getData()
                );
                return $createWalletCommand;
            }
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bc_app_wallet_wallet_create_wallet';
    }
}