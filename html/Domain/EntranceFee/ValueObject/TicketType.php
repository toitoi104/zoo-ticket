<?php declare(strict_types=1);

require_once __DIR__ . '/../Enum/TicketTypeEnum.php';

class TicketType
{
    private int $value;

    /**
     * @throws Exception
     */
    public function __construct(int $ticketType)
    {
        $this->validate($ticketType);
        $this->value = $ticketType;
    }

    /**
     * @throws Exception
     */
    private function validate(int $ticketType): void
    {
        if (!in_array($ticketType, [TicketTypeEnum::GENERALLY->value, TicketTypeEnum::SPECIAL->value])) {
            throw new Exception('チケットタイプは「1」（通常料金）か「2」（特別割引）で指定してください');
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }
}