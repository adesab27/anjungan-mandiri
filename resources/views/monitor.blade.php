<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrean - Lapas Pekalongan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Inter:wght@400;700;900&display=swap');
        
        body { font-family: 'Inter', sans-serif; overflow: hidden; background-color: #0f172a; }
        .digital-font { font-family: 'Orbitron', sans-serif; }
        
        /* Animasi berkedip saat ada panggilan baru */
        .panggil-animasi {
            animation: pulse-bg 1s infinite;
        }

        @keyframes pulse-bg {
            0% { background-color: #1e3a8a; }
            50% { background-color: #3b82f6; }
            100% { background-color: #1e3a8a; }
        }

        .running-text {
            white-space: nowrap;
            display: inline-block;
            animation: marquee 20s linear infinite;
        }

        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
    </style>
</head>
<body class="text-white">

    <!-- TOP BAR -->
    <header class="bg-blue-900 p-4 shadow-xl border-b-4 border-amber-500 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <img src="Logo_Kementrian_Imigrasi_dan_Pemasyarakatan_(2024).png" alt="Logo" class="h-16">
            <div>
                <h1 class="text-3xl font-black tracking-tighter">LAPAS KELAS IIA PEKALONGAN</h1>
                <p class="text-amber-400 font-bold italic">"BERBAKTI NYATA, PRIMA MELAYANI"</p>
            </div>
        </div>
        <div class="text-right">
            <h2 id="clock" class="text-4xl font-black digital-font text-amber-400">00:00:00</h2>
            <p id="date" class="text-lg font-bold"></p>
        </div>
    </header>

    <!-- MIDDLE CONTENT -->
    <div class="grid grid-cols-12 gap-4 p-4" style="height: 65vh;">
        
        <!-- PANGGILAN BESAR (KIRI) -->
        <div id="panggilanUtama" class="col-span-4 bg-slate-800 rounded-3xl border-4 border-blue-600 flex flex-col items-center justify-center shadow-2xl">
            <h3 id="loketLabel" class="text-4xl font-black mb-4 uppercase">LOKET -</h3>
            <div class="w-full bg-blue-900 py-4 text-center border-y-4 border-amber-500">
                <span class="text-2xl font-bold opacity-80 uppercase tracking-widest">Nomor Antrean</span>
            </div>
            <h2 id="nomorBesar" class="text-[8rem] font-black leading-none my-4 text-amber-400">---</h2>
        </div>

        <!-- VIDEO / INFORMASI (TENGAH/KANAN) -->
        <div class="col-span-8 bg-black rounded-3xl border-4 border-slate-700 overflow-hidden shadow-2xl">
            <!-- Ganti ID Video YouTube ini dengan video profil Lapas Anda -->
            <iframe width="100%" height="100%" 
                src="https://www.youtube.com/embed/P_P_K50pWWA?autoplay=1&mute=1&loop=1&playlist=P_P_K50pWWA" 
                title="Video Profile" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
            </iframe>
        </div>
    </div>

    <!-- BOTTOM LIST (HISTORY) -->
    <div class="grid grid-cols-4 gap-4 px-4 h-[18vh]">
        <div class="bg-blue-800 rounded-2xl p-4 border-b-8 border-blue-950 text-center">
            <p class="text-sm font-bold opacity-80 uppercase">Loket 1</p>
            <h4 id="histA" class="text-5xl font-black">A-000</h4>
        </div>
        <div class="bg-emerald-800 rounded-2xl p-4 border-b-8 border-emerald-950 text-center">
            <p class="text-sm font-bold opacity-80 uppercase">Loket 2</p>
            <h4 id="histB" class="text-5xl font-black">B-000</h4>
        </div>
        <div class="bg-amber-700 rounded-2xl p-4 border-b-8 border-amber-900 text-center">
            <p class="text-sm font-bold opacity-80 uppercase">Loket 3</p>
            <h4 id="histC" class="text-5xl font-black">C-000</h4>
        </div>
        <div class="bg-rose-800 rounded-2xl p-4 border-b-8 border-rose-950 text-center">
            <p class="text-sm font-bold opacity-80 uppercase">Loket 4</p>
            <h4 id="histD" class="text-5xl font-black">D-000</h4>
        </div>
    </div>

    <!-- RUNNING TEXT -->
    <footer class="fixed bottom-0 w-full bg-slate-900 border-t-4 border-amber-500 p-3">
        <div class="overflow-hidden">
            <p class="running-text text-2xl font-bold uppercase tracking-wide">
                Selamat Datang di Lembaga Pemasyarakatan Kelas IIA Pekalongan • Dilarang Memberikan Gratifikasi dalam bentuk apapun kepada Petugas kami • Pelayanan pendaftaran dibuka pukul 08.00 s/d 12.00 WIB • Budayakan Mengantre dengan Tertib •
            </p>
        </div>
    </footer>

    <script>
        // Update Jam
        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID');
            document.getElementById('date').innerText = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        }, 1000);

        // MENDENGARKAN DATA DARI PETUGAS
        window.addEventListener('storage', (e) => {
            if (e.key === 'panggilanBaru') {
                const data = JSON.parse(e.newValue);
                panggilNomor(data.nomor, data.loket, data.layanan);
            }
        });

        function panggilNomor(nomor, loket, layanan) {
            const container = document.getElementById('panggilanUtama');
            
            // Efek visual
            container.classList.add('panggil-animasi');
            document.getElementById('nomorBesar').innerText = nomor;
            document.getElementById('loketLabel').innerText = `LOKET ${loket}`;
            
            // Update history di bawah - mapping berdasarkan loket
            const histMap = { 1: 'histA', 2: 'histB', 3: 'histC', 4: 'histD' };
            document.getElementById(histMap[loket]).innerText = nomor;

            // Suara Panggilan
            speak(`Nomor antrean ${nomor}, silakan menuju loket ${loket}`);

            // Berhenti kedip setelah 5 detik
            setTimeout(() => {
                container.classList.remove('panggil-animasi');
            }, 5000);
        }

        function speak(text) {
            const synth = window.speechSynthesis;
            const utter = new SpeechSynthesisUtterance(text);
            utter.lang = 'id-ID';
            utter.rate = 0.8; // Kecepatan suara agar lebih jelas di TV
            synth.speak(utter);
        }
    </script>
</body>
</html>
