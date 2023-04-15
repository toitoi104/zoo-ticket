# zoo-ticket

## オペレーター向け

これはチケット料金を計算するプログラムです

### 使い方

- ターミナルを開き、コマンドのパラメーターを変更し、エンターを押して実行してください。

### パラメーター

- adult：大人の人数(半角数字)
- child：子供の人数(半角数字)
- senior：シニアの人数(半角数字)
- type：1(通常料金)、2(特別料金)
- is_holiday：祝日かどうか

### 実行するコマンド
 
- 通常料金で大人1人、子供2の場合(通常料金の場合、「type」パラメーターは指定しなくても大丈夫です)
```
php index.php --adult=1 --child=2
```

- 特別料金で大人1人、子供1人、シニア2人の場合
```
php index.php --adult=1 --child=1 --senior=2 --type=2
```

-
```
php index.php --adult=1 --child=2
```

- 祝日の場合

## エンジニア向け

### ドメイン
 
- 動物園の開園時間は9:00-20:00

### docker起動

```angular2html
$ docker compose build
$ docker compose up -d
$ docker container exec -it コンテナID bash
```