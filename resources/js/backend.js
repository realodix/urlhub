import './bootstrap';
import {initPasswordFields} from './password-toggle';
import '@coreui/coreui'
import 'datatables.net';

$(document).ready(function() {

    /**
     * DataTables
     * https://datatables.net/
     */

    // All URLs Page
    $('#dt-allUrls').DataTable( {
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: '/admin/allurl/getdata',
        columns: [
            {data: 'url_key'},
            {data: 'long_url', name: 'meta_title'},
            {data: 'clicks', searchable: false,},
            {data: 'created_by'},
            {
                data: 'created_at',
                type: 'num',
                render: {
                    _: 'display',
                    sort: 'timestamp'
                },
                searchable: false
            },
            {data: 'action'}
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search..."
        }
    }).order([4, 'desc']).draw();

    // My URLs Page
    $('#dt-myUrls').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: '/admin/myurl/getdata',
        columns: [
            {data: 'url_key'},
            {data: 'long_url', name: 'meta_title'},
            {data: 'clicks', searchable: false},
            {
                data: 'created_at',
                type: 'num',
                render: {
                    _: 'display',
                    sort: 'timestamp'
                },
                searchable: false
            },
            {data: 'action'}
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search..."
        }
    }).order([3, 'desc']).draw();

    // All Users Page
    $('#dt-Users').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: '/admin/user/user/getdata',
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
                },
                searchable: false,
            },
            {data: 'action'}
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search..."
        }
    }).order([2, 'desc']).draw();

    /**
     * Initialise the password toggle fields.
     */
    initPasswordFields();
});


/**
 * Copy short url to clipboard
 *
 * https://github.com/zenorocha/clipboard.js
 */
var ClipboardJS = require('clipboard');

new ClipboardJS('[data-clipboard-text]').on('success', function(e) {
    $(e.trigger)
        .attr('data-original-title','Copied!').tooltip("_fixTitle").tooltip("show")
        .attr("title", "Copy to clipboard").tooltip("_fixTitle");
});
