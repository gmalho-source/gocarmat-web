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

- **Posts** (blog): 140 artigos migrados do WordPress, com categorias, tags, SEO (meta title/description/OG) e imagens em `storage/app/public/blog`. Route key = slug.
- **Offices**: as 4 oficinas, geríveis no backoffice, mostradas via `partials/offices-grid` (view composer injeta `$offices`).
- **Bookings**: pedidos do formulário `/marcacoes` (validação + honeypot + emails admin/cliente via Mailables markdown).
- **Settings**: key-value (GA4, Pixel, email de notificação, Mailchimp) — `Setting::get()/set()` com cache.
- **Redirects**: 301 dos URLs antigos do WordPress (raiz → `/blog/slug`), servidos por `Route::fallback`.

## Deploy

**Automático:** push para `main` → GitHub Actions compila (composer + vite), envia por FTPS e chama `public/deploy.php` (migrações + caches). O servidor não tem Composer nem Node, por isso a compilação é feita no CI. `.env`, `storage/**` e a base de dados nunca são sobrepostos. Guia completo em `DEPLOY.md`. Estado atual: **staging deployado** em `/home/gocarmat/gocarmat` no servidor 185.31.158.162 (cPanel, SSH porta 45693, user `gocarmat`), docroot `staging.gocarmat.pt` → symlink para `gocarmat/public`, PHP 8.4 (`/opt/cpanel/ea-php84/root/usr/bin/php`), SQLite. **DNS de staging.gocarmat.pt pendente** — a zona autoritativa está nos ns1-4.webhs.org e o registo A ainda não propagou. SSH por chave ainda não funciona (suporte a investigar); deploys feitos por zip + Terminal do cPanel.
⚠️ O staging está na versão do commit `f9fca00` — falta atualizar com tags/pesquisa/Ken Burns/footer.

## Proteção de pré-produção

Definir `STAGING_PASSWORD` no `.env` fecha **todo** o site (incluindo `/admin`) atrás de autenticação HTTP básica, devolve `X-Robots-Tag: noindex, nofollow` e faz o `robots.txt` bloquear tudo — evita indexação pelo Google (conteúdo duplicado face ao site real) e exposição do trabalho em curso. Em produção, deixar a variável vazia. Ver `app/Http/Middleware/ProtegerStaging.php`.

## Por fazer

- Migrar staging/produção para MySQL (hoje SQLite)
- Produção: SMTP real no `.env`, remover utilizador dev do backoffice, DNS final
- Validar com o cliente: textos das FAQs do EVA (3 respostas escritas por nós), horário alargado (9h-19h+sáb vs 08:30-18:00 das oficinas), texto do card Climatização

## Notas

- Loja Online e Campanhas: fase futura — só links no menu.
- `/contactos` → 301 → `/marcacoes` (mesma página no design).
- Emails em dev vão para `storage/logs/laravel.log` (`MAIL_MAILER=log`).
- ⚠️ **`QUEUE_CONNECTION` tem de ser `sync`**. As notificações do Filament (ex: recuperação de password) implementam `ShouldQueue`; com `database` ficam presas na tabela `jobs` e o email nunca sai, porque não há queue worker no alojamento partilhado.
- Backoffice tem recuperação de password (`->passwordReset()`) e página de perfil (`->profile()`), onde cada utilizador muda a sua própria password.
