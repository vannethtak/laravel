@extends('layouts.pages.create')

@section('style')
@endsection

@section('form')
<div class="{{ config('setting.form_group_class') }}">
    <label for="name_en" class="{{ config('setting.form_label_class') }}">{{ __('module_name_en') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('name_en') is-invalid @enderror"
            type="text"
            name="name_en"
            id="name_en"
            value="{{ old('name_en') }}"
            placeholder="{{ __('enter_') }}{{ __('module_name_en') }}"
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
    <label for="name_kh" class="{{ config('setting.form_label_class') }}">{{ __('module_name_kh') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('name_kh') is-invalid @enderror"
            type="text"
            name="name_kh"
            id="name_kh"
            value="{{ old('name_kh') }}"
            placeholder="{{ __('enter_') }}{{ __('module_name_kh') }}"
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
    <label for="icon" class="{{ config('setting.form_label_class') }}">{{ __('icon') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('icon') is-invalid @enderror"
            type="text"
            name="icon"
            id="icon"
            value="{{ old('icon') }}"
            placeholder="{{ __('enter_icon') }}"
            {{-- required --}}
        >
        @error('icon')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
@endsection

@section('script')
@endsection
