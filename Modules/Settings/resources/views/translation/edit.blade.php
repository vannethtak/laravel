@extends('layouts.pages.edit')

@section('style')
@endsection
@php
    $field_data = reset($data['field_data'])['key'] ?? '';
    $optional['form_order'] = 'N';
@endphp
@section('form')
    <div class="form-group row align-items-center">
        <label for="key" class="col-sm-2 col-form-label text-end">{{ __('key') }}</label>
        <div class="col-sm-10">
            <input
                class="form-control @error('key') is-invalid @enderror"
                type="text"
                name="key"
                id="key"
                value="{{ old('key', $field_data) }}"
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

    @foreach ($data['locales'] as $locale_id => $locale)
        <div class="form-group row align-items-center">
            <label for="value_{{ $locale }}" class="col-sm-2 col-form-label text-end">{{ __('value_' . $locale) }}</label>
            <div class="col-sm-10">
                <input
                    class="form-control @error('value_' . $locale) is-invalid @enderror"
                    type="text"
                    name="value_{{ $locale }}"
                    id="value_{{ $locale }}"
                    value="{{ old('value_' . $locale, $data['field_data'][$locale_id]['value'] ?? '') }}"
                    placeholder="{{ __('value_' . $locale) }}"
                    required
                >
                @error('value_' . $locale)
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    @endforeach
@endsection

@section('form_end')
@endsection

@section('script')
@endsection
