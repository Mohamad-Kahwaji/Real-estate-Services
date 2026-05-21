<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all()->keyBy('phone');

        $u1 = $users['0911111111'] ?? null; // Ahmad Khalil
        $u2 = $users['0922222222'] ?? null; // Sara Hamdan
        $u3 = $users['0933333333'] ?? null; // Omar Farouk
        $u4 = $users['0944444444'] ?? null; // Layla Nasser
        $u5 = $users['0955555555'] ?? null; // Khaled Mansour
        $u6 = $users['0966666666'] ?? null; // Rania Zayed

        // [service_title => [[user, rating, comment], ...]]
        $data = [
            'Modern 3-Bedroom Apartment — Mezzeh' => [
                [$u1, 5, 'Excellent apartment, very spacious and well-maintained. The balcony view is amazing.'],
                [$u2, 4, 'Great location near embassies. The kitchen is modern and parking is convenient.'],
                [$u3, 5, 'Highly recommend. The agent was professional and the apartment exceeded expectations.'],
                [$u4, 4, 'Very clean and well-furnished. Slightly above budget but worth it.'],
            ],

            'Furnished Studio — Kafr Sousa' => [
                [$u2, 4, 'Cozy studio with everything you need. Internet speed is good.'],
                [$u5, 3, 'Decent place but the street can be noisy at night. Otherwise fine.'],
                [$u6, 4, 'Good value for the price. All appliances work perfectly.'],
            ],

            'Full Property Brokerage Service — Damascus' => [
                [$u1, 5, 'Saved us a lot of time and negotiated a great price. Very professional team.'],
                [$u3, 5, 'Excellent service from start to finish. They handled all the paperwork.'],
                [$u4, 4, 'Very knowledgeable about the Damascus market. Minor delays but resolved quickly.'],
                [$u6, 5, 'Found us exactly what we were looking for within two weeks. Highly recommend.'],
            ],

            'Luxury Villa with Garden — Aleppo' => [
                [$u2, 5, 'Absolutely stunning villa. The garden and pool are spectacular.'],
                [$u3, 5, 'Top-tier property in a secure compound. Worth every penny.'],
                [$u5, 5, 'Dream home. The construction quality is exceptional.'],
                [$u6, 4, 'Beautiful property. The only downside is distance from the city centre.'],
            ],

            'Office Space in Business District — Aleppo' => [
                [$u1, 4, 'Modern and professional space. The meeting rooms are well equipped.'],
                [$u4, 5, 'Perfect for our corporate needs. Fast fibre internet and great location.'],
                [$u5, 4, 'Good value compared to similar spaces. Reception area is impressive.'],
                [$u6, 3, 'Decent office but parking can be difficult during peak hours.'],
            ],

            'Residential Plot 600 m² — North Aleppo' => [
                [$u2, 4, 'Good size plot with clear title. Utilities are available at the boundary.'],
                [$u3, 3, 'Reasonable price for the area but road access needs improvement.'],
                [$u5, 4, 'Solid investment. The area is developing fast.'],
            ],

            'Sea-View Apartment — Latakia Corniche' => [
                [$u1, 5, 'Waking up to sea views every morning is priceless. Wonderful apartment.'],
                [$u3, 5, 'Perfect location on the corniche. Walking to the beach is a huge plus.'],
                [$u4, 5, 'The 7th floor view is breathtaking. Very well maintained building.'],
                [$u6, 4, 'Beautiful apartment. Slightly warm in summer but that\'s expected on the coast.'],
            ],

            'Coastal Chalet for Rent — Blue Beach' => [
                [$u2, 5, 'Best summer rental we have ever had. The rooftop terrace is amazing.'],
                [$u3, 4, 'Great chalet close to the sea. BBQ area is a nice touch for families.'],
                [$u5, 5, 'Spotlessly clean and fully equipped. Will definitely book again next summer.'],
            ],

            'Full Interior Design Package — Latakia' => [
                [$u1, 5, 'Transformed our apartment completely. The 3D visualisation was very accurate.'],
                [$u2, 4, 'Great design team. Finished on time and within budget.'],
                [$u4, 4, 'Very professional. Material quality is excellent. Highly recommend.'],
                [$u6, 4, 'Satisfied with the result. Communication throughout the project was clear.'],
            ],

            'Agricultural Land 5 Dunams — Tartus' => [
                [$u1, 4, 'Fertile land with good water supply. Paperwork was straightforward.'],
                [$u3, 4, 'Good agricultural land. The citrus trees are already productive.'],
                [$u5, 3, 'Fair price but road to the plot is rough. Investment potential is solid.'],
            ],

            'Ground-Floor Retail Shop — Tartus Souq' => [
                [$u2, 4, 'Excellent foot traffic. The glass shopfront attracts customers.'],
                [$u4, 5, 'Prime location in the souq. Our business grew 30% in the first month.'],
                [$u6, 3, 'Good shop but storage at the rear is smaller than expected.'],
            ],

            'Certified Property Valuation — Tartus Region' => [
                [$u1, 5, 'Report was thorough and accepted by our bank immediately.'],
                [$u3, 5, 'Very professional. Delivered within 4 business days as promised.'],
                [$u5, 4, 'Accurate valuation and official certification. Good service.'],
                [$u6, 5, 'Saved us weeks of hassle. The report is detailed and well-documented.'],
            ],

            '2-Bedroom Apartment — New Homs' => [
                [$u2, 4, 'Newly renovated and very clean. Central heating works great in winter.'],
                [$u3, 3, 'Nice apartment but communal garden is not well maintained.'],
                [$u4, 4, 'Good value for a family apartment. Double-glazed windows keep it quiet.'],
            ],

            'Professional Painting & Finishing — Homs' => [
                [$u1, 5, 'Flawless finish on our entire apartment. The colour consultation was very helpful.'],
                [$u3, 4, 'Excellent work. Premium paint quality is noticeable.'],
                [$u5, 5, 'Completed ahead of schedule. Highly professional team.'],
                [$u6, 4, 'Very satisfied. The exterior painting has lasted perfectly through rain season.'],
            ],

            'Industrial Warehouse 500 m² — Homs Industrial Zone' => [
                [$u2, 4, 'Perfect for our storage needs. Loading bay and 3-phase power are essential.'],
                [$u4, 4, 'Spacious and secure. 24-hour security gives peace of mind.'],
                [$u5, 3, 'Good warehouse but office area inside is minimal.'],
                [$u6, 5, 'Ideal industrial space. The 6m ceiling height accommodates our equipment.'],
            ],

            'Commercial Plot on Main Road — Homs' => [
                [$u1, 4, 'Great frontage on a busy road. Permits are all in order.'],
                [$u3, 4, 'Strategic location for commercial development. Fair asking price.'],
                [$u6, 3, 'Good plot but surrounding infrastructure still under development.'],
            ],
        ];

        foreach ($data as $serviceTitle => $reviews) {
            $service = Service::where('title', $serviceTitle)->first();
            if (! $service) continue;

            foreach ($reviews as [$user, $rating, $comment]) {
                if (! $user) continue;

                Review::updateOrCreate(
                    ['service_id' => $service->id, 'user_id' => $user->id],
                    ['rating' => $rating, 'comment' => $comment, 'order_id' => null]
                );
            }
        }
    }
}
