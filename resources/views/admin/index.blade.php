<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Monitor Masjid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        :root {
            --primary-green: #6ec321;
            --dark-green: #58a01a;
        }

        body { font-family: 'Inter', sans-serif; background-color: #f4f7f6; color: #333; }
        
        /* Navbar dengan aksen hijau */
        .navbar { background: #1a1c1e; border-bottom: 3px solid var(--primary-green); box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        /* Card Styling */
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px; overflow: hidden; }
        .card-header { background: white; border-bottom: 1px solid #edf2f7; font-weight: 700; padding: 1.25rem; }
        
        /* Form Styling */
        .form-label { font-weight: 600; color: #4a5568; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .input-group-text { background-color: #f8f9fa; border-right: none; color: var(--primary-green); }
        .form-control { border-radius: 8px; border-left: 1px solid #dee2e6; }
        .form-control:focus { box-shadow: 0 0 0 0.25rem rgba(110, 195, 33, 0.25); border-color: var(--primary-green); }
        
        /* Button Styling */
        .btn-primary { background: var(--primary-green); border: none; padding: 12px 25px; border-radius: 10px; font-weight: 700; transition: all 0.3s; }
        .btn-primary:hover { background: var(--dark-green); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(110, 195, 33, 0.3); }
        
        /* Status Monitor */
        #statusIndicator { transition: all 0.5s; }
        .bg-success-soft { background: rgba(110, 195, 33, 0.1); color: var(--primary-green); border: 1px solid var(--primary-green); }
        
        /* Live Preview Styling */
        .preview-box { background: #000; border-radius: 10px; padding: 15px; border: 2px dashed var(--primary-green); }
        
        .icon-size { width: 18px; height: 18px; margin-right: 8px; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark mb-4 p-3">
    <div class="container">
        <span class="navbar-brand mb-0 h1 d-flex align-items-center">
            <i data-lucide="layout-dashboard" class="me-2 text-success"></i> 
            PANEL KONTROL <span class="text-success ms-2" style="color: var(--primary-green) !important;">MASJID</span>
        </span>
        
        <div class="d-flex align-items-center">
            <div id="statusIndicator" class="me-3 d-flex align-items-center bg-dark px-3 py-2 rounded-pill border border-secondary">
                <div id="statusLamp" class="spinner-grow spinner-grow-sm text-secondary me-2" role="status" style="width: 10px; height: 10px;"></div>
                <small id="statusText" class="fw-bold text-secondary">MENGECEK...</small>
            </div>

            <a href="/display" target="_blank" class="btn btn-outline-light btn-sm px-3 rounded-pill">
                <i data-lucide="external-link" class="icon-size"></i> Lihat Monitor
            </a>
        </div>
    </div>
</nav>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-left: 5px solid var(--primary-green) !important;">
            <div class="d-flex align-items-center">
                <i data-lucide="check-circle" class="me-2"></i> 
                <strong>Berhasil!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.update') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span><i data-lucide="info" class="text-success me-2"></i> KONTEN MONITOR</span>
                        <span class="badge bg-success-soft">Aktif</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label">Teks Berjalan (Running Text)</label>
                            <textarea id="inputRunningText" name="running_text" class="form-control" rows="3" 
                                    placeholder="Masukkan pengumuman atau hadits...">{{ $settings['running_text'] ?? '' }}</textarea>
                            
                            <div class="mt-3 preview-box">
                                <small class="text-muted d-block mb-1" style="font-size: 0.7rem;">LIVE PREVIEW DI MONITOR:</small>
                                <marquee id="previewText" style="color: var(--primary-green); font-size: 1.4rem; font-weight: bold;">
                                    {{ $settings['running_text'] ?? 'Teks anda akan muncul di sini...' }}
                                </marquee>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <label class="form-label">ID Video YouTube</label>
                            <div class="input-group">
                                <span class="input-group-text"><i data-lucide="youtube"></i></span>
                                <input type="text" name="video_youtube" class="form-control" 
                                    placeholder="Contoh: dQw4w9WgXcQ" 
                                    value="{{ $settings['video_youtube'] ?? '' }}">
                            </div>
                            <div class="form-text mt-2 text-muted">
                                <i data-lucide="help-circle" class="icon-size"></i> 
                                Ambil kode setelah tanda <b>v=</b> pada link YouTube.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header {{ $perluUpdate ? 'bg-warning text-dark' : 'bg-success text-white' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar-check me-2"></i> JADWAL PETUGAS JUMAT</span>
                            <span class="badge {{ $perluUpdate ? 'bg-dark' : 'bg-light text-dark' }}">
                                {{ $perluUpdate ? 'Waktunya Update' : 'Data Pekan Ini' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4 {{ $perluUpdate ? 'bg-light-warning' : '' }}">
                        @if($perluUpdate)
                        <div class="alert alert-warning border-0 small mb-4">
                            <strong>Perhatian:</strong> Hari ini hari <b>{{ now()->translatedFormat('l') }}</b>. Pastikan anda mengisi nama petugas untuk hari Jumat mendatang.
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nama Khatib</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-microphone"></i></span>
                                    <input type="text" name="khatib_jumat" class="form-control" 
                                        value="{{ $settings['khatib_jumat'] ?? '' }}" placeholder="Ustadz...">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nama Imam</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" name="imam_jumat" class="form-control" 
                                        value="{{ $settings['imam_jumat'] ?? '' }}" placeholder="KH. ...">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nama Muadzin</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-volume-up"></i></span>
                                    <input type="text" name="muadzin_jumat" class="form-control" 
                                        value="{{ $settings['muadzin_jumat'] ?? '' }}" placeholder="Ust. ...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-dark text-white">
                        <span><i class="fas fa-wallet text-success me-2"></i> LAPORAN KEUANGAN & TARGET</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Target Pembangunan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white fw-bold">Rp</span>
                                    <input type="text" class="form-control input-rupiah" 
                                        value="{{ number_format($settings['target_pembangunan'] ?? 0, 0, ',', '.') }}" 
                                        placeholder="Contoh: 100.000.000">
                                    <input type="hidden" name="target_pembangunan" id="target_pembangunan_raw" 
                                        value="{{ $settings['target_pembangunan'] ?? 0 }}">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Total Infaq Terkumpul</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white fw-bold">Rp</span>
                                    <input type="text" class="form-control input-rupiah" 
                                        value="{{ number_format($settings['total_infaq_terkumpul'] ?? 0, 0, ',', '.') }}" 
                                        placeholder="0">
                                    <input type="hidden" name="total_infaq_terkumpul" id="total_infaq_terkumpul_raw" 
                                        value="{{ $settings['total_infaq_terkumpul'] ?? 0 }}">
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                        <label class="form-label">Data Grafik Mingguan (Bar Chart)</label>
                        <div class="row">
                            @for($i = 1; $i <= 4; $i++)
                            <div class="col-md-3 mb-2">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white">Mgu {{ $i }}</span>
                                    <input type="text" class="form-control input-rupiah" 
                                        value="{{ number_format($settings['infaq_minggu_'.$i] ?? 0, 0, ',', '.') }}" 
                                        placeholder="0">
                                    <input type="hidden" name="infaq_minggu_{{ $i }}" 
                                        value="{{ $settings['infaq_minggu_'.$i] ?? 0 }}">
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <i data-lucide="timer" class="text-success me-2"></i> JEDA IQAMAH
                    </div>
                    <div class="card-body p-4">
                        @php $prayers = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya']; @endphp
                        @foreach($prayers as $p)
                        <div class="mb-3">
                            <label class="form-label">{{ $p }} (Menit)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i data-lucide="clock" class="icon-size"></i></span>
                                <input type="number" name="iqamah_{{ strtolower($p) }}" class="form-control" 
                                       value="{{ $settings['iqamah_'.strtolower($p)] ?? 10 }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 shadow d-flex align-items-center justify-content-center">
                    <i data-lucide="save" class="me-2"></i> SIMPAN SEMUA DATA
                </button>
                
                <p class="text-center mt-3 text-muted small">
                    <i data-lucide="info" class="icon-size"></i> Perubahan akan langsung tampil di monitor setelah disimpan.
                </p>
            </div>
        </div>
    </form>

    <footer class="text-center my-5 text-muted small">
        &copy; 2026 Management System <strong>Masjid Al-Ikhlas</strong><br>
        <span class="text-success" style="color: var(--primary-green) !important;">Online Monitoring Board v2.0</span>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    lucide.createIcons();

    // Live Preview Logic
    const inputArea = document.getElementById('inputRunningText');
    const previewMarquee = document.getElementById('previewText');

    inputArea.addEventListener('input', function() {
        previewMarquee.innerText = this.value.trim() === "" ? "Teks anda akan muncul di sini..." : this.value;
    });

    // Monitor Status Logic
    function checkMonitorStatus() {
        fetch('/monitor-status-check')
            .then(response => response.json())
            .then(data => {
                const lamp = document.getElementById('statusLamp');
                const text = document.getElementById('statusText');
                const indicator = document.getElementById('statusIndicator');

                if (data.is_online) {
                    lamp.className = "spinner-grow spinner-grow-sm text-success me-2";
                    text.className = "fw-bold text-success";
                    text.innerText = "MONITOR ONLINE";
                    indicator.style.borderColor = "#6ec321";
                } else {
                    lamp.className = "spinner-grow spinner-grow-sm text-danger me-2";
                    text.className = "fw-bold text-danger";
                    text.innerText = "MONITOR OFFLINE";
                    indicator.style.borderColor = "#dc3545";
                }
            })
            .catch(err => {
                console.log("Status check failed");
            });
    }

    setInterval(checkMonitorStatus, 15000);
    checkMonitorStatus();
</script>
<script>
    document.querySelectorAll('.input-rupiah').forEach(function(input) {
        // Fungsi untuk format saat pertama kali halaman dimuat (jika ada nilai awal)
        formatRupiah(input);

        input.addEventListener('keyup', function(e) {
            formatRupiah(this);
        });
    });

    function formatRupiah(element) {
        let value = element.value.replace(/[^,\d]/g, '').toString();
        let split = value.split(',');
        let sisa  = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        element.value = rupiah;

        // Update nilai pada input hidden (angka murni tanpa titik)
        // Kita ambil elemen input tepat setelah input text ini
        let hiddenInput = element.nextElementSibling;
        if (hiddenInput && hiddenInput.type === 'hidden') {
            hiddenInput.value = value.replace(/\./g, '');
        }
    }
</script>
</body>
</html>