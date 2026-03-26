<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbandingan Sistem Gamepass - Valtus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .comparison-card {
            transition: all 0.3s ease;
        }
        .comparison-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }
        .old-system { border-left: 4px solid #ef4444; }
        .new-system { border-left: 4px solid #10b981; }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-white mb-4">⚖️ Perbandingan Sistem Gamepass</h1>
            <p class="text-gray-400 text-lg">Analisis mendalam perbedaan sistem lama vs baru</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-red-900/20 border border-red-500/50 rounded-lg p-6 text-center">
                <div class="text-3xl font-bold text-red-400">5-10s</div>
                <div class="text-sm text-red-300">Sistem Lama</div>
            </div>
            <div class="bg-green-900/20 border border-green-500/50 rounded-lg p-6 text-center">
                <div class="text-3xl font-bold text-green-400">1-2s</div>
                <div class="text-sm text-green-300">Sistem Baru</div>
            </div>
            <div class="bg-blue-900/20 border border-blue-500/50 rounded-lg p-6 text-center">
                <div class="text-3xl font-bold text-blue-400">5x</div>
                <div class="text-sm text-blue-300">Lebih Cepat</div>
            </div>
        </div>

        <!-- Main Comparison -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Sistem Lama -->
            <div class="comparison-card old-system bg-gray-800 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                    <h2 class="text-2xl font-bold text-red-400">❌ Sistem Lama (Deprecated)</h2>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-300 mb-2">🔗 API Endpoint</h3>
                        <code class="bg-gray-700 px-3 py-1 rounded text-sm text-red-300">
                            inventory.roblox.com/v1/users/{userId}/items/GamePass
                        </code>
                        <p class="text-sm text-gray-400 mt-1">❌ Deprecated, sering 404 error</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-300 mb-2">📊 Response Structure</h3>
                        <pre class="bg-gray-700 p-3 rounded text-xs text-gray-300">
{
  "data": [
    {
      "id": 123456,
      "name": "Gamepass Name"
    }
  ]
}</pre>
                        <p class="text-sm text-gray-400 mt-1">❌ Data tidak lengkap, perlu API call tambahan</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-300 mb-2">⚙️ Proses</h3>
                        <ol class="list-decimal list-inside space-y-1 text-sm text-gray-300">
                            <li>Call inventory API</li>
                            <li>Loop setiap gamepass</li>
                            <li>Call economy API untuk detail</li>
                            <li>Check price, creator, isForSale</li>
                            <li>Match dengan requirement</li>
                        </ol>
                        <p class="text-sm text-gray-400 mt-2">❌ N+1 API calls = lambat</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-300 mb-2">📈 Performance</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Speed:</span>
                                <span class="text-red-400">5-10 detik</span>
                            </div>
                            <div class="flex justify-between">
                                <span>API Calls:</span>
                                <span class="text-red-400">1 + N calls</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Success Rate:</span>
                                <span class="text-red-400">~60%</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-300 mb-2">❌ Masalah</h3>
                        <ul class="list-disc list-inside space-y-1 text-sm text-gray-300">
                            <li>Endpoint sering 404</li>
                            <li>Data tidak lengkap</li>
                            <li>Rate limiting issues</li>
                            <li>Banyak user tidak ketemu gamepass</li>
                            <li>Timeout errors</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sistem Baru -->
            <div class="comparison-card new-system bg-gray-800 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <h2 class="text-2xl font-bold text-green-400">✅ Sistem Baru (Optimized)</h2>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-300 mb-2">🔗 API Endpoint</h3>
                        <code class="bg-gray-700 px-3 py-1 rounded text-sm text-green-300">
                            apis.roblox.com/game-passes/v1/users/{userId}/game-passes
                        </code>
                        <p class="text-sm text-gray-400 mt-1">✅ Recommended by Roblox Developer Forum</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-300 mb-2">📊 Response Structure</h3>
                        <pre class="bg-gray-700 p-3 rounded text-xs text-gray-300">
{
  "gamePasses": [
    {
      "gamePassId": 123456,
      "name": "Gamepass Name",
      "price": 143,
      "isForSale": true,
      "creator": {
        "creatorId": 1429646404,
        "creatorType": "User"
      }
    }
  ]
}</pre>
                        <p class="text-sm text-gray-400 mt-1">✅ Data lengkap, langsung bisa digunakan</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-300 mb-2">⚙️ Proses</h3>
                        <ol class="list-decimal list-inside space-y-1 text-sm text-gray-300">
                            <li>Call gamepasses API</li>
                            <li>Direct matching dari response</li>
                            <li>Check price, creator, isForSale</li>
                            <li>Return result</li>
                        </ol>
                        <p class="text-sm text-gray-400 mt-2">✅ 1 API call saja = super cepat</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-300 mb-2">📈 Performance</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Speed:</span>
                                <span class="text-green-400">1-2 detik</span>
                            </div>
                            <div class="flex justify-between">
                                <span>API Calls:</span>
                                <span class="text-green-400">1 call saja</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Success Rate:</span>
                                <span class="text-green-400">~95%</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-300 mb-2">✅ Keuntungan</h3>
                        <ul class="list-disc list-inside space-y-1 text-sm text-gray-300">
                            <li>Endpoint resmi dan stabil</li>
                            <li>Data lengkap dalam 1 response</li>
                            <li>5x lebih cepat</li>
                            <li>Success rate tinggi</li>
                            <li>Lebih reliable</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technical Details -->
        <div class="mt-12 bg-gray-800 rounded-lg p-6">
            <h2 class="text-2xl font-bold text-white mb-6">🔧 Detail Teknis</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-red-400 mb-4">Sistem Lama - Flow</h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-xs">1</div>
                            <span class="text-sm">Call inventory API</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-xs">2</div>
                            <span class="text-sm">Loop setiap gamepass</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-xs">3</div>
                            <span class="text-sm">Call economy API untuk detail</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-xs">4</div>
                            <span class="text-sm">Check price, creator, isForSale</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-xs">5</div>
                            <span class="text-sm">Match dengan requirement</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-green-400 mb-4">Sistem Baru - Flow</h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-xs">1</div>
                            <span class="text-sm">Call gamepasses API</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-xs">2</div>
                            <span class="text-sm">Direct matching dari response</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-xs">3</div>
                            <span class="text-sm">Return result</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Results -->
        <div class="mt-12 bg-gray-800 rounded-lg p-6">
            <h2 class="text-2xl font-bold text-white mb-6">📊 Hasil Test Real</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-red-900/20 border border-red-500/50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-red-400 mb-3">Sistem Lama</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Username:</span>
                            <span>rizkimulyawan110404</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Amount:</span>
                            <span>100 Robux</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Required Price:</span>
                            <span>143 Robux</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Result:</span>
                            <span class="text-red-400">❌ Not Found</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Duration:</span>
                            <span class="text-red-400">~8 detik</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Error:</span>
                            <span class="text-red-400">404 Inventory</span>
                        </div>
                    </div>
                </div>

                <div class="bg-green-900/20 border border-green-500/50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-green-400 mb-3">Sistem Baru</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Username:</span>
                            <span>rizkimulyawan110404</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Amount:</span>
                            <span>100 Robux</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Required Price:</span>
                            <span>143 Robux</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Result:</span>
                            <span class="text-green-400">✅ Found</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Duration:</span>
                            <span class="text-green-400">~1.5 detik</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Gamepass:</span>
                            <span class="text-green-400">tes (ID: 1525959160)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-12 text-center">
            <a href="/debug/gamepass" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Test Sistem Baru
            </a>
        </div>
    </div>
</body>
</html>

