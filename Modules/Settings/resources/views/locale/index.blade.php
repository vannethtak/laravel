@extends('layouts.master')
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
                        <div class="col-md-6 col-lg-4 p-0">
                            <div class="form-group">
                                <label for="name">{{__('name')}}</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ request('name') }}" />
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 p-0">
                            <div class="form-group">
                                <label for="soft_delete">{{__('soft_delete')}}</label>
                                <select class="form-control" id="soft_delete" name="soft_delete">
                                    <option value="">{{__('please_select')}}</option>
                                    <option value="deleted" {{ request('soft_delete') == 'deleted' ? 'selected' : '' }}>{{__('deleted')}}</option>
                                    <option value="all_records" {{ request('soft_delete') == 'all_records' ? 'selected' : '' }}>{{__('all_records')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 p-0">
                            <div class="form-group">
                                <label for="active">{{__('active')}}</label>
                                <select class="form-control" id="active" name="active">
                                    <option value="">{{__('please_select')}}</option>
                                    <option value="Y" {{ request('active') == 'Y' ? 'selected' : '' }}>{{__('active_yes')}}</option>
                                    <option value="N" {{ request('active') == 'N' ? 'selected' : '' }}>{{__('active_no')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-2 pb-2" style="padding-left: 10px; padding-right: 10px;">
                        <div class="col-md-12 col-lg-12 ml-auto text-start p-0">
                            <button type="submit" class="btn btn-primary" ixd="search-btn">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <i class="fas fa-search" style="padding-right: 10px;"></i> {{__('search')}}
                            </button>
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
<script src="{{ custom_asset('js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ custom_asset('js/plugin/datatables/datatables.min.js') }}"></script>
<script src="{{ custom_asset('js/datatables' . $locale . '.js') }}"></script>
{!! $dataTable->scripts() !!}
@endsection
