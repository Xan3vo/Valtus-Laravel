@extends('layouts.app')
@section('title', 'Tes Spreadsheet Order')
@section('body')

<main class="max-w-4xl mx-auto px-6 py-10">
    <div class="rounded-xl border border-white/10 bg-white/5 p-6">
        <div class="text-white text-xl font-semibold">Tes Kirim Order ke Spreadsheet</div>
        <div class="mt-2 text-white/70 text-sm">Endpoint: <code class="text-white/90">/tes/orderan/1102230</code></div>

        <div class="mt-4 grid gap-3 text-sm">
            <div class="rounded-lg border border-white/10 bg-white/5 p-4">
                <div class="text-white/80 font-medium">Status Konfigurasi</div>
                <div class="mt-2 text-white/70">
                    Spreadsheet enabled: <span class="text-white/90 font-medium">{{ $spreadsheetEnabled ? 'YES' : 'NO' }}</span>
                </div>
                <div class="text-white/70 break-all">Spreadsheet URL: <span class="text-white/90">{{ $spreadsheetUrl ?: '-' }}</span></div>
                <div class="text-white/70 break-all">Script URL: <span class="text-white/90">{{ $scriptUrl ?: '-' }}</span></div>
            </div>

            <div class="rounded-lg border border-white/10 bg-white/5 p-4">
                <div class="text-white/80 font-medium">Parameter Order</div>
                <div class="mt-3 grid sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-white/60 text-xs mb-1">Purchase Method</label>
                        <select id="purchase_method" class="w-full rounded-lg bg-gray-900/60 border border-white/10 px-3 py-2 text-white">
                            <option value="gamepass">gamepass</option>
                            <option value="group">group</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-white/60 text-xs mb-1">Amount (Robux)</label>
                        <input id="amount" type="number" min="1" value="1000" class="w-full rounded-lg bg-gray-900/60 border border-white/10 px-3 py-2 text-white" />
                    </div>
                    <div>
                        <label class="block text-white/60 text-xs mb-1">Username (opsional)</label>
                        <input id="username" type="text" value="testuser" class="w-full rounded-lg bg-gray-900/60 border border-white/10 px-3 py-2 text-white" />
                    </div>
                    <div>
                        <label class="block text-white/60 text-xs mb-1">Email (opsional)</label>
                        <input id="email" type="email" value="test@example.com" class="w-full rounded-lg bg-gray-900/60 border border-white/10 px-3 py-2 text-white" />
                    </div>
                </div>

                <div class="mt-4 grid sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-white/60 text-xs mb-1">Jumlah order</label>
                        <input id="count" type="number" min="1" value="10" class="w-full rounded-lg bg-gray-900/60 border border-white/10 px-3 py-2 text-white" />
                    </div>
                    <div>
                        <label class="block text-white/60 text-xs mb-1">Concurrency (berapa request barengan)</label>
                        <input id="concurrency" type="number" min="1" value="5" class="w-full rounded-lg bg-gray-900/60 border border-white/10 px-3 py-2 text-white" />
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <button id="btnRun" class="px-4 py-2 rounded-lg bg-emerald-500/20 border border-emerald-500/30 text-emerald-200 hover:bg-emerald-500/25 transition text-sm">Jalankan Tes</button>
                    <button id="btnClear" class="px-4 py-2 rounded-lg bg-white/5 border border-white/10 text-white/80 hover:bg-white/10 transition text-sm">Clear Log</button>
                </div>

                <div class="mt-4 text-white/60 text-xs">
                    Catatan: tes ini bikin order baru di database dengan status <code class="text-white/80">Completed</code>, lalu langsung call Apps Script untuk append.
                </div>
            </div>

            <div class="rounded-lg border border-white/10 bg-white/5 p-4">
                <div class="text-white/80 font-medium">Hasil</div>
                <div class="mt-2 text-white/60 text-xs" id="summary">-</div>
                <div class="mt-3 overflow-auto max-h-96">
                    <pre id="log" class="text-xs text-white/80 whitespace-pre-wrap"></pre>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
(function(){
    const hash = '1102230';
    const btnRun = document.getElementById('btnRun');
    const btnClear = document.getElementById('btnClear');
    const logEl = document.getElementById('log');
    const summaryEl = document.getElementById('summary');

    function appendLog(line){
        logEl.textContent += line + "\n";
    }

    function setSummary(text){
        summaryEl.textContent = text;
    }

    async function sendOne(i){
        const payload = {
            purchase_method: document.getElementById('purchase_method').value,
            amount: parseInt(document.getElementById('amount').value || '0', 10),
            username: document.getElementById('username').value,
            email: document.getElementById('email').value,
        };

        const resp = await fetch(`/tes/orderan/${hash}/create-and-send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        });

        const data = await resp.json().catch(() => null);
        if (!resp.ok) {
            throw new Error(`HTTP ${resp.status}: ${(data && (data.message || data.error)) || 'unknown error'}`);
        }
        return data;
    }

    async function runPool(total, concurrency){
        let ok = 0;
        let fail = 0;
        const startedAt = Date.now();

        const tasks = Array.from({length: total}, (_, idx) => idx + 1);
        let cursor = 0;

        async function worker(workerId){
            while (cursor < tasks.length) {
                const current = tasks[cursor++];
                try {
                    const res = await sendOne(current);
                    const orderId = res?.order?.order_id || '-';
                    if (res?.sent) {
                        ok++;
                        appendLog(`[${workerId}] OK  #${current} order_id=${orderId}`);
                    } else {
                        fail++;
                        appendLog(`[${workerId}] FAIL#${current} order_id=${orderId} sent=false error=${res?.error || '-'}`);
                    }
                } catch (e) {
                    fail++;
                    appendLog(`[${workerId}] ERROR#${current} ${e.message}`);
                }
                setSummary(`progress: ${ok+fail}/${total} | ok=${ok} fail=${fail}`);
            }
        }

        const workers = [];
        for (let w = 1; w <= concurrency; w++) {
            workers.push(worker(w));
        }
        await Promise.all(workers);

        const ms = Date.now() - startedAt;
        setSummary(`DONE | total=${total} ok=${ok} fail=${fail} | elapsed=${Math.round(ms/100)/10}s`);
    }

    btnRun.addEventListener('click', async function(){
        const total = Math.max(1, parseInt(document.getElementById('count').value || '1', 10));
        const conc = Math.max(1, parseInt(document.getElementById('concurrency').value || '1', 10));

        btnRun.disabled = true;
        btnRun.style.opacity = '0.6';

        appendLog(`--- RUN total=${total} concurrency=${conc} ---`);
        setSummary('starting...');

        try {
            await runPool(total, conc);
        } finally {
            btnRun.disabled = false;
            btnRun.style.opacity = '1';
        }
    });

    btnClear.addEventListener('click', function(){
        logEl.textContent = '';
        setSummary('-');
    });
})();
</script>
@endsection
