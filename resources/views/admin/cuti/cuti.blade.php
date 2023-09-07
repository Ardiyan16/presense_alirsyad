@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Data Cuti Pegawai</h4>
            <a href="{{ url('dashboard/tambah-cuti') }}" class="btn btn-success btn-modal-tambah"><i class="fa fa-plus-circle"></i> Tambah Cuti Pegawai</a>
        </div>
    </div>
    <div class="row" style="margin-top: 20px">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="card-block">
                    <h6 class="card-title text-bold">List Data Cuti Pegawai</h6>
                    <div class="table-responsive">
                        <table class="datatable table table-stripped tabel-data-cuti" id="data_cuti">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Unit</th>
                                    <th>Awal Cuti</th>
                                    <th>Akhir Cuti</th>
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

        function load_tabel() {

            $("table.tabel-data-cuti").DataTable().destroy();
            $("table.tabel-data-cuti").DataTable({
                ajax: {
                    url: '{{ url('/api/v1/data-cuti') }}',
                    headers: {
                        'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                    }
                },
                serverSide: true,
                processing: true,
                aaSorting:[[0,"desc"]],
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1 + '.';
                        }
                    },
                    {data: 'full_name', name: 'full_name'},
                    {data: 'unit', name: 'unit'},
                    {data: null, render: res => {
                        let start_date = new Date(res.start_date);
                        let view_start_date = start_date.getDate() + '-' + (start_date.getMonth() + 1) + '-' + start_date.getFullYear()
                        return `<span>${view_start_date}</span>`
                    }},
                    {data: null, render: res => {
                        let end_date = new Date(res.end_date);
                        let view_end_date = end_date.getDate() + '-' + (end_date.getMonth() + 1) + '-' + end_date.getFullYear()
                        return `<span>${view_end_date}</span>`
                    }},
                    {data: null, render: res => {
                        return `
                            <a href="{{ url('/dashboard/edit-cuti?id=') }}${res.id}" class="btn btn-primary btn-sm btn-edit-jenis-cuti" style="color: #FFF;" title="edit"><i class="fa fa-edit"></i></a>
                            <button class="btn btn-danger btn-sm btn-hapus-cuti" data-id="${res.id}" style="color: #FFF;" title="hapus"><i class="fa fa-trash"</i></button>`
                    }},
                ]
            });

        }

        load_tabel();

        $(document).on('click', '.btn-hapus-cuti', function() {
            id = $(this).attr('data-id');

            Swal.fire({
            title: 'Apakah anda yakin ?',
            text: 'Data cuti akan di hapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#898989',
            confirmButtonText: 'Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {


                    $.ajax({
                        url: '{{ url("/api/v1/hapus-cuti") }}/'+ id,
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
                                    load_tabel();
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
