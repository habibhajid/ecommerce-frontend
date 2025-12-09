<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings['app_name'] ?? 'Chatalog' }} - Admin Dashboard</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">

    <x-navbar />

    <!-- Protected Route Wrapper -->
    <x-protected-route>
        
        <x-product-modal :backendUrl="$backendUrl" />

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:space-x-8">

                <!-- SIDEBAR -->
                <aside class="w-full md:w-60 flex-shrink-0 mb-6 md:mb-0">
                    <nav class="flex flex-col space-y-2 bg-white p-4 rounded-lg shadow-md sticky top-24">
                        <button onclick="switchView('products')" id="nav-products" class="flex items-center space-x-3 w-full px-4 py-3 rounded-md font-medium text-sm transition-all duration-150 bg-orange-500 text-white shadow-md">
                            <i class="fas fa-cube h-5 w-5 flex items-center justify-center"></i>
                            <span>Manajemen Produk</span>
                        </button>
                        <button onclick="switchView('content')" id="nav-content" class="flex items-center space-x-3 w-full px-4 py-3 rounded-md font-medium text-sm transition-all duration-150 text-gray-600 hover:bg-gray-200 hover:text-gray-900">
                            <i class="fas fa-cog h-5 w-5 flex items-center justify-center"></i>
                            <span>Pengaturan Konten</span>
                        </button>


                    </nav>
                </aside>

                <!-- MAIN CONTENT -->
                <main class="flex-1 min-w-0">

                    <!-- VIEW: PRODUCTS -->
                    <section id="view-products" class="bg-white shadow-md rounded-lg overflow-hidden">
                        <div class="flex justify-between items-center p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-700">Manajemen Produk</h2>
                            <button onclick="openProductModal()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md shadow-sm transition-colors duration-200">
                                + Tambah Produk
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($products as $product)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        @php
                                                            $imgSrc = 'https://via.placeholder.com/40';
                                                            if ($product->image) {
                                                                if (Str::startsWith($product->image, ['http', 'https'])) {
                                                                    $imgSrc = $product->image;
                                                                } else {
                                                                    $imgSrc = $backendUrl . '/storage/' . $product->image;
                                                                }
                                                            }
                                                        @endphp
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $imgSrc }}" alt="{{ $product->name }}">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button onclick='openProductModal(@json($product))' class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
                                                <button onclick="deleteProduct({{ $product->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                                Belum ada produk. Silakan "Tambah Produk".
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- VIEW: CONTENT SETTINGS -->
                    <div id="view-content" class="space-y-8 hidden">
                        <form id="settings-form" onsubmit="handleSaveSettings(event)">
                            <!-- Card 1: Pengaturan Halaman Utama -->
                            <section class="bg-white p-6 rounded-lg shadow-md mb-8">
                                <h2 class="text-xl font-semibold text-gray-700 mb-6 border-b pb-3">Pengaturan Halaman Utama</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Kolom Kiri: Form Teks -->
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Headline</label>
                                            <input type="text" name="landing_page_headline" value="{{ $settings['landing_page_headline'] ?? '' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border focus:border-orange-500 focus:ring-orange-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Tagline</label>
                                            <textarea name="landing_page_tagline" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border focus:border-orange-500 focus:ring-orange-500">{{ $settings['landing_page_tagline'] ?? '' }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Deskripsi di Halaman Produk</label>
                                            <textarea name="landing_page_description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border focus:border-orange-500 focus:ring-orange-500">{{ $settings['landing_page_description'] ?? '' }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                                            <input type="text" name="seller_whatsapp" value="{{ $settings['seller_whatsapp'] ?? '' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border focus:border-orange-500 focus:ring-orange-500">
                                        </div>
                                    </div>
                                    <!-- Kolom Kanan: Upload Gambar SLIDER -->
                                    <div class="space-y-6">
                                        <h3 class="text-lg font-semibold text-gray-600 border-b pb-2">Pengaturan Slider (3 Slot Gambar)</h3>
                                        
                                        @foreach(['lp_slider_img1' => 'Gambar Slider 1', 'lp_slider_img2' => 'Gambar Slider 2', 'lp_slider_img3' => 'Gambar Slider 3'] as $key => $label)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                                                <div class="mt-1 p-4 border-2 border-dashed border-gray-300 rounded-md text-center">
                                                    @php
                                                        $previewUrl = 'https://via.placeholder.com/400x200?text=Pilih+Gambar';
                                                        if (!empty($settings[$key])) {
                                                            $previewUrl = $backendUrl . '/storage/' . $settings[$key];
                                                        }
                                                    @endphp
                                                    <img src="{{ $previewUrl }}" id="preview-{{ $key }}" class="w-full h-48 object-cover rounded-md mb-4">
                                                    <input type="file" name="{{ $key }}" id="upload-{{ $key }}" class="hidden" accept="image/*" onchange="previewImage(event, '{{ $key }}')">
                                                    <label for="upload-{{ $key }}" class="cursor-pointer bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                                                        Ganti Gambar
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <hr class="my-8" />
                                <h3 class="text-lg font-semibold text-gray-600 mb-4">Seksi "Kenapa Memilih Kami?"</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium">Judul Seksi</label>
                                        <input type="text" name="lp_section_title" value="{{ $settings['lp_section_title'] ?? '' }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                    </div>
                                    @foreach([1, 2, 3] as $i)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-3 border rounded-md bg-gray-50">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600">Judul Poin {{ $i }}</label>
                                                <input type="text" name="lp_item{{ $i }}_title" value="{{ $settings['lp_item'.$i.'_title'] ?? '' }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600">Teks Poin {{ $i }}</label>
                                                <input type="text" name="lp_item{{ $i }}_text" value="{{ $settings['lp_item'.$i.'_text'] ?? '' }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <hr class="my-8" />
                                <h3 class="text-lg font-semibold text-gray-600 mb-4">Seksi "Call to Action"</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium">Judul CTA</label>
                                        <input type="text" name="lp_cta_title" value="{{ $settings['lp_cta_title'] ?? '' }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">Teks CTA</label>
                                        <input type="text" name="lp_cta_text" value="{{ $settings['lp_cta_text'] ?? '' }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                    </div>
                                </div>
                            </section>

                            <!-- Card 2: Pengaturan Halaman Lain -->
                            <section class="bg-white p-6 rounded-lg shadow-md mb-8">
                                <h2 class="text-xl font-semibold text-gray-700 mb-6 border-b pb-3">Pengaturan Halaman Lain</h2>
                                <div class="space-y-8">
                                    <!-- Halaman "Tentang Kami" -->
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-600 border-b pb-2 mb-4">Halaman "Tentang Kami"</h3>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium">Judul</label>
                                                <input type="text" name="about_title" value="{{ $settings['about_title'] ?? '' }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium">Konten Paragraf</label>
                                                <textarea name="about_content" rows="5" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border">{{ $settings['about_content'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Halaman "Kontak" -->
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-600 border-b pb-2 mb-4">Halaman "Kontak"</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium">Judul</label>
                                                <input type="text" name="contact_title" value="{{ $settings['contact_title'] ?? '' }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium">Tagline</label>
                                                <input type="text" name="contact_tagline" value="{{ $settings['contact_tagline'] ?? '' }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium">Alamat</label>
                                                <input type="text" name="contact_address" value="{{ $settings['contact_address'] ?? '' }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium">Email</label>
                                                <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium">Telepon</label>
                                                <input type="tel" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Tombol Simpan Global (Sticky) -->
                            <div class="text-right p-4 sticky bottom-4">
                                <button type="submit" id="save-settings-btn" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-md shadow-lg transition-all duration-200">
                                    Simpan Semua Pengaturan Konten
                                </button>
                            </div>
                        </form>
                    </div>

                </main>
            </div>
        </div>
    </x-protected-route>

    <x-footer />

    <!-- Scripts -->
    <script>

        function switchView(viewName) {
            const productsView = document.getElementById('view-products');
            const contentView = document.getElementById('view-content');
            const navProducts = document.getElementById('nav-products');
            const navContent = document.getElementById('nav-content');

            if (viewName === 'products') {
                productsView.classList.remove('hidden');
                contentView.classList.add('hidden');
                
                navProducts.classList.add('bg-orange-500', 'text-white', 'shadow-md');
                navProducts.classList.remove('text-gray-600', 'hover:bg-gray-200');
                
                navContent.classList.remove('bg-orange-500', 'text-white', 'shadow-md');
                navContent.classList.add('text-gray-600', 'hover:bg-gray-200');
            } else {
                productsView.classList.add('hidden');
                contentView.classList.remove('hidden');

                navContent.classList.add('bg-orange-500', 'text-white', 'shadow-md');
                navContent.classList.remove('text-gray-600', 'hover:bg-gray-200');

                navProducts.classList.remove('bg-orange-500', 'text-white', 'shadow-md');
                navProducts.classList.add('text-gray-600', 'hover:bg-gray-200');
            }
        }

        function deleteProduct(id) {
            if (confirm('Anda yakin ingin menghapus produk ini secara permanen?')) {
                fetch(`{{ $backendUrl }}/api/admin/products/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message || 'Produk berhasil dihapus');
                    window.location.reload();
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function previewImage(event, key) {
            const file = event.target.files[0];
            if (file) {
                const preview = document.getElementById(`preview-${key}`);
                preview.src = URL.createObjectURL(file);
            }
        }

        function handleSaveSettings(event) {
            event.preventDefault();
            const btn = document.getElementById('save-settings-btn');
            const originalText = btn.innerText;
            btn.innerText = 'Menyimpan...';
            btn.disabled = true;

            const form = event.target;
            const formData = new FormData(form);
            formData.append('_method', 'PUT'); // For Laravel PUT request

            // Separate text settings and image uploads if needed, or send all to one endpoint
            // The React code sent text settings to updateSettings and images to uploadLandingImage separately.
            // Here we can try to send everything to /api/settings if the controller supports it.
            // Based on api.php: Route::put('/settings', [SettingController::class, 'update'])
            // And Route::post('/settings/upload-image', [SettingController::class, 'uploadImage'])
            
            // Let's assume we need to handle them separately or the SettingController::update handles both?
            // Let's check SettingController if possible, but for now let's try sending to /api/settings
            // If images are present, we might need to upload them one by one or modify the controller.
            
            // Strategy: Send text settings first, then upload images.
            
            // 1. Update Text Settings
            fetch('{{ $backendUrl }}/api/settings', {
                method: 'POST', // Using POST with _method: PUT in body
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // 2. Upload Images if any
                const imagePromises = [];
                const imageInputs = form.querySelectorAll('input[type="file"]');
                
                imageInputs.forEach(input => {
                    if (input.files.length > 0) {
                        const imgData = new FormData();
                        imgData.append('image', input.files[0]);
                        imgData.append('image_key', input.name);
                        
                        imagePromises.push(
                            fetch('{{ $backendUrl }}/api/admin/settings/upload-image', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                    'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                                },
                                body: imgData
                            })
                        );
                    }
                });

                return Promise.all(imagePromises);
            })
            .then(() => {
                alert('Pengaturan berhasil disimpan!');
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menyimpan pengaturan.');
            })
            .finally(() => {
                btn.innerText = originalText;
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>
