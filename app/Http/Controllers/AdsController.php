<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdsController extends Controller
{
    // ── Web (user — browse active ads) ────────────────────────────

    public function browse()
    {
        $ads = Ads::where('is_active', true)->latest()->get();
        return view('users.ads', compact('ads'));
    }

    // ── Web (admin panel) ──────────────────────────────────────────

    public function index()
    {
        $ads = Ads::latest()->get();
        return view('admin.ads.index', compact('ads'));
    }

    public function create()
    {
        return view('admin.ads.create');
    }

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

    public function edit(int $id)
    {
        $ad = Ads::findOrFail($id);
        return view('admin.ads.edit', compact('ad'));
    }

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

    public function destroy(int $id)
    {
        $ad = Ads::findOrFail($id);
        if ($ad->image) Storage::disk('public')->delete($ad->image);
        $ad->delete();

        return redirect()->route('ads.index')
            ->with('success', __('app.ad_deleted'));
    }

    public function toggle(int $id)
    {
        $ad = Ads::findOrFail($id);
        $ad->update(['is_active' => !$ad->is_active]);

        return redirect()->route('ads.index')
            ->with('success', __('app.ad_toggled'));
    }
}
