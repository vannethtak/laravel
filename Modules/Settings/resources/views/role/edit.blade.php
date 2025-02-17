@extends('layouts.pages.edit')

@section('style')
    @include($data['load_page'].'style')
@endsection

@section('form')
<div class="{{ config('setting.form_group_class') }}">
    <label for="name_en" class="{{ config('setting.form_label_class') }}">{{ __('role_name_en') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('name_en') is-invalid @enderror"
            type="text"
            name="name_en"
            id="name_en"
            value="{{ old('name_en', $data['field_data']->name_en ?? '') }}"
            placeholder="{{ __('enter_') }}{{ __('role_name_en') }}"
            required
        >
        @error('name_en')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
<div class="{{ config('setting.form_group_class') }}">
    <label for="name_kh" class="{{ config('setting.form_label_class') }}">{{ __('role_name_kh') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('name_kh') is-invalid @enderror"
            type="text"
            name="name_kh"
            id="name_kh"
            value="{{ old('name_kh', $data['field_data']->name_kh ?? '') }}"
            placeholder="{{ __('enter_') }}{{ __('role_name_kh') }}"
            required
        >
        @error('name_kh')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
<div class="{{ config('setting.form_group_class') }}">
    <label for="slug" class="{{ config('setting.form_label_class') }}">{{ __('slug') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('slug') is-invalid @enderror"
            type="text"
            name="slug"
            id="slug"
            value="{{ old('slug', $data['field_data']->slug ?? '') }}"
            placeholder="{{ __('enter_') }}{{ __('slug') }}"
            required
        >
        @error('slug')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
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
                <input id="module_{{ $module['id'] }}" name="module_id[]" type="checkbox" class="form-check-input"
                @if(in_array($module['id'], $data['rolePermission'] ?? [])) checked @endif>
                <div class="state">
                    <img class="svg" src="{{ custom_asset('image/check-circle.svg') }}"/>
                    <label for="module_{{ $module['id'] }}">
                        {{ $module['name'] }}
                    </label>
                </div>
            </div>
            @isset($data['page'])
            <ul class="checktree">
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
                        <ul class="checktree">
                            @foreach ($data['pageAction'] as $pageAction)
                                @if ($pageAction['page_id'] == $page['id'])
                                <li>
                                    <div class="pretty p-svg p-plain p-bigger p-smooth">
                                        <input id="action_{{ $pageAction['id'] }}" value="{{ $pageAction['id'] }}" name="action_id[]" type="checkbox" class="form-check-input"
                                        @if(in_array($pageAction['id'], $data['rolePermission'] ?? [])) checked @endif>
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
