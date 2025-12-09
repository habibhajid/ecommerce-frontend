<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AboutController extends Controller
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
        } catch (\Exception $e) {
        }

        $slides = [
            [
                'id' => 1,
                'image_url' => $settings['lp_slider_img1'] ?? null,
                'title' => $settings['about_title'] ?? 'Tentang Zoeliez Ilux',
                'description' => 'Kisah di balik setiap gigitan nikmat'
            ],
            [
                'id' => 2,
                'image_url' => $settings['lp_slider_img2'] ?? null,
                'title' => $settings['about_title'] ?? 'Tentang Zoeliez Ilux',
                'description' => 'Kisah di balik setiap gigitan nikmat'
            ],
            [
                'id' => 3,
                'image_url' => $settings['lp_slider_img3'] ?? null,
                'title' => $settings['about_title'] ?? 'Tentang Zoeliez Ilux',
                'description' => 'Kisah di balik setiap gigitan nikmat'
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
                    'title' => 'Tentang Kami',
                    'description' => 'Kisah di balik setiap gigitan nikmat.'
                ]
            ];
        }

        // Dynamic Story Sections from Settings
        $storySections = [];
        // Ensure backend URL is clean
        $backendUrlRaw = env('BACKEND_URL'); 
        $backendUrlRaw = rtrim($backendUrlRaw, '/');

        for ($i = 1; $i <= 3; $i++) {
            if (!empty($settings["about_story{$i}_title"])) {
                $imagePath = $settings["about_story{$i}_image"] ?? '';
                // If it's a relative path (not http), prepend backend URL
                if (!empty($imagePath) && !str_starts_with($imagePath, 'http')) {
                    $imagePath = "{$backendUrlRaw}/{$imagePath}";
                }

                $storySections[] = [
                    'id' => $i,
                    'title' => $settings["about_story{$i}_title"] ?? '',
                    'text' => $settings["about_story{$i}_text"] ?? '',
                    'image' => $imagePath,
                    'imageAlt' => $settings["about_story{$i}_title"] ?? '', 
                    'layout' => $settings["about_story{$i}_layout"] ?? ($i % 2 == 0 ? 'image-right' : 'image-left')
                ];
            }
        }

        // Fallback if settings are empty (maintain original hardcoded as default if needed, or just empty)
        if (empty($storySections)) {
            $storySections = [
                [
                    'id' => 1,
                    'title' => "Awal Mula Zoeliez Ilux",
                    'text' => "Di awal perjalanannya, Zoeliez Ilux dikenal sebagai surganya kue basah tradisional maupun modern. (Data Default - Silakan seed database)",
                    'image' => "about_jajanan_pasar.jpg",
                    'imageAlt' => "Berbagai kue basah tradisional dan modern Zoeliez Ilux",
                    'layout' => "image-left"
                ]
            ];
        }

        return view('about', compact('settings', 'slides', 'storySections'));
    }
}
