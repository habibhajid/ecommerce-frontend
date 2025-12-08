<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil Saya - Admin Panel</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <x-navbar />

    <x-protected-route>
        <div class="container mx-auto px-6 py-8 flex-grow">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Profil Saya</h1>
            
            <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md" data-aos="fade-up">
                <form onsubmit="handleUpdateProfile(event)" class="space-y-6">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" id="name" name="name" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border focus:border-orange-500 focus:ring-orange-500">
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" id="phone" name="phone" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border focus:border-orange-500 focus:ring-orange-500">
                    </div>

                    <hr class="my-6 border-gray-200" />
                    
                    <p class="text-sm text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>

                    <!-- Password Baru -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <input type="password" id="password" name="password" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border focus:border-orange-500 focus:ring-orange-500">
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border focus:border-orange-500 focus:ring-orange-500">
                    </div>

                    <!-- Pesan Status -->
                    <div id="status-message" class="hidden p-3 rounded-md text-sm text-center"></div>

                    <!-- Tombol Simpan -->
                    <div class="text-right">
                        <button type="submit" id="save-btn" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-md disabled:bg-orange-300 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-protected-route>

    <x-footer />

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        // Fetch Profile Data on Load
        document.addEventListener('DOMContentLoaded', () => {
            fetchProfile();
        });

        function fetchProfile() {
            const token = localStorage.getItem('authToken');
            if (!token) return; // Protected route will handle redirect

            fetch('/api/admin/user', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name || '';
                document.getElementById('phone').value = data.phone || '';
            })
            .catch(error => {
                console.error('Error fetching profile:', error);
                showStatus('Gagal memuat profil.', 'error');
            });
        }

        function handleUpdateProfile(event) {
            event.preventDefault();
            
            const btn = document.getElementById('save-btn');
            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;

            if (password && password !== passwordConfirmation) {
                showStatus('Konfirmasi password tidak cocok.', 'error');
                return;
            }

            btn.disabled = true;
            btn.innerText = 'Menyimpan...';
            hideStatus();

            const payload = { name, phone };
            if (password) {
                payload.password = password;
                payload.password_confirmation = passwordConfirmation;
            }

            const token = localStorage.getItem('authToken');

            fetch('/api/admin/user/profile', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Gagal menyimpan profil.');
                }
                return data;
            })
            .then(data => {
                showStatus('Profil berhasil diperbarui!', 'success');
                document.getElementById('password').value = '';
                document.getElementById('password_confirmation').value = '';
                // Update displayed name/phone if needed
            })
            .catch(error => {
                console.error('Update error:', error);
                showStatus(error.message, 'error');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerText = 'Simpan Perubahan';
            });
        }

        function showStatus(message, type) {
            const el = document.getElementById('status-message');
            el.innerText = message;
            el.classList.remove('hidden', 'bg-red-100', 'text-red-600', 'bg-green-100', 'text-green-600');
            
            if (type === 'error') {
                el.classList.add('bg-red-100', 'text-red-600');
            } else {
                el.classList.add('bg-green-100', 'text-green-600');
            }
            el.classList.remove('hidden');
        }

        function hideStatus() {
            document.getElementById('status-message').classList.add('hidden');
        }
    </script>
</body>
</html>
