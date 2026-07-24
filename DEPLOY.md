# Deploy GOCARMAT em cPanel

Requisitos: PHP ≥ 8.3 (MultiPHP Manager), MySQL, e idealmente acesso SSH.

## 1. Preparação no cPanel (uma vez)

1. **PHP**: em *MultiPHP Manager*, definir PHP 8.3+ para o domínio. Em *MultiPHP INI Editor*: `memory_limit=256M`, `upload_max_filesize=16M`.
2. **Base de dados**: em *MySQL Databases*, criar BD + utilizador com todos os privilégios; anotar nome/user/password.
3. **Document root**: o Laravel serve a partir de `public/`. Para o domínio principal, apontar o document root do domínio para `~/gocarmat/public` (em *Domains*). Se o cPanel não permitir mudar o docroot do domínio principal, usar a alternativa `.htaccess` no fim deste guia.
4. **Email**: criar a conta `no-reply@gocarmat.pt` em *Email Accounts* (para o SMTP).

## 2. Upload do código

Com SSH (recomendado):
```bash
# no servidor
cd ~ && mkdir -p gocarmat
# a partir da máquina local:
rsync -az --delete \
  --exclude node_modules --exclude .git --exclude .env \
  --exclude storage/logs --exclude database/database.sqlite \
  ~/code/gocarmat/ utilizador@servidor:~/gocarmat/
```

Sem SSH: comprimir o projeto localmente (sem `node_modules`, `.git`, `database/database.sqlite`) e extrair via *File Manager*.

Nota: `vendor/` PODE ser enviado do local (evita correr composer no servidor). Em alternativa, no servidor: `composer install --no-dev --optimize-autoloader`.

## 3. Configuração no servidor

```bash
cd ~/gocarmat
cp .env.production.example .env
# editar .env com os dados da BD e SMTP
php artisan key:generate
php artisan storage:link
php artisan migrate --force
php artisan db:seed --class=OfficeSeeder --force

# importar os artigos do WordPress (as imagens já vão no upload de storage/)
php artisan gocarmat:import-wordpress storage/app/import/wordpress-export.xml --skip-images

# criar o utilizador do backoffice (pede nome/email/password)
php artisan make:filament-user

# caches de produção
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## 4. Verificação

- https://www.gocarmat.pt (Home) e páginas interiores
- https://www.gocarmat.pt/inspecao-automovel → deve dar 301 para /blog/inspecao-automovel
- /admin → login e criação de um artigo de teste
- Submeter o formulário de marcações → verificar receção dos 2 emails

## 5. Pós-deploy

- Ativar SSL (AutoSSL/Let's Encrypt) e forçar HTTPS
- Só depois de validado: apontar o DNS de gocarmat.pt para este servidor (se ainda estiver no hosting antigo)

## Alternativa sem docroot configurável

Colocar o projeto em `~/gocarmat` e criar `~/public_html/.htaccess`:
```apache
RewriteEngine on
RewriteCond %{REQUEST_URI} !^/gocarmat-public/
RewriteRule ^(.*)$ /gocarmat-public/$1 [L]
```
com um symlink `~/public_html/gocarmat-public -> ~/gocarmat/public`. (Menos limpo; preferir docroot.)
