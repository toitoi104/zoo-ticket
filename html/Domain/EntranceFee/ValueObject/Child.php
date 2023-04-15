<?php declare(strict_types=1);

require_once __DIR__ . '/../Enum/TicketTypeEnum.php';
require_once __DIR__ . '/Visitor.php';

class Child extends Visitor
{
    protected string $name = '子供';
    protected int $generallyCost = 500;
    protected int $specialCost = 400;

    public function __construct(int $person)
    {
        parent::__construct($person);
    }

    public function countGroupPerson(): float
    {
        return $this->getPerson() * 0.5;
    }
}