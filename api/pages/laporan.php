<?php
include 'config/database.php';

// Initialize variables
$data = null;
$error_message = null;
$total_records = 0;

// Check connection
if (!$conn) {
    $error_message = "Koneksi database gagal. Silakan hubungi administrator.";
} else {
    $query = "
        SELECT 
            nama_penunggu,
            nama_pasien,
            ruangan,
            SUM(CASE WHEN status='MASUK' THEN 1 ELSE 0 END) AS total_masuk,
            SUM(CASE WHEN status='KELUAR' THEN 1 ELSE 0 END) AS total_keluar,
            MAX(waktu_scan) AS terakhir
        FROM riwayat_scan
        GROUP BY nama_penunggu, nama_pasien, ruangan
        ORDER BY MAX(waktu_scan) DESC
    ";
    
    $data = mysqli_query($conn, $query);
    
    if (!$data) {
        $error_message = "Query Error: " . mysqli_error($conn);
    } else {
        $total_records = mysqli_num_rows($data);
    }
}
?>

<!-- Header Section -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Laporan Riwayat Masuk & Keluar
            </h1>
            <p class="text-gray-600 dark:text-gray-300">
                Pantau riwayat kehadiran penunggu pasien rawat inap.
            </p>
        </div>
        <button class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors duration-200 focus:outline-none focus:ring-4 focus:ring-blue-500/50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2m0 0v-8m0 8l-4-2m4 2l4-2"></path>
            </svg>
            Download Laporan
        </button>
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
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Data Table -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Data Riwayat (<?php echo $total_records; ?> Catatan)
        </h3>
        <div class="relative hidden sm:block">
            <input
                type="text"
                placeholder="Cari data..."
                class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
            >
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Nama Penunggu</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Nama Pasien</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Ruangan</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Masuk</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Keluar</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Terakhir Scan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                <?php 
                if ($data && $total_records > 0):
                    $no = 1; 
                    while($d = mysqli_fetch_assoc($data)): 
                ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                        <?php echo $no++; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                <?php echo substr($d['nama_penunggu'], 0, 1); ?>
                            </div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                <?php echo htmlspecialchars($d['nama_penunggu']); ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        <?php echo htmlspecialchars($d['nama_pasien']); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                            <?php echo htmlspecialchars($d['ruangan']); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 font-semibold text-sm">
                            <?php echo $d['total_masuk'] ?: 0; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-orange-100 dark:bg-orange-900/20 text-orange-700 dark:text-orange-300 font-semibold text-sm">
                            <?php echo $d['total_keluar'] ?: 0; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        <?php 
                        if ($d['terakhir']) {
                            echo date('d/m/Y H:i', strtotime($d['terakhir']));
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
                </tr>
                <?php 
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada data</h3>
                        <p class="text-gray-500 dark:text-gray-400">Riwayat scan akan ditampilkan di sini setelah ada aktivitas pengenalan wajah.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Summary Stats (Optional) -->
<?php if ($total_records > 0 && !$error_message): ?>
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Total Catatan</p>
        <p class="text-3xl font-bold text-gray-900 dark:text-white"><?php echo $total_records; ?></p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Total Masuk</p>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">
            <?php 
            $total_masuk_query = mysqli_query($conn, "SELECT SUM(status='MASUK') as total FROM riwayat_scan");
            $total_masuk = mysqli_fetch_assoc($total_masuk_query)['total'] ?: 0;
            echo $total_masuk;
            ?>
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Total Keluar</p>
        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">
            <?php 
            $total_keluar_query = mysqli_query($conn, "SELECT SUM(status='KELUAR') as total FROM riwayat_scan");
            $total_keluar = mysqli_fetch_assoc($total_keluar_query)['total'] ?: 0;
            echo $total_keluar;
            ?>
        </p>
    </div>
</div>
<?php endif; ?>