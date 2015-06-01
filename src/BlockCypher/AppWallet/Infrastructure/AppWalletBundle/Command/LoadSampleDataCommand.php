<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LoadSampleDataCommand
 *
 * Load sample data.
 *
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Command
 */
class LoadSampleDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('wallet:load-sample-data')
            ->setDescription('Load App Wallet sample data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ATTENTION!: This command will remove data with the same Id.

        $this->loadAccountFixtures();
        $output->writeln("Account data loaded");

        $this->loadWalletFixtures();
        $output->writeln("Wallet data loaded");
    }

    private function loadAccountFixtures()
    {
        $accountDataLoader = $this->getContainer()->get('bc_app_wallet_account.account_data_loader');
        $accountRepository = $this->getContainer()->get('bc_app_wallet_account.account_repository');
        $accountDataLoader->loadFixtures($accountRepository);
    }

    private function loadWalletFixtures()
    {
        $accountDataLoader = $this->getContainer()->get('bc_app_wallet_wallet.wallet_data_loader');
        $walletRepository = $this->getContainer()->get('bc_app_wallet_wallet.wallet_repository');
        $accountDataLoader->loadFixtures($walletRepository);
    }
}