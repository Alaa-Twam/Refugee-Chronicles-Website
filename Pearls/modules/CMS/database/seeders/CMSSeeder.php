<?php

namespace Pearls\Modules\CMS\database\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Pearls\Modules\CMS\database\seeders\CMSPermissionSeeder;

class CMSSeeder extends Seeder
{    
	public function run(): void
    {
	    $this->call(CMSPermissionSeeder::class);
	}
}
