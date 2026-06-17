<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SystemDictionariesSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CityTypeSeeder::class,
            StreetTypeSeeder::class,
            PowerLineTypeSeeder::class,
            TpTypeSeeder::class,
            CustomerTypeSeeder::class,
        ]);
    }
}
