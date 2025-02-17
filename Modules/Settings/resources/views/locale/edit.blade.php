@extends('layouts.pages.edit')

@section('style')
@endsection

@section('form')
<div class="{{ config('setting.form_group_class') }}">
    <label for="locale" class="{{ config('setting.form_label_class') }}">{{ __('locale') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('locale') is-invalid @enderror"
            type="text"
            name="locale"
            id="locale"
            value="{{ old('locale', $data['field_data']->locale) }}"
            placeholder="{{ __('enter_') }}{{ __('locale') }}"
            required
        >
        @error('locale')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
<div class="{{ config('setting.form_group_class') }}">
    <label for="locale" class="{{ config('setting.form_label_class') }}">{{ __('translations') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('translations') is-invalid @enderror"
            type="text"
            name="translations"
            id="translations"
            value="{{ old('translations', $data['field_data']->translations) }}"
            placeholder="{{ __('enter_') }}{{ __('translations') }}"
            required
        >
        @error('translations')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
<div class="{{ config('setting.form_group_class') }}">
    <label for="locale" class="{{ config('setting.form_label_class') }}">{{ __('attachment_image') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('attachment') is-invalid @enderror"
            type="file"
            name="attachment"
            id="attachment"
            accept="image/*"
            onchange="previewLogo(event)"
        >
        @error('attachment')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
        <div class="mt-2">
            <img id="attachment-preview" src="#" alt="{{ __('attachment_preview') }}" style="display: none; max-height: 200px;">
        </div>
        @if(assetFile(config('setting.disk_name'), $data['field_data']->logo))
        <div id="old_image">
            <input type="hidden" name="attachment_" value="{{ $data['field_data']->logo }}">
            <div class="d-flex align-items-center">
                <img src="{{ assetFile(config('setting.disk_name'), $data['field_data']->logo) }}" alt="{{ __('attachment_preview') }}" style="max-height: 200px;">
            </div>
            <a href="javascript:void(0)"
                class="btn btn-danger btn-sm mt-2 text-center"
                style="width: 255px;"
                onclick="confirmDelete()">
                <i class="fas fa-trash-alt"></i> {{ __('delete') }}
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('form_end')
@endsection

@section('script')
@endsection
