import './bootstrap';
import '@coreui/coreui'


/**
 * DataTables
 * https://datatables.net/
 */
import 'datatables.net';
$(document).ready(function() {
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
            {data: 'created_at'},
            {data: 'action'},
        ]
    } );

    $('#dt-myUrls').DataTable( {
        order: [ 3, 'dsc' ],
        stateSave: true,
    } );

    $('#dt-Users').DataTable( {
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: '/api/user/getdata',
        columns: [
            {data: 'name'},
            {data: 'email'},
            {data: 'created_at'},
            {data: 'action'},
        ]
    } );
} );
