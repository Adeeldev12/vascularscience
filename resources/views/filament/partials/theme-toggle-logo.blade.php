<!-- resources/views/filament/partials/theme-toggle-logo.blade.php -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to switch logos based on theme
        function updateLogoBasedOnTheme() {
            const logo = document.querySelector('.filament-brand-logo img');
            if (document.documentElement.classList.contains('dark')) {
                logo.src = '{{ asset("images/Vescular-Science-dark.png") }}'; // Dark theme logo
            } else {
                logo.src = '{{ asset("images/Vescular-Science-light.png") }}'; // Light theme logo
            }
        }

        // Update logo initially
        updateLogoBasedOnTheme();

        // Listen for theme change event
        window.addEventListener('theme:changed', updateLogoBasedOnTheme);
    });
</script>
