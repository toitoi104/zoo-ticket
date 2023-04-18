# 問題２

## 設問1. 案件進行中にどのような問題が発生しうるでしょうか？

### 納期の遅れ

- 本人の実力不足(タスクのアサインミス)
- 予期せぬ技術的な問題(技術負債など)
- スプリント途中での仕様変更
- 無理のあるスケジュール
- テストケース漏れ
- 差し込みタスクによる影響(運用調査、不具合修正など)
- 属人化したコード
- 担当者の不在
- 新メンバーのキャッチアップスピードが遅い
- メンバーの離職

## 設問2. 発生しそうな問題に対して、どのような準備や対策ができますか？

- ドキュメント化
- 各メンバーの技術力向上(タスクに対して1人でできる範囲を大きくする)
- タスクの目的に関してヒアリング(目的と要望がズレいないか)
- 技術負債の返済
- タスクの粒度を小さくする(バッファを多く持つ)
- スプリント生産量の再計算
- テストコードの最低限書く量を7割以上にする
- レビューでのLGTM人数を2人以上にする

## 設問3. 前提条件を踏まえ開発チームが生産性や品質を改善するためにできる対策を提案してください。

### 課題

- 開発スピードの向上

### 開発環境の仮定

- PHPのCodeigniterが採用されている
- Codeigniterのバージョンは古い
- 開発メンバーからはLaravelを触りたい要望が出ている

### 何をやるべきか

- プロダクトチームや組織トップ層への承認作業や説得
- 開発チームでの認識合わせ
- アーキテクチャの選定
- 技術負債返済プロジェクトの発足

### なぜやるべきか

- サービスをさらに拡大させるため
- プロダクトバックログが増加傾向である
- 開発チームとしても時間をあてたいと要望が出ている
- スプリント生産量の平均が徐々に下がっていくことが予想される

### 具体的な改善策

- 事業ドメイン単位、機能単位での段階的リニューアル
- 独自ドメインのURLディレクトリ単位でWEBアクセスの向き先を変更する

### 想定される改善効果

- 開発効率の向上（スプリントの生産量の向上）
- 開発ストレスの軽減(離職防止)
- メンバーの技術知識の向上
- 技術負債への意識向上
- 不具合の軽減
- エンジニア採用での応募者向上