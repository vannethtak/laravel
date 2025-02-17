@extends('layouts.master')

@section('style')
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 p-0">
        <div class="card rounded-0 mb-4">
            <div class="card-header">
                @include('components.breadcrumb')
            </div>
            <div class="card-body">
                <form id="filter" autocomplete="off">
                    <div class="row">
                        @php $filterClass = 'col-md-6 col-lg-4 p-0'; @endphp
                        <div class="{{ $filterClass }}">
                            <div class="form-group">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ request('name') }}" />
                            </div>
                        </div>
                        <div class="{{ $filterClass }}">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" id="search-btn">
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    <i class="fas fa-search" style="padding-right: 10px;"></i> {{ __('search') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12 p-0">
        <div class="card rounded-0">
            <div class="card-body">
                <div class="table-responsive">
                    {!! $dataTable->table(['class' => config('setting.datatable_card')], true) !!}
                </div>
            </div>
        </div>
    </div>
</div>

@include($data['load_page'].'modal-details')

<script src="{{ custom_asset('js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ custom_asset('js/plugin/datatables/datatables.min.js') }}"></script>
<script src="{{ custom_asset('js/datatables' . $locale . '.js') }}"></script>
@section('script')
    @include($data['load_page'].'script')
@endsection

{!! $dataTable->scripts() !!}
@endsection
