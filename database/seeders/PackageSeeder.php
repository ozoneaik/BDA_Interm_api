<?php

namespace Database\Seeders;

use App\Models\Packages;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'แพ็กเกจ A',
                'detail' => 'ต้นลำไยไม่เกิน 60 ต้น',
                'price' => 2500,
                'amount_of_longan' => 60,
                'other' => [
                    'color' => '#028C41',
                    'bg_color' => '#E7FFE9'
                ]
            ],
            [
                'name' => 'แพ็กเกจ B',
                'detail' => 'ต้นลำไยไม่เกิน 120 ต้น',
                'price' => 5000,
                'amount_of_longan' => 120,
                'other' => [
                    'color' => '#004B8A',
                    'bg_color' => '#E0F4FF'
                ]
            ],
            [
                'name' => 'แพ็กเกจ C',
                'detail' => 'ต้นลำไยไม่เกิน 180 ต้น',
                'price' => 7500,
                'amount_of_longan' => 180,
                'other' => [
                    'color' => '#EB7100',
                    'bg_color' => '#FFF1E7'
                ]
            ],
            [
                'name' => 'แพ็กเกจ D',
                'detail' => 'ต้นลำไยไม่เกิน 240 ต้น',
                'price' => 10000,
                'amount_of_longan' => 240,
                'other' => [
                    'color' => '#8764BF',
                    'bg_color' => '#F7E5FF'
                ]
            ]
        ];

        foreach ($packages as $package) {
            $pk = Packages::query()
                ->where('name', $package['name'])
                ->first();

            if(!$pk) {
                $pk = new Packages();
            }

            $pk->fill($package);
            $pk->save();
        }
    }
}
