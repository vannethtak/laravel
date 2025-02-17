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

                <form autocomplete="off" action="{{ route($data['prefix_route'].'store') }}" method="post" enctype="multipart/form-data">
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
                                value="{{ old('order') }}"
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
                                <label class="selectgroup-item">
                                  <input type="radio" name="active" value="Y" class="selectgroup-input" checked="">
                                  <span class="selectgroup-button">{{ __('active_yes') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                  <input type="radio" name="active" value="N" class="selectgroup-input">
                                  <span class="selectgroup-button">{{ __('active_no') }}</span>
                                </label>
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
                            <button type="submit" name="submit" value="save_new" class="{{config('setting.button_save_new_class')}}">
                                <i class="{{config('setting.icon_save_new')}}" style="padding-right: 10px;"></i> {{__('save-new')}}
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
