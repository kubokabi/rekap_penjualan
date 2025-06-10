@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Rekap Otomatis</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Data Produk</a></li>
                </ul>
            </div>

            @if ($errors->has('file_excel'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: '{{ $errors->first('file_excel') }}',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>
            @endif



            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        {{-- ============ CARD-HEADER ============ --}}
                        <div class="card-header py-3">
                            <h4 class="card-title mb-3">Filter Data</h4>

                            <form method="GET" action="{{ route('dataRekap') }}"
                                class="d-flex flex-wrap align-items-end gap-3">

                                {{-- Dari Tanggal --}}
                                <div class="d-flex flex-column">
                                    <label for="start_date" class="form-label fw-semibold mb-1">Dari Tanggal</label>
                                    <input type="date" name="start_date" id="start_date"
                                        value="{{ request('start_date') }}" class="form-control" style="min-width:150px;">
                                </div>

                                {{-- Sampai Tanggal --}}
                                <div class="d-flex flex-column">
                                    <label for="end_date" class="form-label fw-semibold mb-1">Sampai Tanggal</label>
                                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                        class="form-control" style="min-width:150px;">
                                </div>

                                {{-- Tombol aksi --}}
                                <div class="d-flex gap-2 ms-auto">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('rekap.export', request()->only(['start_date', 'end_date'])) }}"
                                        class="btn btn-success">
                                        Download Excel
                                    </a>
                                </div>
                            </form>
                        </div>
                        {{-- ============ /CARD-HEADER ============ --}}


                        <div class="card-body">

                            {{-- =================== TABEL DATA (contoh statis) =================== --}}
                            <div class="table-responsive">
                                @php
                                    $totalOmset = $data_master->sum(function ($master) {
                                        $cols = $master->penjualan->pluck('isi_kolom', 'nama_kolom');
                                        return (int) ($cols['Harga'] ?? 0) * (int) ($cols['Terjual'] ?? 0);
                                    });
                                @endphp

                                <div class="alert alert-success">
                                    <strong>Total Omset:</strong> Rp {{ number_format($totalOmset, 0, ',', '.') }}
                                </div>

                                <table id="master-table" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>SKU</th>
                                            <th>Nama Produk</th>
                                            <th>Harga</th>
                                            <th>Terjual</th>
                                            <th>Stok Tersisa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1;@endphp
                                        {{-- Loop data_master jika sudah di-pass dari controller --}}
                                        @foreach ($data_master as $master)
                                            @php
                                                $cols = $master->penjualan->pluck('isi_kolom', 'nama_kolom');
                                            @endphp
                                            <tr data-json='@json(['id_master' => $master->id_master, 'platform' => $master->platform, 'cols' => $cols])'>

                                                <td>{{ $no++ }}</td>
                                                <td>{{ \Carbon\Carbon::parse($master->created_at)->format('j/n/Y') }}</td>
                                                <td>{{ $cols['SKU'] ?? '-' }}</td>
                                                <td>{{ $cols['Nama Produk'] ?? '-' }}</td>
                                                <td>
                                                    {{ isset($cols['Harga']) ? 'Rp ' . number_format($cols['Harga'], 0, ',', '.') : '-' }}
                                                </td>
                                                <td>{{ $cols['Terjual'] ?? '-' }}</td>
                                                <td>{{ $cols['Stok Awal'] - $cols['Terjual'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    $("#master-table").DataTable({
                                        pageLength: 5,
                                    });
                                });
                            </script>
                            {{-- =================== /TABEL DATA =================== --}}

                        </div><!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
