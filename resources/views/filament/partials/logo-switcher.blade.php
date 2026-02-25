<!-- resources/views/filament/partials/logo-switcher.blade.php -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function updateLogoBasedOnTheme() {
            const logo = document.querySelector('.filament-brand-logo img');
            if (!logo) return; // If the logo doesn't exist, return early.

            // Check for dark mode
            if (document.documentElement.classList.contains('dark')) {
                logo.src = '{{ asset('images/Vescular-Science-dark.png') }}'; // Dark theme logo
            } else {
                logo.src = '{{ asset('images/Vescular-Science-light.png') }}'; // Light theme logo
            }
        }

        // Run the function to update the logo when the page loads
        updateLogoBasedOnTheme();

        // Re-run the function if the theme is switched
        window.addEventListener('theme:changed', updateLogoBasedOnTheme);
    });
</script>
