# Deploy GOCARMAT em cPanel

## Deploy automático (GitHub Actions → FTPS)

A partir do momento em que os secrets estiverem configurados, **cada push para `main` publica automaticamente no staging**. O workflow está em `.github/workflows/deploy-staging.yml` e faz: instala dependências PHP, compila os assets, envia os ficheiros alterados por FTPS e chama `public/deploy.php` para correr migrações e reconstruir caches.

Isto é necessário porque o servidor partilhado não tem Composer nem Node — a compilação acontece toda no GitHub.

### Configuração (uma vez)

**1. Conta FTP no cPanel** — *FTP Accounts* → criar (ex: `deploy@gocarmat.pt`), com diretório `/home/gocarmat/gocarmat`.

**2. Segredo do endpoint** — gerar um valor aleatório longo (`openssl rand -hex 24`) e acrescentar ao `.env` **do servidor**:
```
DEPLOY_SECRET=<valor>
```

**3. Secrets no GitHub** (*Settings → Secrets and variables → Actions*):

| Secret | Valor |
|---|---|
| `FTP_SERVER` | `185.31.158.162` (ou `ftp.gocarmat.pt`) |
| `FTP_USERNAME` | utilizador FTP completo |
| `FTP_PASSWORD` | password do FTP |
| `FTP_SERVER_DIR` | `/` se a conta FTP já apontar para `~/gocarmat`, senão `/gocarmat/` |
| `DEPLOY_URL` | `https://staging.gocarmat.pt/deploy.php` |
| `DEPLOY_SECRET` | o **mesmo** valor posto no `.env` do servidor |

### O que nunca é enviado

O workflow exclui `.env`, `storage/**`, `database/database.sqlite` e `public/storage/**` — ou seja, **a base de dados e as imagens carregadas no backoffice nunca são sobrepostas** por um deploy.

### Primeira execução

O primeiro deploy envia todos os ficheiros (alguns minutos); os seguintes são incrementais e rápidos, porque a action guarda no servidor o estado do último envio.

---

## Deploy manual (primeira instalação / recuperação)

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
