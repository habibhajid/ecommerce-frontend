<nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-6 py-4 flex items-center justify-between relative">

        <!-- Bagian Kiri Navbar (Link Navigasi) -->
        <div class="flex-1 flex items-center space-x-8 pl-8">
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ url('/catalog') }}" class="text-gray-600 hover:text-orange-500">Produk</a>
                <a href="{{ url('/about') }}" class="text-gray-600 hover:text-orange-500">Tentang Kami</a>
                <a href="{{ url('/contact') }}" class="text-gray-600 hover:text-orange-500">Kontak</a>
                
                <!-- Admin Link (Hidden by default, shown via JS if admin) -->
                <a id="nav-admin-link" href="{{ url('/admin') }}" class="text-orange-600 hover:text-orange-700 font-medium hidden">
                    Admin
                </a>
            </div>
        </div>

        <!-- Bagian Tengah Navbar (Logo) -->
        <div class="absolute left-1/2 -translate-x-1/2">
            <a href="{{ url('/home') }}" class="flex items-center">
                <img
                    src="{{ env('BACKEND_URL') }}/storage/dummy_images/logo/Logo-Zoeliez-Ilux.png"
                    alt="Zoeliez Ilux Snack Ponorogo Logo"
                    class="h-16 w-auto"
                />
            </a>
        </div>


        <!-- Bagian Kanan Navbar (Tombol Profil/Login) -->
        <div class="flex-1 flex justify-end relative pr-8">
             <!-- Logout Button (Hidden by default, shown via JS if admin) -->
             <button id="nav-logout-btn" onclick="handleLogout()" class="flex items-center space-x-2 text-red-600 hover:text-red-700 font-medium hidden transition-colors duration-200 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-full">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const authToken = localStorage.getItem('authToken');
        const adminLink = document.getElementById('nav-admin-link');
        const logoutBtn = document.getElementById('nav-logout-btn');

        if (authToken) {
            // User is logged in (Admin)
            if (adminLink) adminLink.classList.remove('hidden');
            if (logoutBtn) logoutBtn.classList.remove('hidden');
        } else {
            // User is Guest
            if (adminLink) adminLink.classList.add('hidden');
            if (logoutBtn) logoutBtn.classList.add('hidden');
        }
    });

    function handleLogout() {
        if(confirm('Apakah Anda yakin ingin logout?')) {
            const authToken = localStorage.getItem('authToken');
             // Optional: Call API to invalidate token on backend if needed
             // fetch('/api/logout', ... ) 
             
            // Clear local storage
            localStorage.removeItem('authToken');
            
            // Redirect to home
            window.location.href = '/home';
        }
    }
</script>
