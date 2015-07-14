<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Transaction;

use BlockCypher\AppWallet\App\Command\CreateTransactionCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

/**
 * Class CreateTransactionType
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Transaction
 */
class CreateTransactionType extends AbstractType
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

        $builder
            ->add('walletId', 'choice', array(
                'choices' => $this->walletIdChoices,
                'required' => true,
                'data' => $this->defaultWalletId
            ))
            ->add('payToAddress', 'text', array(
                'required' => true,
                'constraints' => array(
                    new NotBlank(),
                    new Type(array('type' => 'string')),
                )))
            ->add('description', 'text', array(
                'required' => false
            ))
            ->add('amount', 'text', array(
                'required' => true,
                'constraints' => array(
                    new NotBlank(),
                    new Type(array('type' => 'integer')),
                )));

// TODO: Code Review. Use money type for amount
//            ->add('amount', 'money', array(
//                'divisor' => 100000000,
//                'scale' => 8,
//                'currency' => false, // Hide currency symbol
//                'required' => true)
//            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlockCypher\AppWallet\App\Command\CreateTransactionCommand',
            'empty_data' => function (FormInterface $form) {
                $createTransactionCommand = new CreateTransactionCommand(
                    $form->get('walletId')->getData(),
                    $form->get('payToAddress')->getData(),
                    $form->get('description')->getData(),
                    $form->get('amount')->getData()
                );
                return $createTransactionCommand;
            }
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bc_app_wallet_payment_create_transaction';
    }
}