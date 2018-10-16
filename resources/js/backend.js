import './bootstrap';
import '@coreui/coreui'

/**
 * DataTables
 * https://datatables.net/
 */
import 'datatables.net';
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#dt-allUrls').DataTable( {
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: '/api/allurl/getdata',
        columns: [
            {data: 'short_url'},
            {data: 'long_url'},
            {data: 'views'},
            {data: 'created_by'},
            {
                data: 'created_at',
                type: 'num',
                render: {
                    _: 'display',
                    sort: 'timestamp'
                }
            },
            {data: 'action'},
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search..."
        },
        aoColumnDefs: [
          { "bSearchable": false, "aTargets": [ 2, 4 ] }
        ]
    } )
    .order([4, 'desc']).draw();

    $('#dt-myUrls').DataTable( {
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: '/api/myurl/getdata',
        columns: [
            {data: 'short_url'},
            {data: 'long_url'},
            {data: 'views'},
            {
                data: 'created_at',
                type: 'num',
                render: {
                    _: 'display',
                    sort: 'timestamp'
                }
            },
            {data: 'action'},
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search..."
        },
        aoColumnDefs: [
          { "bSearchable": false, "aTargets": [ 2, 3 ] }
        ]
    } )
    .order([3, 'desc']).draw();

    $('#dt-Users').DataTable( {
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: '/api/user/getdata',
        columns: [
            {data: 'name'},
            {data: 'email'},
            {
                data: 'created_at',
                type: 'num',
                render: {
                    _: 'display',
                    sort: 'timestamp'
                }
            },
            {
                data: 'updated_at',
                type: 'num',
                render: {
                    _: 'display',
                    sort: 'timestamp'
                }
            },
            {data: 'action'},
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search..."
        },
        aoColumnDefs: [
          { "bSearchable": false, "aTargets": 2  }
        ]
    } )
    .order([2, 'desc']).draw();
} );


/**
 * Copy short url to clipboard
 */
// https://github.com/zenorocha/clipboard.js
var ClipboardJS = require('clipboard');

new ClipboardJS('[data-clipboard-text]').on('success', function(e) {
    $(e.trigger)
        .attr('data-original-title','Copied!').tooltip("_fixTitle").tooltip("show")
        .attr("title", "Copy to clipboard").tooltip("_fixTitle");
});
