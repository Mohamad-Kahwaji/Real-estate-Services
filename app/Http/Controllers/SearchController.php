<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Service;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $term = '%' . $q . '%';

        $services = Service::with('business')
            ->where(fn($query) => $query
                ->where('title', 'like', $term)
                ->orWhere('description', 'like', $term)
            )
            ->limit(5)
            ->get()
            ->map(fn($s) => [
                'type'     => 'Service',
                'icon'     => 'ri-tools-line',
                'color'    => '#696cff',
                'title'    => $s->title,
                'subtitle' => $s->business->name ?? '—',
                'badge'    => $s->status,
                'url'      => route('allserviesad') . '?search=' . urlencode($q),
            ]);

        $businesses = Business::with('city')
            ->where(fn($query) => $query
                ->where('name', 'like', $term)
                ->orWhere('details', 'like', $term)
            )
            ->limit(5)
            ->get()
            ->map(fn($b) => [
                'type'     => 'Business',
                'icon'     => 'ri-building-2-line',
                'color'    => '#ff9f43',
                'title'    => $b->name,
                'subtitle' => $b->city->name_ar ?? $b->city->name_en ?? '—',
                'badge'    => $b->status,
                'url'      => route('business.index') . '?search=' . urlencode($q),
            ]);

        $users = User::where(fn($query) => $query
                ->where('name', 'like', $term)
                ->orWhere('phone', 'like', $term)
            )
            ->limit(5)
            ->get()
            ->map(fn($u) => [
                'type'     => 'User',
                'icon'     => 'ri-user-line',
                'color'    => '#28c76f',
                'title'    => $u->name ?? $u->phone,
                'subtitle' => $u->phone,
                'badge'    => null,
                'url'      => route('allindex.index'),
            ]);

        $results = $services->concat($businesses)->concat($users)->values();

        if ($results->isEmpty()) {
            $words = array_filter(explode(' ', $q));
            foreach ($words as $word) {
                $wTerm = '%' . $word . '%';
                $extra = Service::where('title', 'like', $wTerm)->limit(3)->get()
                    ->map(fn($s) => [
                        'type'     => 'Service',
                        'icon'     => 'ri-tools-line',
                        'color'    => '#696cff',
                        'title'    => $s->title,
                        'subtitle' => 'Similar result',
                        'badge'    => $s->status,
                        'url'      => route('allserviesad') . '?search=' . urlencode($word),
                    ]);
                $results = $results->concat($extra);
            }
        }

        return response()->json(['results' => $results->unique('title')->values()]);
    }

    public function filters()
    {
        return response()->json([
            'cities'        => City::orderBy('name_ar')->get(['id', 'name_ar', 'name_en']),
            'categories'    => Category::orderBy('name_ar')->get(['id', 'name_ar', 'name_en']),
            'subcategories' => Subcategory::orderBy('name_ar')->get(['id', 'name_ar', 'name_en', 'category_id']),
        ]);
    }
}
