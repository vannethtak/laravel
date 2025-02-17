@extends('layouts.master')
@section('style')
    @yield('style')
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 p-0">
        <div class="card rounded-0 mb-4">
            <div class="card-header">
                @include('components.breadcrumb')
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

                <form autocomplete="off" action="{{ route($data['prefix_route'].'update', ['field_data' => $field_data ?? $data['field_data']->id ?? '']) }}" method="post" enctype="multipart/form-data">
                    @csrf

                    @yield('form')

                    @if (!empty($optional['form_order']) && $optional['form_order'] == 'N')
                        <div class="{{ config('setting.form_group_class') }} d-none">
                    @else
                        <div class="{{ config('setting.form_group_class') }}">
                    @endif
                        <label for="order" class="{{ config('setting.form_label_class') }}">{{ __('order') }}</label>
                        <div class="{{ config('setting.form_div_input_class') }}">
                            <input
                                class="{{ config('setting.form_input_class') }} @error('order') is-invalid @enderror"
                                type="number"
                                name="order"
                                id="order"
                                value="{{ old('order', $data['field_data']->order ?? 0) }}"
                                placeholder="{{ __('enter_') }}{{ __('order') }}"
                            >
                            @error('order')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="{{ config('setting.form_group_class') }}">
                        <label for="active" class="{{ config('setting.form_label_class') }}">{{ __('active') }}</label>
                        <div class="{{ config('setting.form_div_input_class') }}">
                            <div class="selectgroup w-100">
                                @foreach(['Y' => __('active_yes'), 'N' => __('active_no')] as $value => $label)
                                    <label class="selectgroup-item">
                                        <input
                                            type="radio"
                                            name="active"
                                            value="{{ $value }}"
                                            class="selectgroup-input"
                                            {{ old('active', $data['field_data']->active ?? 'Y') === $value ? 'checked' : '' }}
                                        >
                                        <span class="selectgroup-button">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('active')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>


                    @yield('form_end')
                    <div class="row pt-2 pb-2 px-2">
                        <div class="col-md-12 col-lg-12 ml-auto text-end">
                            <a href="{{ route($data['prefix_route'].'index') }}" class="{{config('setting.button_back_class')}}">
                                <i class="{{config('setting.icon_back')}}" style="padding-right: 10px;"></i> {{__('cancel')}}
                            </a>
                            <button type="submit" name="submit" value="save" class="{{config('setting.button_save_class')}}">
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
    @yield('script')
@endsection
@endsection
