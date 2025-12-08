<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Zoeliez Ilux</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Navbar (Optional, included as requested) -->
    <!-- Note: Usually login pages don't have full navbars, but user asked to include related components -->
    <!-- We can include a simplified header or just the main navbar if desired. -->
    <!-- For now, I'll include the main navbar but it might look busy. Let's stick to the React design which was standalone, 
         but since user said "jangan lupa ada nvabar footer", I will include them. -->
    <x-navbar />

    <div class="flex-grow flex items-center justify-center px-4 py-12">
        <!-- Kartu Form Login -->
        <div class="w-full max-w-sm p-8 space-y-6 bg-white rounded-xl shadow-lg" data-aos="fade-up">
            
            <!-- Header Form -->
            <div class="text-center">
                <h1 class="text-3xl font-serif font-bold text-gray-800">
                    Zoeliez Ilux
                </h1>
                <p class="mt-2 text-sm text-gray-600">Admin Panel</p>
            </div>

            <!-- Form -->
            <form onsubmit="handleLogin(event)" class="space-y-6">
                
                <!-- Input Nomor Telepon -->
                <div>
                    <label htmlFor="phone" class="block text-sm font-medium text-gray-700 mb-1">
                        Nomor Telepon
                    </label>
                    <input
                        id="phone"
                        name="phone"
                        type="text"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500"
                        placeholder="081234567890"
                    />
                </div>

                <!-- Input Password -->
                <div>
                    <label htmlFor="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500"
                        placeholder="••••••••"
                    />
                </div>

                <!-- Pesan Error -->
                <p id="error-message" class="text-xs text-center text-red-600 bg-red-100 p-2 rounded-md hidden"></p>

                <!-- Tombol Submit -->
                <div>
                    <button
                        type="submit"
                        id="login-btn"
                        class="w-full py-2 px-4 font-semibold text-white bg-orange-500 rounded-md hover:bg-orange-600 disabled:bg-orange-300 disabled:cursor-not-allowed transition-colors"
                    >
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-footer />

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        function handleLogin(event) {
            event.preventDefault();
            
            const btn = document.getElementById('login-btn');
            const errorMsg = document.getElementById('error-message');
            const phone = document.getElementById('phone').value;
            const password = document.getElementById('password').value;

            btn.disabled = true;
            btn.innerText = 'Memproses...';
            errorMsg.classList.add('hidden');
            errorMsg.innerText = '';

            fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ phone, password })
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200 && body.token) {
                    localStorage.setItem('authToken', body.token);
                    // Redirect to Admin Dashboard
                    // Since the dashboard route is /api/admin (returning a view), we redirect there.
                    window.location.href = '/api/admin'; 
                } else {
                    throw new Error(body.message || 'Login gagal');
                }
            })
            .catch(error => {
                console.error('Login error:', error);
                errorMsg.innerText = error.message || 'Terjadi kesalahan saat login.';
                errorMsg.classList.remove('hidden');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerText = 'Login';
            });
        }
    </script>
</body>
</html>
