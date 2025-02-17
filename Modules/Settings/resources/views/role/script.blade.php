
<script>
 $(document).ready(function() {
    function updateActionCount() {
        let count = $('input[name="action_id[]"]:checked').length;
        $('#actionCount').text(`សកម្មភាពអនុញ្ញាត (${count})`);
    }

    $('input[type="checkbox"]').change(function() {
        let $this = $(this);
        let isChecked = $this.prop("checked");

        // Check/uncheck all child checkboxes when a parent is clicked
        $this.closest("li").find("ul input[type='checkbox']").prop("checked", isChecked);

        // Update parent checkboxes
        updateParentCheckbox($this);

        // Update count of checked action checkboxes
        updateActionCount();
    });

    function updateParentCheckbox(childCheckbox) {
        let parentLi = $(childCheckbox).closest("ul").closest("li");
        let parentCheckbox = parentLi.find("> .pretty > input[type='checkbox']");

        if (parentCheckbox.length) {
            let allChecked = parentLi.find("ul > li > .pretty > input[type='checkbox']").length ===
                             parentLi.find("ul > li > .pretty > input[type='checkbox']:checked").length;

            let anyChecked = parentLi.find("ul > li > .pretty > input[type='checkbox']:checked").length > 0;

            parentCheckbox.prop("checked", allChecked);
            parentCheckbox.prop("indeterminate", !allChecked && anyChecked);

            updateParentCheckbox(parentCheckbox);
        }
    }

    // Run on page load to restore previous selections and update count
    $('input[type="checkbox"]:checked').each(function() {
        updateParentCheckbox($(this));
    });

    updateActionCount();
});

</script>
