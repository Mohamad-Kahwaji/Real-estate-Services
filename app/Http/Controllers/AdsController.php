<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdsController extends Controller
{
    // Displays all active ads to browsing users.
    public function browse()
    {
        $ads = Ads::where('is_active', true)->latest()->get();
        return view('users.ads', compact('ads'));
    }

    // Lists all ads (active and inactive) for the admin management panel.
    public function index(Request $request)
    {
        $term = $request->search ? '%' . $request->search . '%' : null;

        $ads = Ads::when($term, fn($q) => $q->where('title', 'like', $term)
                                            ->orWhere('description', 'like', $term))
            ->when($request->status === 'active',   fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.ads.index', compact('ads'));
    }

    // Returns the admin form for creating a new ad.
    public function create()
    {
        return view('admin.ads.create');
    }

    // Validates, stores the uploaded image, and persists a new ad record.
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'link'        => 'nullable|url|max:255',
            'is_active'   => 'boolean',
        ]);

        $data['image']     = $request->file('image')->store('ads', 'public');
        $data['is_active'] = $request->boolean('is_active', true);

        Ads::create($data);

        return redirect()->route('ads.index')
            ->with('success', __('app.ad_created'));
    }

    // Returns the admin edit form pre-populated with the specified ad's data.
    public function edit(int $id)
    {
        $ad = Ads::findOrFail($id);
        return view('admin.ads.edit', compact('ad'));
    }

    // Validates and updates an ad's details, replacing the image file if a new one is uploaded.
    public function update(Request $request, int $id)
    {
        $ad   = Ads::findOrFail($id);
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'link'        => 'nullable|url|max:255',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($ad->image) Storage::disk('public')->delete($ad->image);
            $data['image'] = $request->file('image')->store('ads', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', $ad->is_active);

        $ad->update($data);

        return redirect()->route('ads.index')
            ->with('success', __('app.ad_updated'));
    }

    // Deletes an ad and removes its image from public storage.
    public function destroy(int $id)
    {
        $ad = Ads::findOrFail($id);
        if ($ad->image) Storage::disk('public')->delete($ad->image);
        $ad->delete();

        return redirect()->route('ads.index')
            ->with('success', __('app.ad_deleted'));
    }

    // Toggles an ad's active/inactive state.
    public function toggle(int $id)
    {
        $ad = Ads::findOrFail($id);
        $ad->update(['is_active' => !$ad->is_active]);

        return redirect()->route('ads.index')
            ->with('success', __('app.ad_toggled'));
    }
}
