<?php

namespace App\Command;

use App\CoffeeMachine;
use App\CoffeeMachineManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CoffeeMachineCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:coffee-machine';

    protected function configure()
    {
        $this->setName("app:coffee-machine")
            ->setDescription("Your virtual coffee machine");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $machine = new CoffeeMachine();
        $machineManager = new CoffeeMachineManager($machine, $io);
        $io->title("Coffee Machine");
        $machineManager->menu();

    }


}