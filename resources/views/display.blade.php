<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Monitor Masjid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* TEMA WARNA UTAMA */
        :root {
            --primary-green: #6ec321;
            --dark-bg: #000000;
            --card-bg: #121212;
            --border-color: #2a2a2a;
            --text-muted: #a0a0a0;
        }

        body { 
            background: var(--dark-bg); 
            color: white; 
            overflow: hidden; 
            height: 100vh; 
            margin: 0;
            display: flex; 
            flex-direction: column; 
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
        }

        /* CONTAINER UTAMA */
        .display-container { 
            flex: 1; 
            display: flex; 
            padding: 25px; 
            gap: 25px; 
            align-items: stretch; 
            max-height: calc(100vh - 70px); 
            box-sizing: border-box; 
        }

        /* BAGIAN KIRI: VIDEO */
        .content-left { 
            flex: 7; 
            display: flex; 
            flex-direction: column; 
            min-width: 0;
        }

        .video-wrapper { 
            width: 100%; 
            height: 100%; 
            background: #080808; 
            border-radius: 20px; 
            overflow: hidden; 
            position: relative; 
            box-shadow: 0 0 30px rgba(110, 195, 33, 0.1);
            border: 1px solid var(--border-color);
        }

        #player {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
        }

        /* BAGIAN KANAN: JAM & JADWAL */
        .content-right { 
            flex: 3; 
            display: flex; 
            flex-direction: column; 
            justify-content: flex-start; 
            gap: 15px; 
            min-width: 380px;
        }

        .clock { 
            font-size: 6rem; 
            font-weight: 800; 
            line-height: 1; 
            text-align: right;
            color: var(--primary-green);
            text-shadow: 0 0 20px rgba(110, 195, 33, 0.3);
        }

        .date-info { 
            font-size: 1.7rem; 
            color: white; 
            text-align: right; 
            margin-bottom: 5px;
            font-weight: 300;
        }

        .prayer-group { 
            display: flex; 
            flex-direction: column; 
            gap: 12px; 
        }

        .prayer-card { 
            background: var(--card-bg); 
            border-radius: 15px; 
            padding: 18px 25px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border: 1px solid var(--border-color); 
            transition: all 0.3s ease;
        }

        /* SHOLAT AKTIF: MENGGUNAKAN HIJAU #6ec321 */
        .active-sholat { 
            background: var(--primary-green) !important; 
            color: #000 !important; 
            transform: scale(1.03); 
            box-shadow: 0 0 25px rgba(110, 195, 33, 0.4);
            border-color: var(--primary-green);
        }

        .prayer-name { font-size: 1.7rem; font-weight: 500; }
        .prayer-time { font-size: 2rem; font-weight: 800; color: var(--primary-green); }
        .active-sholat .prayer-time, .active-sholat .prayer-name { color: #000 !important; }

        /* FOOTER BAR: HIJAU */
        .footer-bar { 
            height: 70px; 
            background: var(--primary-green); 
            color: #000;
            display: flex; 
            align-items: center; 
            z-index: 1000; 
            box-shadow: 0 -5px 20px rgba(0,0,0,0.5);
        }
        marquee { font-size: 2.3rem; font-weight: 700; }
        
        /* OVERLAY HIJAU */
        #sholat-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: #000; z-index: 9999; display: none; 
            flex-direction: column; justify-content: center; align-items: center; 
        }
        #overlay-title { color: var(--primary-green); text-shadow: 0 0 30px rgba(110, 195, 33, 0.5); }

        /* EXTRA CONTENT BOX */
        .extra-content-box {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .friday-info, .quotes-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 22px;
            border: 1px solid var(--border-color);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .friday-title {
            color: var(--primary-green);
            font-size: 1.5rem;
            font-weight: 800;
            text-align: center;
            border-bottom: 2px solid var(--primary-green);
            padding-bottom: 10px;
            margin-bottom: 18px;
            text-transform: uppercase;
        }

        .petugas-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 1.3rem;
        }

        .petugas-label { color: var(--text-muted); }
        .petugas-name { font-weight: 600; color: white; }

        /* PROGRESS BAR INFAQ HIJAU */
        .progress-bar.bg-success { background-color: var(--primary-green) !important; color: #000; font-weight: bold; }
        .text-warning { color: var(--primary-green) !important; }

        /* Container khusus untuk membungkus Chart dan Progress */
        .infaq-content-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start; /* Mulai dari atas agar tidak terlalu turun */
            height: 100%;
            gap: 20px; /* Kurangi jarak sedikit agar chart bisa lebih tinggi */
        }

        /* Penyesuaian Tinggi Chart agar lebih dominan */
        .chart-container {
            width: 95%;
            height: 500px; /* Tingkatkan tinggi dari 350px ke 500px */
            position: relative;
            margin-top: 10px;
        }

        /* Card Target di bawah dibuat lebih ramping namun tetap lebar */
        .target-card-bottom {
            width: 90%;
            max-width: 900px;
            background: rgba(110, 195, 33, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 20px 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>

    <div id="sholat-overlay" style="display:none; flex-direction:column; justify-content:center; align-items:center; text-align:center; color:white;">
        <h1 id="overlay-title" style="font-size: 5rem; font-weight: bold; color: #6ec321;"></h1>
        <h2 id="overlay-timer" style="font-size: 8rem; font-family: monospace;"></h2>
    </div>

    <div class="display-container">
        <div class="content-left">
            <div id="section-video" class="video-wrapper">
                <div id="player"></div>
            </div>
            
            <div id="section-infaq" class="video-wrapper d-none p-4"> <h2 class="text-center mb-4" style="color: var(--primary-green); font-weight: 800; font-size: 2.8rem;">
                LAPORAN INFAQ MASJID
            </h2>

            <div class="infaq-content-wrapper">
                <div class="chart-container">
                    <canvas id="infaqChart"></canvas>
                </div>

                <div class="target-card-bottom">
                    <div class="progress mb-3" style="height: 40px; background: #1a1a1a; border-radius: 12px; border: 1px solid #333;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                            style="width: {{ $persentase }}%; background-color: var(--primary-green); color: #000; font-weight: 900; font-size: 1.4rem;">
                            {{ number_format($persentase, 1) }}% Pembangunan Masjid
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-baseline">
                        <div class="d-flex align-items-baseline">
                            <span style="color: #aaa; font-size: 1.2rem; margin-right: 10px;">Terkumpul:</span>
                            <h2 class="fw-bold text-white mb-0" style="font-size: 2.5rem;">
                                Rp {{ number_format($totalTerkumpul, 0, ',', '.') }}
                            </h2>
                        </div>
                        <h4 style="color: #666; margin-bottom: 0;">
                            Target: Rp {{ number_format($targetPembangunan, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="content-right">
            <div>
                <div id="clock" class="clock">00:00:00</div>
                <div id="date" class="date-info">Memuat...</div>
            </div>

            <div class="prayer-group">
                <div id="row-subuh" class="prayer-card"><span class="prayer-name">Subuh</span><span id="subuh" class="prayer-time">--:--</span></div>
                <div id="row-dzuhur" class="prayer-card"><span class="prayer-name">Dzuhur</span><span id="dzuhur" class="prayer-time">--:--</span></div>
                <div id="row-ashar" class="prayer-card"><span class="prayer-name">Ashar</span><span id="ashar" class="prayer-time">--:--</span></div>
                <div id="row-maghrib" class="prayer-card"><span class="prayer-name">Maghrib</span><span id="maghrib" class="prayer-time">--:--</span></div>
                <div id="row-isya" class="prayer-card"><span class="prayer-name">Isya</span><span id="isya" class="prayer-time">--:--</span></div>
            </div>

            <div class="extra-content-box">
                @php
                    $hariIni = now()->dayOfWeek; 
                    $tampilkanJadwalJumat = in_array($hariIni, [4, 5]); 
                @endphp

                @if($tampilkanJadwalJumat)
                    <div class="friday-info">
                        <div class="friday-title"><i class="fa-solid fa-users me-2"></i>Petugas Jumat</div>
                        <div class="petugas-row">
                            <span class="petugas-label">Khatib</span>
                            <span class="petugas-name">{{ $settings['khatib_jumat'] ?? 'Dalam Konfirmasi' }}</span>
                        </div>
                        <div class="petugas-row">
                            <span class="petugas-label">Imam</span>
                            <span class="petugas-name">{{ $settings['imam_jumat'] ?? 'Dalam Konfirmasi' }}</span>
                        </div>
                        <div class="petugas-row">
                            <span class="petugas-label">Muadzin</span>
                            <span class="petugas-name">{{ $settings['muadzin_jumat'] ?? 'Dalam Konfirmasi' }}</span>
                        </div>
                    </div>
                @else
                    <div class="quotes-card" style="text-align: center;">
                        <div style="color: var(--primary-green); font-size: 2.5rem; margin-bottom: 15px;">
                            <i class="fas fa-quran"></i>
                        </div>
                        <p id="quote-text" style="font-size: 1.5rem; font-style: italic; line-height: 1.6; color: #fff; font-weight: 300;">
                            "Maka sesungguhnya beserta kesulitan ada kemudahan."
                        </p>
                        <span id="quote-source" style="color: var(--primary-green); font-weight: bold; margin-top: 15px; font-size: 1.1rem;">
                            (QS. Al-Insyirah: 5)
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="footer-bar">
        <marquee scrollamount="8">
            <i class="fa-solid fa-star-and-crescent me-3"></i> 
            {{ $settings['running_text'] ?? 'Selamat Datang di Masjid Al-Ikhlas - Luruskan Shaf - Matikan HP' }}
        </marquee>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
    <script>
        const settings = @json($settings);
        const DURASI_VIDEO = 300000; 
        const DURASI_INFAQ = 60000;  
        let player;
        let isIbadahMode = false;

        // 1. YouTube API
        function onYouTubeIframeAPIReady() {
            player = new YT.Player('player', {
                videoId: '{{ $settings["video_youtube"] ?? "dQw4w9WgXcQ" }}',
                playerVars: { 'autoplay': 1, 'mute': 1, 'controls': 0, 'loop': 1, 'playlist': '{{ $settings["video_youtube"] ?? "dQw4w9WgXcQ" }}' },
                events: { 'onReady': (e) => e.target.playVideo() }
            });
        }

        // 2. Fungsi Jam & Jadwal
        function updateTime() {
            const now = new Date();
            
            // Format jam untuk tampilan di layar (HH:mm:ss)
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID', { hour12: false });
            document.getElementById('date').innerText = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            
            // Format jam untuk pembanding jadwal (HH:mm) -> HARUS SAMA DENGAN FORMAT API
            const jamMenitSekarang = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit', 
                hour12: false 
            }).replace(/\./g, ':'); // Mengubah titik jadi titik dua jika perlu

            if (!isIbadahMode) {
                const daftarShalat = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
                
                daftarShalat.forEach(s => {
                    const waktuJadwal = document.getElementById(s).innerText.trim();
                    
                    // CEK APAKAH WAKTUNYA SAMA
                    if (jamMenitSekarang === waktuJadwal) {
                        jalankanModeAdzan(s);
                    }
                });
            }
        }
        setInterval(updateTime, 1000);

        async function fetchJadwal() {
            const tgl = new Date().toISOString().slice(0, 10).replace(/-/g, '/');
            try {
                const res = await fetch(`https://api.myquran.com/v2/sholat/jadwal/1301/${tgl}`);
                const data = await res.json();
                let j = data.data.jadwal;
                // j.ashar = "12:26";
                // console.log(j);
                ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'].forEach(s => {
                    document.getElementById(s).innerText = j[s];
                });
            } catch (e) { console.error("Gagal ambil jadwal"); }
        }
        fetchJadwal();

        // 3. Logika Slideshow (Didefinisikan dulu)
        let isFirstRun = true;
        function startSlideshow() {
            if (isIbadahMode) {
                setTimeout(startSlideshow, 5000);
                return;
            }
            const videoSec = document.getElementById('section-video');
            const infaqSec = document.getElementById('section-infaq');
            let durasiBerikutnya = DURASI_VIDEO;

            if (isFirstRun) {
                isFirstRun = false; 
                durasiBerikutnya = videoSec.classList.contains('d-none') ? DURASI_INFAQ : DURASI_VIDEO;
            } else {
                if (videoSec.classList.contains('d-none')) {
                    // --- LOGIKA REFRESH OTOMATIS ---
                    // Saat posisi di Infaq dan akan pindah ke Video, 
                    // kita segarkan halaman agar data terbaru dari DB ditarik kembali.
                    window.location.reload(); 
                    return; // Berhenti di sini, biarkan browser memuat ulang halaman
                    
                } else {
                    // LOGIKA PINDAH DARI VIDEO KE INFAQ
                    videoSec.classList.add('d-none');
                    infaqSec.classList.remove('d-none');
                    if(player && typeof player.pauseVideo === 'function') player.pauseVideo();
                    durasiBerikutnya = DURASI_INFAQ;
                }
            }

            setTimeout(startSlideshow, durasiBerikutnya);
        }

        // 4. Jalankan semua saat dokumen siap
        document.addEventListener("DOMContentLoaded", function() {
            // Jalankan Slideshow
            startSlideshow();

            // Quotes Logic
            const listQuotes = [
                { teks: "Maka sesungguhnya beserta kesulitan ada kemudahan.", sumber: "QS. Al-Insyirah: 5" },
                { teks: "Perumpamaan orang beriman dalam kasih sayang bagaikan satu tubuh.", sumber: "HR. Muslim" },
                { teks: "Sebaik-baik manusia adalah yang paling bermanfaat bagi orang lain.", sumber: "HR. Ahmad" },
                { teks: "Barangsiapa membangun masjid karena Allah, Allah bangunkan rumah di surga.", sumber: "HR. Muslim" },
                { teks: "Shalat adalah tiang agama, barangsiapa mendirikannya maka ia mendirikan agama.", sumber: "Hadits" }
            ];
            const quoteText = document.getElementById('quote-text');
            const quoteSource = document.getElementById('quote-source');
            if (quoteText) {
                const index = new Date().getDate() % listQuotes.length;
                quoteText.innerText = `"${listQuotes[index].teks}"`;
                quoteSource.innerText = `(${listQuotes[index].sumber})`;
            }
        });

        // 5. Fungsi Cek Waktu Shalat
        function checkPrayerTime(now) {
            // Ambil jam & menit sekarang (Contoh: "12:05")
            const currentTime = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit', 
                hour12: false 
            });

            // Daftar ID elemen jadwal di HTML Anda
            const jadwalIds = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];

            jadwalIds.forEach(id => {
                const elemenJadwal = document.getElementById(id);
                if (elemenJadwal) {
                    const waktuJadwal = elemenJadwal.innerText.trim();

                    // JIKA JAM SEKARANG = JADWAL SHALAT
                    if (currentTime === waktuJadwal && !isIbadahMode) {
                        console.log("WAKTUNYA ADZAN: " + id);
                        jalankanModeAdzan(id); // Panggil tampilan adzan
                    }
                }
            });
        }

        // 6. Fungsi Tampilan Saat Adzan
        function jalankanModeAdzan(namaShalat) {
            console.log("Mode Adzan Aktif");
            isIbadahMode = true; 

            // 1. Tampilkan Overlay Hitam Full
            const overlay = document.getElementById('sholat-overlay');
            overlay.style.display = 'flex';
            overlay.style.position = 'fixed';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100vw';
            overlay.style.height = '100vh';
            overlay.style.backgroundColor = 'black';
            overlay.style.zIndex = '99999';

            // 2. Tampilkan pesan ADZAN selama 2 menit (120000 ms)
            document.getElementById('overlay-title').innerText = "ADZAN " + namaShalat.toUpperCase();
            document.getElementById('overlay-timer').innerText = "Dikumandangkan...";
            
            if(player && typeof player.pauseVideo === 'function') player.pauseVideo();

            // 3. JEDA 2 MENIT: Lalu mulai Hitung Mundur Iqamah
            setTimeout(() => {
                // Ambil durasi iqamah dari settings (jika ada) atau default 10 menit
                const durasiIqamah = settings['iqamah_' + namaShalat.toLowerCase()] || 10;
                startIqamahTimer(durasiIqamah);
            }, 120000); // 120.000 ms = 2 Menit
        }
        function startIqamahTimer(menit) {
            let totalDetik = menit * 60;
            const timerElement = document.getElementById('overlay-timer');
            const titleElement = document.getElementById('overlay-title');

            titleElement.innerText = "MENUJU IQAMAH";
            
            const interval = setInterval(() => {
                let m = Math.floor(totalDetik / 60);
                let s = totalDetik % 60;

                // Tampilkan format 00:00
                timerElement.innerText = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;

                if (totalDetik <= 0) {
                    clearInterval(interval);
                    // Fase Shalat: Layar Gelap Total
                    titleElement.innerText = "SHALAT BERJAMAAH";
                    timerElement.innerText = "Luruskan & Rapatkan Shaf";
                    
                    // Setelah 15 menit shalat, layar otomatis kembali ke Video
                    setTimeout(() => {
                        kembaliKeNormal();
                    }, 900000); // 15 menit durasi shalat
                }
                totalDetik--;
            }, 1000);
        }

        // Fungsi pembantu untuk mereset layar ke Video/Infaq
        function kembaliKeNormal() {
            isIbadahMode = false;
            const overlay = document.getElementById('sholat-overlay');
            overlay.style.display = 'none';
            
            document.getElementById('section-video').classList.remove('d-none');
            if(player && typeof player.playVideo === 'function') player.playVideo();
        }
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('infaqChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                datasets: [{
                    // Mengambil data dari variabel PHP $infaqMingguan
                    data: @json($infaqMingguan), 
                    backgroundColor: '#6ec321',
                    borderRadius: 12,
                    barPercentage: 0.6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,               // Mulai dari 0
                        max: 5000000,         // Maksimal 5 Juta
                        ticks: { 
                            stepSize: 500000, // Kenaikan setiap 500.000
                            color: '#6ec321', 
                            font: { size: 12, weight: 'bold' },
                            callback: function(value) { 
                                // Format tampilan: 0, 500rb, 1jt, 1.5jt, dst.
                                if (value === 0) return '0';
                                if (value >= 1000000) {
                                    return (value / 1000000).toFixed(1).replace('.0', '') + ' Jt';
                                }
                                return (value / 1000) + ' Rb';
                            }
                        },
                        grid: { 
                            display: true,
                            color: 'rgba(110, 195, 33, 0.15)', // Hijau transparan
                            borderDash: [5, 5],               // Garis putus-putus
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            color: '#fff', 
                            font: { size: 16, weight: 'bold' } 
                        }
                    }
                }
            }
        });
    });
    </script>
    <script>
        function sendPing() {
            fetch("{{ route('monitor.ping') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                }
            })
            .then(res => console.log("Laporan terkirim ke Admin"))
            .catch(e => console.log("Gagal lapor: Monitor Offline/RTO"));
        }
        sendPing();
        setInterval(sendPing, 30000);
    </script>
</body>
</html>