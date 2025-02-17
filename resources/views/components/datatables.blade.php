<script>
    $(document).ready(function () {
        var tableName = "{{ $data['table_name'] }}";

        $("#filter").on("submit", function (e) {
            e.preventDefault();

            const $button = $("#search-btn");
            toggleSpinner($button, true);

            $.ajax({
                url: "{{ request()->url() }}",
                type: "GET",
                data: $(this).serialize(),
                success: () => $("#" + tableName + "-table").DataTable().ajax.reload(),
                error: () => alert("An error occurred. Please try again."),
                complete: () => toggleSpinner($button, false)
            });
        });

        function toggleSpinner(button, isLoading) {
            button.prop("disabled", isLoading);
            button.find(".spinner-border").toggleClass("d-none", !isLoading);
            button.find(".fas").toggleClass("d-none", isLoading);
        }
    });
</script>
