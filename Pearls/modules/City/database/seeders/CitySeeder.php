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
			['latLng' => [32.9646301, 35.502451], 'name' => 'Safad', 'id' => 'PS1'],
			['latLng' => [32.92560269605157, 35.246176383937154], 'name' => 'Acre (Akka)', 'id' => 'PS2'],
			['latLng' => [32.637615362692145, 35.29260071241356], 'name' => 'Nazareth (An-Naasira)', 'id' => 'PS4'],
			['latLng' => [32.431173037921454, 35.301637795877554], 'name' => 'Jenin', 'id' => 'PS7'],
			['latLng' => [32.05490882640248, 34.888074594391156], 'name' => 'Jaffa', 'id' => 'PS9'],
			['latLng' => [32.26248938894997, 35.024835969411726], 'name' => 'Tulkarm', 'id' => 'PS8'],
			['latLng' => [32.66480146608798, 35.0737221480631], 'name' => 'Haifa', 'id' => 'PS3'],
			['latLng' => [31.94197022798066, 35.2481588463269], 'name' => 'Ramallah', 'id' => 'PS11'],
			['latLng' => [31.46709582432962, 35.095844565296005], 'name' => 'Hebron (Al-Khalil)', 'id' => 'PS13'],
			['latLng' => [31.85493029342973, 34.945140482769006], 'name' => 'Al-Ramla', 'id' => 'PS10'],
			['latLng' => [31.747197625407708, 35.22440533474071], 'name' => 'Jerusalem (Al-Quds)', 'id' => 'PS12'],
			['latLng' => [31.54381529441669, 34.6528831928301], 'name' => 'Gaza', 'id' => 'PS14'],
			['latLng' => [31.2457442, 34.7925181], 'name' => 'Beersheba (Bir As-Saba)', 'id' => 'PS15'],
			['latLng' => [32.733994746206406, 35.5064561818146], 'name' => 'Tiberias (Tabariyya)', 'id' => 'PS18'],
			['latLng' => [32.122411063132404, 35.33430152162333], 'name' => 'Nablus', 'id' => 'PS17'],
			['latLng' => [31.908285835892755, 35.49653126286336], 'name' => 'Jericho (Ariha)', 'id' => 'PS19'],
			['latLng' => [31.635732530132884, 35.30119617035112], 'name' => 'Bethlehem', 'id' => 'PS20'],
			['latLng' => [32.4967519, 35.4973022], 'name' => 'Baysan', 'id' => 'PS6'],
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
