<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CriarAdmin extends Command
{
    protected $signature = 'gocarmat:criar-admin
        {--email= : E-mail do utilizador}
        {--nome= : Nome a mostrar no backoffice}
        {--password= : Password (se omitida, é gerada uma aleatória e mostrada no fim)}';

    protected $description = 'Cria ou repõe um utilizador do backoffice. Útil em alojamentos sem acesso a terminal.';

    public function handle(): int
    {
        $email = $this->option('email') ?: 'hello@jelly.pt';
        $nome = $this->option('nome') ?: 'Administrador';
        $password = $this->option('password') ?: Str::password(16, symbols: false);

        $existia = User::where('email', $email)->exists();

        User::updateOrCreate(
            ['email' => $email],
            ['name' => $nome, 'password' => $password],
        );

        $this->info(($existia ? 'Password reposta para ' : 'Utilizador criado: ').$email);

        if (! $this->option('password')) {
            $this->newLine();
            $this->warn('Password gerada: '.$password);
            $this->line('Guarde-a agora — não voltará a ser mostrada.');
        }

        return self::SUCCESS;
    }
}
