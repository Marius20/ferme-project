<?php

namespace App\Command;

use App\Repository\FamilleBetailRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-default-families',
    description: 'Create default cattle families in the database',
)]
class CreateDefaultFamiliesCommand extends Command
{
    public function __construct(private FamilleBetailRepository $familleBetailRepository)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->familleBetailRepository->createDefaultFamilies();

        $io->success('Default cattle families have been created successfully!');

        return Command::SUCCESS;
    }
}