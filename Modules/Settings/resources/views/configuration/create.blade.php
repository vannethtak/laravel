@extends('layouts.pages.create')

@section('style')

@endsection
@php
    $optional['form_order'] = 'N';
@endphp
@section('form')
<div class="{{ config('setting.form_group_class') }}">
    <label for="key" class="{{ config('setting.form_label_class') }}">{{ __('key') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('key') is-invalid @enderror"
            type="text"
            name="key"
            id="key"
            value="{{ old('key') }}"
            placeholder="{{ __('enter_') }}{{ __('key') }}"
            required
        >
        @error('key')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

<div class="{{ config('setting.form_group_class') }}">
    <label for="value" class="{{ config('setting.form_label_class') }}">{{ __('value') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('value') is-invalid @enderror"
            type="text"
            name="value"
            id="value"
            value="{{ old('value') }}"
            placeholder="{{ __('enter_') }}{{ __('value') }}"
            required
        >
        @error('value')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

<div class="{{ config('setting.form_group_class') }}">
    <label for="datatype" class="{{ config('setting.form_label_class') }}">{{ __('datatype') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <select
            class="{{ config('setting.form_input_class') }} @error('datatype') is-invalid @enderror"
            name="datatype"
            id="datatype"
            required
        >
            <option value="string" {{ old('datatype') == 'string' ? 'selected' : '' }}>{{ __('string') }}</option>
            <option value="number" {{ old('datatype') == 'number' ? 'selected' : '' }}>{{ __('number') }}</option>
        </select>
        @error('datatype')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
@endsection

@section('form_end')

@endsection
@section('script')

@endsection
