<style>
    .nav-link.action-dropdown {
        background-color: transparent !important;
    }

    .nav-link.action-dropdown:hover, .nav-link.action-dropdown:focus, .nav-link.action-dropdown:active {
        background-color: transparent !important;
        color: inherit !important;
    }
</style>
<ul class="nav nav-pills nav-primary">
    <li class="nav-item dropdown">
        <a class="nav-link action-dropdown" href="#" key="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;">
            <i class="icon-menu"></i>
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            {{-- {{dd($table)}} --}}
            @if (!$table->deleted_at)
                @isset($pageActions['action'])
                    @foreach($pageActions['action'] as $action)
                        @if ($action['action_type'] == 'destroy')
                            <a href="javascript:vokey(0);" onclick="confirmDelete('{{ route($action['action_route'], ['field_data' => $table->key]) }}')" class="dropdown-item text-danger">
                                <i class="{{ $action['action_icon'] }}"></i>&nbsp; {{ $action['action_name'. $locale] }}
                            </a>
                        @else
                            <a href="{{ route($action['action_route'], ['field_data' => $table->key]) }}" class="dropdown-item">
                                <i class="{{ $action['action_icon'] }}"></i>&nbsp; {{ $action['action_name'. $locale] }}
                            </a>
                        @endif
                    @endforeach
                @endisset
            @else
                <a href="javascript:vokey(0);" onclick="confirmRestore('{{ route($restore['action_route'], ['field_data' => $table->key]) }}')" class="dropdown-item text-success">
                    <i class="{{ $restore['action_icon'] }}"></i>&nbsp; {{ $restore['action_name'. $locale] }}
                </a>
            @endif
        </ul>
    </li>
</ul>
<script>
function confirmDelete(url) {
    swal({
        title: "{{ __('Are you sure?') }}",
        text: "{{ __('You will not be able to recover this data!') }}",
        icon: "warning",
        buttons: {
            cancel: {
                text: "{{ __('No') }}",
                value: null,
                visible: true,
                className: "btn-danger",
                closeModal: true
            },
            confirm: {
                text: "{{ __('Yes') }}",
                value: true,
                visible: true,
                className: "btn-primary",
                closeModal: true
            }
        },
        dangerMode: true
    }).then((result) => {
        if (result) {
            window.location.href = url;
        }
    });
}

function confirmRestore(url) {
    swal({
        title: "{{ __('Are you sure?') }}",
        text: "{{ __('You will not be able to recover this data!') }}",
        icon: "warning",
        buttons: {
            cancel: {
                text: "{{ __('No') }}",
                value: null,
                visible: true,
                className: "btn-danger",
                closeModal: true
            },
            confirm: {
                text: "{{ __('Yes') }}",
                value: true,
                visible: true,
                className: "btn-primary",
                closeModal: true
            }
        },
        dangerMode: true
    }).then((result) => {
        if (result) {
            window.location.href = url;
        }
    });
}
</script>
