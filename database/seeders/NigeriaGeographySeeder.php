<?php

namespace Database\Seeders;

use App\Models\Local;
use App\Models\State;
use Illuminate\Database\Seeder;

class NigeriaGeographySeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            ['Abia', 'NG-AB'], ['Adamawa', 'NG-AD'], ['Akwa Ibom', 'NG-AK'],
            ['Anambra', 'NG-AN'], ['Bauchi', 'NG-BA'], ['Bayelsa', 'NG-BY'],
            ['Benue', 'NG-BE'], ['Borno', 'NG-BO'], ['Cross River', 'NG-CR'],
            ['Delta', 'NG-DE'], ['Ebonyi', 'NG-EB'], ['Edo', 'NG-ED'],
            ['Ekiti', 'NG-EK'], ['Enugu', 'NG-EN'], ['FCT', 'NG-FC'],
            ['Gombe', 'NG-GO'], ['Imo', 'NG-IM'], ['Jigawa', 'NG-JI'],
            ['Kaduna', 'NG-KD'], ['Kano', 'NG-KN'], ['Katsina', 'NG-KT'],
            ['Kebbi', 'NG-KE'], ['Kogi', 'NG-KO'], ['Kwara', 'NG-KW'],
            ['Lagos', 'NG-LA'], ['Nasarawa', 'NG-NA'], ['Niger', 'NG-NI'],
            ['Ogun', 'NG-OG'], ['Ondo', 'NG-ON'], ['Osun', 'NG-OS'],
            ['Oyo', 'NG-OY'], ['Plateau', 'NG-PL'], ['Rivers', 'NG-RI'],
            ['Sokoto', 'NG-SO'], ['Taraba', 'NG-TA'], ['Yobe', 'NG-YO'],
            ['Zamfara', 'NG-ZA'],
        ];

        foreach ($states as [$name, $code]) {
            $state = State::firstOrCreate(['name' => $name], ['code' => $code]);
            // Seed Nasarawa LGAs (FUTH-Lafia is in Nasarawa State)
            if ($name === 'Nasarawa') {
                $lgas = ['Lafia', 'Akwanga', 'Wamba', 'Nasarawa Eggon', 'Keffi',
                    'Kokona', 'Obi', 'Doma', 'Keana', 'Awe', 'Nasarawa',
                    'Toto', 'Karü', 'Wamba', 'Akpasu'];
                foreach ($lgas as $lga) {
                    Local::firstOrCreate([
                        'state_id' => $state->state_id,
                        'local_name' => $lga,
                    ]);
                }
            }
        }
    }
}
