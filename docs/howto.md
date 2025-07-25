# 環境構築方法

## インストール

- Git (GitHub)
- Docker Desktop
- ターミナル (Windowsの場合、「PowerShell」, 「GitBash」等)
- VSCode (任意)
- SQLクライアント (任意 「A5:SQL Mk-2」、「DBeaver」等)

## 開発環境

Dockerを使って環境を構築します。  

### 構築する環境

- Webコンテナ
  - [php:8.1.14-apache](https://hub.docker.com/_/php)
  - [composer:2.5.1](https://hub.docker.com/_/composer)
- DBコンテナ
  - [mysql:8.0.31](https://hub.docker.com/_/mysql)
- phpMyAdminコンテナ
  - [phpmyadmin:5.2.0](https://hub.docker.com/_/phpmyadmin)

### .env

[.env](./.env)ファイルはDockerの環境ファイルです。  
各名称・ポート設定をしてください。  
基本的にはそのまま使用可能ですが、IPとポートが重複するとコンテナが起動しないので  
自身の環境に合わせて設定を変えてください。


### リポジトリをクローン & ディレクトリ移動 
```bash
git clone https://github.com/haruka-a95/asset_management.git
cd asset_management
```

### Dockerイメージのビルド＆起動
docker-compose up -d --build

### Laravel

Laravel関連のコマンドはDockerで用意した、WEBサーバー（コンテナ）上で行います。

```bash
# ターミナルで実行
## WEBサーバーに入るコマンド（-itの後に入る名称はコンテナ名「{NAME_PREFIX}-web」）
docker exec -it asset-manager-web bash
```

#### composer install

```bash
# ■ WEBサーバーで入力
# 「composer.json」、「composer.lock」に記載されているパッケージをvendorディレクトリにインストール
#   ※ 時間がかかるので注意。
composer install
```

`composer install` 実行後に「`Exception`」が出ていると失敗しているので  
[root/vendor/](./root/vendor/)ディレクトリを削除して、再実行してみましょう。  
「`successfully`」が出ていれば成功です。

#### Node.jsパッケージのインストールとビルド
```bash
# ■ WEBサーバーで入力
npm install
npm run dev
```
npmをインストール時に脆弱性のエラーが出た場合、以下を試してください。
```npm audit fix```

#### Laravel初期設定

```bash
# ■ WEBサーバーで入力
# 「.env」ファイル
## 「.env.dev」ファイルを「.env」にコピー
cp .env.dev .env
# storage ディレクトリに読み取り・書き込み権限を与える（bootstrap, storage内に書き込み（ログ出力時等）に「Permission denied」のエラーが発生する）
chmod -R 777 bootstrap/cache/
chmod -R 777 storage/
```

## 動作確認
- Web画面
 - URL例: http://localhost:81
 ※ IP・ポートは .env の IP と PORT_WEB を参照

- phpMyAdmin
 - URL例: http://localhost:8081
 ※ IP・ポートは .env の IP と PORT_PHPMYADMIN を参照

### Laravelのテスト環境設定
- 日本語設定済み
- Laravel Debugbar導入済み
- .env.testing 設定済み

#### マイグレーション（テスト用DB）
```bash
php artisan migrate --env=testing
```