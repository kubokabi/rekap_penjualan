<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataMasterModel;
use App\Models\PenjualanModel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Rekap extends Controller
{
    public function index()
    {
        $id_users = session('id_users');

        $userCount    = User::count();
        $productCount = DataMasterModel::where('id_users', $id_users)->count();

        $totalOmset = DataMasterModel::with('penjualan')
            ->where('id_users', $id_users)
            ->get()
            ->sum(
                fn($m) =>
                (int)($m->penjualan->pluck('isi_kolom', 'nama_kolom')['Harga'] ?? 0) *
                    (int)($m->penjualan->pluck('isi_kolom', 'nama_kolom')['Terjual'] ?? 0)
            );

        $year   = now()->year;
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $values = array_fill(0, 12, 0);

        DataMasterModel::with('penjualan')
            ->where('id_users', $id_users)
            ->whereYear('created_at', $year)
            ->get()
            ->each(function ($m) use (&$values) {
                $c = $m->penjualan->pluck('isi_kolom', 'nama_kolom');
                $omzet = (int)($c['Harga'] ?? 0) * (int)($c['Terjual'] ?? 0);
                $values[$m->created_at->month - 1] += $omzet;
            });

        $yearlyOmzet = array_sum($values);

        return view('index', [
            'userCount'    => $this->short($userCount),
            'productCount' => $this->short($productCount),
            'totalOmset'   => $this->short($totalOmset),
            'yearlyOmzet'  => $yearlyOmzet,
            'chartLabels'  => $labels,
            'chartValues'  => $values,
        ]);
    }


    /** format 12 500 → 12.5K, 1 300 000 → 1.3M */
    private function short($num, $precision = 1)
    {
        if ($num < 1000) {
            return number_format($num, 0, ',', '.');
        }

        $units = ['', ' RB', ' JT', ' M', ' T'];
        $power = min((int)floor(log10($num) / 3), 4);
        $short = $num / (1000 ** $power);

        // hilangkan .0 di belakang
        return rtrim(rtrim(number_format($short, $precision, '.', ''), '0'), '.') .
            $units[$power];
    }

    ##### Data Master

    public function dataMaster()
    {
        $data_master = DataMasterModel::with('penjualan')
            ->orderByDesc('created_at')->where('id_users', session('id_users'))
            ->get();

        return view('DataMaster.index', compact('data_master'));
    }

    public function storeManual(Request $request)
    {
        // Validasi input
        $request->validate([
            'platform'   => 'required|string',
            'sku'        => 'required',
            'nama'       => 'required',
            'warna'      => 'required',
            'ukuran'     => 'required',
            'stok'     => 'required',
            'harga'      => 'required|numeric',
            'terjual'    => 'required|numeric',
        ], [
            'platform.required' => 'Pilih platform.',
            'sku.required' => 'SKU tidak boleh kosong.',
            'nama.required' => 'Nama produk wajib diisi.',
            'warna.required' => 'Warna wajib diisi.',
            'ukuran.required' => 'Ukuran tidak boleh kosong.',
            'stok.required' => 'Stok Awal tidak boleh kosong.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'terjual.required' => 'Jumlah terjual wajib diisi.',
            'terjual.numeric' => 'Terjual harus berupa angka.'
        ]);

        // Cek SKU sudah ada atau belum
        $existingSKU = PenjualanModel::where('nama_kolom', 'SKU')
            ->where('isi_kolom', $request->sku)
            ->exists();

        if ($existingSKU) {
            return redirect()->back()
                ->with('error', 'SKU sudah digunakan. Gunakan SKU yang lain.')
                ->withInput();
        }

        // 1. Simpan ke data_master
        $master = DataMasterModel::create([
            'platform' => $request->platform ?? '-',
            'id_users' => session('id_users'),
        ]);

        // 2. Simpan ke penjualan (SKU, Nama Produk, dst)
        $fields = [
            'SKU' => strtoupper($request->sku),
            'Nama Produk' => $request->nama,
            'Warna' => $request->warna,
            'Ukuran' => $request->ukuran,
            'Stok Awal' => $request->stok,
            'Harga' => $request->harga,
            'Terjual' => $request->terjual,
        ];

        foreach ($fields as $col => $val) {
            PenjualanModel::create([
                'id_master' => $master->id_master,
                'nama_kolom' => $col, // Sudah bagus, jangan pakai Str::title
                'isi_kolom' => $val,
            ]);
        }

        return redirect()->route('rekap.index')->with('success', 'Data berhasil ditambah!');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'platform'   => 'required|string',
            'file_excel' => 'required|mimes:xls,xlsx,csv'
        ], [
            'platform.required' => 'Pilih platform.',
            'file_excel.mimes' => 'File harus berupa Excel (.xls, .xlsx, .csv).',
            'file_excel.required' => 'Silakan unggah file Excel terlebih dahulu.'
        ]);

        $file = $request->file('file_excel');
        $data = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname())
            ->getActiveSheet()
            ->toArray(null, true, true, true);

        $columns = ["SKU", "Nama Produk", "Warna", "Ukuran", "Harga", "Terjual", "Stok Awal"];

        $skuList = array_map(function ($row) {
            return $row['A'];
        }, array_slice($data, 1));

        // Ambil semua SKU yang sudah ada di database
        $existing = PenjualanModel::where('nama_kolom', 'SKU')
            ->whereIn('isi_kolom', $skuList)
            ->pluck('isi_kolom')
            ->toArray();

        // Cek SKU duplikat
        if (!empty($existing)) {
            return redirect()->back()
                ->with('error', 'Gagal import. SKU berikut sudah ada: ' . implode(', ', $existing));
        }

        // Lewati header (baris 1)
        foreach (array_slice($data, 1) as $row) {
            $master = DataMasterModel::create([
                'platform' => $request->platform ?? '-',
                'id_users' => session('id_users')
            ]);
            $i = 'A';
            foreach ($columns as $col) {
                PenjualanModel::create([
                    'id_master' => $master->id_master,
                    'nama_kolom' => $col,
                    'isi_kolom' => $row[$i] ?? '-',
                ]);
                $i++;
            }
        }

        return redirect()->route('rekap.index')->with('success', 'Import dari Excel berhasil!');
    }

    public function update(Request $request, $id_master)
    {
        // Validasi input
        $request->validate([
            'platform'   => 'required|string',
            'sku'        => 'required',
            'nama'       => 'required',
            'warna'      => 'required',
            'ukuran'     => 'required',
            'stok'     => 'required',
            'harga'      => 'required|numeric',
            'terjual'    => 'required|numeric',
        ], [
            'platform.required' => 'Pilih platform.',
            'sku.required' => 'SKU tidak boleh kosong.',
            'nama.required' => 'Nama produk wajib diisi.',
            'warna.required' => 'Warna wajib diisi.',
            'ukuran.required' => 'Ukuran tidak boleh kosong.',
            'stok.required' => 'Stok Awal tidak boleh kosong.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'terjual.required' => 'Jumlah terjual wajib diisi.',
            'terjual.numeric' => 'Terjual harus berupa angka.'
        ]);

        $existingSKU = PenjualanModel::where('nama_kolom', 'SKU')
            ->where('isi_kolom', $request->sku)
            ->where('id_master', '!=', $id_master)
            ->exists();

        if ($existingSKU) {
            return redirect()->back()
                ->with('error', 'SKU sudah digunakan. Gunakan SKU yang lain.')
                ->withInput();
        }

        // 1. Update data master (platform)
        DataMasterModel::where('id_master', $id_master)
            ->update(['platform' => $request->platform ?? '-']);

        // 2. Update data penjualan
        $fields = [
            'SKU' => $request->sku,
            'Nama Produk' => $request->nama,
            'Warna' => $request->warna,
            'Ukuran' => $request->ukuran,
            'Stok Awal' => $request->stok,
            'Harga' => $request->harga,
            'Terjual' => $request->terjual,
        ];

        foreach ($fields as $col => $val) {
            PenjualanModel::where('id_master', $id_master)
                ->where('nama_kolom', $col)
                ->update(['isi_kolom' => $val]);
        }

        return redirect()->route('rekap.index')->with('success', 'Data berhasil diupdate!');
    }

    public function destroy($id_master)
    {
        PenjualanModel::where('id_master', $id_master)->delete();

        DataMasterModel::where('id_master', $id_master)->delete();

        return redirect()->route('rekap.index')->with('success', 'Data berhasil dihapus!');
    }

    ##### End

    ##### Rekap Otomatis
    public function rekapAuto()
    {
        $data_master = DataMasterModel::with('penjualan')
            ->orderByDesc('created_at')->where('id_users', session('id_users'))
            ->get();

        return view('RekapOtomatis.index', compact('data_master'));
    }

    public function dataRekap(Request $request)
    {
        $query = DataMasterModel::with('penjualan')->where('id_users', session('id_users'));

        if ($request->filled('start_date') && $request->filled('end_date')) {
            // dua tanggal → BETWEEN (inklusif)
            $query->whereDate('created_at', '>=', $request->start_date)
                ->whereDate('created_at', '<=', $request->end_date);
        } elseif ($request->filled('start_date')) {
            // hanya start_date → sama dengan start_date
            $query->whereDate('created_at', $request->start_date);
        } elseif ($request->filled('end_date')) {
            // hanya end_date → sama dengan end_date
            $query->whereDate('created_at', $request->end_date);
        }

        $data_master = $query->orderByDesc('created_at')->get();
        return view('RekapOtomatis.index', compact('data_master'));
    }

    public function export(Request $request)
    {
        $query = DataMasterModel::with('penjualan')->where('id_users', session('id_users'));
        $periodText = '';

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('created_at', '>=', $request->start_date)
                ->whereDate('created_at', '<=', $request->end_date);
            $periodText = $request->start_date . '_to_' . $request->end_date;
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', $request->start_date);
            $periodText = $request->start_date;
        } elseif ($request->filled('end_date')) {
            $query->whereDate('created_at', $request->end_date);
            $periodText = $request->end_date;
        }

        $data = $query->get();

        /* ============ Spreadsheet ============ */
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* ---- Baris 1 : Judul ---- */
        $title = 'Rekap Otomatis' . ($periodText ? " ({$request->start_date} s/d {$request->end_date})" : '');
        $sheet->mergeCells('A1:F1')->setCellValue('A1', $title);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        /* ---- Baris 2 : Header Kolom ---- */
        $header = ['Tanggal', 'SKU', 'Nama Produk', 'Harga', 'Terjual', 'Stok Tersisa'];
        $sheet->fromArray($header, null, 'A2');
        $sheet->getStyle('A2:F2')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E0E0E0'],
            ],
        ]);

        /* ---- Isi Data mulai baris 3 ---- */
        $row = 3;
        $totalOmset = 0;
        foreach ($data as $master) {
            $cols = $master->penjualan->pluck('isi_kolom', 'nama_kolom');

            $harga   = (int) ($cols['Harga']   ?? 0);
            $terjual = (int) ($cols['Terjual'] ?? 0);
            $totalOmset += $harga * $terjual;

            $sheet->fromArray([
                $master->created_at->format('j/n/Y'),
                $cols['SKU'] ?? '-',
                $cols['Nama Produk'] ?? '-',
                $harga ?: 0,          // biarkan numerik
                $terjual ?: 0,
                isset($cols['Stok Awal']) ? $cols['Stok Awal'] - $terjual : 0,
            ], null, 'A' . $row);

            $row++;
        }

        /* ---- Format ribuan Harga (kolom D) ---- */
        $sheet->getStyle("D3:D" . ($row - 1))
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        /* ---- Baris Total Omset ---- */
        $sheet->mergeCells("A{$row}:E{$row}")
            ->setCellValue("A{$row}", 'TOTAL OMSET');
        $sheet->setCellValue("F{$row}", $totalOmset);

        /* -- Format ribuan TOTAL OMSET -- */
        $sheet->getStyle("F{$row}")
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
            'font'  => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill'  => [
                'fillType'  => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '28A745'],
            ],
        ]);

        /* ---- Auto Width ---- */
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        /* ---- Nama File ---- */
        $filename = 'rekap-otomatis' . ($periodText ? "_{$periodText}" : '') . '.xlsx';

        /* ---- Download ---- */
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    ##### End

    #### Akun Pengguna
    // Tampilkan halaman CRUD akun pengguna
    public function akunPengguna()
    {
        if (session('email') !== 'admin@gmail.com') {
            abort(403, 'Akses hanya untuk admin.');
        }

        $users = User::all();
        return view('AkunPengguna.index', compact('users'));
    }

    // Simpan akun baru
    public function storeAkun(Request $request)
    {
        if (session('email') !== 'admin@gmail.com') {
            abort(403, 'Akses hanya untuk admin.');
        }
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('akun.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    // Update akun
    public function updateAkun(Request $request, $id)
    {
        if (session('email') !== 'admin@gmail.com') {
            abort(403, 'Akses hanya untuk admin.');
        }
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'min:6';
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('akun.index')->with('success', 'Akun berhasil diperbarui.');
    }

    // Hapus akun dengan validasi minimal 1 tersisa
    public function deleteAkun($id)
    {
        if (session('email') !== 'admin@gmail.com') {
            abort(403, 'Akses hanya untuk admin.');
        }
        $totalUser = User::count();
        if ($totalUser <= 1) {
            return redirect()->route('akun.index')->with('error', 'Minimal harus ada satu akun.');
        }

        User::destroy($id);

        return redirect()->route('akun.index')->with('success', 'Akun berhasil dihapus.');
    }
    #### End
}
