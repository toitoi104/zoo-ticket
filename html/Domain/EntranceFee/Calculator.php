<?php declare(strict_types=1);

require_once __DIR__ . '/../App.php';
require_once __DIR__ . '/ValueObject/TicketType.php';
require_once __DIR__ . '/ValueObject/Adult.php';
require_once __DIR__ . '/ValueObject/Child.php';
require_once __DIR__ . '/ValueObject/Senior.php';
require_once __DIR__ . '/Enum/TicketTypeEnum.php';

class Calculator extends App
{
    private TicketType $ticketType;
    private Adult $adult;
    private Child $child;
    private Senior $senior;
    private bool $isHoliday = false; // 祝日フラグ
    private string $resultMessage = ''; // 計算結果
    private string $changedCostMessage = ''; // 金額変更明細
    private DateTime $dateTime; // 日時

    /**
     * @throws Exception
     */
    protected function main(): void
    {
        $this->init();
        $this->calc();
        $this->showResult();
    }

    /**
     * @throws Exception
     */
    private function init(): void
    {
        $options  = [
            "ticket_type:", // チケットタイプ（通常 or 特別）
            "adult:",       // 大人の人数
            "child:",       //　子供の人数
            "senior:",      //　シニアの人数
            "is_holiday",  //　祝日かどうか
            "dateTime:"     // 日時(テスト用)
        ];

        $options = getopt('', $options);

        $ticketType = isset($options['ticket_type']) ? (int) $options['ticket_type'] : TicketTypeEnum::GENERALLY->value;
        $this->ticketType = new TicketType($ticketType);
        $this->adult = new Adult(isset($options['adult']) ? (int) $options['adult'] : 0);
        $this->child = new Child(isset($options['child']) ? (int) $options['child'] : 0);
        $this->senior = new Senior(isset($options['senior']) ? (int) $options['senior'] : 0);
        $this->isHoliday = isset($options['is_holiday']);
        $this->dateTime = (isset($options['dateTime']) ? new DateTime($options['dateTime']) : new DateTime());
    }

    /** @throws Exception */
    private function calc(): void
    {
        $sumPerson = $this->adult->getPerson() + $this->child->getPerson() + $this->senior->getPerson();
        $sumGenerallyCost = $this->sumGenerallyCost();
        $defaultCost = $this->calcDefaultCost($sumGenerallyCost);

        $holidayCharge = $this->chargeHoliday($sumPerson);
        $sumDiscount = $this->sumDiscount($sumGenerallyCost, $sumPerson);

        $sumCost = $defaultCost + $holidayCharge - $sumDiscount;

        $this->validateResult($sumPerson, $sumCost);

        $this->writeResult("■人数");
        $this->writeResult("{$this->adult->getName()}:{$this->adult->getPerson()}名");
        $this->writeResult("{$this->child->getName()}:{$this->child->getPerson()}名");
        $this->writeResult("{$this->senior->getName()}:{$this->senior->getPerson()}名");
        $this->writeResult("合計：{$sumPerson}名");
        $this->writeResult("■販売合計金額：".number_format($sumCost));
        $this->writeResult("■金額変更前合計金額：".number_format($sumGenerallyCost));
        $this->writeResult("■金額変更明細");
        $this->writeResult($this->changedCostMessage);
    }

    private function sumGenerallyCost(): int
    {
        return $this->adult->sumGenerallyCost() + $this->child->sumGenerallyCost() + $this->senior->sumGenerallyCost();
    }

    private function calcDefaultCost(int $sumGenerallyCost): int
    {
        if($this->ticketType->getValue() !== TicketTypeEnum::SPECIAL->value){
            return $this->adult->sumSpecialCost() + $this->child->sumSpecialCost() + $this->senior->sumSpecialCost();
        }

        return $sumGenerallyCost;
    }

    /**
     * 休日割増 (土曜日、日曜日は200円割引)
     * 祝日は変動することがあるため、オペレーターにパラメーターとして入力してもらう
     * 日:0  月:1  火:2  水:3  木:4  金:5  土:6
     */
    private function chargeHoliday(int $sumPerson): int
    {
        if($this->isHoliday || in_array($this->dateTime->format('w'), [0, 6])){
            $perCharge = 200;
            $charge = $sumPerson * $perCharge;
            $this->writeChangedCostMessage("休日割増料金", $charge, "1人あたり{$perCharge}円");

            return $charge;
        }

        return 0;
    }

    private function sumDiscount(int $sumGenerallyCost, int $sumPerson): int
    {
        if($this->ticketType->getValue() !== TicketTypeEnum::GENERALLY->value){
            return 0;
        }

        $groupDiscount = $this->discountGroup($sumGenerallyCost); // 団体割引
        $eveningDiscount = $this->discountEvening($sumPerson);    // 夕方割引
        $weekDiscount = $this->discountWeek($sumPerson);          // 曜日割引

        return $groupDiscount + $eveningDiscount + $weekDiscount;
    }

    /**
     * 団体割引 (10人以上で10%割引)
     * 子供は0.5人で計算する
     * チケットタイプが通常の時のみ適用する
     */
    private function discountGroup(int $sumGenerallyCost): int
    {
        $sumGroupPerson = $this->adult->countGroupPerson() + $this->child->countGroupPerson() + $this->senior->countGroupPerson();

        if($this->ticketType->getValue() !== TicketTypeEnum::GENERALLY->value){
            return 0;
        }

        if($sumGroupPerson < 10){
            return 0;
        }

        $discount = (int) floor($sumGenerallyCost * 10 / 100);
        $this->writeChangedCostMessage("団体割引料金", $discount, "10%割引");

        return $discount;
    }

    // 夕方割引（17時以降は100円割引）
    private function discountEvening(int $sumPerson): int
    {
        if($this->ticketType->getValue() !== TicketTypeEnum::GENERALLY->value){
            return 0;
        }

        if((int) $this->dateTime->format('H') >= 17){
            $perDiscount = 100;
            $discount = $sumPerson * $perDiscount;
            $this->writeChangedCostMessage("夕方割引", $discount, "1人あたり{$perDiscount}円");

            return $discount;
        }

        return 0;
    }

    /**
     * 曜日割引 (月曜日、水曜日に100円割引）
     * 日:0  月:1  火:2  水:3  木:4  金:5  土:6
     * 祝日と被ってしまった場合、休日割増が優先される
     */
    private function discountWeek(int $sumPerson): int
    {
        if(!$this->isHoliday && in_array($this->dateTime->format('w'), [1, 3])){
            $perDiscount = 100;
            $discount = $sumPerson * $perDiscount;
            $this->writeChangedCostMessage("曜日割引", $discount, "1人あたり{$perDiscount}円");
        }

        return 0;
    }

    /** @throws Exception */
    private function validateResult(int $sumPerson, int $sumCost): void
    {
        if($sumPerson <= 0){
            throw new Exception('人数を設定してください。');
        }

        if($sumCost < 0){
            throw new Exception("合計金額が0円以下になっています。エンジニアに連絡をしてください。({$sumPerson}円)");
        }
    }

    private function showResult(): void
    {
        echo $this->resultMessage;
    }

    private function writeResult(string $v): void
    {
        $this->resultMessage .= $v."\n";
    }

    private function writeChangedCostMessage(string $name, int $sumPrice, string $memo): void
    {
        $sumPrice = number_format($sumPrice);
        $this->changedCostMessage .= "【{$name}】：{$sumPrice}円（{$memo}）"."\n";
    }
}