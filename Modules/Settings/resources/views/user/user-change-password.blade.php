@extends('layouts.master')
@section('style')
    @yield('style')
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 p-0">
        <div class="card rounded-0 mb-4">
            <div class="card-header">
            <div class="page-header mb-0">
                <h3 class="fw-bold">@lang('reset_password')</h3>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="/dashboard">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('settings.user.profile') }}">@lang('profile')</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item active">
                        <a href="#">@lang('reset_password')</a>
                    </li>
                </ul>
            </div>
            </div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li class="text-danger">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form autocomplete="off" action="{{ route($data['prefix_route'].'resetPassword', ['field_data' => $data['field_data']->id]) }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="{{ config('setting.form_group_class') }}">
                        <label for="username" class="{{ config('setting.form_label_class') }}">{{ __('username') }}</label>
                        <div class="{{ config('setting.form_div_input_class') }}">
                            <input
                                class="{{ config('setting.form_input_class') }} @error('username') is-invalid @enderror"
                                type="text"
                                name="username"
                                id="username"
                                value="{{ old('username', $data['field_data']->username ?? '') }}"
                                placeholder="{{ __('enter_') }}{{ __('username') }}"
                                disabled
                                readonly
                            >
                            @error('username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="{{ config('setting.form_group_class') }}">
                        <label for="email" class="{{ config('setting.form_label_class') }}">{{ __('email') }}</label>
                        <div class="{{ config('setting.form_div_input_class') }}">
                            <input
                                class="{{ config('setting.form_input_class') }} @error('email') is-invalid @enderror"
                                type="text"
                                name="email"
                                id="email"
                                value="{{ old('email', $data['field_data']->email ?? 0) }}"
                                placeholder="{{ __('enter_') }}{{ __('email') }}"
                                disabled
                                readonly
                            >
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="{{ config('setting.form_group_class') }}">
                        <label for="current_password" class="{{ config('setting.form_label_class') }}">{{ __('current_password') }}</label>
                        <div class="{{ config('setting.form_div_input_class') }} position-relative">
                            <div class="input-icon">
                                <span toggle="#current_password" class="input-icon-addon fa fa-fw fa-eye field-icon toggle-password"></span>
                                <input
                                    class="{{ config('setting.form_input_class') }} @error('current_password') is-invalid @enderror"
                                    type="current_password"
                                    name="current_password"
                                    id="current_password"
                                    value="{{ old('current_password') }}"
                                    placeholder="{{ __('enter_') }}{{ __('current_password') }}"
                                    required >
                            </div>
                            @error('current_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="{{ config('setting.form_group_class') }}">
                        <label for="password" class="{{ config('setting.form_label_class') }}">{{ __('password') }}</label>
                        <div class="{{ config('setting.form_div_input_class') }} position-relative">
                            <div class="input-icon">
                                <span toggle="#password" class="input-icon-addon fa fa-fw fa-eye field-icon toggle-password"></span>
                                <input
                                    class="{{ config('setting.form_input_class') }} @error('password') is-invalid @enderror"
                                    type="password"
                                    name="password"
                                    id="password"
                                    value="{{ old('password') }}"
                                    placeholder="{{ __('enter_') }}{{ __('password') }}"
                                    required >
                            </div>
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="{{ config('setting.form_group_class') }}">
                        <label for="password_confirmation" class="{{ config('setting.form_label_class') }}">{{ __('confirm_password') }}</label>
                        <div class="{{ config('setting.form_div_input_class') }} position-relative">
                            <div class="input-icon">
                                <span toggle="#password_confirmation" class="input-icon-addon fa fa-fw fa-eye field-icon toggle-password"></span>
                                <input
                                    class="{{ config('setting.form_input_class') }} @error('password_confirmation') is-invalid @enderror"
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    value="{{ old('password_confirmation') }}"
                                    placeholder="{{ __('enter_') }}{{ __('confirm_password') }}"
                                    required
                                >
                            </div>
                            @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row pt-2 pb-2 px-2">
                        <div class="col-md-12 col-lg-12 ml-auto text-end">
                            <a href="{{ route('settings.user.profile') }}" class="btn btn-danger">
                                <i class="{{config('setting.icon_back')}}" style="padding-right: 10px;"></i> {{__('cancel')}}
                            </a>
                            <button type="submit" username="submit" value="save" class="btn btn-primary">
                                <i class="{{config('setting.icon_save')}}" style="padding-right: 10px;"></i> {{__('save')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@section('script')
<script>
    $(document).on('click', '.toggle-password', function() {
        $(this).toggleClass('fa-eye fa-eye-slash');
        var input = $($(this).attr('toggle'));
        if (input.attr('type') == 'password') {
            input.attr('type', 'text');
        } else {
            input.attr('type', 'password');
        }
    });
</script>
@endsection
@endsection
