/*
 *  Document   : tablesDatatables.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in Tables Datatables page
 */

var TablesDatatables = function () {

    return {
        init: function () {
            /* Initialize Bootstrap Datatables Integration */
            App.datatables();

            /* Initialize Datatables */
            $('#company-management').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 3] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#company-departments').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 3] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#company-job-grade').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 3] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#company-job-grade-set').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 3] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#employee-list').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 3] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#univ-col').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 4] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#emergency-contacts').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 5] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#tbl-ids').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 3] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#supporting-documents').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 3] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#position-history').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 3] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#account-list').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 3] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#leave-list').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 6] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#leave-balances').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 11] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[1, "desc"]]
            });
            $('#ot-list').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 6] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#certificate-request-list').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 7] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#holiday-list').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 4] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#car-maintenance-list').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 7] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#benefits-approver').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 3] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[2, "desc"]]
            });
            $('#reimbursement-list').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 7] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#benefits-approver-roles').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 4] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#bond-list').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 5] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#salary-loan-approver').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 4] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#salary-loan-approver-roles').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 4] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#loan-list').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 8] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#certificate-request-approver').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 3] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#tbl-audit-trail').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 3] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#attendance-list').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 9] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#training-list').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 7] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            $('#training-approver').dataTable({
                columnDefs: [{ orderable: true, targets: [0, 3] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']],
                "order": [[0, "desc"]]
            });
            /* Add placeholder attribute to the search input */
            $('.dataTables_filter input').attr('placeholder', 'Search');
        }
    };
}();