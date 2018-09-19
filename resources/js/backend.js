import './bootstrap';
import '@coreui/coreui'


/**
 * DataTables
 * https://datatables.net/
 */
import 'datatables.net';
$(document).ready(function() {
    $('#dt-allUrls').DataTable( {
        order: [ 3, 'dsc' ],
        stateSave: true
    } );

    $('#dt-myUrls').DataTable( {
        order: [ 3, 'dsc' ],
        stateSave: true
    } );

    $('#dt-Users').DataTable( {
        order: [ 3, 'dsc' ],
        stateSave: true
    } );
} );
