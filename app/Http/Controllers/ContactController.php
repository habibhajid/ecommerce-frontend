<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    public function index()
    {
        $backendUrl = env('BACKEND_URL');
        $settings = [];
        try {
            $backendUrl = rtrim($backendUrl, '/');
            $response = Http::get("{$backendUrl}/api/settings");
            if ($response->successful()) {
                $settings = $response->json();
            }
        } catch (\Exception $e) {}
        
        return view('contact', compact('settings'));
    }
}
