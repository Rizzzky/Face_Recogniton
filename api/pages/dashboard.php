<?php
// Initialize variables
$data = null;
$total_pasien = 0;
$today_count = 0;
$room_count = 0;
$error_message = null;

// Connect to database
@include 'config/database.php';

// Check connection
if (!isset($conn) || !$conn) {
    $error_message = "Database tidak terhubung. Pastikan XAMPP/MySQL sedang berjalan.";
} else {
    // Get total patients
    $query = "SELECT * FROM penunggu_pasien LIMIT 1000";
    $result = @mysqli_query($conn, $query);
    
    if (!$result) {
        $error_message = "Error: " . mysqli_error($conn);
    } else {
        $total_pasien = mysqli_num_rows($result);
        $data = $result;
        
        // Get today's count
        $today = date('Y-m-d');
        $today_query = @mysqli_query($conn, "SELECT COUNT(*) as count FROM penunggu_pasien WHERE DATE(tanggal_masuk) = '$today'");
        if ($today_query) {
            $today_count = mysqli_fetch_assoc($today_query)['count'];
        }
        
        // Get room count
        $room_query = @mysqli_query($conn, "SELECT COUNT(DISTINCT nama_ruangan) as count FROM penunggu_pasien");
        if ($room_query) {
            $room_count = mysqli_fetch_assoc($room_query)['count'];
        }
    }
}
?>

<!-- Header Section -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Dashboard Penunggu Pasien
            </h1>
            <p class="text-gray-600 dark:text-gray-300">
                Pantau dan kelola penunggu pasien rawat inap dengan mudah
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <div class="inline-flex items-center px-4 py-2 <?php echo $error_message ? 'bg-red-50 dark:bg-red-900/20' : 'bg-blue-50 dark:bg-blue-900/20'; ?> rounded-xl">
                <div class="w-2 h-2 <?php echo $error_message ? 'bg-red-500' : 'bg-green-500'; ?> rounded-full mr-2 animate-pulse"></div>
                <span class="text-sm font-medium <?php echo $error_message ? 'text-red-700 dark:text-red-300' : 'text-blue-700 dark:text-blue-300'; ?>">
                    <?php echo $error_message ? 'Koneksi Database Gagal' : 'Sistem Aktif'; ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <?php if ($error_message): ?>
    <div class="mb-8 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="font-semibold text-red-800 dark:text-red-200">Terjadi Kesalahan</h3>
                <p class="text-sm text-red-700 dark:text-red-300 mt-1"><?php echo htmlspecialchars($error_message); ?></p>
                <p class="text-xs text-red-600 dark:text-red-400 mt-2">
                    💡 Pastikan: 
                    <br>1. XAMPP sedang berjalan
                    <br>2. MySQL Service aktif
                    <br>3. Database 'db_penunggu_rs' sudah dibuat
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Pasien</p>
                    <p class="text-3xl font-bold mt-1"><?php echo $total_pasien; ?></p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-100 text-sm font-medium">Pasien Hari Ini</p>
                    <p class="text-3xl font-bold mt-1">
                        <?php echo $today_count; ?>
                    </p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Ruangan Terpakai</p>
                    <p class="text-3xl font-bold mt-1">
                        <?php echo $room_count; ?>
                    </p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Rata-rata Tinggal</p>
                    <p class="text-3xl font-bold mt-1">
                        3.2
                        <span class="text-lg font-normal">hari</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table Section -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 sm:mb-0">
                Daftar Penunggu Pasien
            </h3>
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Cari pasien..."
                        class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors"
                    >
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Pasien
                </button>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        No
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        No RM
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Nama Pasien
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Nama Penunggu
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Ruangan
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Tanggal Masuk
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                <?php 
                if ($data && $total_pasien > 0):
                    $no = 1; 
                    while($d = mysqli_fetch_assoc($data)): 
                ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                        <?php echo $no++; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        <?php echo htmlspecialchars($d['no_rm']); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                <?php echo substr($d['nama_pasien'], 0, 1); ?>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    <?php echo htmlspecialchars($d['nama_pasien']); ?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        <?php echo htmlspecialchars($d['nama_penunggu']); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                            <?php echo htmlspecialchars($d['nama_ruangan']); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        <?php echo date('d/m/Y', strtotime($d['tanggal_masuk'])); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button class="inline-flex items-center px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-lg transition-colors duration-200 text-xs font-medium">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Lihat
                            </button>
                            <a href="pasien/hapus_pasien.php?id=<?php echo htmlspecialchars($d['id']); ?>"
                               class="inline-flex items-center px-3 py-1.5 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-lg transition-colors duration-200 text-xs font-medium"
                               onclick="return confirm('Apakah pasien sudah pulang?')">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Pulang
                            </a>
                        </div>
                    </td>
                </tr>
                <?php 
                    endwhile;
                endif;
                ?>
            </tbody>
        </table>
    </div>

    <?php if ($total_pasien == 0 || $error_message): ?>
    <div class="px-6 py-12 text-center">
        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada data pasien</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">Mulai dengan menambahkan data penunggu pasien pertama.</p>
        <a href="index.php?page=input" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Pasien Baru
        </a>
    </div>
    <?php endif; ?>
</div>