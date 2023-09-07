@extends('layouts.admin')
@section('content')

<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Data Jenis Cuti</h4>
            <a href="#tambah" class="btn btn-success btn-modal-tambah"><i class="fa fa-plus-circle"></i> Tambah Jenis Cuti</a>
        </div>
    </div>
    <div class="row" style="margin-top: 20px">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="card-block">
                    <h6 class="card-title text-bold">List Data Jenis Cuti</h6>
                    <div class="table-responsive">
                        <table class="datatable table table-stripped data-jenis-cuti" id="data_jenis_cuti">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Cuti</th>
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

<div class="modal fade" id="tambah_jenis_cuti" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Jenis Cuti</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- <form name="add_unit"> --}}
                <div class="form-group">
                    <label>Keterangan Cuti</label>
                    <input type="text" class="form-control" id="reason" name="reason">
                </div>
                <button type="submit" class="btn btn-primary btn-sm simpan-jenis-cuti"><i class="fa fa-save"></i> Simpan</button>
                {{-- </form> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger tutup-modal" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalJenisCuti" data-konfirmasi-pengajuan-pembatalan="false">
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

            $("table.data-jenis-cuti").DataTable().destroy();
            $("table.data-jenis-cuti").DataTable({
                ajax: {
                url: '{{ url('/api/v1/data-jenis-cuti') }}',
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
                    {data: 'reason', name: 'reason'},
                    {data: null, render: res => {
                        return `
                            <button href="" class="btn btn-primary btn-sm btn-edit-jenis-cuti" data-id="${res.id}" data-reason="${res.reason}" style="color: #FFF;" title="edit"><i class="fa fa-edit"></i></button>
                            <button href="" class="btn btn-danger btn-sm btn-hapus-jenis-cuti" data-id="${res.id}" data-reason="${res.reason}" style="color: #FFF;" title="hapus"><i class="fa fa-trash"</i></button>`
                    }},
                ]
            })

        }
        load_tabel();

        $(document).on('click', '.btn-modal-tambah', function() {
            $('#tambah_jenis_cuti').modal('show');
        })

        $(document).on('click', '.simpan-jenis-cuti', function() {
            let reason = $('#reason').val();

            if(reason == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Keterangan cuti harus diisi'
                });
            } else {

                $.ajax({
                    url: '{{ url("api/v1/simpan-jenis-cuti") }}',
                    headers: {
                        'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                    },
                    type: 'POST',
                    dataType: "JSON",
                    data: {
                        "reason": reason,
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
                                $('#tambah_jenis_cuti').modal('hide');
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

        $(document).on('click', '.btn-edit-jenis-cuti', function() {
            let id = $(this).attr('data-id');
            let reason = $(this).attr('data-reason');

            $('.modal#ModalJenisCuti').modal('show');

            $('.modal#ModalJenisCuti div.modal-content').html(`
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Jenis Cuti</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Keterangan Cuti</label>
                        <input type="hidden" class="form-control" id="id_jenis" value="${id}" name="id">
                        <input type="text" class="form-control" id="val_reason" value="${reason}" name="reason">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm update-jenis-cuti"><i class="fa fa-save"></i> Simpan</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger tutup-modal" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
                </div>
            `)
        })

        $(document).on('click', '.update-jenis-cuti', function() {
            let id = $('#id_jenis').val();
            let reason = $('#val_reason').val();

            if(reason == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Keterangan cuti harus diisi'
                });
            } else {

                $.ajax({
                    url: '{{ url("api/v1/update-jenis-cuti") }}',
                    headers: {
                        'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                    },
                    type: 'POST',
                    dataType: "JSON",
                    data: {
                        "id": id,
                        "reason": reason,
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
                                $('.modal#ModalJenisCuti').modal('hide');
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

        $(document).on('click', '.btn-hapus-jenis-cuti', function(e) {
            e.preventDefault()
            let id = $(this).attr('data-id');
            let reason = $(this).attr('data-reason');

            Swal.fire({
                title: 'Apakah anda yakin ?',
                text: "Data "+ reason +" akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#898989',
                confirmButtonText: 'Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: '{{ url('api/v1/hapus-jenis-cuti') }}/' + id,
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
                    });

                }
            })
        })

    })

</script>

@endsection
