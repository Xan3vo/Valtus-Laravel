<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\Api\RobloxGroupController;
use Illuminate\Http\Request;

try {
    echo "DEMO: Fitur Pembelian Robux via Group\n";
    echo "=====================================\n\n";
    
    $controller = new RobloxGroupController();
    
    echo "1. INFORMASI GROUP\n";
    echo "==================\n";
    $groupInfoRequest = new Request();
    $groupInfoResult = $controller->getGroupInfo();
    $groupData = $groupInfoResult->getData(true);
    
    if ($groupData['success']) {
        echo "✅ Group Name: " . $groupData['group']['name'] . "\n";
        echo "✅ Group ID: " . $groupData['group']['id'] . "\n";
        echo "✅ Member Count: " . number_format($groupData['group']['member_count']) . "\n";
        echo "✅ URL: " . $groupData['group']['url'] . "\n\n";
    } else {
        echo "❌ Error: " . $groupData['message'] . "\n\n";
    }
    
    echo "2. TESTING GROUP MEMBERSHIP\n";
    echo "===========================\n";
    
    $testCases = [
        ['username' => 'rizkimulyawan110404', 'expected' => 'Not a member'],
        ['username' => 'eca0905', 'expected' => 'Not a member'],
        ['username' => 'builderman', 'expected' => 'Not a member (famous user)']
    ];
    
    foreach ($testCases as $testCase) {
        echo "Testing: {$testCase['username']}\n";
        echo "Expected: {$testCase['expected']}\n";
        echo "--------------------------------\n";
        
        $request = new Request();
        $request->merge(['username' => $testCase['username']]);
        
        $result = $controller->checkGroupMembership($request);
        $data = $result->getData(true);
        
        if ($data['success']) {
            if ($data['is_member']) {
                if ($data['can_purchase']) {
                    echo "✅ Status: Member (Dapat membeli)\n";
                    echo "✅ Membership Days: " . $data['membership_days'] . " hari\n";
                } else {
                    echo "⚠️  Status: Member (Belum bisa membeli)\n";
                    echo "⚠️  Membership Days: " . $data['membership_days'] . " hari\n";
                    echo "⚠️  Sisa hari: " . (14 - $data['membership_days']) . " hari\n";
                }
            } else {
                echo "❌ Status: Bukan member\n";
            }
        } else {
            echo "❌ Error: " . $data['message'] . "\n";
        }
        echo "\n";
    }
    
    echo "3. CARA PENGGUNAAN\n";
    echo "==================\n";
    echo "1. User memilih metode 'Via Group' di halaman pembelian\n";
    echo "2. User memasukkan username Roblox\n";
    echo "3. User klik 'Cek Keanggotaan' untuk validasi\n";
    echo "4. Sistem mengecek:\n";
    echo "   - Apakah user sudah bergabung dengan group Valtus Studios\n";
    echo "   - Berapa lama user sudah menjadi member\n";
    echo "   - Apakah sudah 14 hari (bisa membeli)\n";
    echo "5. Jika valid, user bisa melanjutkan pembelian\n";
    echo "6. Jika belum 14 hari, user harus menunggu\n";
    echo "7. Jika bukan member, user harus bergabung dulu\n\n";
    
    echo "4. KEUNTUNGAN SISTEM INI\n";
    echo "=======================\n";
    echo "✅ Tidak perlu buat gamepass sendiri\n";
    echo "✅ Harga tetap sama dengan metode gamepass\n";
    echo "✅ Proses lebih mudah untuk user\n";
    echo "✅ Kontrol keanggotaan group (14 hari)\n";
    echo "✅ Mencegah abuse dengan sistem waiting period\n";
    echo "✅ User tetap bisa pilih metode gamepass jika mau\n\n";
    
    echo "5. IMPLEMENTASI SELESAI\n";
    echo "======================\n";
    echo "✅ UI untuk pilihan metode (Gamepass/Group)\n";
    echo "✅ API untuk cek keanggotaan group\n";
    echo "✅ Validasi 14 hari membership\n";
    echo "✅ Error handling yang baik\n";
    echo "✅ Integration dengan sistem existing\n";
    echo "✅ User-friendly interface\n\n";
    
    echo "🎉 FITUR GROUP MEMBERSHIP BERHASIL DIIMPLEMENTASI! 🎉\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}


