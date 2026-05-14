<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penunggu Pasien Rawat Inap</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                    },
                },
            },
        }
    </script>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">

    <?php include '../layout/sidebar.php'; ?>

    <div class="ml-0 lg:ml-72 p-4 lg:p-8 min-h-screen">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 lg:p-8 animate-fade-in">
            <?php
            switch ($page) {
                case 'dashboard':
                    include '../pages/dashboard.php';
                    break;

                case 'input':
                    include './pasien/input_penunggu.php';
                    break;

                case 'recognition':
                    include '../pages/pengenalan_wajah.php';
                    break;

                case 'laporan':
                    include '../pages/laporan.php';
                    break;

                default:
                    echo "<div class='text-center py-12'><h3 class='text-2xl font-bold text-gray-900 dark:text-white mb-4'>Halaman tidak ditemukan</h3><p class='text-gray-600 dark:text-gray-300'>Silakan kembali ke dashboard.</p></div>";
                    break;
            }
            ?>
        </div>
    </div>

    <!-- Dark Mode Toggle -->
    <button
        id="theme-toggle"
        class="fixed bottom-6 right-6 w-14 h-14 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-500/20 z-50 group"
        aria-label="Toggle dark mode"
    >
        <svg id="theme-icon-sun" class="w-6 h-6 text-gray-800 dark:text-gray-200 m-4 transition-opacity duration-300 opacity-100 dark:opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        <svg id="theme-icon-moon" class="w-6 h-6 text-gray-800 dark:text-gray-200 m-4 transition-opacity duration-300 opacity-0 dark:opacity-100 absolute inset-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
        </svg>
    </button>

    <script>
        // Theme toggle functionality
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;

        // Check for saved theme preference or default to light mode
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            html.classList.add('dark');
        }

        themeToggle.addEventListener('click', () => {
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        });

        // Sidebar functionality (existing code)
        const sidebar = document.getElementById('sidebar');
        const toggleSidebar = document.getElementById('toggleSidebar');
        const closeSidebar = document.getElementById('closeSidebar');

        if (toggleSidebar) {
            toggleSidebar.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }

        if (closeSidebar) {
            closeSidebar.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
            });
        }

        // Close sidebar when clicking outside
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !toggleSidebar.contains(e.target)) {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>

</body>
</html>