<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Farm;

class FarmSeeder extends Seeder
{
    public function run(): void
    {
        $farms = [
            [
                'name'                => 'Ferme El Mansouri',
                'owner'               => 'Hassan El Mansouri',
                'city'                => 'Fès',
                'region'              => 'Fès-Meknès',
                'email'               => 'contact@ferme-elmansouri.ma',
                'phone'               => '+212 535 000 001',
                'emoji'               => '🌿',
                'bio_certified'       => true,
                'contract_expires_at' => '2027-06-30',
            ],
            [
                'name'                => 'Domaine Souissi',
                'owner'               => 'Karim Souissi',
                'city'                => 'Meknès',
                'region'              => 'Fès-Meknès',
                'email'               => 'info@domaine-souissi.ma',
                'phone'               => '+212 535 000 002',
                'emoji'               => '🫒',
                'bio_certified'       => true,
                'contract_expires_at' => '2026-05-20',  // expires soon — triggers notification
            ],
            [
                'name'                => 'Coopérative Idriss',
                'owner'               => 'Idriss Berrada',
                'city'                => 'Azilal',
                'region'              => 'Béni Mellal-Khénifra',
                'email'               => 'coop.idriss@gmail.com',
                'phone'               => '+212 523 000 003',
                'emoji'               => '🍯',
                'bio_certified'       => true,
                'contract_expires_at' => '2027-12-31',
            ],
            [
                'name'                => 'Ferme Benali',
                'owner'               => 'Youssef Benali',
                'city'                => 'Meknès',
                'region'              => 'Fès-Meknès',
                'email'               => 'ferme.benali@gmail.com',
                'phone'               => '+212 535 000 004',
                'emoji'               => '🌾',
                'bio_certified'       => true,
                'contract_expires_at' => '2027-09-15',
            ],
            [
                'name'                => 'Moulin Berbère',
                'owner'               => 'Aicha Tazi',
                'city'                => 'Marrakech',
                'region'              => 'Marrakech-Safi',
                'email'               => 'moulin.berbere@ma.ma',
                'phone'               => '+212 524 000 005',
                'emoji'               => '🏺',
                'bio_certified'       => true,
                'contract_expires_at' => '2028-01-01',
            ],
            [
                'name'                => 'Coopérative Féminine',
                'owner'               => 'Fatna Chraibi',
                'city'                => 'Tiznit',
                'region'              => 'Souss-Massa',
                'email'               => 'coop.feminine@gmail.com',
                'phone'               => '+212 528 000 006',
                'emoji'               => '🌺',
                'bio_certified'       => true,
                'contract_expires_at' => '2027-03-31',
            ],
        ];

        foreach ($farms as $farm) {
            Farm::firstOrCreate(['name' => $farm['name']], $farm);
        }
    }
}
