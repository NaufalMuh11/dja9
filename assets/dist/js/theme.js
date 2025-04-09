
// Theme toggling functionality
document.addEventListener('DOMContentLoaded', function () {
    // Check for saved theme preference, default to 'light'
    const currentTheme = localStorage.getItem('theme') || 'light';
    setTheme(currentTheme);

    // Dark mode toggle
    const darkModeToggle = document.querySelector('.hide-theme-dark');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function (e) {
            e.preventDefault();
            setTheme('dark');
        });
    }

    // Light mode toggle
    const lightModeToggle = document.querySelector('.hide-theme-light');
    if (lightModeToggle) {
        lightModeToggle.addEventListener('click', function (e) {
            e.preventDefault();
            setTheme('light');
        });
    }

    // Function to set theme
    function setTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        document.body.classList.remove('theme-dark', 'theme-light');
        document.body.classList.add(`theme-${theme}`);
        localStorage.setItem('theme', theme);

        // Update visibility of theme toggles
        const darkToggle = document.querySelector('.hide-theme-dark');
        const lightToggle = document.querySelector('.hide-theme-light');

        if (darkToggle && lightToggle) {
            if (theme === 'dark') {
                darkToggle.style.display = 'none';
                lightToggle.style.display = 'block';
            } else {
                darkToggle.style.display = 'block';
                lightToggle.style.display = 'none';
            }
        }

        // Update logo based on theme
        const logo = document.querySelector('.navbar-brand-image');
        if (logo) {
            const logoPath = theme === 'dark' ? './files/images/data-dark.png' : './files/images/data.png';
            logo.src = logoPath;
        }
    }
});