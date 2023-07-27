<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:set-test-mail',
    description: 'Add a short description for your command',
)]
class SetTestMailCommand extends Command
{

    public function __construct(
        private HttpClientInterface $httpClient,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->httpClient->request('POST', "https://api.brevo.com/v3/smtp/email", [
            "headers" => [
                "accept" => "application/json",
                "api-key" => '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',
                "content-type" => "application/json"
            ],
            'json' => [
                "sender" => [
                    'name' => "Franck Pertosa",
                    'email' => 'franck.pertosa@hotmail.fr'
                ],
                "to" => [
                    'email' => 'franck.pertosa@gmail.com',
                    'name' => 'Franck Pertosa'
                ],
                "subject" => "Bonjour !!!",
                "htmlContent" => "<p>Bien le bonjour l'ami !!! </p>"
            ]

        ]);

        return Command::SUCCESS;
    }
}
