
<script>
$(document).on('click', '.toggle-password', function() {
    $(this).toggleClass('fa-eye fa-eye-slash');
    var input = $($(this).attr('toggle'));
    if (input.attr('type') == 'password') {
        input.attr('type', 'text');
    } else {
        input.attr('type', 'password');
    }
});
$(document).ready(function () {
    const password = $('#password');
    const passwordConfirmation = $('#password_confirmation');
    const form = password.closest('form');
    form.on('submit', function (event) {
        if (password.val() !== passwordConfirmation.val()) {
            event.preventDefault();
            $.notify({
                icon: 'fa fa-exclamation-triangle',
                message: '{{ __("Password Confirmation Does Not Match") }}'
            },{
                type: 'danger',
                delay: 0,
                allow_dismiss: true,
                placement: {
                    from: "top",
                    align: "right"
                },
            });
        }
    });

    function updateActionCount() {
        let count = $('input[name="action_id[]"]:checked').length;
        $('#actionCount').text(`សកម្មភាពអនុញ្ញាត (${count})`);
    }

    function updateParentCheckbox(childCheckbox) {
        let parentLi = childCheckbox.closest("ul").closest("li");
        let parentCheckbox = parentLi.find("> .pretty > input[type='checkbox']");

        if (parentCheckbox.length) {
            let childCheckboxes = parentLi.find("ul > li > .pretty > input[type='checkbox']");
            let checkedCount = childCheckboxes.filter(":checked").length;

            parentCheckbox.prop("checked", checkedCount === childCheckboxes.length);
            parentCheckbox.prop("indeterminate", checkedCount > 0 && checkedCount < childCheckboxes.length);

            updateParentCheckbox(parentCheckbox);
        }
    }

    $('input[type="checkbox"]').change(function () {
        if ($(this).prop('disabled')) return; // Prevent changes to disabled checkboxes

        let isChecked = $(this).prop("checked");
        $(this).closest("li").find("ul input[type='checkbox']").not(':disabled').prop("checked", isChecked);

        updateParentCheckbox($(this));
        updateActionCount();
    });

    function loadRolePermissions(roleId) {
        if (!roleId) return;

        $.ajax({
            url: "{{ route('getRolePermissions') }}",
            type: "GET",
            data: { role_id: roleId },
            success: function (response) {
                if (response.success) {
                    $('input[type="checkbox"]').prop('checked', false).prop('indeterminate', false).prop('disabled', false);

                    response.permissions.forEach(id => {
                        let checkbox = $('#action_' + id);
                        checkbox.prop('checked', true).prop('disabled', true);
                    });

                    $('input[type="checkbox"]:checked').each(function () {
                        updateParentCheckbox($(this));
                    });

                    updateActionCount();
                }
            }
        });
    }

    $('#role_id').change(function () {
        let roleId = $(this).val();
        loadRolePermissions(roleId);
    });

    $(document).ready(function () {
        let initialRoleId = $('#role_id').val();
        loadRolePermissions(initialRoleId);
    });

    $('input[type="checkbox"]:checked').each(function () {
        updateParentCheckbox($(this));
    });

    updateActionCount();
});


</script>
