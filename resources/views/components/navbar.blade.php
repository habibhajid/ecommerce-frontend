<nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-6 py-4 flex items-center justify-between relative">

        <!-- Bagian Kiri Navbar (Link Navigasi) -->
        <div class="flex-1 flex items-center space-x-8 pl-8">
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ url('/catalog') }}" class="text-gray-600 hover:text-orange-500">Produk</a>

                @auth
                    <a href="{{ url('/api/admin') }}" class="text-gray-600 hover:text-orange-500">Admin</a>
                    <a href="{{ url('/contact') }}" class="text-gray-600 hover:text-orange-500">Kontak</a>
                    <a href="{{ url('/about') }}" class="text-gray-600 hover:text-orange-500">Tentang</a>
                @endauth

                @guest
                    <a href="{{ url('/about') }}" class="text-gray-600 hover:text-orange-500">Tentang Kami</a>
                    <a href="{{ url('/contact') }}" class="text-gray-600 hover:text-orange-500">Kontak</a>
                @endguest
            </div>
        </div>

        <!-- Bagian Tengah Navbar (Logo) -->
        <div class="absolute left-1/2 -translate-x-1/2">
            <a href="{{ url('/home') }}" class="flex items-center">
                <img
                    src="{{ asset('dummy_images/logo/Logo-Zoeliez-Ilux.png') }}"
                    alt="Zoeliez Ilux Snack Ponorogo Logo"
                    class="h-16 w-auto"
                />
            </a>
        </div>


        <!-- Bagian Kanan Navbar (Tombol Profil/Login) -->
        <div class="flex-1 flex justify-end relative">
            @auth
                <div class="relative">
                    <button
                        id="profile-menu-btn"
                        class="w-9 h-9 bg-gray-300 rounded-full flex items-center justify-center font-bold text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                    >
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </button>

                    <!-- Menu Dropdown Profil -->
                    <div id="profile-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-xl z-20 hidden animate-fade-in-down">
                        <div class="py-2">
                            <a href="{{ url('/api/admin/profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">
                                Pengaturan Profil
                            </a>
                            <button onclick="handleLogout()" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                Logout
                            </button>

                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileBtn = document.getElementById('profile-menu-btn');
        const profileMenu = document.getElementById('profile-menu');

        if (profileBtn && profileMenu) {
            // Toggle menu
            profileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                profileMenu.classList.toggle('hidden');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileMenu.classList.add('hidden');
                }
            });
        }
    });

    function handleLogout() {
        fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(() => {
            localStorage.removeItem('authToken');
            window.location.href = '/home';
        })
        .catch(err => {
            console.error('Logout failed', err);
            window.location.href = '/home';
        });
    }

</script>
