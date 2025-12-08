<div id="protected-content" style="display: none;">
    {{ $slot }}
</div>

<script>
    (function() {
        const token = localStorage.getItem('authToken');
        if (!token) {
            window.location.replace('/admin/login'); // Redirect to login if no token
        } else {
            document.getElementById('protected-content').style.display = 'block';
        }
    })();
</script>
