# GOCARMAT — Website corporativo

Site para a GOCARMAT (rede de 4 oficinas multimarca na Grande Lisboa, com o laboratório de mobilidade elétrica EVA Powerlab). Substitui o WordPress antigo em gocarmat.pt. Projeto da Jelly.

## Stack

- **Laravel 13** (PHP ≥ 8.3) + **Filament 5** (backoffice em `/admin`)
- **Tailwind CSS 4** via Vite — tokens do design system em `resources/css/app.css` (`@theme`)
- Blade no front-office; SQLite em dev (`database/database.sqlite`), MySQL previsto para produção
- Fontes: Inter + JetBrains Mono (bunny.net); ícones: SVGs inline em `resources/views/components/ui/icon.blade.php` (o design usa Font Awesome Pro, substituído por falta de licença)

## Comandos

```bash
php artisan serve --port=8095     # dev server (8090 é usado por outro projeto local)
npm run build                      # rebuild do CSS/JS (fazer após mexer em views/CSS)
php artisan gocarmat:import-wordpress storage/app/import/wordpress-export.xml --skip-images
                                   # importação WordPress (idempotente; tags/categorias/redirects)
php artisan make:filament-user     # criar utilizador do backoffice
```

## Design system (do Figma)

Cores em `@theme`: `energia` #0e61fb, `carbono` #030919, `lima` #e4fe55, `gelo` #eef5ff, `cristal` #b6f1ff, `tecnico` #030d54, `cloud` #f7fafe (fundo), `signal` #3a44fc (menu ativo).
Headings de secção: `font-mono font-extrabold uppercase` 52px. Eyebrows: mono 13px tracking largo. CTAs: componente `<x-pill>` (variantes lima/dark/blue/outline-dark/outline-light). Breakpoints: design é 1920px — valores exatos aplicam-se em `2xl`; layout adapta em baixo.
Mockup Figma: fileKey `YyW4CEWQ5n46oteChtccZh` (Home 2:18788, Sobre Nós 23-557, Serviços 29-1123, EVA 30-1675, Contactos 37-3226).

## Estrutura de conteúdos

- **Posts** (blog): 140 artigos migrados do WordPress, com categorias, tags, SEO (meta title/description/OG) e imagens em `storage/app/public/blog`. Route key = slug. Quando o cliente publica artigos novos no WordPress sem gerar um export XML atualizado, `php artisan gocarmat:import-recent-blog-posts` importa os que foram recolhidos manualmente por scraping (ver docblock do comando); idempotente por `wp_id`, tal como o `gocarmat:import-wordpress`.
- **Offices**: as 4 oficinas, geríveis no backoffice, mostradas via `partials/offices-grid` (view composer injeta `$offices`).
- **Bookings**: pedidos do formulário `/marcacoes` (validação + honeypot + emails admin/cliente via Mailables markdown).
- **Settings**: key-value (GA4, Pixel, email de notificação, Brevo) — `Setting::get()/set()` com cache.
- **Redirects**: 301 dos URLs antigos do WordPress (raiz → `/blog/slug`), servidos por `Route::fallback`.

## Deploy

**GitHub Actions compila** (composer + vite) a cada push para `main` e publica no branch `deploy` (inclui `vendor/` e `public/build`, porque o servidor não tem Composer nem Node). O deploy em si **não é automático**: o pipeline por FTPS (`deploy-staging.yml`) existe mas mostrava sucesso sem realmente atualizar ficheiros — não usar. O caminho que funciona:
1. cPanel → Git Version Control → "Update from Remote" no repositório certo (clone só do código, não o site ao vivo)
2. Chamar `public/deploy.php?acao=instalar` (com `X-Deploy-Secret`) — copia do clone para a pasta do site (preserva `.env`, `storage/`, BD) e corre migrações/caches

Guia completo em `DEPLOY.md`.

