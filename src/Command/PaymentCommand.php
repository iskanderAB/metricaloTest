<?php

namespace App\Command;

use App\Domain\Payment\UseCases\Interactor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:payment',
    description: 'Processes a payment using Shift4',
)]
class PaymentCommand extends Command
{
    public function __construct(
        private readonly Interactor $PaymentInteractor,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Processes a payment using Shift4')
            ->addOption('test', null, InputOption::VALUE_NONE, 'Run the command with test data')
            ->addOption('amount', null, InputOption::VALUE_OPTIONAL, 'The amount of the payment', '4000')
            ->addOption('currency', null, InputOption::VALUE_OPTIONAL, 'The currency of the payment', 'EUR')
            ->addOption('cardNumber', null, InputOption::VALUE_OPTIONAL, 'The card number', '4242424242424242')
            ->addOption('cardExpYear', null, InputOption::VALUE_OPTIONAL, 'The card expiry year', '2026')
            ->addOption('cardExpMonth', null, InputOption::VALUE_OPTIONAL, 'The card expiry month', '01')
            ->addOption('cardCvv', null, InputOption::VALUE_OPTIONAL, 'The card CVV', '463')
            ->addOption('getaway', null, InputOption::VALUE_OPTIONAL, 'The payment getaway', 'shift4');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $amount = $input->getOption('amount');
        $currency = $input->getOption('currency');
        $cardNumber = $input->getOption('cardNumber');
        $cardExpYear = $input->getOption('cardExpYear');
        $cardExpMonth = $input->getOption('cardExpMonth');
        $cardCvv = $input->getOption('cardCvv');
        $getaway = $input->getOption('getaway');

        try {
            $this->PaymentInteractor->chargePayment(
                cardNumber: $cardNumber,
                expMonth: $cardExpMonth,
                expYear: $cardExpYear,
                cvvNumber: $cardCvv,
                amount: $amount,
                currency: $currency,
                getaway: $getaway
            );
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        $io->success('Payment processed successfully!');
        return Command::SUCCESS;
    }
}
