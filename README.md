# laravel-backend-pdv
### Requisitos
PHP >= 7.1

Composer

### Instalação

 - Terminal: composer install

 - copie o arquivo .env.example para .env e altere as conexões com o banco de dados

 - Terminal: php artisan key:generate

 - Terminal:  php artisan migrate

 - Terminal: php artisan passport:install

 - Abra o banco de dados, execute update oauth_clients set user_id = 2 where id = 2

 - Utilize os dados para essa configuração do Front-End

#### Subir Servidor
 - Terminal: php artisan serve
 
 #### Tests:
  - Terminal: composer test