@extends('layouts.pages.edit')

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
            required
        >
            <option value="">{{ __('please_select') }}</option>
            @foreach($data['roles'] as $role)
                <option value="{{ $role['id'] }}" {{ old('role_id', $data['field_data']->role_id ?? '') == $role['id'] ? 'selected' : '' }}>
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
                value="{{ old('name', $data['field_data']->name ?? '') }}"
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
                value="{{ old('phone', $data['field_data']->phone ?? '') }}"
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
<div class="form-group row align-items-center">
    <label for="attachment" class="col-sm-2 col-form-label text-end">{{ __('attachment_image') }}</label>
    <div class="col-sm-10">
        <input
            class="form-control @error('attachment') is-invalid @enderror"
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
        <div class="mt-2">
            <img id="attachment-preview" src="#" alt="{{ __('attachment_preview') }}" style="display: none; max-height: 200px;">
        </div>
        @if(assetFile(config('setting.disk_name'), $data['field_data']->avatar))
            <div id="old_image">
                <input type="hidden" name="attachment_" value="{{ $data['field_data']->avatar }}">
                <div class="d-flex align-items-center">
                    <img src="{{ assetFile(config('setting.disk_name'), $data['field_data']->avatar) }}" alt="{{ __('attachment_preview') }}" style="max-height: 200px;">
                </div>
                <a href="javascript:void(0)"
                    class="btn btn-danger btn-sm mt-2 text-center"
                    style="width: 200px;"
                    onclick="confirmDelete()">
                    <i class="fas fa-trash-alt"></i> {{ __('delete') }}
                </a>
            </div>
        @endif
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
@include($data['load_page'].'edit-script')
@endsection
