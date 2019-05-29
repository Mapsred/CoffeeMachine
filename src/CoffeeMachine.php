<?php

namespace App;

use App\Products\AbstractProduct;
use App\Products\Chocolate;
use App\Products\Coffee;
use App\Products\Tea;

class CoffeeMachine
{
    /** @var array $products */
    private $products = [
        Chocolate::class,
        Coffee::class,
        Tea::class
    ];

    /** @var array $orders */
    private $orders = [];

    /** @var int $money */
    private $money = 0;

    /** @var int $sugar */
    private $sugar = 0;

    /** @var int $milk */
    private $milk = 0;

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return array
     */
    public function getOrders(): array
    {
        return $this->orders;
    }

    /**
     * @return int
     */
    public function getMoney(): int
    {
        return $this->money;
    }

    /**
     * @param int $money
     * @return CoffeeMachine
     */
    public function addMoney(int $money): self
    {
        $this->money+= $money;

        return $this;
    }

    /**
     * @return int
     */
    public function refundMoney(): int
    {
        $money = $this->money;
        $this->money = 0;

        return $money;
    }

    /**
     * @param int $quantity
     * @return CoffeeMachine
     */
    public function addSugar(int $quantity = 1): self
    {
        $this->sugar = $this->increase($this->sugar, $quantity);
        
        return $this;
    }

    /**
     * @param int $quantity
     * @return CoffeeMachine
     */
    public function removeSugar(int $quantity = 1): self
    {
        $this->sugar = $this->decrease($this->sugar, $quantity);
        
        return $this;
    }

    /**
     * @param int $quantity
     * @return CoffeeMachine
     */
    public function addMilk(int $quantity = 1): self
    {
        $this->milk = $this->increase($this->milk, $quantity);
        
        return $this;
    }

    /**
     * @param int $quantity
     * @return CoffeeMachine
     */
    public function removeMilk(int $quantity = 1): self
    {
        $this->milk = $this->decrease($this->milk, $quantity);
        
        return $this;
    }

    /**
     * @param string $productName
     * @return string
     * @throws \Exception
     */
    public function selectProduct(string $productName)
    {
        /** @var AbstractProduct $product */
        $product = new $productName();
        if ($this->money < $product::PRICE) {
            $productName = str_replace("App\Products\\", "", $productName);
            throw new \Exception(sprintf("Not enough money. Product %s costs %s$", $productName, $product::PRICE));
        }

        $this->money-= $product::PRICE;
        $product->setMilk($this->milk)->setSugar($this->sugar);
        $this->orders[] = $product;

        return $product->__toString();
    }
    
    private function increase(int $quantity, int $increment = 1, int $limit = 4)
    {
        $quantity+= $increment;

        return $quantity >= $limit ? $limit : $quantity;
    }

    private function decrease(int $quantity, int $decrement = 1, int $limit = 0)
    {
        $quantity-= $decrement;

        return $quantity <= $limit ? $limit : $quantity;
    }

    public function __toString()
    {
        return sprintf("In this machine : %s money, %s sugar and %s milk.", $this->money, $this->sugar, $this->milk);
    }
}