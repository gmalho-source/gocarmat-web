<?php

return [
    /*
     * Quando STAGING_PASSWORD está definida, todo o site (incluindo o backoffice)
     * fica atrás de uma autenticação HTTP básica e com noindex. Deixar vazio em
     * produção.
     */
    'username' => env('STAGING_USERNAME', 'gocarmat'),
    'password' => env('STAGING_PASSWORD'),
];
