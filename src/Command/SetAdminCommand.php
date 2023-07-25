<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\UserRepository;

#[AsCommand(
    name: 'app:set-admin',
    description: 'Add a short description for your command',
)]
class SetAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('userMail', InputArgument::REQUIRED, "Email de l'utilisateur");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $userMail = $input->getArgument('userMail');
        $user = $this->userRepository->findOneBy(['email' => $userMail]);

        if ($user !== null) {
        $user->setRoles(['ROLE_ADMIN']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln("L'utilisateur ".$userMail." est désormais administrateur");

        return Command::SUCCESS;
        }

        $output->writeln("Une erreur est survenue (utilisateur inexistant ou autre), aucune opération n'a été effectuée.");

        return Command::FAILURE;
    }
}
