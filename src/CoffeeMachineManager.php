<?php

namespace App;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class CoffeeMachineManager
{
    /** @var CoffeeMachine $machine */
    private $machine;

    /** @var SymfonyStyle */
    private $io;

    /**
     * CoffeeMachineManager constructor.
     * @param CoffeeMachine $machine
     * @param SymfonyStyle $io
     */
    public function __construct(CoffeeMachine $machine, SymfonyStyle $io)
    {
        $this->machine = $machine;
        $this->io = $io;
    }


    public function menu()
    {
        $menus = [
            ['Access the menu', 'menu'],
            ['See my money', 'money'],
            ['Add some money', 'add money'],
            ['Get a refund', 'refund'],
            ['Sugar operations', 'sugar'],
            ['Milk operations', 'milk'],
            ['Get a coffee', 'coffee'],
            ['Get a tea', 'tea'],
            ['Get a chocolate', 'chocolate'],
            ['inventory of the machine', 'inventory'],
            ['Return to work', 'exit'],


        ];

        $this->io->table(['Options', 'Access'], $menus);

        $question = new Question("Choose an option");
        $question->setAutocompleterValues(array_column($menus, 1));
        $option = $this->io->askQuestion($question);

        switch ($option) {
            case "menu":
                $this->menu();
                break;
            case "money":
                $this->io->note(sprintf("You have %s$ in the machine.", $this->machine->getMoney()));
                break;
            case "add money":
                $this->addMoney();
                break;
            case "refund":
                $this->refund();
                break;
            case "sugar":
            case "milk":
                $this->handleAdditionals($option);
                break;
            case "coffee":
            case "tea":
            case "chocolate":
                $this->handleProduct($option);
                break;
            case "inventory":
                $this->handleInventory();
                break;
            case "exit":
                $this->exit();
        }

        $this->menu();
    }

    private function addMoney()
    {
        $answer = $this->io->ask("How much money do you put in the machine ?", 2, function ($answer) {
            $this->handleDefaultAnswer($answer);
            if (!preg_match("/^[0-9]*$/", $answer)) {
                $this->io->error("You can only add money in the machine !");

                return false;
            }

            return $answer;
        });

        if (false !== $answer) {
            $this->machine->addMoney(intval($answer));
            $this->io->success(sprintf("You added %s$ in the machine !", $answer));
        }
    }

    private function refund()
    {
        $money = $this->machine->refundMoney();
        $this->io->success(sprintf("%s$ refunded.", $money));
    }

    private function handleAdditionals(string $type)
    {
        $answer = $this->io->ask("How much $type do you want ?", 2, function ($answer) use ($type) {
            $this->handleDefaultAnswer($answer);
            if (!preg_match("/^[0-4]$/", $answer)) {
                $this->io->error("You can only add up to 4 $type !");
                $this->handleAdditionals($type);

                return false;
            }

            return $answer;
        });

        if (false !== $answer) {
            $method = "set" . ucfirst($type);
            // Call setSugar / setMilk
            call_user_func([$this->machine, $method], intval($answer));
        }
    }

    private function handleProduct(string $type)
    {
        $products = $this->machine->getProducts();
        $products = array_combine(array_map(function ($product) {
            return strtolower(str_replace("App\\Products\\", "", $product));
        }, $products), $products);

        $product = $products[$type];
        try {
            $this->machine->selectProduct($product);
        } catch (\Exception $e) {
            $this->io->error($e->getMessage());
        }
    }

    private function handleInventory()
    {
        $this->io->listing(["1", "2"]);
    }

    private function handleDefaultAnswer(string $answer)
    {
        if ($answer === "menu") {
            $this->menu();
        } elseif ($answer === "exit") {
            $this->exit();
        }
    }

    private function exit()
    {
        $this->io->note("Bye !");
        exit;
    }

}