@extends('layouts.master')
@section('title')
    {{ ucfirst(Auth::user()->name ?? Auth::user()->username) }}
@endsection
@section('style')
@include($data['load_page'].'style')
<link rel="stylesheet" href="{{ custom_asset('css/account.css') }}">
@endsection
@section('content')
<section class="profile-wrapper pt-25">
    <div class="container-fluid">
        <div class="row justify-content-center p-0 m-0">
            <div class="col-lg-12">
                <div class="profile">
                <form id="update-profile-form" style="width: 100%;" enctype="multipart/form-data">
                    @csrf
                    <div class="profile-header">
                        <div class="profile-cover-photo bg_cover" style="background-image: url(https://i.pinimg.com/736x/f9/39/bb/f939bbdfb0ac7fe008e46a817da42702.jpg)"></div>
                        <div class="profile-author d-sm-flex flex-row-reverse justify-content-between align-items-end">
                            <div class="profile-photo position-relative">
                                <img id="profile-image" src="{{ assetFile(config('setting.disk_name'), Auth::user()->avatar ) ?? custom_asset('image/me.jpg') }}" alt="Profile Photo">
                                <label id="upload-image-label" for="upload-image" class="upload-icon d-none">
                                    <i class="fa fa-camera fa-2x"></i>
                                </label>
                                <input type="file" name="attachment" id="upload-image" accept="image/*" hidden>
                            </div>

                            <div class="profile-name">
                                <h4 class="name">{{ ucfirst(Auth::user()->name ?? Auth::user()->username) }}</h4>
                                <p class="email">{{ Auth::user()->email ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div id="profile-form" class="profile-body">
                        <div class="profile-title">
                            <h5 id="profile-title" class="title">Personal Details</h5>
                            <a id="change-password-btn" class="profile-link d-none" href="{{ route('settings.user.resetPassword') }}">@lang('reset_password')</a>
                            <a id="edit-btn" class="profile-link" href="javascript:void(0);">@lang('edit')</a>
                            <a id="cancel-btn" class="profile-link d-none" href="javascript:void(0);" style="color: gray;">@lang('cancel')</a>
                        </div>
                        <div class="profile-details">
                        <div id="change-password" class="d-none">

                        </div>
                        <div id="info" class="info">
                            <div class="single-details-item d-flex align-items-center mb-2">
                                <label for="name" class="details-title me-3"><h6 class="title mb-0">@lang('full_name'):</h6></label>
                                <input type="text" id="name" name="name" value="{{ Auth::user()->name ?? '' }}" class="form-control" disabled style="border: none; background-color: transparent; width: 350px;">
                            </div>
                            <div class="single-details-item d-flex align-items-center mb-2">
                                <label for="username" class="details-title me-3"><h6 class="title mb-0">@lang('username'):</h6></label>
                                <input type="text" id="username" name="username" value="{{ Auth::user()->username ?? '' }}" class="form-control" disabled style="border: none; background-color: transparent; width: 350px;">
                            </div>
                            <div class="single-details-item d-flex align-items-center mb-2">
                                <label for="email" class="details-title me-3"><h6 class="title mb-0">@lang('email'):</h6></label>
                                <input type="email" id="email" name="email" value="{{ Auth::user()->email ?? '' }}" class="form-control" disabled style="border: none; background-color: transparent; width: 350px;">
                            </div>
                            <div class="single-details-item d-flex align-items-center mb-2">
                                <label for="phone" class="details-title me-3"><h6 class="title mb-0">@lang('phone'):</h6></label>
                                <input type="text" id="phone" name="phone" value="{{ Auth::user()->phone ?? '' }}" class="form-control" disabled style="border: none; background-color: transparent; width: 350px;">
                            </div>
                        </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ custom_asset('js/core/jquery-3.7.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('script')
<script>
$(document).ready(function() {
    $('#upload-image').change(function(event) {
        let input = event.target;
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#profile-image').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    });
    let isEditing = false;
    $('#cancel-btn').click(function() {
        $('#profile-form input').prop('disabled', true).css('color', 'rgb(26, 26, 26)');
        $('#edit-btn').text('@lang('edit')').css('right', '16px');
        $('#cancel-btn').addClass('d-none');
        $('#upload-image-label').addClass('d-none');
        isEditing = false;
    });
    $('#edit-btn').click(function() {
        if (!isEditing) {
            if ($(window).width() <= 768) {
                $('#profile-title').hide();
            }
            $('#profile-form input').prop('disabled', false).css('color', 'black');
            $('#cancel-btn').removeClass('d-none');
            $('#upload-image-label').removeClass('d-none');
            $('#change-password-btn').removeClass('d-none').css('right', '180px');
            $(this).text('@lang('save')').css('right', '100px');
            isEditing = true;
        } else {
            Swal.fire({
                title: 'Confirm Password',
                input: 'password',
                inputPlaceholder: 'Enter your password',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                showLoaderOnConfirm: true,
                preConfirm: (password) => {
                    return new Promise((resolve) => {
                        if (!password) {
                            Swal.showValidationMessage('Password is required');
                        } else {
                            resolve(password);
                        }
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    formData.append('name', $('#name').val());
                    formData.append('username', $('#username').val());
                    formData.append('email', $('#email').val());
                    formData.append('phone', $('#phone').val());
                    formData.append('password', result.value);
                    formData.append('_token', $('input[name="_token"]').val());
                    // Append file only if selected
                    let file = $('#upload-image')[0].files[0];
                    if (file) {
                        formData.append('attachment', file);
                    }

                    $.ajax({
                        url: "{{ route('settings.user.updateProfile') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false, // Important for file uploads
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire('Success', response.message, 'success').then(() => {
                                    window.location.reload();
                                });
                                $('#profile-form input').prop('disabled', true).css('color', 'rgb(26, 26, 26)');
                                $('#edit-btn').text('Edit');
                                isEditing = false;
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = 'An error occurred. Please try again.';
                            if (errors) {
                                errorMessage = Object.values(errors).join('<br>');
                            }
                            Swal.fire('Error', errorMessage, 'error');
                        }
                    });
                }
            });
        }
    });
});
</script>
@endsection

@endsection
