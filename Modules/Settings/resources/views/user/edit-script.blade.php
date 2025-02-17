<script>
$(document).ready(function () {
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

            // Check if all child checkboxes are checked, and set the parent checkbox accordingly
            parentCheckbox.prop("checked", checkedCount === childCheckboxes.length);
            parentCheckbox.prop("indeterminate", checkedCount > 0 && checkedCount < childCheckboxes.length);
        }

        // Handle case for the module_id when all page_id are checked
        let moduleLi = childCheckbox.closest("ul").closest("li").closest("ul").closest("li");
        let moduleCheckbox = moduleLi.find("> .pretty > input[type='checkbox']");

        if (moduleCheckbox.length) {
            let allPageCheckboxes = moduleLi.find("ul > li > .pretty > input[type='checkbox']");
            let checkedPages = allPageCheckboxes.filter(":checked").length;

            // Check if all page checkboxes are checked, and set the module checkbox accordingly
            moduleCheckbox.prop("checked", checkedPages === allPageCheckboxes.length);
            moduleCheckbox.prop("indeterminate", checkedPages > 0 && checkedPages < allPageCheckboxes.length);
        }
    }

    $('input[type="checkbox"]').change(function () {
        if ($(this).prop('disabled')) return;

        let isChecked = $(this).prop("checked");
        $(this).closest("li").find("ul input[type='checkbox']").not(':disabled').prop("checked", isChecked);

        updateParentCheckbox($(this));
        updateActionCount();
    });

    function loadRolePermissions(roleId, userPermissions) {
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

                    userPermissions.forEach(permissionId => {
                        let checkbox = $('#action_' + permissionId);
                        checkbox.prop('checked', true);
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
        let userPermissions = @json($data['userPermission'] ?? []);
        loadRolePermissions(roleId, userPermissions);
    });

    let initialRoleId = $('#role_id').val();
    let userPermissions = @json($data['userPermission'] ?? []);
    loadRolePermissions(initialRoleId, userPermissions);

    $('input[type="checkbox"]:checked').each(function () {
        updateParentCheckbox($(this));
    });

    updateActionCount();
});

// $(document).ready(function () {
//     function updateActionCount() {
//         let count = $('input[name="action_id[]"]:checked').length;
//         $('#actionCount').text(`សកម្មភាពអនុញ្ញាត (${count})`);
//     }

//     function updateParentCheckbox(childCheckbox) {
//         let parentLi = childCheckbox.closest("ul").closest("li");
//         let parentCheckbox = parentLi.find("> .pretty > input[type='checkbox']");

//         if (parentCheckbox.length) {
//             let childCheckboxes = parentLi.find("ul > li > .pretty > input[type='checkbox']");
//             let checkedCount = childCheckboxes.filter(":checked").length;

//             parentCheckbox.prop("checked", checkedCount === childCheckboxes.length);
//             parentCheckbox.prop("indeterminate", checkedCount > 0 && checkedCount < childCheckboxes.length);
//         }
//     }

//     $('input[type="checkbox"]').change(function () {
//         if ($(this).prop('disabled')) return;

//         let isChecked = $(this).prop("checked");
//         $(this).closest("li").find("ul input[type='checkbox']").not(':disabled').prop("checked", isChecked);

//         updateParentCheckbox($(this));
//         updateActionCount();
//     });

//     function loadRolePermissions(roleId, userPermissions) {
//         if (!roleId) return;

//         $.ajax({
//             url: "{{ route('getRolePermissions') }}",
//             type: "GET",
//             data: { role_id: roleId },
//             success: function (response) {
//                 if (response.success) {
//                     $('input[type="checkbox"]').prop('checked', false).prop('indeterminate', false).prop('disabled', false);

//                     response.permissions.forEach(id => {
//                         let checkbox = $('#action_' + id);
//                         checkbox.prop('checked', true).prop('disabled', true);
//                     });

//                     userPermissions.forEach(permissionId => {
//                         let checkbox = $('#action_' + permissionId);
//                         checkbox.prop('checked', true);
//                     });

//                     $('input[type="checkbox"]:checked').each(function () {
//                         updateParentCheckbox($(this));
//                     });

//                     updateActionCount();
//                 }
//             }
//         });
//     }

//     $('#role_id').change(function () {
//         let roleId = $(this).val();
//         let userPermissions = @json($data['userPermission'] ?? []);
//         loadRolePermissions(roleId, userPermissions);
//     });

//     let initialRoleId = $('#role_id').val();
//     let userPermissions = @json($data['userPermission'] ?? []);
//     loadRolePermissions(initialRoleId, userPermissions);

//     $('input[type="checkbox"]:checked').each(function () {
//         updateParentCheckbox($(this));
//     });

//     updateActionCount();
// });
function previewLogo(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('attachment-preview');
        const oldImage = document.getElementById('old_image');
        output.src = reader.result;
        output.style.display = 'block';
        if (oldImage) {
            oldImage.style.display = 'none';
        }
    };
    reader.readAsDataURL(event.target.files[0]);
}
function confirmDelete() {
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
            const oldImage = document.getElementById('old_image');
            if (oldImage) {
                oldImage.style.display = 'none';
            }
            const attachmentInput = document.querySelector('input[name="attachment_"]');
            if (attachmentInput) {
                attachmentInput.value = 'deleted';
            }
        }
    });
}
</script>
