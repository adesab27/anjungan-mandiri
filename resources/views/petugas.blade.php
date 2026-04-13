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
        function nextQueue(loket) {
            fetch(`/next-queue/${loket}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('loket' + loket).innerText = data.nomor;
            });
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