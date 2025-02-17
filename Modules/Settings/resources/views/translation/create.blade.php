@extends('layouts.pages.create')

@section('style')
@endsection
@php
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
                value="{{ old('key') }}"
                placeholder="{{ __('enter_key') }}"
                required
            >
            @error('key')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

    @foreach ($data['locales'] as $locale)
        <div class="form-group row align-items-center">
            <label for="value_{{ $locale }}" class="col-sm-2 col-form-label text-end">{{ __('value_'. $locale) }}</label>
            <div class="col-sm-10">
                <input
                    class="form-control @error('value_' . $locale) is-invalid @enderror"
                    type="text"
                    name="value_{{ $locale }}"
                    id="value_{{ $locale }}"
                    value="{{ old('value_' . $locale) }}"
                    placeholder="{{ __('value_'. $locale) }}"
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
