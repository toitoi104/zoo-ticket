<?php declare(strict_types=1);

function test(int $testID, $ticketType, $adult, $child, $senior, bool $isHoliday, string $date, string $time, bool $result): void
{
    $command = "php index.php --ticket_type={$ticketType} --adult={$adult} --child={$child} --senior={$senior} --date_time=\"{$date} {$time}\"";

    if($isHoliday){
        $command .= " --is_holiday";
    }

    exec($command, $output);

    echo "テスト{$testID}\n";
    print_r($output);

    echo (str_contains($output[0], 'エラー') !== $result) ? 'SUCCESS' : 'ERROR';
    echo "\n\n";
}

const MONDAY = '2023-04-10';
const WEDNESDAY = '2023-04-12';
const FRIDAY = '2023-04-14';
const SATURDAY = '';
const SUNDAY = '';

const MORNING = '10:00';
const EVENING = '17:00';

# [ERROR] 人数が0人  【結果】エラー：人数を設定してください。人数は半角数字で入力してください。
test(1,1, 0, 0, 0, false, FRIDAY, MORNING, false);

# [ERROR] 予期せぬチケットタイプ　【結果】エラー：チケットタイプは「1」（通常料金）か「2」（特別割引）で指定してください
test(2,3, 1, 0, 0, false, FRIDAY, MORNING, false);

# [ERROR] 予期せぬチケットタイプ　【結果】エラー：チケットタイプは「1」（通常料金）か「2」（特別割引）で指定してください
test(3,3, 1, 0, 0, false, FRIDAY, MORNING, false);

# [ERROR] 人数にマイナス　【結果】エラー：人数にマイナスを設定することはできません
test(4,1, -1, 0, 0, false, FRIDAY, MORNING, false);

# [ERROR] 人数に全角　【結果】エラー：人数を設定してください。人数は半角数字で入力してください。
test(5,1, "１", 0, 0, false, FRIDAY, MORNING, false);

# [SUCCESS] [通常チケット][割引なし曜日]大人1人　【結果】■販売合計金額：1,000円
test(6,1, 1, 0, 0, false, FRIDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引なし曜日]子供1人　【結果】■販売合計金額：500円
test(7,1, 0, 1, 0, false, FRIDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引なし曜日]シニア1人　【結果】■販売合計金額：800円
test(8,1, 0, 0, 1, false, FRIDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引なし曜日]大人1人、子供1人　【結果】■販売合計金額：1,500円
test(9,1, 1, 1, 0, false, FRIDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引なし曜日]大人1人、子供1人、シニア1人　【結果】■販売合計金額：2,300円
test(10,1, 1, 1, 1, false, FRIDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引なし曜日]大人3人、子供3人、シニア3人　【結果】■販売合計金額：6,900円
test(11,1, 3, 3, 3, false, FRIDAY, MORNING, true);

# [SUCCESS] [特別チケット][割引なし曜日]大人1人　【結果】■販売合計金額：600円
test(12,2, 1, 0, 0, false, FRIDAY, MORNING, true);

# [SUCCESS] [特別チケット][割引なし曜日]子供1人　【結果】■販売合計金額：400円
test(13,2, 0, 1, 0, false, FRIDAY, MORNING, true);

# [SUCCESS] [特別チケット][割引なし曜日]シニア1人　【結果】■販売合計金額：500円
test(14,2, 0, 0, 1, false, FRIDAY, MORNING, true);

# [SUCCESS] [特別チケット][割引なし曜日]大人1人、子供1人、シニア1人　【結果】■販売合計金額：1500円
test(15,2, 1, 1, 1, false, FRIDAY, MORNING, true);

# [SUCCESS] [特別チケット][割引なし曜日]大人2人、子供2人、シニア2人　【結果】■販売合計金額：3000円
test(16,2, 2, 2, 2, false, FRIDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引なし曜日]団体割 大人10人、子供0人、シニア0人　【結果】■販売合計金額：9,000
test(17,1, 10, 0, 0, false, FRIDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引なし曜日]団体割 大人0人、子供20人、シニア0人　【結果】■販売合計金額：9,000
test(18,1, 0, 20, 0, false, FRIDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引なし曜日]団体割がギリギリ適用外 大人0人、子供19人、シニア0人　【結果】■販売合計金額：9,500
test(19,1, 0, 19, 0, false, FRIDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引なし曜日]団体割 大人0人、子供0人、シニア10人　【結果】■販売合計金額：7,200円
test(20,1, 0, 0, 10, false, FRIDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引なし曜日]団体割がギリギリ適用外 大人9人、子供1人、シニア0人　【結果】■販売合計金額：9,500円
test(21,1, 9, 1, 0, false, FRIDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引なし曜日]団体割 大人9人、子供2人、シニア0人　【結果】■販売合計金額：9,000円
test(22,1, 9, 2, 0, false, FRIDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引なし曜日]団体割 大人9人、子供2人、シニア0人　【結果】■販売合計金額：9,000円
test(23,1, 9, 2, 0, false, FRIDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引なし曜日]夕方割引 大人1人、子供1人、シニア1人　【結果】■販売合計金額：2,000円
test(24,1, 1, 1, 1, false, FRIDAY, EVENING, true);

