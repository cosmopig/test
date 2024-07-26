# 使用官方 PHP 镜像
FROM php:8.1-apache

# 安裝 Composer 和 PHP 擴展
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && docker-php-ext-install mysqli

# 設置工作目錄
WORKDIR /var/www/html

# 複製當前目錄內容到容器中的工作目錄
COPY . .

# 使用 Composer 安裝 PHP 依賴
RUN composer install --no-dev --optimize-autoloader || true

# 設置 ServerName 指令
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# 設置 Apache 的 DocumentRoot
RUN echo 'DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/000-default.conf

# 暴露端口
EXPOSE 80

# 啟動 Apache 服務
CMD ["apache2-foreground"]
