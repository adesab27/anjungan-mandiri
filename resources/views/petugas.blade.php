<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Petugas</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#0B1E3A] text-white">

    <!-- HEADER -->
    <div class="flex justify-between items-center px-6 py-4 bg-[#1E3A8A] shadow">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gray-300 rounded"></div>
            <div>
                <h1 class="font-bold text-lg">LEMBAGA PEMASYARAKATAN KELAS IIA PEKALONGAN</h1>
                <p class="text-sm text-blue-200 italic">"BERBAKTI NYATA, PRIMA MELAYANI"</p>
            </div>
        </div>

        <div class="text-right">
            <p id="jam" class="text-xl font-bold"></p>
            <p id="tanggal" class="text-sm"></p>
        </div>
    </div>

    <!-- TITLE -->
    <div class="text-center mt-10">
        <h2 class="text-3xl font-bold">PANEL PETUGAS</h2>
        <p class="text-gray-300 mt-2">Silakan panggil nomor antrian berikutnya</p>
    </div>

    <!-- NOTIFIKASI ANTREAN MASUK -->
    <div id="notifikasiAntrean" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-2xl hidden z-50 animate-bounce">
        <p class="font-bold text-lg">Antrean Baru Masuk!</p>
        <p id="notifikasiTeks" class="text-sm mt-1">-</p>
    </div>

    <!-- CARD LOKET -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-10 mt-10">

        <!-- LOKET 1 -->
        <div class="bg-blue-600 rounded-2xl p-6 shadow-lg">
            <h3 class="text-sm font-semibold opacity-80">LOKET 1</h3>
            <h2 class="text-2xl font-bold mb-2">TAMU DINAS</h2>

            <div class="text-5xl font-bold my-6" id="loket1">A-000</div>

            <button onclick="nextQueue(1)"
                class="bg-white text-blue-600 w-full py-3 rounded-xl font-bold hover:bg-gray-200 transition">
                PANGGIL BERIKUTNYA
            </button>
        </div>

        <!-- LOKET 2 -->
        <div class="bg-green-600 rounded-2xl p-6 shadow-lg">
            <h3 class="text-sm font-semibold opacity-80">LOKET 2</h3>
            <h2 class="text-2xl font-bold mb-2">KUNJUNGAN WARGA BINAAN</h2>

            <div class="text-5xl font-bold my-6" id="loket2">B-000</div>

            <button onclick="nextQueue(2)"
                class="bg-white text-green-600 w-full py-3 rounded-xl font-bold hover:bg-gray-200 transition">
                PANGGIL BERIKUTNYA
            </button>
        </div>

        <!-- LOKET 3 -->
        <div class="bg-orange-500 rounded-2xl p-6 shadow-lg">
            <h3 class="text-sm font-semibold opacity-80">LOKET 3</h3>
            <h2 class="text-2xl font-bold mb-2">LAYANAN INFORMASI</h2>

            <div class="text-5xl font-bold my-6" id="loket3">C-000</div>

            <button onclick="nextQueue(3)"
                class="bg-white text-orange-600 w-full py-3 rounded-xl font-bold hover:bg-gray-200 transition">
                PANGGIL BERIKUTNYA
            </button>
        </div>

        <!-- LOKET 4 -->
        <div class="bg-red-600 rounded-2xl p-6 shadow-lg">
            <h3 class="text-sm font-semibold opacity-80">LOKET 4</h3>
            <h2 class="text-2xl font-bold mb-2">LAPORAN / PENGADUAN</h2>

            <div class="text-5xl font-bold my-6" id="loket4">D-000</div>

            <button onclick="nextQueue(4)"
                class="bg-white text-red-600 w-full py-3 rounded-xl font-bold hover:bg-gray-200 transition">
                PANGGIL BERIKUTNYA
            </button>
        </div>

    </div>

    <!-- SCRIPT -->
    <script>
        // State untuk menyimpan nomor saat ini di setiap loket
        let currentQueue = {
            1: { nomor: 'A-000', kode: 'A' },
            2: { nomor: 'B-000', kode: 'B' },
            3: { nomor: 'C-000', kode: 'C' },
            4: { nomor: 'D-000', kode: 'D' }
        };

        // Initialize dari localStorage jika ada
        const savedQueue = localStorage.getItem('currentQueue');
        if (savedQueue) {
            currentQueue = JSON.parse(savedQueue);
        }
        updateLoketDisplay();

        // Simpan ke localStorage
        function saveCurrentQueue() {
            localStorage.setItem('currentQueue', JSON.stringify(currentQueue));
        }

        // Update tampilan loket
        function updateLoketDisplay() {
            for (let i = 1; i <= 4; i++) {
                document.getElementById('loket' + i).innerText = currentQueue[i].nomor;
            }
        }

        function nextQueue(loket) {
            // Ambil kode loket
            const kode = currentQueue[loket].kode;
            
            // Extract nomor dari nomor saat ini (contoh A-001 -> 001)
            const parts = currentQueue[loket].nomor.split('-');
            let num = parseInt(parts[1]) || 0;
            
            // Increment nomor
            num++;
            const newNumber = `${kode}-${String(num).padStart(3, '0')}`;
            
            // Update state
            currentQueue[loket].nomor = newNumber;
            saveCurrentQueue();
            updateLoketDisplay();

            // KIRIM PERINTAH KE MONITOR
            const callData = {
                nomor: newNumber,
                loket: loket,
                kode: kode,
                waktu: new Date().getTime()
            };
            localStorage.setItem('panggilanBaru', JSON.stringify(callData));

            // Hapus notifikasi setelah dipanggil
            hideNotification();
        }

        // LISTENER UNTUK ANTREAN BARU
        window.addEventListener('storage', (e) => {
            if (e.key === 'queueBaru' && e.newValue) {
                const data = JSON.parse(e.newValue);
                showNotification(data);
            }
        });

        function showNotification(data) {
            const notif = document.getElementById('notifikasiAntrean');
            const notifTeks = document.getElementById('notifikasiTeks');
            
            notifTeks.innerText = `Loket ${data.loket}: ${data.nomor} (${data.layanan})`;
            notif.classList.remove('hidden');
            
            // Notifikasi suara
            playNotificationSound();
        }

        function hideNotification() {
            document.getElementById('notifikasiAntrean').classList.add('hidden');
        }

        function playNotificationSound() {
            const synth = window.speechSynthesis;
            const utter = new SpeechSynthesisUtterance('Antrean baru masuk');
            utter.lang = 'id-ID';
            synth.speak(utter);
        }

        // JAM REALTIME
        function updateJam() {
            const now = new Date();

            const jam = now.toLocaleTimeString('id-ID');
            const tanggal = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            document.getElementById('jam').innerText = jam;
            document.getElementById('tanggal').innerText = tanggal;
        }

        setInterval(updateJam, 1000);
        updateJam();
    </script>

</body>
</html>
