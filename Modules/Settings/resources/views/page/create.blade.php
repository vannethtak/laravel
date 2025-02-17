@extends('layouts.pages.create')

@section('style')
<style>
    .table>tbody>tr>td, .table>tbody>tr>th {
        padding: 16px 4px !important;
    }
</style>
@endsection

@section('form')
<div class="form-group row align-items-center">
    <label for="module_id" class="col-sm-2 col-form-label text-end">{{ __('module') }}</label>
    <div class="col-sm-10">
        <select class="form-control @error('module_id') is-invalid @enderror" name="module_id" id="module_id" required>
            <option value="">{{ __('please_select') }}</option>
            @foreach ($data['module'] as $module)
                <option value="{{ $module->id }}" {{ old('module_id', $data['field_data']->module_id ?? '') === $module->id ? 'selected' : '' }}>
                    {{ $module->name_en }} ({{ $module->name_kh }})
                </option>
            @endforeach
        </select>
        @error('module_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
<div class="{{ config('setting.form_group_class') }}">
    <label for="name_en" class="{{ config('setting.form_label_class') }}">{{ __('page_name_en') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('name_en') is-invalid @enderror"
            type="text"
            name="name_en"
            id="name_en"
            value="{{ old('name_en') }}"
            placeholder="{{ __('enter_') }}{{ __('name_en') }}"
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
    <label for="name_kh" class="{{ config('setting.form_label_class') }}">{{ __('page_name_kh') }}</label>
    <div class="{{ config('setting.form_div_input_class') }}">
        <input
            class="{{ config('setting.form_input_class') }} @error('name_kh') is-invalid @enderror"
            type="text"
            name="name_kh"
            id="name_kh"
            value="{{ old('name_kh') }}"
            placeholder="{{ __('enter_') }}{{ __('name_kh') }}"
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

@section('form_end')
<div class="row">
    <div class="col-md-12">
        <div class="card rounded-0">
            <div class="card-header bg-light text-dark bg-opacity-100">
                @lang('action_list')
            </div>
            <div class="card-body py-0 m-0">
                <div class="table-responsive">
                    <table class="table py-0 m-0">
                        <thead class="pt-0 mt-0 text-center">
                            <tr class="text-nowrap">
                                <th scope="col">@lang('action_name_en') <span class="text-danger">*</span></th>
                                <th scope="col">@lang('action_name_kh') <span class="text-danger">*</span></th>
                                <th scope="col">@lang('action_route') <span class="text-danger">*</span></th>
                                <th scope="col">@lang('action_type') <span class="text-danger">*</span></th>
                                <th scope="col">@lang('action_position') <span class="text-danger">*</span></th>
                                <th scope="col">@lang('action_icon')</th>
                                <th scope="col">@lang('active')</th>
                                <th scope="col">
                                    <a href="#" id="add-row-btn" class="btn btn-primary m-0 d-flex align-items-center">
                                        <i class="fas fa-plus me-2"></i><span>@lang('create')</span>
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-end">
                            <tr>
                                <td class="p-0">
                                    <input type="text" class="form-control m-0" id="action_name_en" name="action_name_en[]" placeholder="@lang('enter_')@lang('action_name_en')" value="{{ old('action_name_en.0') }}" />
                                </td>
                                <td class="p-0">
                                    <input type="text" class="form-control m-0" id="action_name_kh" name="action_name_kh[]" placeholder="@lang('enter_')@lang('action_name_kh')" value="{{ old('action_name_kh.0') }}" />
                                </td>
                                <td class="p-0">
                                    <input type="text" class="form-control m-0" id="action_route" name="action_route[]" placeholder="@lang('enter_')@lang('action_route')" value="{{ old('action_route.0', 'index') }}" />
                                </td>
                                <td class="p-0">
                                    <input type="text" class="form-control m-0" id="action_type" name="action_type[]" placeholder="@lang('enter_')@lang('action_type')" value="{{ old('action_type.0', 'index') }}" />
                                </td>
                                <td class="p-0">
                                    <select class="form-control m-0" id="action_position" name="action_position[]">
                                        @foreach (\App\Enums\ActionPositionEnum::casesArray() as $key => $label)
                                            <option value="{{ $key }}">
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-0">
                                    <input type="text" class="form-control m-0" id="action_icon" name="action_icon[]" placeholder="@lang('enter_')@lang('action_icon')" value="{{ old('action_icon.0') }}" />
                                </td>
                                <td class="p-0">
                                    <select class="form-control m-0" id="action_active" name="action_active[]">
                                        <option value="Y">@lang('active_yes')</option>
                                        <option value="N">@lang('active_no')</option>
                                    </select>
                                </td>
                                <td class="p-0">
                                    {{-- <a href="#" class="btn btn-danger m-0 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-trash me-2"></i><span>@lang('delete')</span>
                                    </a> --}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@include($data['load_page'].'script')
@endsection
