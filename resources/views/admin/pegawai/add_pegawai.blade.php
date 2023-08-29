@extends('layouts.admin')
@section('content')

<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Tambah Pegawai</h4>
            <a href="{{ url('dashboard/data-pegawai') }}" class="btn btn-success btn-modal-tambah"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
        </div>
    </div>
    <div class="row" style="margin-top: 20px">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="card-title">Form Tambah Pegawai</h4>
                <form action="#">
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Nama Lengkap</label>
                        <div class="col-md-10">
                            <input type="text" id="full_name" class="form-control">
                            <span class="text-danger full_name_err"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Nama Panggilan</label>
                        <div class="col-md-10">
                            <input type="text" id="name" class="form-control">
                            <span class="text-danger name_err"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Username</label>
                        <div class="col-md-10">
                            <input type="text" id="username" class="form-control">
                            <span class="text-danger username_err"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Email</label>
                        <div class="col-md-10">
                            <input type="email" id="email" class="form-control">
                            <span class="text-danger email_err"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Unit</label>
                        <div class="col-md-10">
                            <select class="form-control select-unit-kerja" id="unit_id">
                            </select>
                            <span class="text-danger unit_id_err"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2"></label>
                        <div class="col-md-10">
                            <button class="btn btn-success btn-simpan-pegawai" type="button"><i class="fa fa-save"></i> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')

<script>

    $(document).ready(function() {

        $.ajax({
            url: '{{ url("/api/v1/data-unit-select") }}',
            headers: {
                'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
            },
            type: 'GET',
            dataType: 'JSON',

            success: function(res) {

                if(res.status) {

                    let option = '<option value="" disable selected>Pilih Unit</option>';
                    $.each(res.data, function(index, val) {

                        option += '<option value="'+ val.id +'" title="'+ val.unit +'">'+ val.unit +'</option>';

                    });

                    $('select.select-unit-kerja').html(option).select2();

                }

            }
        })

        $(document).on('click', '.btn-simpan-pegawai', function() {
            let full_name = $('#full_name').val();
            let name = $('#name').val();
            let username = $('#username').val();
            let email = $('#email').val();
            let unit = $('#unit_id').val();

            $.ajax({
                url: '{{ url("/api/v1/simpan-pegawai") }}',
                headers: {
                    'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                },
                type: 'POST',
                dataType: 'JSON',
                data: {
                    "full_name": full_name,
                    "name": name,
                    "username": username,
                    "email": email,
                    'unit_id': unit,
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

                        if(res.info_error) {
                            tampikan_error(res.errors);
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Gagal!',
                                text: res.message
                            });
                        }

                    }

                }
            })

        })

        function tampikan_error(message) {
            $.each(message, function(key,val) {
                $('.'+key+'_err').text(val);
            })
        }

    });

</script>

@endsection
