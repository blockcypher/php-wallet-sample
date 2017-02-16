<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Faucet;

use BlockCypher\AppWallet\App\Command\FundAddressCommand;
use BlockCypher\AppWallet\Domain\Wallet\WalletCoinSymbol;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FundAddressType
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Faucet
 */
class FundAddressType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', 'text', array(
                'required' => true
            ))
            ->add('amount', 'integer', array(
                'required' => true
            ))
            ->add('coinSymbol', 'choice', array(
                'choices' => array(WalletCoinSymbol::BTC_TESTNET => 'BTC_TESTNET', WalletCoinSymbol::BCY => 'BCY'),
                'required' => true,
                'placeholder' => 'Choose a blockchain' // TODO: I18N
            ));
    }

    public function configureOptions(/** @noinspection PhpUndefinedClassInspection */
        OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlockCypher\AppWallet\App\Command\FundAddressCommand',
            'empty_data' => function (FormInterface $form) {
                $fundAddressCommand = new FundAddressCommand(
                    $form->get('address')->getData(),
                    (int)$form->get('amount')->getData(),
                    $form->get('coinSymbol')->getData()
                );
                return $fundAddressCommand;
            }
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bc_app_wallet_faucet_fund_address';
    }
}