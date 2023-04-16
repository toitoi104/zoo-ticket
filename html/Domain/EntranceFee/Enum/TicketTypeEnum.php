<?php declare(strict_types=1);

enum TicketTypeEnum: int
{
    case GENERALLY = 1;
    case SPECIAL = 2;

    /**
     * @return string
     */
    public function name(): string
    {
        return match($this) {
            self::GENERALLY => '通常',
            self::SPECIAL => '特別',
        };
    }
}