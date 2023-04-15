<?php declare(strict_types=1);

require_once __DIR__ . '/TicketType.php';

class Visitor
{
    protected string $name = '';
    protected int $generallyCost; // 通常料金
    protected int $specialCost; // 特別料金
    protected int $person; // 人数

    /** @throws Exception */
    public function __construct(int $person)
    {
        $this->validate($person);
        $this->person = $person;
    }

    /** @throws Exception */
    private function validate(int $person): void
    {
        if($person < 0){
            throw new Exception('人数にマイナスを設定することはできません');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGenerallyCost(): int
    {
        return $this->generallyCost;
    }

    public function getSpecialCost(): int
    {
        return $this->specialCost;
    }

    public function sumGenerallyCost(): int
    {
        return $this->getGenerallyCost() * $this->getPerson();
    }

    public function sumSpecialCost(): int
    {
        return $this->getSpecialCost() * $this->getPerson();
    }

    public function getPerson(): int
    {
        return $this->person;
    }

    // 団体人数
    public function countGroupPerson(): float
    {
        return $this->person;
    }
}