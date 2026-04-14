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

    <!-- NOTIFIKASI -->
    <div id="notifikasiAntrean"
        class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-2xl hidden z-50 animate-bounce">
        <p class="font-bold text-lg">Antrean Baru Masuk!</p>
        <p id="notifikasiTeks" class="text-sm mt-1">-</p>
    </div>

    <!-- CARD LOKET -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-10 mt-10">

        <!-- LOKET 1 -->
        <div class="bg-blue-600 rounded-2xl p-6 shadow-lg">
            <h3>LOKET 1</h3>
            <h2 class="text-xl font-bold">TAMU DINAS</h2>
            <div class="text-5xl my-6" id="loket1">A-000</div>
            <button onclick="nextQueue(1)" class="btn bg-white text-blue-600 w-full py-3 rounded-xl font-bold">
                PANGGIL BERIKUTNYA
            </button>
        </div>

        <!-- LOKET 2 -->
        <div class="bg-green-600 rounded-2xl p-6 shadow-lg">
            <h3>LOKET 2</h3>
            <h2 class="text-xl font-bold">KUNJUNGAN</h2>
            <div class="text-5xl my-6" id="loket2">B-000</div>
            <button onclick="nextQueue(2)" class="btn bg-white text-green-600 w-full py-3 rounded-xl font-bold">
                PANGGIL BERIKUTNYA
            </button>
        </div>

        <!-- LOKET 3 -->
        <div class="bg-orange-500 rounded-2xl p-6 shadow-lg">
            <h3>LOKET 3</h3>
            <h2 class="text-xl font-bold">INFORMASI</h2>
            <div class="text-5xl my-6" id="loket3">C-000</div>
            <button onclick="nextQueue(3)" class="btn bg-white text-orange-600 w-full py-3 rounded-xl font-bold">
                PANGGIL BERIKUTNYA
            </button>
        </div>

        <!-- LOKET 4 -->
        <div class="bg-red-600 rounded-2xl p-6 shadow-lg">
            <h3>LOKET 4</h3>
            <h2 class="text-xl font-bold">PENGADUAN</h2>
            <div class="text-5xl my-6" id="loket4">D-000</div>
            <button onclick="nextQueue(4)" class="btn bg-white text-red-600 w-full py-3 rounded-xl font-bold">
                PANGGIL BERIKUTNYA
            </button>
        </div>

    </div>

    <script>
        // DATA LOKET SEKARANG
        let currentQueue = {
            1: { nomor: 'A-000', kode: 'A' },
            2: { nomor: 'B-000', kode: 'B' },
            3: { nomor: 'C-000', kode: 'C' },
            4: { nomor: 'D-000', kode: 'D' }
        };

        // ANTRIAN MASUK
        let queues = {
            A: [],
            B: [],
            C: [],
            D: []
        };

        // LOAD STORAGE
        const savedQueue = localStorage.getItem('currentQueue');
        if (savedQueue) {
            currentQueue = JSON.parse(savedQueue);
        }

        updateLoketDisplay();
        updateButtonState();

        function saveCurrentQueue() {
            localStorage.setItem('currentQueue', JSON.stringify(currentQueue));
        }

        function updateLoketDisplay() {
            for (let i = 1; i <= 4; i++) {
                document.getElementById('loket' + i).innerText = currentQueue[i].nomor;
            }
        }

        function nextQueue(loket) {
            const kode = currentQueue[loket].kode;

            if (queues[kode].length === 0) return;

            const next = queues[kode].shift();

            currentQueue[loket].nomor = next.nomor;
            saveCurrentQueue();
            updateLoketDisplay();

            localStorage.setItem('panggilanBaru', JSON.stringify(next));

            speak(`Nomor ${next.nomor}, silakan ke loket ${loket}`);

            updateButtonState();
            hideNotification();
        }

        function updateButtonState() {
            for (let i = 1; i <= 4; i++) {
                const kode = currentQueue[i].kode;
                const btn = document.querySelector(`button[onclick="nextQueue(${i})"]`);

                if (queues[kode].length === 0) {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
        }

        // LISTENER ANTREAN MASUK
        window.addEventListener('storage', (e) => {
            if (e.key === 'queueBaru' && e.newValue) {
                const data = JSON.parse(e.newValue);

                queues[data.kode].push(data);

                showNotification(data);
                updateButtonState();
            }
        });

        function showNotification(data) {
            const notif = document.getElementById('notifikasiAntrean');
            const teks = document.getElementById('notifikasiTeks');

            teks.innerText = `Loket ${data.loket}: ${data.nomor} (${data.layanan})`;
            notif.classList.remove('hidden');

            playNotificationSound();
        }

        function hideNotification() {
            document.getElementById('notifikasiAntrean').classList.add('hidden');
        }

        function playNotificationSound() {
            const utter = new SpeechSynthesisUtterance('Antrean baru masuk');
            utter.lang = 'id-ID';
            speechSynthesis.speak(utter);
        }

        function speak(text) {
            const utter = new SpeechSynthesisUtterance(text);
            utter.lang = 'id-ID';
            speechSynthesis.speak(utter);
        }

        // JAM
        function updateJam() {
            const now = new Date();
            document.getElementById('jam').innerText = now.toLocaleTimeString('id-ID');
            document.getElementById('tanggal').innerText = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        setInterval(updateJam, 1000);
        updateJam();
    </script>

</body>
</html>