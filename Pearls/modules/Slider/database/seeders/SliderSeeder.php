<?php

namespace Pearls\Modules\Slider\database\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Pearls\Modules\Slider\database\seeders\SliderPermissionSeeder;

class SliderSeeder extends Seeder
{    
	public function run(): void
    {
	    $this->call(SliderPermissionSeeder::class);
	}
}
