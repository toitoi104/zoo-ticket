<?php declare(strict_types=1);

require_once __DIR__ . '/../Enum/TicketTypeEnum.php';
require_once __DIR__ . '/Visitor.php';

class Senior extends Visitor
{
    protected string $name = 'シニア';
    protected int $generallyCost = 800;
    protected int $specialCost = 500;

    public function __construct(int $person)
    {
        parent::__construct($person);
    }
}