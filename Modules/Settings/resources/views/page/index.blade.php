@extends('layouts.pages.index')
@section('style')

@endsection

@section('filter')
    @php $filterClass = 'col-md-6 col-lg-4 p-0'; @endphp
    <div class="{{ $filterClass ?? 'col-md-6 col-lg-4 p-0' }}">
        <div class="form-group">
            <label for="name">{{__('name')}}</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ request('name') }}" />
        </div>
    </div>
    <div class="{{ $filterClass ?? 'col-md-6 col-lg-4 p-0' }}">
        <div class="form-group">
            <label for="module_id">{{__('module')}}</label>
            <select class="form-control" id="module_id" name="module_id">
                <option value="">{{ __('please_select') }}</option>
                @foreach($data['module'] as $module)
                    <option value="{{ $module->id }}">{{ $module->name ?? '' }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection
@section('button')

@endsection
@section('script')

@endsection
