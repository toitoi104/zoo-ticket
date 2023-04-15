<?php declare(strict_types=1);

require_once __DIR__ . '/../Enum/TicketTypeEnum.php';
require_once __DIR__ . '/Visitor.php';

class Adult extends Visitor
{
    protected string $name = '大人';
    protected int $generallyCost = 1000;
    protected int $specialCost = 600;

    public function __construct(int $person)
    {
        parent::__construct($person);
    }
}