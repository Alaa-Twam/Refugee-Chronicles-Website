<?php

namespace Pearls\Base\database\seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Pearls\User\database\seeders\PermissionSeeder;
use Pearls\User\database\seeders\RoleSeeder;
use Pearls\User\database\seeders\UserSeeder;
use Pearls\Modules\CMS\database\seeders\CMSSeeder;
use Pearls\Modules\City\database\seeders\CitySeeder;
use Pearls\Modules\Slider\database\seeders\SliderSeeder;

class PearlsDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CMSSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(SliderSeeder::class);
    }
}
