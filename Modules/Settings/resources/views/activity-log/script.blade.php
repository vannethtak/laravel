<script>
    $(function() {
        $('body').on('click', '.show_properties', function() {
            var log_id = $(this).attr('log_id');

            $.ajax({
                type: "get",
                url: '{{ route('settings.systemlog.showDetail') }}',
                data: { log_id: log_id },
                dataType: "json",
                success: function(response) {
                    $('#modal_show_property').modal('show');
                    $('.log_lists').html(response.trs);
                    $('.activity_log').text(response.event);
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        });
    });
</script>
