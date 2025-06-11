@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header d-flex justify-content-between align-items-center">
                <h4 class="fw-bold mb-0">Manajemen Akun Pengguna</h4>
                <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalAdd">
                    <i class="fa fa-plus"></i> Tambah Akun
                </button>
            </div>

            {{-- TABEL --}}
            <div class="card mt-4">
                <div class="card-body">
                    <table id="userTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Dibuat</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $i => $user)
                                <tr data-json='@json($user)'>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        {{ $user->name }}
                                        @if ($user->email === 'admin@gmail.com')
                                            <span class="badge bg-success ms-2">Primary</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-primary btn-edit" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            @if ($user->email !== 'admin@gmail.com')
                                                <form action="{{ route('akun.delete', $user->id) }}" method="POST"
                                                    class="form-delete d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            {{-- MODAL TAMBAH --}}
            <div class="modal fade" id="modalAdd" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('akun.store') }}" method="POST" class="modal-content">
                        @csrf
                        <div class="modal-header border-0">
                            <h5 class="modal-title">Tambah Akun</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" name="name" class="form-control mb-2" placeholder="Nama Lengkap"
                                required>
                            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                            <input type="password" name="password" class="form-control mb-2" placeholder="Password"
                                required>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" class="btn btn-primary">Tambah</button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- MODAL EDIT --}}
            <div class="modal fade" id="modalEdit" tabindex="-1">
                <div class="modal-dialog">
                    <form id="editForm" method="POST" class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-0">
                            <h5 class="modal-title">Edit Akun</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="editId">
                            <input type="text" name="name" id="editName" class="form-control mb-2"
                                placeholder="Nama Lengkap" required>
                            <input type="email" name="email" id="editEmail" class="form-control mb-2"
                                placeholder="Email" required>
                            <input type="password" name="password" class="form-control mb-2"
                                placeholder="Kosongkan jika tidak diubah">
                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert & DataTable --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $("#userTable").DataTable();

            document.querySelectorAll('.btn-delete').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Yakin ingin menghapus akun ini?',
                        text: 'Akun akan dihapus permanen!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#aaa'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            document.querySelectorAll('.btn-edit').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const data = JSON.parse(this.closest('tr').dataset.json);
                    document.getElementById('editForm').action = `/akun-pengguna/${data.id}`;
                    document.getElementById('editName').value = data.name;
                    document.getElementById('editEmail').value = data.email;
                });
            });
        });
    </script>
@endsection
