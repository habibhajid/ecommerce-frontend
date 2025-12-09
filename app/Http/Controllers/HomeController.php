<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $backendUrl = env('BACKEND_URL');
        $settings = [];
        $slides = [];

        try {
            $backendUrl = rtrim($backendUrl, '/');
            $response = Http::get("{$backendUrl}/api/settings");
            if ($response->successful()) {
                $settings = $response->json();
            }
        } catch (\Exception $e) {
            // Handle error or use defaults
        }

        // Reconstruct slides logic from original HomeController
        $slides = [
            [
                'id' => 1,
                'image_url' => $settings['lp_slider_img1'] ?? null,
                'title' => $settings['landing_page_headline'] ?? 'Selamat Datang',
                'description' => $settings['landing_page_tagline'] ?? 'Temukan produk terbaik kami.'
            ],
            [
                'id' => 2,
                'image_url' => $settings['lp_slider_img2'] ?? null,
                'title' => $settings['landing_page_headline'] ?? 'Kualitas Terbaik',
                'description' => $settings['landing_page_tagline'] ?? 'Kami hanya menyajikan yang terbaik.'
            ],
            [
                'id' => 3,
                'image_url' => $settings['lp_slider_img3'] ?? null,
                'title' => $settings['landing_page_headline'] ?? 'Rasa Autentik',
                'description' => $settings['landing_page_tagline'] ?? 'Citarasa yang tidak terlupakan.'
            ]
        ];

        $slides = array_filter($slides, function($slide) {
            return !empty($slide['image_url']);
        });

        if (empty($slides)) {
            $slides = [
                [
                    'id' => 1,
                    'image_url' => null,
                    'title' => 'Selamat Datang',
                    'description' => 'Silakan upload gambar di admin.'
                ]
            ];
        }

        return view('home', compact('settings', 'slides'));
    }

}
