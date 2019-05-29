<?php


namespace App\Products;


/**
 * Class AbstractProduct
 */
abstract class AbstractProduct implements ProductInterface
{
    const PRICE = 0;

    /** @var int $sugar */
    private $sugar;

    /** @var int $milk */
    private $milk;

    /** @var \DateTime $creationDate */
    private $creationDate;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
    }

    /**
     * @param int $sugar
     * @return ProductInterface
     */
    public function setSugar(int $sugar): ProductInterface
    {
        $this->sugar = $sugar;

        return $this;
    }

    /**
     * @return int
     */
    public function getSugar(): int
    {
        return $this->sugar;
    }

    /**
     * @param int $milk
     * @return ProductInterface
     */
    public function setMilk(int $milk): ProductInterface
    {
        $this->milk = $milk;

        return $this;
    }

    /**
     * @return int
     */
    public function getMilk(): int
    {
        return $this->milk;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate(): \DateTimeInterface
    {
        return $this->creationDate;
    }

    public function __toString()
    {
        return sprintf("%s with %s sugar and %s milk.", self::class, $this->getSugar(), $this->getMilk());
    }
}