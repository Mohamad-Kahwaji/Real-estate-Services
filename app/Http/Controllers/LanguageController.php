<?php

namespace App\Http\Controllers;

class LanguageController extends Controller
{
    // Stores the selected locale (en or ar) in the session and redirects back.
    public function switch(string $locale)
    {
        if (in_array($locale, ['en', 'ar'])) {
            session(['locale' => $locale]);
        }
        return back();
    }
}
