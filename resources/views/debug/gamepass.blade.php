<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Gamepass - Valtus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .json-viewer {
            background: #1a1a1a;
            color: #e0e0e0;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            max-height: 500px;
            overflow-y: auto;
            border-radius: 8px;
            padding: 16px;
        }
        .status-success { color: #10b981; }
        .status-error { color: #ef4444; }
        .status-warning { color: #f59e0b; }
        .status-info { color: #3b82f6; }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">🔧 Debug Gamepass System</h1>
                    <p class="text-gray-400">Test dan debug logika pencarian gamepass untuk top-up Robux</p>
                </div>
                <div class="flex gap-3">
                    <a href="/debug/comparison" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Perbandingan
                    </a>
                </div>
            </div>
        </div>

        <!-- Input Form -->
        <div class="bg-gray-800 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Test Parameters</h2>
            <form id="debugForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Username Roblox</label>
                    <input type="text" id="username" name="username" 
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="mis: builderman" value="rizkimulyawan110404">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Amount (Robux)</label>
                    <input type="number" id="amount" name="amount" 
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="100" value="100" min="1">
                </div>
                <div class="flex items-end">
                    <button type="submit" id="testBtn" 
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Test Debug
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="hidden bg-gray-800 rounded-lg p-6 mb-6">
            <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mr-3"></div>
                <span class="text-lg">Testing gamepass logic...</span>
            </div>
        </div>

        <!-- Results -->
        <div id="results" class="hidden">
            <!-- Summary Card -->
            <div class="bg-gray-800 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">📊 Test Summary</h2>
                <div id="summary" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Summary will be populated by JavaScript -->
                </div>
            </div>

            <!-- Steps Timeline -->
            <div class="bg-gray-800 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">🔄 Execution Steps</h2>
                <div id="stepsTimeline" class="space-y-4">
                    <!-- Steps will be populated by JavaScript -->
                </div>
            </div>

            <!-- Raw JSON -->
            <div class="bg-gray-800 rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">📄 Raw JSON Response</h2>
                <div class="flex justify-between items-center mb-4">
                    <button id="toggleJson" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-sm">
                        Toggle JSON View
                    </button>
                    <button id="copyJson" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded text-sm">
                        Copy JSON
                    </button>
                </div>
                <pre id="jsonViewer" class="json-viewer hidden"></pre>
            </div>
        </div>

        <!-- Error State -->
        <div id="errorState" class="hidden bg-red-900/20 border border-red-500/50 rounded-lg p-6">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-red-400">Test Failed</h3>
                    <p id="errorMessage" class="text-red-300 mt-1"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('debugForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value.trim();
            const amount = document.getElementById('amount').value;
            
            if (!username || !amount) {
                alert('Please fill in both username and amount');
                return;
            }
            
            // Show loading state
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('results').classList.add('hidden');
            document.getElementById('errorState').classList.add('hidden');
            
            try {
                const response = await fetch(`/api/test/gamepass-debug?username=${encodeURIComponent(username)}&amount=${amount}`);
                const data = await response.json();
                
                // Hide loading
                document.getElementById('loadingState').classList.add('hidden');
                
                if (response.ok) {
                    displayResults(data);
                } else {
                    displayError(data.error || 'Unknown error occurred');
                }
            } catch (error) {
                document.getElementById('loadingState').classList.add('hidden');
                displayError('Network error: ' + error.message);
            }
        });
        
        function displayResults(data) {
            // Show results
            document.getElementById('results').classList.remove('hidden');
            
            // Populate summary
            const summary = document.getElementById('summary');
            summary.innerHTML = `
                <div class="text-center">
                    <div class="text-2xl font-bold ${data.result.found ? 'text-green-400' : 'text-red-400'}">
                        ${data.result.found ? '✅' : '❌'}
                    </div>
                    <div class="text-sm text-gray-400">Status</div>
                </div>
                <div class="text-center">
                    <div class="text-xl font-semibold">${data.input.amount}</div>
                    <div class="text-sm text-gray-400">Robux</div>
                </div>
                <div class="text-center">
                    <div class="text-xl font-semibold">${data.steps['2_price_calculation'].requiredPrice}</div>
                    <div class="text-sm text-gray-400">Required Price</div>
                </div>
                <div class="text-center">
                    <div class="text-xl font-semibold">${data.result.total_duration_ms}ms</div>
                    <div class="text-sm text-gray-400">Duration</div>
                </div>
            `;
            
            // Populate steps timeline
            const stepsTimeline = document.getElementById('stepsTimeline');
            stepsTimeline.innerHTML = '';
            
            Object.keys(data.steps).forEach((stepKey, index) => {
                const step = data.steps[stepKey];
                const stepNumber = index + 1;
                
                let statusClass = 'status-info';
                let statusIcon = 'ℹ️';
                
                if (step.response) {
                    if (step.response.success) {
                        statusClass = 'status-success';
                        statusIcon = '✅';
                    } else {
                        statusClass = 'status-error';
                        statusIcon = '❌';
                    }
                }
                
                const stepHtml = `
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center text-sm font-semibold">
                            ${stepNumber}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="text-lg">${statusIcon}</span>
                                <h3 class="text-lg font-medium">${step.action || stepKey}</h3>
                            </div>
                            ${step.url ? `<p class="text-sm text-gray-400 mb-2">URL: ${step.url}</p>` : ''}
                            ${step.response ? `
                                <div class="text-sm space-y-1">
                                    <div class="flex justify-between">
                                        <span>Status:</span>
                                        <span class="${statusClass}">${step.response.status}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Duration:</span>
                                        <span class="${statusClass}">${step.response.duration_ms}ms</span>
                                    </div>
                                    ${step.response.success ? '<div class="text-green-400 text-xs">✓ Success</div>' : '<div class="text-red-400 text-xs">✗ Failed</div>'}
                                </div>
                            ` : ''}
                            ${step.result ? `
                                <div class="mt-2 p-3 bg-gray-700 rounded text-sm">
                                    <pre class="text-xs">${JSON.stringify(step.result, null, 2)}</pre>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
                
                stepsTimeline.innerHTML += stepHtml;
            });
            
            // Store raw data for JSON viewer
            window.debugData = data;
            
            // Setup JSON viewer toggle
            document.getElementById('toggleJson').addEventListener('click', function() {
                const jsonViewer = document.getElementById('jsonViewer');
                jsonViewer.classList.toggle('hidden');
                this.textContent = jsonViewer.classList.contains('hidden') ? 'Show JSON' : 'Hide JSON';
            });
            
            // Setup copy JSON
            document.getElementById('copyJson').addEventListener('click', function() {
                navigator.clipboard.writeText(JSON.stringify(data, null, 2));
                this.textContent = 'Copied!';
                setTimeout(() => {
                    this.textContent = 'Copy JSON';
                }, 2000);
            });
        }
        
        function displayError(message) {
            document.getElementById('errorState').classList.remove('hidden');
            document.getElementById('errorMessage').textContent = message;
        }
    </script>
</body>
</html>