**Ambientes**, todos no servidor 185.31.158.162 (cPanel, PHP 8.4 em `/opt/cpanel/ea-php84/root/usr/bin/php`, SQLite, Terminal do cPanel disponível para comandos):
- **Staging** (`staging.gocarmat.pt`, protegido por `STAGING_PASSWORD`) — pasta `/home/gocarmat/gocarmat`, clone em `/home/gocarmat/repositories/gocarmat-web` (branch `deploy`).
- **Produção** (`gocarmat.pt` / `www.gocarmat.pt`, ao vivo, sem password) — pasta `/home/gocarmat/producao`, clone em `/home/gocarmat/repositories/gocarmat-web-producao`. O domínio principal desta conta cPanel não permite mudar o document root — `public_html` é o WordPress antigo (guardado em `public_html_wordpress_backup`), e a produção liga-se por um symlink + `.htaccess` dentro de `public_html` (ver `DEPLOY.md`).
- `deploy.php` deteta sozinho qual clone usar consoante a pasta onde corre, mas é sempre excluído da cópia automática (evita autodestruir-se a meio) — qualquer alteração a esse ficheiro tem de ser colada à mão via File Manager nos dois ambientes.

## Proteção de pré-produção

Definir `STAGING_PASSWORD` no `.env` fecha **todo** o site (incluindo `/admin`) atrás de autenticação HTTP básica, devolve `X-Robots-Tag: noindex, nofollow` e faz o `robots.txt` bloquear tudo — evita indexação pelo Google (conteúdo duplicado face ao site real) e exposição do trabalho em curso. Em produção, deixar a variável vazia. Ver `app/Http/Middleware/ProtegerStaging.php`.

## Por fazer

- Migrar staging/produção para MySQL (hoje SQLite)
- DNS final: `gocarmat.pt`/`www.gocarmat.pt` já apontam para este servidor — nada a fazer aí. Falta só emitir/confirmar SSL (AutoSSL) para o domínio principal, já que o certificado até agora servia o WordPress.
- Utilizadores de backoffice da Jelly mantidos em produção de propósito (acesso de suporte), a pedido do cliente.
- Validar com o cliente: textos das FAQs do EVA (3 respostas escritas por nós), horário alargado (9h-19h+sáb vs 08:30-18:00 das oficinas), texto do card Climatização
- **Iubenda (RGPD)**: integração completa com os scripts fornecidos pelo cliente — Cookie Solution (`partials/cookie-consent.blade.php`, siteId `2498738`, cookiePolicyId `35917140`, locale `pt`), Política de Privacidade (`resources/views/privacy.blade.php`, rota `/politica-de-privacidade`), Política de Cookies (`resources/views/cookies.blade.php`, rota `/politica-de-cookies`) e Termos e Condições (`resources/views/terms.blade.php`, rota `/termos-e-condicoes`), todas ligadas no footer. Falta apenas:
  - IDs de "purpose" (Privacy Controls → Purposes no painel Iubenda) para Analytics e Marketing, para religar o GA4/Meta Pixel ao consentimento (ficou desligado quando o banner caseiro foi substituído — ver `App\Models\Setting::get('ga4_id'/'meta_pixel_id')`)

## Notas

- **SMTP oficial ativo** (local, staging e produção): Microsoft 365, `MAIL_HOST=smtp.office365.com`, porta 587, `MAIL_USERNAME=MAIL_FROM_ADDRESS=apoiocliente@gocarmat.pt`. Password só no `.env` de cada ambiente (nunca no repositório).
- **Redirecionamentos** (`App\Models\Redirect`, `Route::fallback`) já são editáveis no backoffice em `/admin` → Redirecionamentos — antes só existiam via os comandos de importação do WordPress.
- **Google Search Console**: propriedade `www.gocarmat.pt` verificada por ficheiro (`public/google154ffd39c2849e87.html` — não apagar, o Google reconfirma a verificação de vez em quando) e sitemap (`/sitemap.xml`) submetido. Há também um campo alternativo em Definições (`google_site_verification`, método "etiqueta HTML") para se um dia for preciso reverificar.
- `/contactos` → 301 → `/marcacoes` (mesma página no design).
- Emails em dev vão para `storage/logs/laravel.log` (`MAIL_MAILER=log`).
- ⚠️ **`QUEUE_CONNECTION` tem de ser `sync`**. As notificações do Filament (ex: recuperação de password) implementam `ShouldQueue`; com `database` ficam presas na tabela `jobs` e o email nunca sai, porque não há queue worker no alojamento partilhado.
- Backoffice tem recuperação de password (`->passwordReset()`) e página de perfil (`->profile()`), onde cada utilizador muda a sua própria password.
