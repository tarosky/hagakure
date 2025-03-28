# Hagakure 開発ガイド

## Requirements

- PHP 7.4 以上
- Composer
- Node.js と npm
- Docker

## セットアップ

1. リポジトリをクローンします。

```sh
git clone https://github.com/tarosky/hagakure.git
cd hagakure
```

2. Composer 依存関係をインストールします。

```sh
composer install
```

3. npm 依存関係をインストールします。

```sh
npm install
```

4. WordPressのローカル環境を立ち上げます。Dockerが必要です。

```sh
npm start
```

## ローカル開発の手順

hagakureはエラーが起きたときに反応するプラグインなので、人為的にエラーを引き起こす必要があります。

### ショートコード

テスト用のディレクトリにあるクラス `tests/src/InappropriateShortCode` でショートコードを登録してあります。

- `[overflow]` を投稿に追加すると、無限ループによりメモリリミットエラーを引き起こします。
- `[fatal_error]` を投稿に追加すると、存在しない関数を呼び出すことで致命的なエラーを引き起こします。

### CLIツール

hagakureは専用のWP-CLIコマンドを提供しています。

```sh
# エラーを引き起こす	
wp hagakure error "Error occurred"
# 使用方法を確認
wp help hagakure
```

## テスト

### PHP Unit テスト

PHP Unit テストを実行するには、以下のコマンドを使用しますが、Docker環境が必要です。

```sh
npm test
```

### コードスタイルチェック

PHP コードスタイルをチェックするには、以下のコマンドを使用します。

```sh
# チェック
composer lint
# 自動修正
composer fix
```

## ビルド

プラグインをビルドするには、以下のコマンドを使用します。

```sh
bash bin/build.sh "github/ref/[バージョン番号]"
```

## デプロイ

- バージョン番号（例・ `2.0.0` ）をタグとしてmasterブランチのコミットにつけてください。
- タグをプッシュすると、GitHub Actions を使用して、プラグインビルドし、を WordPress.org にデプロイします。詳細は `.github/workflows/wordpress.yml` を参照してください。

## コントリビュート

1. Issue を作成し、変更内容を説明してください。
2. このリポジトリのWrite権限がない場合はフォークして、ブランチを作成します。

```sh
git checkout -b feature/your-feature
```

3. 変更をコミットします。

```sh
git commit -m 'Add some feature'
```

4. ブランチにプッシュします。

```sh
git push origin feature/your-feature
```

5. プルリクエストを作成します。

## その他

詳細な利用方法や FAQ は [README.md](./README.md) を参照してください。

---

<p style="text-align: center">&copy; 2025 <a href="https://tarosky.co.jp">Tarosky</a></p>
