<?php


namespace App\Products;


interface ProductInterface
{
    public function setSugar(int $sugar): self;
    public function getSugar(): int;

    public function setMilk(int $milk): self;
    public function getMilk(): int;

    public function getCreationDate(): \DateTimeInterface;
}