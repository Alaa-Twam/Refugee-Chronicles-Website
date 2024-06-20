<?php

namespace Pearls\Modules\City\database\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Pearls\Modules\City\database\seeders\CityPermissionSeeder;
use Pearls\Modules\City\Models\City;

class CitySeeder extends Seeder
{    
	public function run(): void
    {
	    $this->call(CityPermissionSeeder::class);

		$cities = [
			['latLng' => [32.718088222818714, 35.366031010064084], 'name' => 'Northern Palestine', 'id' => 'PS1'],
			['latLng' => [32.48040397275276, 34.97620692899044], 'name' => 'Haifa', 'id' => 'PS2'],
			['latLng' => [32.04652073530775, 34.797186743493015], 'name' => 'Jaffa', 'id' => 'PS4'],
			['latLng' => [32.39913564556745, 35.25835390401352], 'name' => 'Jenin', 'id' => 'PS7'],
			['latLng' => [32.298848152539655, 35.46049315019438], 'name' => 'Tubas', 'id' => 'PS9'],
			['latLng' => [32.279293182986734, 35.08022700858173], 'name' => 'Tulkarm', 'id' => 'PS8'],
			['latLng' => [31.89786196227892, 34.833156592624526], 'name' => 'Al-Lidd', 'id' => 'PS3'],
			['latLng' => [32.16066387595548, 35.01939512030795], 'name' => 'Qalqilya', 'id' => 'PS11'],
			['latLng' => [32.09198481809072, 35.12266548158363], 'name' => 'Salfit', 'id' => 'PS13'],
			['latLng' => [32.17579356926294, 35.30277770868626], 'name' => 'Nablus', 'id' => 'PS10'],
			['latLng' => [31.96846115165632, 35.47302444749869], 'name' => 'Jericho', 'id' => 'PS12'],
			['latLng' => [31.950641101896995, 35.190930116838686], 'name' => 'Ramallah and Al-Bireh', 'id' => 'PS14'],
			['latLng' => [31.744976860080424, 35.06550053282216], 'name' => 'Jerusalem', 'id' => 'PS15'],
			['latLng' => [31.486206762245995, 35.081903294319794], 'name' => 'Hebron', 'id' => 'PS18'],
			['latLng' => [31.61056032076845, 35.343589192720124], 'name' => 'Bethlehem', 'id' => 'PS17'],
			['latLng' => [31.532543954704913, 34.49774098593927], 'name' => 'North Gaza', 'id' => 'PS19'],
			['latLng' => [31.475410938366878, 34.43455474911522], 'name' => 'Gaza', 'id' => 'PS20'],
			['latLng' => [31.406763412706667, 34.3662323592311], 'name' => 'Dair Al Balah', 'id' => 'PS21'],
			['latLng' => [31.328402864400875, 34.31014589211448], 'name' => 'Khan Younes', 'id' => 'PS22'],
			['latLng' => [31.26708937455332, 34.27559608380837], 'name' => 'Rafah', 'id' => 'PS23'],
			['latLng' => [31.070402240838177, 34.90358263406303], 'name' => 'Naqab', 'id' => 'PS6'],
		];

		foreach ($cities as $cityData) {
            City::create([
                'id' => $cityData['id'],
                'name' => $cityData['name'],
                'lat' => $cityData['latLng'][0],
                'lng' => $cityData['latLng'][1],
            ]);
        }
	}
}
