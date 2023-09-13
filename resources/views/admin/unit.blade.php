@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Data Unit</h4>
            <a href="#tambah" class="btn btn-success btn-modal-tambah"><i class="fa fa-plus-circle"></i> Tambah Unit</a>
        </div>
    </div>
    <div class="row" style="margin-top: 20px">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="card-block">
                    <h6 class="card-title text-bold">List Data Unit</h6>
                    <div class="table-responsive">
                        <table class="table table-stripped table-data-unit" id="data_unit">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Unit</th>
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Pulang</th>
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
<div class="modal fade" id="tambah_unit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Unit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- <form name="add_unit"> --}}
                <div class="form-group">
                    <label>Unit Kerja</label>
                    <input type="text" class="form-control" id="unit_kerja" name="unit">
                    <label>Waktu Masuk</label>
                    <input type="time" class="form-control" id="time_in" name="time_in">
                    <label>Waktu Keluar</label>
                    <input type="time" class="form-control" id="time_out" name="time_out">
                </div>
                <button type="submit" class="btn btn-primary btn-sm simpan-unit"><i class="fa fa-save"></i> Simpan</button>
                {{-- </form> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger tutup-modal" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalUnit" data-konfirmasi-pengajuan-pembatalan="false">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
		</div>
	</div>
</div>
@endsection
@section('js')

<script>

    $(document).ready(function() {

        function load_tabel() {

            $('table.table-data-unit').DataTable().destroy();
            $('table.table-data-unit').DataTable({
                ajax: {
                    url: '{{ url('/api/v1/data_unit') }}',
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
                    {data: 'unit', name: 'unit'},
                    {data: 'time_in', name: 'time_in'},
                    {data: 'time_out', name: 'time_out'},
                    {data: null, render: res => {
                        return `
                            <button href="" class="btn btn-primary btn-sm btn-edit-unit" data-id="${res.id}" data-unit="${res.unit}" data-time_in="${res.time_in}" data-time_out="${res.time_out}" style="color: #FFF;" title="edit"><i class="fa fa-edit"></i></button>
                            <button href="" class="btn btn-danger btn-sm btn-hapus-unit" data-id="${res.id}" style="color: #FFF;" title="hapus"><i class="fa fa-trash"</i></button>`
                    }},
                ]
            })
        }

        load_tabel();


        $(document).on('click', '.btn-modal-tambah', function() {
            $('#tambah_unit').modal('show');
        })


        $(document).on('click', '.simpan-unit', function() {
            let unit = $('#unit_kerja').val();
            let time_in = $('#time_in').val();
            let time_out = $('#time_out').val();

            if(unit == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Unit kerja harus diisi'
                });
            } else {
                $.ajax({
                    url: '{{ url("api/v1/simpan-unit") }}',
                    headers: {
                        'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                    },
                    type: 'POST',
                    dataType: "JSON",
                    data: {
                        "unit": unit,
                        "time_in": time_in,
                        "time_out": time_out
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
                                $('#tambah_unit').modal('hide');
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

        $(document).on("click", ".btn-edit-unit", function() {
            let id = $(this).attr('data-id');
            let unit = $(this).attr('data-unit');
            let time_in = $(this).attr('data-time_in');
            let time_out = $(this).attr('data-time_out');

            $('.modal#ModalUnit').modal('show');

            $('.modal#ModalUnit div.modal-content').html(`
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Unit Kerja</label>
                        <input type="hidden" name="id" id="id_unit" value="${id}">
                        <input type="text" class="form-control" value="${unit}" id="val_unit_kerja" name="unit">
                        <label>Waktu Masuk</label>
                        <input type="time" class="form-control" id="time_in_edit" value="${time_in}" name="time_in">
                        <label>Waktu Keluar</label>
                        <input type="time" class="form-control" id="time_out_edit" value="${time_out}" name="time_out">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm btn-update-unit"><i class="fa fa-save"></i> Simpan</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger tutup-modal" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
                </div>
            `)
        })

        $(document).on('click', '.btn-update-unit', function() {
            let id = $('#id_unit').val();
            let unit = $('#val_unit_kerja').val();
            let time_in = $('#time_in_edit').val();
            let time_out = $('#time_out_edit').val();

            if(unit == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Unit kerja harus diisi'
                });

            } else {

                $.ajax({
                    url: '{{ url("api/v1/update-unit") }}',
                    headers: {
                        'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                    },
                    type: 'POST',
                    dataType: "JSON",
                    data: {
                        "id": id,
                        "unit": unit,
                        "time_in": time_in,
                        "time_out": time_out
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
                                $('.modal#ModalUnit').modal('hide');
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

        $(document).on('click', '.btn-hapus-unit', function(e) {
            e.preventDefault()
            let id = $(this).attr('data-id');

            Swal.fire({
                title: 'Apakah anda yakin ?',
                text: "Data unit akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#898989',
                confirmButtonText: 'Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: '{{ url('api/v1/hapus-unit') }}/' + id,
                        headers: {
                            'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                        },
                        type: 'GET',
                        dataType: "JSON",
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
                                    window.location.href = "{{ url('/dashboard/data-unit') }}";
                                });

                            } else {

                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Gagal!',
                                    text: res.message
                                });

                            }

                        }
                    });

                }
            })
        })

    })

</script>

@endsection
