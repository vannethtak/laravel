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
@endsection
@section('button')

@endsection
@section('script')

@endsection
