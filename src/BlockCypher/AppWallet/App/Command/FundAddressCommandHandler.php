<?php

namespace BlockCypher\AppWallet\App\Command;

use BlockCypher\AppCommon\App\Service\Internal\BlockCypherFaucetService;

/**
 * Class FundAddressCommandHandler
 * @package BlockCypher\AppWallet\App\Command
 */
class FundAddressCommandHandler
{
    /**
     * @var BlockCypherFaucetService
     */
    private $blockCypherWalletService;

    /**
     * @var FundAddressCommandValidator
     */
    private $fundAddressCommandValidator;

    /**
     * FundAddressCommandHandler constructor.
     * @param FundAddressCommandValidator $fundAddressCommandValidator
     * @param BlockCypherFaucetService $blockCypherWalletService
     */
    public function __construct(
        FundAddressCommandValidator $fundAddressCommandValidator,
        BlockCypherFaucetService $blockCypherWalletService
    )
    {
        $this->fundAddressCommandValidator = $fundAddressCommandValidator;
        $this->blockCypherWalletService = $blockCypherWalletService;
    }

    /**
     * @param FundAddressCommand $command
     * @throws \Exception
     */
    public function handle(FundAddressCommand $command)
    {
        $address = $command->getAddress();
        $amount = $command->getAmount();
        $walletCoinSymbol = $command->getCoinSymbol();
        $walletToken = $command->getToken();

        $this->fundAddressCommandValidator->validate($command);

        $this->blockCypherWalletService->fundAddress($address, $amount, $walletCoinSymbol, $walletToken);
    }
}