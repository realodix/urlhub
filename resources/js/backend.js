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
            {data: 'author'},
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
        }
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
        }
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
            {data: 'action'},
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search..."
        }
    } )
    .order([2, 'desc']).draw();
} );
