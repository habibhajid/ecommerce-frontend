<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    public function login()
    {
        $backendUrl = env('BACKEND_URL', 'http://localhost:8000');
        $backendUrl = rtrim($backendUrl, '/');
        
        return view('admin_login', compact('backendUrl'));
    }

    public function dashboard()
    {
        $backendUrl = env('BACKEND_URL', 'http://localhost:8000');
        $backendUrl = rtrim($backendUrl, '/');
        
        $products = [];
        $settings = [];

        try {
            // Fetch Products
            $response = Http::get("$backendUrl/api/products");
            if ($response->successful()) {
                $products = $response->json();
            }

            // Fetch Settings
            $response = Http::get("$backendUrl/api/settings");
            if ($response->successful()) {
                $settings = $response->json();
            }
        } catch (\Exception $e) {
            // Handle error (log or just ignore to show empty dashboard)
        }

        // Convert products array to object for Blade syntax consistency ($product->name)
        $products = json_decode(json_encode($products));

        return view('admin_dashboard', compact('products', 'settings', 'backendUrl'));
    }
}
