<div class="page-header mb-0">
    @if(isset($breadcrumbs))
        <h3 class="fw-bold">{{ $breadcrumbs['page_action']['name' . $locale ] ?? '' }}</h3>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="/dashboard">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route($breadcrumbs['page_action']['route_name']) }}">{{ $breadcrumbs['page_action']['name' . $locale] ?? '' }}</a>
            </li>
            @if ($breadcrumbs['current_action']['type'] !== 'index')
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item active">
                    <a href="#">{{ $breadcrumbs['current_action']['name' . $locale] ?? '' }}</a>
                </li>
            @endif
        </ul>
    @endif
    @isset($breadcrumbs['current_action']['type'])
        @if($breadcrumbs['current_action']['type'] == 'index')
            <div class="ms-md-auto py-2 py-md-0">
                <ul class="nav nav-pills nav-primary">
                    @if (isset($pageActions['other']))
                        @if (count($pageActions['other']) > 1)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-cogs px-2"></i> {{ ucfirst(__('action')) }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    @foreach ($pageActions['other'] as $action)
                                        @if (Route::has($action['action_route']))
                                            @if ($action['action_type'] == 'exported')
                                                <li>
                                                    <a class="dropdown-item" id="{{$action['action_type'] ?? ''}}" href="{{ route($action['action_route'], ['export_to' => 'excel']) }}">
                                                        <i class="{{ $action['action_icon'] }} px-2"></i> {{ $action['action_name'. $locale ] }}
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    <a class="dropdown-item" id="{{$action['action_type'] ?? ''}}" href="{{ route($action['action_route']) }}">
                                                        <i class="{{ $action['action_icon'] }} px-2"></i>
                                                        {{ $action['action_name'. $locale ] }}
                                                    </a>
                                                </li>
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            @foreach ($pageActions['other'] as $action)
                                @if (Route::has($action['action_route']))
                                <li class="nav-item">
                                    @if ($action['action_type'] == 'exported')
                                        <li class="nav-item">
                                            <a class="nav-link" id="{{$action['action_type'] ?? ''}}" href="{{ route($action['action_route'], ['export_to' => 'excel']) }}">
                                                <i class="{{ $action['action_icon'] }} px-2"></i> {{ ($action['action_name'. $locale ] ?? $action['action_name_en' ]) }}
                                            </a>
                                        </li>
                                    @else
                                        <li class="nav-item">
                                            <a class="nav-link" id="{{$action['action_type'] ?? ''}}" href="{{ route($action['action_route']) }}">
                                                <i class="{{ $action['action_icon'] }} px-2"></i> {{ ($action['action_name'. $locale ] ?? $action['action_name_en' ]) }}
                                            </a>
                                        </li>
                                    @endif
                                </li>
                                @endif
                            @endforeach
                        @endif
                    @endif
                    @if (isset($pageActions['top']))
                        @foreach ($pageActions['top'] as $action)
                            @if (Route::has($action['action_route']))
                                <li class="nav-item">
                                    <a class="nav-link" id="{{$action['action_type'] ?? ''}}" href="{{ route($action['action_route']) }}">
                                        <i class="{{ $action['action_icon'] }} px-2"></i> {{ ($action['action_name'. $locale ] ?? $action['action_name_en' ]) }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
        @endif
    @endisset
</div>
