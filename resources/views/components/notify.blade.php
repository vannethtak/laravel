@if(session('notify'))
<script>
    $(document).ready(function() {
        // Notify
        $.notify({
            icon: '{{ session('notify.icon') }}',
            title: '{{ session('notify.title') }}',
            message: '{{ session('notify.message') }}',
        },{
            type: '{{ session('notify.type') }}',
            placement: {
                from: "{{ session('notify.placement.from', 'bottom') }}",
                align: "{{ session('notify.placement.align', 'right') }}"
            },
            delay: {{ session('notify.delay', 1000) }},
        });
    });
</script>
@endif
