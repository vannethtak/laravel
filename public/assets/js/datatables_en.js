const DatatableBasic = function() {
    // Setup module components
    const _componentDatatableBasic = function() {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }
        $.extend($.fn.dataTable.defaults, {
            // autoWidth: false,
            // scrollCollapse: true,
            // scrollY: "480px",
            // scrollX: true,
            // pageLength: 10,
            // searching: false,
            autoWidth: false,
            scrollCollapse: true,
            scrollY: "480px",
            scrollX: true,
            searching: false,
            pageLength: 10,
            processing: true,
            serverSide: true,
            lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
            language: {
                lengthMenu: "Show <span class='ms-2'>_MENU_ entries</span>",
                info: "Showing _START_ to _END_ of _TOTAL_ Records",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                emptyTable: "No data available in table",
                zeroRecords: "No data available in table",
                processing: "Loading...",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    };

    // Return objects assigned to module
    return {
        init: function() {
            _componentDatatableBasic();
        }
    }
}();

// Initialize module
document.addEventListener('DOMContentLoaded', function() {
    DatatableBasic.init();
});
