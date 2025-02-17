@extends('layouts.pages.create')

@section('style')
    @include($data['load_page'].'style')
@endsection
@php
    $optional['form_order'] = 'N';
@endphp
@section('form')

<div class="{{ config('setting.form_group_class') }}">
    <label for="role_id" class="{{ config('setting.form_label_class') }}">{{ __('role') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <select
            class="{{ config('setting.form_input_class') }} @error('role_id') is-invalid @enderror"
            name="role_id"
            id="role_id"
            value="{{ old('role_id') }}"
            required
        >
            <option value="">{{ __('please_select') }}</option>
            @foreach($data['roles'] as $role)
                <option value="{{ $role['id'] }}" {{ old('role_id') == $role['id'] ? 'selected' : '' }}>
                    {{ $role['name'] }}
                </option>
            @endforeach
        </select>
        @error('role_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

<div class="{{ config('setting.form_group_class') }}">
    <label for="name" class="{{ config('setting.form_label_class') }}">{{ __('full_name') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <div class="input-icon">
            <span class="input-icon-addon">
                <i class="fas fa-user-circle"></i>
            </span>
            <input
                class="{{ config('setting.form_input_class') }} @error('name') is-invalid @enderror"
                type="text"
                name="name"
                id="name"
                value="{{ old('name') }}"
                placeholder="{{ __('enter_') }}{{ __('name') }}"
                required
            >
        </div>
        @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

<div class="{{ config('setting.form_group_class') }}">
    <label for="username" class="{{ config('setting.form_label_class') }}">{{ __('username') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <div class="input-icon">
            <span class="input-icon-addon">
              <i class="fa fa-user"></i>
            </span>
            <input
                class="{{ config('setting.form_input_class') }} @error('username') is-invalid @enderror"
                type="text"
                name="username"
                id="username"
                value="{{ old('username') }}"
                placeholder="{{ __('enter_') }}{{ __('username') }}"
                required
            >
        </div>
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
        <div class="input-icon">
            <span class="input-icon-addon">
                <i class="fa fa-envelope"></i>
            </span>
            <input
                class="{{ config('setting.form_input_class') }} @error('email') is-invalid @enderror"
                type="text"
                name="email"
                id="email"
                value="{{ old('email') }}"
                placeholder="{{ __('enter_') }}{{ __('email') }}"
                required
            >
        </div>
        @error('email')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

<div class="{{ config('setting.form_group_class') }}">
    <label for="phone" class="{{ config('setting.form_label_class') }}">{{ __('phone') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <div class="input-icon">
            <span class="input-icon-addon">
                <i class="fas fa-phone"></i>
            </span>
            <input
                class="{{ config('setting.form_input_class') }} @error('phone') is-invalid @enderror"
                type="text"
                name="phone"
                id="phone"
                value="{{ old('phone') }}"
                placeholder="{{ __('enter_') }}{{ __('phone') }}"
                required
            >
        </div>
        @error('phone')
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

<div class="{{ config('setting.form_group_class') }}">
    <label for="attachment" class="{{ config('setting.form_label_class') }}">{{ __('attachment_image') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('attachment') is-invalid @enderror"
            type="file"
            name="attachment"
            id="attachment"
            accept="image/*"
            onchange="previewLogo(event)"
            {{-- required --}}
        >
        @error('attachment')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
        <div class="attachment-preview-container">
            <img id="attachment-preview" src="#" alt="{{ __('attachment_preview') }}" style="display: none; max-height: 200px;">
            <div class="attachment-preview-overlay">
                <a href="#" class="text-danger" style="font-size: 2.5rem;" onclick="deleteAttachmentPreview()">&times;</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('form_end')
<div class="p-3 mb-3 border rounded table-responsive text-nowrap" style="line-height: 2;">
    <p class=""><span id="actionCount">សកម្មភាពអនុញ្ញាត (0)</span></p>
    <ul class="checktree">
        @isset($data['modules'])
        @foreach ($data['modules'] as $module)
        <li>
            <div class="pretty p-svg p-plain p-bigger p-smooth">
                <input id="module_{{ $module['id'] }}" name="module_id[]" type="checkbox" class="form-check-input">
                <div class="state">
                    <img class="svg" src="{{ custom_asset('image/check-circle.svg') }}"/>
                    <label for="module_{{ $module['id'] }}">
                        {{ $module['name'] }}
                    </label>
                </div>
            </div>
            @isset($data['page'])
            <ul>
                @foreach ($data['page'] as $page)
                    @if ($page['module_id'] == $module['id'])
                    <li>
                        <div class="pretty p-svg p-plain p-bigger p-smooth">
                            <input id="page_{{ $page['id'] }}" name="page_id[]" type="checkbox" class="form-check-input">
                            <div class="state">
                                <img class="svg" src="{{ custom_asset('image/check-circle.svg') }}"/>
                                <label for="page_{{ $page['id'] }}">
                                    {{ $page['name'] }}
                                </label>
                            </div>
                        </div>
                        @isset($data['pageAction'])
                        <ul>
                            @foreach ($data['pageAction'] as $pageAction)
                                @if ($pageAction['page_id'] == $page['id'])
                                <li>
                                    <div class="pretty p-svg p-plain p-bigger p-smooth">
                                        <input id="action_{{ $pageAction['id'] }}" value="{{ $pageAction['id'] }}" name="action_id[]" type="checkbox" class="form-check-input">
                                        <div class="state">
                                            <img class="svg" src="{{ custom_asset('image/check-circle.svg') }}"/>
                                            <label for="action_{{ $pageAction['id'] }}">
                                                {{ $pageAction['name'] }}
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                        @endisset
                    </li>
                    @endif
                @endforeach
            </ul>
            @endisset
        </li>
        @endforeach
        @endisset
    </ul>
</div>
@endsection
@section('script')
@include($data['load_page'].'script')
@endsection
