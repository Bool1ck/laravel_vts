<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\City;
use App\Models\CityType;
use App\Models\ConnectingPoint;
use App\Models\ConnectingPointWorkType;
use App\Models\CustomerType;
use App\Models\PowerLineType;
use App\Models\Region;
use App\Models\Role;
use App\Models\RoleRegionUser;
use App\Models\Street;
use App\Models\StreetType;
use App\Models\Tp;
use App\Models\TpType;
use App\Models\User;
use App\Models\WorkType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoSandboxSeeder extends Seeder
{
    /**
     * Запуск наповнення демо-песочниці з повною перезаписом даних
     */
    public function run(): void
    {
        // Перевірка існування довідників перед запуском сідерів для уникнення дублікатів СУБД
        if (! CityType::exists()) {
            (new CityTypeSeeder)->run();
        }
        if (! StreetType::exists()) {
            (new StreetTypeSeeder)->run();
        }
        if (! TpType::exists()) {
            (new TpTypeSeeder)->run();
        }
        if (! CustomerType::exists()) {
            (new CustomerTypeSeeder)->run();
        }
        if (! PowerLineType::exists()) {
            (new PowerLineTypeSeeder)->run();
        }

        if (! WorkType::exists()) {
            (new WorkTypeSeeder)->run();
        }

        DB::transaction(function () {
            // =========================================================================
            // 1. ТОТАЛЬНЕ ОЧИЩЕННЯ БАЗИ ДАНИХ (ІЗОЛЬОВАНЕ СЕРВЕРНЕ ОТОРЖЕННЯ)
            // =========================================================================

            // ІСПРАВЛЕНО: Швидке та безпечне очищення таблиць через прямі SQL-запити query()
            // Це виключає падіння колекцій за методом forceDelete()
            ConnectingPointWorkType::query()->delete(); // 1. Види робіт точок
            ConnectingPoint::query()->forceDelete();    // 2. Самі точки приєднання ПДК
            Tp::query()->delete();                      // 3. Трансформатори (ТП)
            Street::query()->delete();                  // 4. Вулиці
            City::query()->delete();                    // 5. Населені пункти
            RoleRegionUser::query()->delete();          // 6. Зв'язки ролей користувачів
            User::query()->forceDelete();               // 7. Самі користувачі системи
            Region::query()->forceDelete();

        });

        // Стискаємо лічильники автоінкременту до початкового рівня
        DB::statement('ALTER TABLE cities AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE streets AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE tps AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE connecting_points AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE role_region_users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE connecting_point_work_types AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE regions AUTO_INCREMENT = 1;');

        DB::transaction(function () {
            $region = Region::create(['name' => 'Тестовий РЕМ']);

            // =========================================================================
            // 2. СТВОРЕННЯ СТРУКТУРИ ІНФРАСТРУКТУРИ (МІСТА, ВУЛИЦІ, ТП)
            // =========================================================================
            $cityType = CityType::first();

            $city1 = City::create(['name' => 'Демо 1', 'city_type_id' => $cityType->id, 'region_id' => $region->id]);
            $city2 = City::create(['name' => 'Демо 2', 'city_type_id' => $cityType->id, 'region_id' => $region->id]);

            $streetType = StreetType::first();

            $street1 = Street::create(['name' => 'Грушевського Демо', 'city_id' => $city1->id, 'street_type_id' => $streetType->id]);
            $street2 = Street::create(['name' => 'Шевченка Демо', 'city_id' => $city1->id, 'street_type_id' => $streetType->id]);
            $street3 = Street::create(['name' => 'Петренка Демо', 'city_id' => $city2->id, 'street_type_id' => $streetType->id]);
            $street4 = Street::create(['name' => 'Козленка Демо', 'city_id' => $city2->id, 'street_type_id' => $streetType->id]);

            $tpType = TpType::first();

            $tp1 = Tp::create(['name' => '100', 'city_id' => $city1->id, 'tp_type_id' => $tpType->id]);
            $tp2 = Tp::create(['name' => '200', 'city_id' => $city1->id, 'tp_type_id' => $tpType->id]);
            $tp3 = Tp::create(['name' => '300', 'city_id' => $city2->id, 'tp_type_id' => $tpType->id]);
            $tp4 = Tp::create(['name' => '400', 'city_id' => $city2->id, 'tp_type_id' => $tpType->id]);

            // =========================================================================
            // 3. СТВОРЕННЯ ТЕСТОВИХ КОРИСТУВАЧІВ ТА ПРИВ'ЯЗКА РОЛЕЙ
            // =========================================================================
            $rootUser = User::firstOrCreate(
                ['email' => 'demo.root@rem.com'],
                ['name' => 'Root', 'password' => Hash::make('derparol')],
            );
            $rootUser->update(['name' => 'Root', 'password' => Hash::make('derparol')]);
            $adminUser = User::firstOrCreate(
                ['email' => 'demo.admin@rem.com'],
                ['name' => 'Андрій', 'password' => Hash::make('password')],
            );
            $adminUser->update(['name' => 'Андрій', 'password' => Hash::make('password')]);

            $vtgUser = User::firstOrCreate(
                ['email' => 'demo.vtg@rem.com'],
                ['name' => 'Сергій', 'password' => Hash::make('password')],
            );
            $vtgUser->update(['name' => 'Сергій', 'password' => Hash::make('password')]);

            $engineerUser = User::firstOrCreate(
                ['email' => 'demo.engineer@rem.com'],
                ['name' => 'Володимир', 'password' => Hash::make('password')],
            );
            $engineerUser->update(['name' => 'Володимир', 'password' => Hash::make('password')]);

            RoleRegionUser::create([
                'user_id' => $adminUser->id,
                'region_id' => $region->id,
                'role_id' => Role::where('name', 'admin')->first()->id,
            ]);
            RoleRegionUser::create([
                'user_id' => $vtgUser->id,
                'region_id' => $region->id,
                'role_id' => Role::where('name', 'ВТГ')->first()->id,
            ]);
            RoleRegionUser::create([
                'user_id' => $engineerUser->id,
                'region_id' => $region->id,
                'role_id' => Role::where('name', 'Головний інженер')->first()->id,
            ]);

            // =========================================================================
            // 4. ГЕНЕРАЦІЯ ТОЧОК ПРИЄДНАННЯ ПДК ЧЕРЕЗ ФАБРИКИ
            // =========================================================================
            $customerType = CustomerType::first();
            $powerLineType = PowerLineType::first();

            ConnectingPoint::factory()->create([
                'region_id' => $region->id,
                'customer_type_id' => $customerType->id,
                'technical_conditions' => 'ТУ-DEMO/01',
                'point_place' => $city1->fullName() . ', ' . $street1->fullName() . ', буд. ' . rand(10, 30),
                'power_point' => 'ПЛ-' . $powerLineType->name . 'кВ від ' . $tp1->fullName() . ', ' .
                    $tp1->city->fullName() . ',Л-1 опора №' . rand(10, 30),
                'performance_date' => null,
            ]);

            ConnectingPoint::factory()->create([
                'region_id' => $region->id,
                'customer_type_id' => $customerType->id,
                'technical_conditions' => 'ТУ-DEMO/02',
                'point_place' => $city2->fullName() . ', ' . $street3->fullName() . ', буд. ' . rand(10, 30),
                'power_point' => 'ПЛ-' . $powerLineType->name . 'кВ від ' . $tp3->fullName() . ', ' .
                    $tp3->city->fullName() . ',Л-1 опора №' . rand(10, 30),
                'performance_date' => now()->format('Y-m-d'),
            ]);
        });
    }
}
