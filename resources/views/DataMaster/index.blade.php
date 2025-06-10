@extends('layouts.app')

@section('content')
    <style>
        .drop-zone {
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            color: #777;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .drop-zone.dragover {
            border-color: #28a745;
            background-color: #f9fff9;
        }

        .drop-zone input {
            display: none;
        }
    </style>
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Data Master</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Master Produk</a></li>
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
                        <div class="card-header">
                            <div class="d-flex align-items-center w-100">
                                <h4 class="card-title mb-0">Add Data</h4>

                                {{-- Tombol Manual --}}
                                <button class="btn btn-primary btn-round ms-auto me-2" data-bs-toggle="modal"
                                    data-bs-target="#addManualModal">
                                    <i class="fa fa-plus"></i> Manual Input
                                </button>

                                {{-- Tombol Upload --}}
                                <button class="btn btn-success btn-round" data-bs-toggle="modal"
                                    data-bs-target="#uploadExcelModal">
                                    <i class="fa fa-upload"></i> Upload Excel
                                </button>
                            </div>
                        </div>
                        {{-- ============ /CARD-HEADER ============ --}}

                        <div class="card-body">

                            {{-- ================= MODAL MANUAL INPUT ================= --}}
                            <div class="modal fade" id="addManualModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('rekap.storeManual') }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title fw-bold">Manual Input</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="fw-semibold mb-1">Platform</label>
                                                <select id="editPlatformSelect" name="platform"
                                                    class="form-control mb-2 form-select">
                                                    <option value="" selected hidden>-- pilih platform --</option>
                                                    <option value="Tiktok">Tiktok</option>
                                                    <option value="Shopee">Shopee</option>
                                                    <option value="Lainnya">Lainnya…</option>
                                                </select>

                                                <input type="text" id="editPlatformCustom"
                                                    class="form-control mt-2 mb-2 d-none"
                                                    placeholder="Isi nama platform lain…">
                                                <input type="text" name="sku" class="form-control mb-2"
                                                    placeholder="SKU">
                                                <input type="text" name="nama" class="form-control mb-2"
                                                    placeholder="Nama Produk">
                                                <input type="text" name="warna" class="form-control mb-2"
                                                    placeholder="Warna">
                                                <input type="text" name="ukuran" class="form-control mb-2"
                                                    placeholder="Ukuran">
                                                <input type="number" name="stok" class="form-control mb-2"
                                                    placeholder="Stok Awal">
                                                <input type="number" name="harga" class="form-control mb-2"
                                                    placeholder="Harga">
                                                <input type="number" name="terjual" class="form-control mb-2"
                                                    placeholder="Terjual">
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="submit" class="btn btn-primary">Tambah</button>
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Batal</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- ================= /MODAL MANUAL INPUT ================= --}}

                            {{-- ================= MODAL UPLOAD EXCEL ================= --}}
                            <div class="modal fade" id="uploadExcelModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('rekap.importExcel') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title fw-bold">Upload Excel</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="fw-semibold mb-1">Platform</label>
                                                <select id="uploadPlatformSelect" class="form-select form-control mb-2">
                                                    <option value="" selected hidden>-- pilih platform --</option>
                                                    <option value="Tiktok">Tiktok</option>
                                                    <option value="Shopee">Shopee</option>
                                                    <option value="Lainnya">Lainnya…</option>
                                                </select>

                                                <input type="text" id="uploadPlatformCustom"
                                                    class="form-control mt-2 d-none mb-2"
                                                    placeholder="Isi nama platform lain…">

                                                <div class="drop-zone" id="dropZone">
                                                    <p>Seret file Excel ke sini atau klik untuk memilih</p>
                                                    <input type="file" name="file_excel" id="fileInput"
                                                        accept=".xlsx,.xls,.csv">
                                                </div>

                                                <small class="text-muted d-block mt-2">Kolom berurutan (A-G): Platform,
                                                    SKU, Nama Produk, Warna, Ukuran, Harga, Terjual, Stok Awal</small>

                                                <div id="filePreview" class="mt-2 text-center text-muted small d-none">
                                                    <span id="fileName"></span><br>
                                                    <span id="fileSize"></span>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="submit" class="btn btn-success">Upload</button>
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Batal</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const dropZone = document.getElementById('dropZone');
                                    const fileInput = document.getElementById('fileInput');
                                    const preview = document.getElementById('filePreview');
                                    const fileName = document.getElementById('fileName');
                                    const fileSize = document.getElementById('fileSize');

                                    // Fungsi untuk ubah bytes ke KB/MB
                                    function formatBytes(bytes) {
                                        if (bytes < 1024) return bytes + ' bytes';
                                        else if (bytes < 1048576) return (bytes / 1024).toFixed(2) + ' KB';
                                        else return (bytes / 1048576).toFixed(2) + ' MB';
                                    }

                                    function showPreview(file) {
                                        fileName.textContent = `📄 ${file.name}`;
                                        fileSize.textContent = `Ukuran: ${formatBytes(file.size)}`;
                                        preview.classList.remove('d-none');
                                    }

                                    dropZone.addEventListener('click', () => fileInput.click());

                                    dropZone.addEventListener('dragover', (e) => {
                                        e.preventDefault();
                                        dropZone.classList.add('dragover');
                                    });

                                    dropZone.addEventListener('dragleave', () => {
                                        dropZone.classList.remove('dragover');
                                    });

                                    dropZone.addEventListener('drop', (e) => {
                                        e.preventDefault();
                                        dropZone.classList.remove('dragover');
                                        if (e.dataTransfer.files.length) {
                                            fileInput.files = e.dataTransfer.files;
                                            showPreview(e.dataTransfer.files[0]);
                                        }
                                    });

                                    fileInput.addEventListener('change', () => {
                                        if (fileInput.files.length) {
                                            showPreview(fileInput.files[0]);
                                        }
                                    });
                                });
                            </script>

                            {{-- ================= /MODAL UPLOAD EXCEL ================= --}}

                            {{-- ================= /MODAL EDIT ================= --}}
                            <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form id="editForm" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title fw-bold">Edit Data</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" id="editIdMaster" name="id_master">

                                                <label class="fw-semibold mb-1">Platform</label>
                                                <select id="editPlatformSelect" name="platform"
                                                    class="form-control mb-2 form-select">
                                                    <option value="" selected hidden>-- pilih platform --</option>
                                                    <option value="Tiktok">Tiktok</option>
                                                    <option value="Shopee">Shopee</option>
                                                    <option value="Lainnya">Lainnya…</option>
                                                </select>

                                                <input type="text" id="editPlatformCustom"
                                                    class="form-control mt-2 mb-2 d-none"
                                                    placeholder="Isi nama platform lain…">
                                                <input type="text" id="editSku" name="sku"
                                                    class="form-control mb-2" placeholder="SKU">
                                                <input type="text" id="editNama" name="nama"
                                                    class="form-control mb-2" placeholder="Nama Produk">
                                                <input type="text" id="editWarna" name="warna"
                                                    class="form-control mb-2" placeholder="Warna">
                                                <input type="text" id="editUkuran" name="ukuran"
                                                    class="form-control mb-2" placeholder="Ukuran">
                                                <input type="number" id="editStok" name="stok"
                                                    class="form-control mb-2" placeholder="Stok Awal">
                                                <input type="number" id="editHarga" name="harga"
                                                    class="form-control mb-2" placeholder="Harga">
                                                <input type="number" id="editTerjual" name="terjual"
                                                    class="form-control mb-2" placeholder="Terjual">
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Batal</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- ================= /MODALEDIT ================= --}}

                            {{-- =================== TABEL DATA (contoh statis) =================== --}}
                            <div class="table-responsive">
                                <table id="master-table" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Platform</th>
                                            <th>SKU</th>
                                            <th>Nama Produk</th>
                                            <th>Warna</th>
                                            <th>Ukuran</th>
                                            <th>Stok <br> Awal</th>
                                            <th>Harga</th>
                                            <th>Terjual</th>
                                            <th style="width:8%">Action</th>
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
                                                <td>{{ $master->platform ?? '-' }}</td>
                                                <td>{{ $cols['SKU'] ?? '-' }}</td>
                                                <td>{{ $cols['Nama Produk'] ?? '-' }}</td>
                                                <td>{{ $cols['Warna'] ?? '-' }}</td>
                                                <td>{{ $cols['Ukuran'] ?? '-' }}</td>
                                                <td>{{ $cols['Stok Awal'] ?? '-' }}</td>
                                                <td>
                                                    {{ isset($cols['Harga']) ? 'Rp ' . number_format($cols['Harga'], 0, ',', '.') : '-' }}
                                                </td>
                                                <td>{{ $cols['Terjual'] ?? '-' }}</td>
                                                <td>
                                                    {{-- action button edit / delete (optional) --}}
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-primary btn-edit" data-bs-toggle="modal"
                                                            data-bs-target="#editModal"><i
                                                                class="fa fa-edit"></i></button>
                                                        <form action="{{ route('rekap.destroy', $master->id_master) }}"
                                                            method="POST" class="form-delete d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn-delete btn btn-danger"><i
                                                                    class="fa fa-times"></i></button>
                                                        </form>

                                                    </div>
                                                </td>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: `Data akan dihapus secara permanen!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === Platform select untuk Manual Input ===
            const manualSelect = document.getElementById('manualPlatformSelect');
            const manualCustom = document.getElementById('manualPlatformCustom');

            if (manualSelect) {
                manualSelect.addEventListener('change', () => {
                    if (manualSelect.value === 'Lainnya') {
                        manualCustom.classList.remove('d-none');
                        manualCustom.name = 'platform';
                        manualCustom.required = true;
                        manualSelect.name = '';
                    } else {
                        manualCustom.classList.add('d-none');
                        manualCustom.required = false;
                        manualCustom.name = '';
                        manualSelect.name = 'platform';
                    }
                });
            }

            // === Platform select untuk Upload Excel ===
            const uploadSelect = document.getElementById('uploadPlatformSelect');
            const uploadCustom = document.getElementById('uploadPlatformCustom');

            if (uploadSelect) {
                uploadSelect.addEventListener('change', () => {
                    if (uploadSelect.value === 'Lainnya') {
                        uploadCustom.classList.remove('d-none');
                        uploadCustom.name = 'platform';
                        uploadCustom.required = true;
                        uploadSelect.name = '';
                    } else {
                        uploadCustom.classList.add('d-none');
                        uploadCustom.required = false;
                        uploadCustom.name = '';
                        uploadSelect.name = 'platform';
                    }
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk menangani platform select di modal edit
            const editSelect = document.getElementById('editPlatformSelect');
            const editCustom = document.getElementById('editPlatformCustom');

            if (editSelect) {
                editSelect.addEventListener('change', () => {
                    if (editSelect.value === 'Lainnya') {
                        editCustom.classList.remove('d-none');
                        editCustom.name = 'platform';
                        editCustom.required = true;
                        editSelect.name = '';
                    } else {
                        editCustom.classList.add('d-none');
                        editCustom.required = false;
                        editCustom.name = '';
                        editSelect.name = 'platform';
                    }
                });
            }

            // Fungsi untuk mengisi modal edit dengan data
            function fillEditModal(data) {
                document.getElementById('editIdMaster').value = data.id_master;

                // Set platform
                const platform = data.platform;
                if (['Tiktok', 'Shopee'].includes(platform)) {
                    document.getElementById('editPlatformSelect').value = platform;
                    document.getElementById('editPlatformCustom').classList.add('d-none');
                } else if (platform && platform !== '-') {
                    document.getElementById('editPlatformSelect').value = 'Lainnya';
                    document.getElementById('editPlatformCustom').value = platform;
                    document.getElementById('editPlatformCustom').classList.remove('d-none');
                } else {
                    document.getElementById('editPlatformSelect').value = '';
                }

                // Set nilai form dari data
                document.getElementById('editSku').value = data.cols['SKU'] || '';
                document.getElementById('editNama').value = data.cols['Nama Produk'] || '';
                document.getElementById('editWarna').value = data.cols['Warna'] || '';
                document.getElementById('editUkuran').value = data.cols['Ukuran'] || '';
                document.getElementById('editStok').value = data.cols['Stok Awal'] || '';
                document.getElementById('editHarga').value = data.cols['Harga'] || '';
                document.getElementById('editTerjual').value = data.cols['Terjual'] || '';

                // Set action form
                document.getElementById('editForm').action = `/update/${data.id_master}`;
            }

            // Event listener untuk tombol edit
            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const jsonData = row.getAttribute('data-json');
                    if (jsonData) {
                        fillEditModal(JSON.parse(jsonData));
                    }
                });
            });
        });
    </script>
@endsection