# [SUCCESS] [通常チケット][割引なし曜日]夕方割引がギリギリ適用外 大人1人、子供1人、シニア1人　【結果】■販売合計金額：2,300円
test(25,1, 1, 1, 1, false, FRIDAY,'16:59', true);

# [SUCCESS] [通常チケット][割引あり曜日(月曜日)] 大人1人、子供1人、シニア1人　【結果】■販売合計金額：2,000円
test(26,1, 1, 1, 1, false, MONDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引あり曜日(水曜日)] 大人1人、子供1人、シニア1人　【結果】■販売合計金額：2,000円
test(27,1, 1, 1, 1, false, WEDNESDAY, MORNING, true);

# [SUCCESS] [特別チケット][割引あり曜日(水曜日)] 大人10人、子供1人、シニア1人　【結果】■販売合計金額：6,900円 (特別チケットの割引のみ適用)
test(28,2, 10, 1, 1, false, WEDNESDAY, EVENING, true);

# [SUCCESS] [通常チケット][割引あり曜日(水曜日)]団体割 大人10人、子供1人、シニア1人　【結果】■販売合計金額：8,970円
test(29,1, 10, 1, 1, false, WEDNESDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引あり曜日(水曜日)]団体割 大人10人、子供1人、シニア1人　【結果】■販売合計金額：8,970円
test(30,1, 10, 1, 1, false, WEDNESDAY, MORNING, true);

# [SUCCESS] [通常チケット][割引あり曜日(水曜日)]夕方割 大人1人、子供1人、シニア1人　【結果】■販売合計金額：1,700
test(31,1, 1, 1, 1, false, WEDNESDAY, EVENING,true);

# [SUCCESS] [通常チケット][割引あり曜日(水曜日)]団体割 夕方割 大人10人、子供1人、シニア1人　【結果】■販売合計金額：7,770円
test(32,1, 10, 1, 1, false, WEDNESDAY, EVENING, true);

# [SUCCESS] [通常チケット][割引なし曜日]団体割 夕方割 大人10人、子供1人、シニア1人　【結果】■販売合計金額：8,970円
test(33,1, 10, 1, 1, false, FRIDAY, EVENING, true);

# [SUCCESS] [通常チケット][休日割増(土曜日)] 大人1人、子供1人、シニア1人　【結果】■販売合計金額：2,900円
test(34,1, 1, 1, 1, false, SATURDAY, EVENING,true);

# [SUCCESS] [通常チケット][休日割増(日曜日)] 大人1人、子供1人、シニア1人　【結果】■販売合計金額：2,900円
test(35,1, 1, 1, 1, false, SUNDAY, MORNING,true);

# [SUCCESS] [通常チケット][休日割増(割引なし曜日 & 祝日)] 大人1人、子供1人、シニア1人　【結果】■販売合計金額：2,900円
test(36,1, 1, 1, 1, true, FRIDAY, MORNING, true);

# [SUCCESS] [通常チケット][休日割増(割引あり曜日 & 祝日)] 大人1人、子供1人、シニア1人　【結果】■販売合計金額：2,900円 (曜日割引は適用されず、休日割増が適用)
test(37,1, 1, 1, 1, true, WEDNESDAY, MORNING, true);

# [SUCCESS] [通常チケット][休日割増(割引あり曜日 & 祝日)] 大人1人、子供1人、シニア1人　【結果】■販売合計金額：2,900円
test(38,1, 1, 1, 1, true, WEDNESDAY, MORNING, true);

# [SUCCESS] [通常チケット][休日割増]団体割 大人10人、子供1人、シニア1人　【結果】■販売合計金額：12,570円
test(39,1, 10, 1, 1, true, WEDNESDAY, MORNING, true);

# [SUCCESS] [通常チケット][休日割増]団体割 夕方割 大人10人、子供1人、シニア1人　【結果】■販売合計金額：11,370
test(40,1, 10, 1, 1, true, WEDNESDAY, EVENING, true);

##テストパターンの追加
## Q&A追加
## README追記

## 問題2をやる