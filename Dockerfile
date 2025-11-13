# 1. Começamos com uma imagem oficial do PHP que já inclui o Apache
FROM php:8.2-apache

# 2. Copia o código da sua aplicação para dentro do contêiner
# (O docker-compose.yml já faz isso com o 'volume', mas é uma boa prática)
COPY . /var/www/html/

# 3. A CORREÇÃO PRINCIPAL: Instala as extensões 'mysqli' E 'pdo_mysql'
# 'docker-php-ext-install' é o comando oficial para isso
RUN docker-php-ext-install pdo pdo_mysql mysqli && docker-php-ext-enable mysqli

# 4. Habilita o 'mod_rewrite' do Apache (bom para o futuro, para URLs amigáveis)
RUN a2enmod rewrite
# ... existing code ...