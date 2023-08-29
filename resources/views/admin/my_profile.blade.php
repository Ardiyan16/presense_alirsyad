@extends('layouts.admin')
@section('content')

<div class="content">
    <div class="row">
        <div class="col-sm-7 col-6">
            <h4 class="page-title">My Profile</h4>
        </div>

        <div class="col-sm-5 col-6 text-right m-b-30">
            <button class="btn btn-success btn-rounded btn-edit-profile"><i class="fa fa-edit"></i> Edit Profile</button>
        </div>
    </div>
    <div class="card-box profile-header">
        <div class="row">
            <div class="col-md-12 form-edit-profile">
                <div class="card-box">
                    <span class="pull-right tutup-form" data-effect="fadeOut" title="Tutup Form"><i class="fa fa-times"></i></span>
                    <h4 class="card-title">Form Edit Profile</h4>
                    <form action="#">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Nama Lengkap</label>
                            <div class="col-md-10">
                                <input type="hidden" id="user_id" class="form-control">
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
                            <label class="col-form-label col-md-2"></label>
                            <div class="col-md-10">
                                <button class="btn btn-success btn-edit-pegawai" type="button"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-12">
                <div class="profile-view">
                    <div class="profile-img-wrap">
                        <div class="profile-img">
                            <a href="#"><img class="avatar" src="{{ url('image/user.png') }}" alt=""></a>
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="profile-info-left">
                                    <h3 class="user-name m-t-0 mb-0 nama-lengkap"></h3>
                                    <div class="staff-id status-akun"></div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <ul class="personal-info">
                                    <li>
                                        <span class="title">nama panggilan:</span>
                                        <span class="text-name"></span>
                                    </li>
                                    <li>
                                        <span class="title">Email:</span>
                                        <span class="text-email"></span>
                                    </li>
                                    <li>
                                        <span class="title">Unit:</span>
                                        <span class="text-unit"></span>
                                    </li>
                                    <li>
                                        <span class="title">Username:</span>
                                        <span class="text-username"></span>
                                    </li>
                                    <li>
                                        <span class="title">Role:</span>
                                        <span class="text-role"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
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
        let user_id = {{ auth()->user()->id }};

        $.ajax({
            url: '{{ url("/api/v1/data-profile") }}/' + user_id,
            headers: {
                'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
            },
            type: 'GET',
            dataType: 'JSON',
            success: function(res) {

                let full_name = res.data.full_name;
                let unit = res.data.unit;
                let email = res.data.email;
                let username = res.data.username;
                let name = res.data.name;
                let role = res.data.role;
                let status = res.data.status;
                let id_user = res.data.id;

                let span_status = '';
                if(status == 1) {
                    span_status = '<span class="badge bg-success" style="color: #FFF">Aktif</span>';
                } else {
                    span_status = '<span class="badge bg-danger" style="color: #FFF">Non Aktif</span>';
                }

                let text_role = '';
                if(role == 1) {
                    text_role = 'Admin';
                } else {
                    text_role = 'User';
                }

                $('.nama-lengkap').html(full_name);
                $('.status-akun').html(span_status);
                $('.text-name').html(name);
                $('.text-email').html(email);
                $('.text-unit').html(unit);
                $('.text-username').html(username);
                $('.text-role').html(text_role);
                $('#full_name').val(full_name);
                $('#name').val(name);
                $('#username').val(username);
                $('#user_id').val(id_user)

            }
        })

        $('.form-edit-profile').hide();

        $(document).on('click', '.btn-edit-profile', function() {
            $('.form-edit-profile').show();
        })

        $(document).on('click', '.tutup-form', function() {
            $('.form-edit-profile').hide();
        })

        $(document).on('click', '.btn-edit-pegawai', function() {
            let full_name = $('#full_name').val();
            let name = $('#name').val();
            let username = $('#username').val();
            let id = $('#user_id').val();

            $.ajax({
                url: '{{ url("/api/v1/update-profile") }}',
                headers: {
                    'Authorization': 'Bearer '+get_cookie('ALD_SESSION')
                },
                type: 'POST',
                dataType: 'JSON',
                data: {
                    "id": id,
                    "full_name": full_name,
                    "name": name,
                    "username": username
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
                            window.location.href = "{{ url('/dashboard/profile-saya') }}";
                        });

                    } else {

                        if(res.info_error) {
                            tampilkan_error(res.errors);
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

            function tampilkan_error(message) {
                $.each(message, function(key,val) {
                    $('.'+key+'_err').text(val);
                })
            }

        })

    })

</script>

@endsection
