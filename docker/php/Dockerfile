FROM php:8.1.14-apache
# php.iniをコピー
COPY ./php.ini /usr/local/etc/php/
# composer インストール (マルチステージビルド)
COPY --from=composer:2.5.1 /usr/bin/composer /usr/bin/composer
# コマンド実行
# apt(Advanced Package Tool) パッケージインストール
RUN apt-get update && apt-get install -y curl gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    # その他必要なパッケージ
    git unzip vim zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    # PHP PDO driver インストール(mysql)
    && docker-php-ext-install pdo_mysql \
    # Apache mod_rewrite 有効化
    && a2enmod rewrite \
    # DocumentRoot変更
    && sed -i 's!/var/www/html!/var/www/root/public!g' /etc/apache2/sites-available/000-default.conf
