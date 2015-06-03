<?php

namespace BlockCypher\AppWallet\App\Command;

use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppCommon\App\Service\WalletService;
use BlockCypher\AppWallet\Domain\Account\Account;
use BlockCypher\AppWallet\Domain\Account\AccountRepository;
use BlockCypher\AppWallet\Domain\Account\AccountType;
use BlockCypher\AppWallet\Domain\Wallet\WalletRepository;

class CreateAccountCommandHandler
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @var WalletRepository
     */
    private $walletRepository;

    /**
     * @var WalletService
     */
    private $walletService;

    /**
     * @var Clock
     */
    private $clock;

    /**
     * Constructor
     * @param AccountRepository $accountRepository
     * @param WalletRepository $walletRepository
     * @param WalletService $walletService
     * @param Clock $clock
     */
    public function __construct(
        AccountRepository $accountRepository,
        WalletRepository $walletRepository,
        WalletService $walletService,
        Clock $clock
    )
    {
        $this->accountRepository = $accountRepository;
        $this->walletRepository = $walletRepository;
        $this->walletService = $walletService;
        $this->clock = $clock;
    }

    /**
     * @param CreateAccountCommand $command
     * @throws \Exception
     */
    public function handle(CreateAccountCommand $command)
    {
        $accountType = $command->getType();
        $accountTag = $command->getTag();

        // TODO: command validator
        // https://github.com/SimpleBus/MessageBus/issues/19
        // https://gist.github.com/josecelano/ded0a68154376dbec7ac
        // Alternatives to command validation using service
        // $this->createAccountCommandValidator->validate($command);
        // $this->accountCommandValidator->validateCreateAccount($command);
        // Command validation should
        // http://verraes.net/2015/02/form-command-model-validation/
        // "Note that we’re not trying to inform the user of validation errors.
        // We simply throw exceptions. The assumption here is that either the form prevents malformed values,
        // or the user is trying to bypass form validation. We don’t return friendly error messages to attackers."
        // @author @mathiasverraes

        $account = new Account(
            $this->accountRepository->nextIdentity(),
            $accountType,
            $this->clock->now(),
            $accountTag
        );
        $this->accountRepository->insert($account);

        // TODO: Code Review. Add WalletType (Crypto|Fiat) property to Account?
        switch ($accountType) {
            case AccountType::BTC:
            case AccountType::BTC_TESTNET:
            case AccountType::BCY:
            case AccountType::LTC:
            case AccountType::DOGE:
                $account->createCryptoWallet(
                    $this->walletRepository,
                    $this->walletService,
                    $this->clock);
                break;
            //case AccountType::EUR:
            //    $account->createFiatWallet($this->fiatWalletRepository, $this->clock);
            //    break;
            default:
                throw new \Exception(sprintf("Unsupported account type %s", $accountType));
        }
        $this->accountRepository->update($account);
    }
}