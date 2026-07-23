<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    public function run(): void
    {
        $offices = [
            [
                'name' => 'Adroana',
                'slug' => 'adroana',
                'address_line1' => 'Rua Beatriz Costa, 36, 36A armazém',
                'address_line2' => '2645-132 Alcabideche',
                'schedule' => 'SEG-SEX: 08:30 – 18:00',
                'phones' => '925 410 248 / 910 684 941',
                'phone_note' => 'Chamada para a rede móvel nacional',
                'email' => 'adroana@gocarmat.pt',
                'sort_order' => 1,
            ],
            [
                'name' => 'Carnaxide',
                'slug' => 'carnaxide',
                'address_line1' => 'Avenida dos Cavaleiros 37',
                'address_line2' => '2794-055 Portela de Carnaxide',
                'schedule' => 'SEG-SEX: 08:30 – 18:00',
                'phones' => '912 682 735',
                'phone_note' => 'Chamada para a rede móvel nacional',
                'email' => 'carnaxide@gocarmat.pt',
                'sort_order' => 2,
            ],
            [
                'name' => 'Elias Garcia',
                'slug' => 'elias-garcia',
                'address_line1' => 'Av. Elias Garcia 105',
                'address_line2' => '1050-053 Lisboa',
                'schedule' => 'SEG-SEX: 08:30 – 18:30',
                'phones' => '910 689 291',
                'phone_note' => 'Chamada para a rede móvel nacional',
                'email' => 'eliasgarcia@gocarmat.pt',
                'sort_order' => 3,
            ],
            [
                'name' => 'Foros da Amora',
                'slug' => 'foros-da-amora',
                'address_line1' => 'Estrada dos Foros da Amora S/N',
                'address_line2' => '2840-213 Seixal',
                'schedule' => 'SEG-SEX: 08:30 – 18:00',
                'phones' => '210 900 114 / 910 684 696',
                'phone_note' => 'Chamada para a rede fixa e móvel nacional',
                'email' => 'amora@gocarmat.pt',
                'sort_order' => 4,
            ],
        ];

        foreach ($offices as $office) {
            Office::updateOrCreate(['slug' => $office['slug']], $office);
        }
    }
}
