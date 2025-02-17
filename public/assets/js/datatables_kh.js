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
            // searching: false,
            // pageLength: 10,
            autoWidth: false,
            scrollCollapse: true,
            scrollY: "480px",
            scrollX: true,
            searching: false,
            pageLength: 10,
            processing: true,
            serverSide: true,
            lengthMenu: [[10, 15, 25, 50, -1], ['១០', '១៥', '២៥', '៥០', 'ទាំងអស់']],
            language: {
                lengthMenu: "បង្ហាញ _MENU_ ធាតុ",
                info: "កំពុងបង្ហាញ _START_ ដល់ _END_ នៃ _TOTAL_ ធាតុ",
                infoEmpty: "កំពុងបង្ហាញ 0 ដល់ 0 នៃ 0 ធាតុ",
                emptyTable: "គ្មានទិន្នន័យក្នុងតារាង",
                zeroRecords: "គ្មានទិន្នន័យដែលត្រូវផ្គូផ្គង",
                search: "ស្វែងរក:",
                paginate: {
                    first: "ដំបូង",
                    last: "ចុងក្រោយ",
                    next: "បន្ទាប់",
                    previous: "មុន"
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
