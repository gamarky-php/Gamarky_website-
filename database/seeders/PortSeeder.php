<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Port;

class PortSeeder extends Seeder
{
    public function run(): void
    {
        $ports = [
            'EG' => [
                ['name'=>'Alexandria Port', 'code'=>'ALY', 'mode'=>'sea'],
                ['name'=>'Cairo International Airport', 'code'=>'CAI', 'mode'=>'air'],
            ],
            'SA' => [
                ['name'=>'Jeddah Islamic Port', 'code'=>'JED', 'mode'=>'sea'],
                ['name'=>'Riyadh King Khalid Airport', 'code'=>'RUH', 'mode'=>'air'],
            ],
            'AE' => [
                ['name'=>'Jebel Ali Port', 'code'=>'JEA', 'mode'=>'sea'],
                ['name'=>'Dubai Intl Airport', 'code'=>'DXB', 'mode'=>'air'],
            ],
            'US' => [
                ['name'=>'Port of Los Angeles', 'code'=>'LAXP','mode'=>'sea'],
                ['name'=>'JFK Airport', 'code'=>'JFK','mode'=>'air'],
            ],
            'CN' => [
                ['name'=>'Port of Shanghai', 'code'=>'CNSHA','mode'=>'sea'],
                ['name'=>'Beijing Capital Airport', 'code'=>'PEK','mode'=>'air'],
            ],
        ];

        foreach ($ports as $iso2 => $list) {
            $country = Country::where('iso2',$iso2)->first();
            if (!$country) continue;
            foreach ($list as $p) {
                Port::updateOrCreate(
                    ['country_id'=>$country->id,'name'=>$p['name'],'mode'=>$p['mode']],
                    $p
                );
            }
        }
    }
}
