import './bootstrap';
import {initPasswordFields} from './password-toggle';
import 'datatables.net';

$(function() {
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
            {data: 'keyword'},
            {data: 'long_url', name: 'meta_title'},
            {data: 'clicks', searchable: false},
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


    /**
     * Initialise the password toggle fields.
     */
    initPasswordFields();

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
});
