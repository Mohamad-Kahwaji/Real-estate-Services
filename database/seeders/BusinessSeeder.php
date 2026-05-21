<?php

namespace Database\Seeders;

use App\Models\Activetype;
use App\Models\Business;
use App\Models\City;
use App\Models\User;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        $typeRealEstate = Activetype::where('name', 'Real Estate')->first();

        $damascus  = City::where('name_en', 'Damascus')->first();
        $aleppo    = City::where('name_en', 'Aleppo')->first();
        $latakia   = City::where('name_en', 'Latakia')->first();
        $tartus    = City::where('name_en', 'Tartus')->first();
        $homs      = City::where('name_en', 'Homs')->first();

        $ahmad   = User::where('phone', '0911111111')->first();
        $sara    = User::where('phone', '0922222222')->first();
        $omar    = User::where('phone', '0933333333')->first();
        $layla   = User::where('phone', '0944444444')->first();
        $khaled  = User::where('phone', '0955555555')->first();

        $businesses = [
            [
                'user_id'        => $ahmad->id,
                'activetype_id'  => $typeRealEstate->id,
                'city_id'        => $damascus->id,
                'job_name_ar'    => 'مكتب النور للعقارات',
                'job_name_en'    => 'Al Nour Real Estate Office',
                'license_number' => 10234,
                'activites'      => 'Buying, selling and renting residential & commercial properties in Damascus',
                'details'        => 'A trusted real estate office with over 10 years of experience in the Damascus market, offering full brokerage and consulting services.',
                'status'         => 'approved',
                'latitude'       => 33.5138,
                'longitude'      => 36.2765,
            ],
            [
                'user_id'        => $sara->id,
                'activetype_id'  => $typeRealEstate->id,
                'city_id'        => $aleppo->id,
                'job_name_ar'    => 'وكالة حلب العقارية',
                'job_name_en'    => 'Aleppo Real Estate Agency',
                'license_number' => 20451,
                'activites'      => 'Property listings, tours, and transaction support across Aleppo',
                'details'        => 'Specialised real estate agent covering all major districts in Aleppo, with an extensive portfolio of apartments, villas, and commercial spaces.',
                'status'         => 'approved',
                'latitude'       => 36.2021,
                'longitude'      => 37.1343,
            ],
            [
                'user_id'        => $omar->id,
                'activetype_id'  => $typeRealEstate->id,
                'city_id'        => $latakia->id,
                'job_name_ar'    => 'المنازل الساحلية للتطوير',
                'job_name_en'    => 'Coastal Homes Development',
                'license_number' => 30789,
                'activites'      => 'Residential and resort development on the Syrian coast',
                'details'        => 'A leading property developer on the Syrian coastline, building modern residential complexes and holiday retreats in Latakia.',
                'status'         => 'approved',
                'latitude'       => 35.5317,
                'longitude'      => 35.7916,
            ],
            [
                'user_id'        => $layla->id,
                'activetype_id'  => $typeRealEstate->id,
                'city_id'        => $tartus->id,
                'job_name_ar'    => 'استثمارات طرطوس العقارية',
                'job_name_en'    => 'Tartus Property Investments',
                'license_number' => 41003,
                'activites'      => 'Real estate investment, land acquisition, and portfolio management in Tartus',
                'details'        => 'Active real estate investor managing a diverse portfolio of residential and commercial properties in Tartus and surrounding areas.',
                'status'         => 'approved',
                'latitude'       => 34.8958,
                'longitude'      => 35.8858,
            ],
            [
                'user_id'        => $khaled->id,
                'activetype_id'  => $typeRealEstate->id,
                'city_id'        => $homs->id,
                'job_name_ar'    => 'شركة عقارات حمص للإدارة',
                'job_name_en'    => 'Homs Estates Management Co.',
                'license_number' => 50672,
                'activites'      => 'Full property management, tenant placement, and maintenance coordination in Homs',
                'details'        => 'Professional property management company in Homs handling residential and commercial buildings, including rent collection and maintenance services.',
                'status'         => 'approved',
                'latitude'       => 34.7324,
                'longitude'      => 36.7137,
            ],
        ];

        foreach ($businesses as $data) {
            Business::updateOrCreate(
                ['license_number' => $data['license_number']],
                $data
            );
        }
    }
}
