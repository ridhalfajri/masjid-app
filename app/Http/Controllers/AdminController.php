<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class AdminController extends Controller
{
    public function display()
    {
        // Ambil semua data dari tabel settings
        $settings = \App\Models\Setting::pluck('value', 'key')->all();
        
        // 1. Ambil data mingguan dari settings (bukan dari tabel infaqs)
        $infaqMingguan = [
            (int)($settings['infaq_minggu_1'] ?? 0),
            (int)($settings['infaq_minggu_2'] ?? 0),
            (int)($settings['infaq_minggu_3'] ?? 0),
            (int)($settings['infaq_minggu_4'] ?? 0),
        ];

        // 2. Data untuk Progress Bar
        $totalTerkumpul = (int)($settings['total_infaq_terkumpul'] ?? 0);
        $targetPembangunan = (int)($settings['target_pembangunan'] ?? 100000000);
        
        // Hitung persentase (pastikan tidak pembagian dengan nol)
        $persentase = $targetPembangunan > 0 ? ($totalTerkumpul / $targetPembangunan) * 100 : 0;

        return view('display', compact(
            'settings', 
            'infaqMingguan', 
            'totalTerkumpul', 
            'targetPembangunan', 
            'persentase'
        ));
    }
    public function index()
    {
        // 1. Ambil semua setting dari database
        $settings = \App\Models\Setting::pluck('value', 'key')->all();

        // 2. Ambil angka hari ini (0 = Minggu, 1 = Senin, ..., 5 = Jumat, 6 = Sabtu)
        $hariIni = now()->dayOfWeek;

        // 3. Tentukan apakah ini waktu untuk input data baru (Sabtu s/d Rabu)
        // Jika hari ini Sabtu (6) sampai Rabu (3), maka beri peringatan update
        $perluUpdate = in_array($hariIni, [6, 0, 1, 2, 3]);
        
        // 4. Kirim variabel ke view
        return view('admin.index', compact('settings', 'perluUpdate', 'hariIni'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except('_token');

        foreach ($inputs as $key => $value) {
            // Simpan ke tabel settings
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? 0] // Jika kosong simpan 0
            );
        }

        return redirect()->back()->with('success', 'Data Berhasil Disimpan!');
    }
}
