@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    <div class="row">
        @foreach ([
            ['icon' => 'table', 'color' => 'primary', 'label' => 'table', 'id' => 'count-table'],
            ['icon' => 'file-alt', 'color' => 'secondary', 'label' => 'page', 'id' => 'count-page'],
            ['icon' => 'cubes', 'color' => 'success', 'label' => 'module', 'id' => 'count-module'],
            ['icon' => 'users', 'color' => 'info', 'label' => 'user', 'id' => 'count-user'],
        ] as $card)
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-{{ $card['color'] }} bubble-shadow-small">
                                <i class="fas fa-{{ $card['icon'] }}"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">@lang($card['label'])</p>
                                <h4 class="card-title counter" id="{{ $card['id'] }}">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">User Statistics</div>
                        <div class="card-tools">
                            <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                                <span class="btn-label"><i class="fa fa-pencil"></i></span> Export
                            </a>
                            <a href="#" class="btn btn-label-info btn-round btn-sm">
                                <span class="btn-label"><i class="fa fa-print"></i></span> Print
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 375px">
                        <canvas id="statisticsChart"></canvas>
                    </div>
                    <div id="myChartLegend"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-primary card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Daily Sales</div>
                        <div class="card-tools">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-label-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Export
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-category">March 25 - April 02</div>
                </div>
                <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                        <h1>$4,578.58</h1>
                    </div>
                    <div class="pull-in">
                        <canvas id="dailySalesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="card card-round">
                <div class="card-body pb-0">
                    <div class="h1 fw-bold float-end text-primary">+5%</div>
                    <h2 class="mb-2">17</h2>
                    <p class="text-muted">Users online</p>
                    <div class="pull-in sparkline-fix">
                        <div id="lineChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ custom_asset('js/core/jquery-3.7.1.min.js') }}"></script>
<script>
    function animateCounter(element, start, end, duration) {
        let current = start;
        let increment = (end - start) / (duration / 10);
        let interval = setInterval(() => {
            current += increment;
            if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                clearInterval(interval);
                current = end;
            }
            element.text(Math.floor(current));
        }, 10);
    }

    $(document).ready(function () {
        $.ajax({
            url: '{{ route("dashboard.count") }}',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                ['table', 'user', 'module', 'page'].forEach(type => {
                    animateCounter($(`#count-${type}`), 0, data[type + 's'], 1700);
                    // $('#count-' + type).text(data[type + 's']);
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching dashboard data:", error);
            }
        });
    });
</script>
@endsection
