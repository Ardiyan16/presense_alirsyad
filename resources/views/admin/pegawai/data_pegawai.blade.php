@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Data Pegawai</h4>
            <a href="{{ url('dashboard/tambah-pegawai') }}" class="btn btn-success btn-modal-tambah"><i class="fa fa-plus-circle"></i> Tambah Pegawai</a>
        </div>
    </div>
    <div class="row" style="margin-top: 20px">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="card-block">
                    <h6 class="card-title text-bold">List Data Pegawai</h6>
                    <div class="table-responsive">
                        <table class="datatable table table-stripped" id="data_pegawai">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>Unit</th>
                                    <th>Status</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script>

        $(document).ready(function() {
            var tabel = $('#data_pegawai').DataTable({
                ajax: {
                    url: '{{ url('/api/v1/data-pegawai') }}',
                    headers: {
                        'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                    }
                },
                serverSide: true,
                processing: true,
                destroy: true,
                aaSorting:[[0,"desc"]],
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1 + '.';
                        }
                    },
                    {data: 'full_name', name: 'full_name'},
                    {data: 'email', name: 'email'},
                    {data: 'unit', name: 'unit'},
                    {
                        data:null, render: res => {
                            let status = '';
                            if(res.status == 1) {
                                status += `<span class="badge bg-success" style="color: #FFF">Aktif</span>`;
                            } else {
                                status += `<span class="badge bg-danger" style="color: #FFF">Non AKtif</span>`
                            }

                            return status;
                        }
                    },
                    {
                        data: null, render: res => {

                        let button_nonaktif = '';
                        if(res.status == 1) {
                            button_nonaktif = `<button class="btn btn-warning btn-sm btn-nonaktif-pegawai" data-id="${res.id}" style="color: #FFF;" title="non aktifkan"><i class="fa fa-power-off"></i></button>`
                        } else {
                            button_nonaktif = `<button class="btn btn-success btn-sm btn-aktif-pegawai" data-id="${res.id}" style="color: #FFF;" title="aktifkan"><i class="fa fa-check"></i></button>`
                        }
                        return `
                            <a href="{{ url("dashboard/edit-pegawai?id=") }}${res.id}" class="btn btn-primary btn-sm btn-edit-pegawai" data-id="${res.id}" style="color: #FFF;" title="edit"><i class="fa fa-edit"></i></a>
                            ${button_nonaktif}
                            <button href="" class="btn btn-danger btn-sm btn-hapus-pegawai" data-id="${res.id}" data-fullname="${res.full_name}" style="color: #FFF;" title="hapus"><i class="fa fa-trash"</i></button>`
                    }},
                ]
            })

            $(document).on('click', '.btn-nonaktif-pegawai', function() {
                let id = $(this).attr('data-id');

                Swal.fire({
                title: 'Apakah anda yakin ?',
                text: "Data akan di nonaktifkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#898989',
                confirmButtonText: 'Nonaktifkan!'
                }).then((result) => {
                    if (result.isConfirmed) {


                        $.ajax({
                            url: '{{ url("/api/v1/nonaktif-pegawai") }}',
                            headers: {
                                'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                            },
                            type: 'POST',
                            dataType: 'JSON',
                            data: {
                                "id": id
                            },
                            success: function(res) {

                                if(res.status) {

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: res.message,
                                        timer: 2000,
                                        showCancelButton: false,
                                        showConfirmButton: false
                                    })
                                    .then (function() {
                                        window.location.href = "{{ url('/dashboard/data-pegawai') }}";
                                    });

                                } else {

                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Gagal!',
                                        text: res.message
                                    });
                                }

                            }
                        })

                    }
                })
            })

            $(document).on('click', '.btn-aktif-pegawai', function() {
                let id = $(this).attr('data-id');

                Swal.fire({
                title: 'Apakah anda yakin ?',
                text: "Data akan di aktifkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#028000',
                cancelButtonColor: '#898989',
                confirmButtonText: 'Aktifkan!'
                }).then((result) => {
                    if (result.isConfirmed) {


                        $.ajax({
                            url: '{{ url("/api/v1/aktif-pegawai") }}',
                            headers: {
                                'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                            },
                            type: 'POST',
                            dataType: 'JSON',
                            data: {
                                "id": id
                            },
                            success: function(res) {

                                if(res.status) {

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: res.message,
                                        timer: 2000,
                                        showCancelButton: false,
                                        showConfirmButton: false
                                    })
                                    .then (function() {
                                        window.location.href = "{{ url('/dashboard/data-pegawai') }}";
                                    });

                                } else {

                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Gagal!',
                                        text: res.message
                                    });
                                }

                            }
                        })

                    }
                })
            })

            $(document).on('click', '.btn-hapus-pegawai', function() {
                let id = $(this).attr('data-id');
                let full_name = $(this).attr('data-fullname');

                Swal.fire({
                title: 'Apakah anda yakin ?',
                text: full_name +' akan di hapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#898989',
                confirmButtonText: 'Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {


                        $.ajax({
                            url: '{{ url("/api/v1/hapus-pegawai") }}/'+ id,
                            headers: {
                                'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                            },
                            type: 'GET',
                            dataType: 'JSON',
                            success: function(res) {

                                if(res.status) {

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: res.message,
                                        timer: 2000,
                                        showCancelButton: false,
                                        showConfirmButton: false
                                    })
                                    .then (function() {
                                        window.location.href = "{{ url('/dashboard/data-pegawai') }}";
                                    });

                                } else {

                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Gagal!',
                                        text: res.message
                                    });
                                }

                            }
                        })

                    }
                })
            })

        })


    </script>
@endsection
