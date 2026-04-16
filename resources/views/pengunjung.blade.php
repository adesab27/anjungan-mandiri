<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Antrean Lapas Pekalongan</title>
    <!-- Tailwind CSS untuk Styling Modern -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .kiosk-card:active { transform: scale(0.95); }

        /* Aturan Khusus Cetak Thermal */
        @media print {
            body * { visibility: hidden; }
            #ticketModal, #ticketModal * { visibility: visible; }
            #ticketModal { 
                position: absolute; left: 0; top: 0; width: 100%; height: fit-content;
                background: white !important; color: black !important;
            }
            .no-print { display: none !important; }
            .bg-white { box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body class="bg-slate-900 text-white min-h-screen flex flex-col">

    <!-- Header Section -->
    <header class="bg-slate-800 p-6 shadow-2xl border-b-4 border-blue-600">
        <div class="container mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="bg-white p-2 rounded-lg">
                    <img src="Logo_Kementrian_Imigrasi_dan_Pemasyarakatan_(2024).png" alt="Logo" class="h-16">
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight uppercase">Lembaga Pemasyarakatan Kelas IIA Pekalongan</h1>
                    <p class="text-blue-400 font-semibold italic">"BERBAKTI NYATA, PRIMA MELAYANI"</p>
                </div>
            </div>
            <div class="text-right">
                <p id="clock" class="text-2xl font-bold"></p>
                <p id="date" class="text-sm opacity-70"></p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-2 uppercase">Pilih Layanan</h2>
            <p class="text-slate-400 text-xl">Silakan tekan tombol di bawah ini sesuai keperluan Anda</p>
        </div>

        <!-- Grid Menu Antrean -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            
            <!-- Loket 1 -->
            <button onclick="generateTicket('A', 'Pendaftaran Tamu Dinas', 1)" 
                class="kiosk-card bg-blue-700 hover:bg-blue-600 p-8 rounded-3xl shadow-xl flex items-center gap-6 transition-all duration-200 border-b-8 border-blue-900">
                <i class="fas fa-briefcase text-4xl"></i>
                <div class="text-left">
                    <span class="block text-sm opacity-80 uppercase tracking-widest font-bold">Loket 1</span>
                    <span class="text-2xl font-black">TAMU DINAS</span>
                </div>
            </button>

            <!-- Loket 2 -->
            <button onclick="generateTicket('B', 'Kunjungan Narapidana', 2)" 
                class="kiosk-card bg-emerald-700 hover:bg-emerald-600 p-8 rounded-3xl shadow-xl flex items-center gap-6 transition-all duration-200 border-b-8 border-emerald-900">
                <i class="fas fa-users text-4xl"></i>
                <div class="text-left">
                    <span class="block text-sm opacity-80 uppercase tracking-widest font-bold">Loket 2</span>
                    <span class="text-2xl font-black">KUNJUNGAN WARGA BINAAN</span>
                </div>
            </button>

            <!-- Loket 3 -->
            <button onclick="generateTicket('C', 'Layanan Informasi', 3)" 
                class="kiosk-card bg-amber-600 hover:bg-amber-500 p-8 rounded-3xl shadow-xl flex items-center gap-6 transition-all duration-200 border-b-8 border-amber-800">
                <i class="fas fa-info-circle text-4xl"></i>
                <div class="text-left">
                    <span class="block text-sm opacity-80 uppercase tracking-widest font-bold">Loket 3</span>
                    <span class="text-2xl font-black">LAYANAN INFORMASI</span>
                </div>
            </button>

            <!-- Loket 4 -->
            <button onclick="generateTicket('D', 'Laporan / Pengaduan', 4)" 
                class="kiosk-card bg-rose-700 hover:bg-rose-600 p-8 rounded-3xl shadow-xl flex items-center gap-6 transition-all duration-200 border-b-8 border-rose-900">
                <i class="fas fa-bullhorn text-4xl"></i>
                <div class="text-left">
                    <span class="block text-sm opacity-80 uppercase tracking-widest font-bold">Loket 4</span>
                    <span class="text-2xl font-black">LAPORAN ATAU PENGADUAN</span>
                </div>
            </button>

        </div>
    </main>

    <!-- Modal Tiket -->
    <div id="ticketModal" class="fixed inset-0 bg-black/90 flex items-center justify-center hidden z-50">
        <div class="bg-white text-slate-900 p-8 rounded-2xl w-80 text-center shadow-2xl">
            <p class="font-bold border-b-2 border-dashed border-slate-300 pb-2 mb-4 uppercase">Lapas Pekalongan</p>
            <p class="text-sm">Nomor Antrean Anda:</p>
            <h3 id="ticketNumber" class="text-7xl font-black my-4 text-black">A-001</h3>
            <p id="serviceName" class="font-bold text-sm mb-4 uppercase"></p>
            <p id="ticketTime" class="text-xs opacity-60 mb-6"></p>
            <div class="border-t-2 border-dashed border-slate-300 pt-4">
                <p class="text-xs italic font-bold uppercase">Berbakti Nyata, Prima Melayani</p>
            </div>
            <!-- Tombol Tutup (Sembunyi saat diprint) -->
            <button onclick="closeModal()" class="no-print mt-8 bg-slate-900 text-white px-6 py-2 rounded-full font-bold w-full">TUTUP</button>
        </div>
    </div>

    <!-- JavaScript Logic -->
    <script>
        // Reset semua data ketika halaman di-refresh
        let counters = { A: 0, B: 0, C: 0, D: 0 };
        localStorage.removeItem('queueCounters');
        localStorage.removeItem('currentQueue');
        localStorage.removeItem('history');
        
        let closeTimer;

        function generateTicket(kode, layanan, loket) {
            // Reset timer jika user klik cepat
            clearTimeout(closeTimer);

            counters[kode]++;
            const num = String(counters[kode]).padStart(3, '0');
            const finalNumber = `${kode}-${num}`;
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID');
            const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

            document.getElementById('ticketNumber').innerText = finalNumber;
            document.getElementById('serviceName').innerText = layanan;
            document.getElementById('ticketTime').innerText = `${dateStr} | ${timeStr}`;

            // Tampilkan modal
            document.getElementById('ticketModal').classList.remove('hidden');

            // Suara AI
            setTimeout(() => {
                speak(`Nomor antrian ${finalNumber}`);
            }, 500);

            // Kirim notifikasi ke petugas
            const queueData = {
                nomor: finalNumber,
                loket: loket,
                layanan: layanan,
                kode: kode,
                waktu: now.getTime()
            };
            localStorage.setItem('queueBaru', JSON.stringify(queueData));

            // 1. Print Otomatis
            setTimeout(() => {
                window.print();
            }, 500);

            // 2. Auto Close 3 Detik
            closeTimer = setTimeout(() => {
                closeModal();
            }, 3000);
        }

        function closeModal() {
            document.getElementById('ticketModal').classList.add('hidden');
        }

        // function speak(text) {
        //     const synth = window.speechSynthesis;

        //     // hentikan suara sebelumnya (biar tidak tumpuk)
        //     synth.cancel();

        //     const utter = new SpeechSynthesisUtterance(text);

        //     utter.lang = 'id-ID';
        //     utter.rate = 0.75;   // lebih lambat (seperti bank)
        //     utter.pitch = 1;   // netral tapi stabil
        //     utter.volume = 1;

        //     // cari voice Indonesia (kalau tersedia)
        //     let voices = synth.getVoices();
        //     let indoVoice = voices.find(v => v.lang.includes('id'));

        //     if (indoVoice) {
        //         utter.voice = indoVoice;
        //     }

        //     synth.speak(utter);
        // }

        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID');
            document.getElementById('date').innerText = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        }, 1000);
    </script>

</body>
</html>
