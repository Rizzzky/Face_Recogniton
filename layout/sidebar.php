<!-- Mobile Menu Toggle -->
<button
    id="toggleSidebar"
    class="fixed top-4 left-4 z-50 lg:hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-500/20 group"
    aria-label="Toggle sidebar"
>
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
</button>

<!-- Sidebar Overlay (Mobile) -->
<div
    id="sidebar-overlay"
    class="fixed inset-0 bg-black/50 z-30 lg:hidden opacity-0 invisible transition-all duration-300"
></div>

<!-- Sidebar -->
<div
    id="sidebar"
    class="w-72 min-h-screen bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 fixed left-0 top-0 p-6 shadow-2xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-40 backdrop-blur-sm bg-white/95 dark:bg-gray-800/95"
>
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Sistem RS</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Penunggu Pasien</p>
            </div>
        </div>

        <button
            id="closeSidebar"
            class="lg:hidden text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
            aria-label="Close sidebar"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="space-y-2">
        <a href="index.php?page=dashboard"
           class="group flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300 transition-all duration-200 <?php echo ($page == 'dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : ''; ?>">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                </svg>
            </div>
            <span class="font-medium">Dashboard</span>
            <?php if ($page == 'dashboard') { ?>
            <div class="ml-auto w-2 h-2 bg-blue-500 rounded-full"></div>
            <?php } ?>
        </a>

        <a href="index.php?page=input"
           class="group flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300 transition-all duration-200 <?php echo ($page == 'input') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : ''; ?>">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <span class="font-medium">Input Penunggu</span>
            <?php if ($page == 'input') { ?>
            <div class="ml-auto w-2 h-2 bg-blue-500 rounded-full"></div>
            <?php } ?>
        </a>

        <a href="index.php?page=recognition"
           class="group flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300 transition-all duration-200 <?php echo ($page == 'recognition') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : ''; ?>">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
            </div>
            <span class="font-medium">Pengenalan Wajah</span>
            <?php if ($page == 'recognition') { ?>
            <div class="ml-auto w-2 h-2 bg-blue-500 rounded-full"></div>
            <?php } ?>
        </a>

        <a href="index.php?page=laporan"
           class="group flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300 transition-all duration-200 <?php echo ($page == 'laporan') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : ''; ?>">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <span class="font-medium">Laporan Riwayat</span>
            <?php if ($page == 'laporan') { ?>
            <div class="ml-auto w-2 h-2 bg-blue-500 rounded-full"></div>
            <?php } ?>
        </a>
    </nav>

    <!-- Footer -->
    <div class="absolute bottom-6 left-6 right-6">
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-xl p-4 border border-blue-100 dark:border-blue-800">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Butuh Bantuan?</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Hubungi admin sistem</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebar-overlay');
const toggleSidebar = document.getElementById('toggleSidebar');
const closeSidebar = document.getElementById('closeSidebar');

// Toggle sidebar
if (toggleSidebar) {
    toggleSidebar.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        sidebarOverlay.classList.toggle('opacity-0');
        sidebarOverlay.classList.toggle('invisible');
    });
}

// Close sidebar
if (closeSidebar) {
    closeSidebar.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('opacity-0');
        sidebarOverlay.classList.add('invisible');
    });
}

// Close sidebar when clicking overlay
if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('opacity-0');
        sidebarOverlay.classList.add('invisible');
    });
}

// Close sidebar after clicking menu links (mobile)
const menuLinks = sidebar.querySelectorAll('a');
menuLinks.forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth < 1024) { // lg breakpoint
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('opacity-0');
            sidebarOverlay.classList.add('invisible');
        }
    });
});

// Close sidebar on escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('opacity-0');
        sidebarOverlay.classList.add('invisible');
    }
});
</script>