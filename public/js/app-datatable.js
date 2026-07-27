/**
 * App DataTable Helper
 * ---------------------------------------------------------------
 * A single, reusable initializer for every server-side DataTable
 * in this project (Students, Teachers, Classes, Subjects, Fees,
 * Attendances, Marks, Activity Logs, ...).
 *
 * Responsibilities:
 *   1. Boot a DataTables instance in "serverSide" mode against a
 *      given AJAX endpoint (search, sort, and pagination all run
 *      on the server — the client only ever receives one page of
 *      rows at a time).
 *   2. Provide a single delegated "Delete" handler: any button
 *      rendered by the server with class="ajax-delete-btn" and a
 *      data-url attribute is deleted via AJAX, then the table
 *      refreshes itself in place — no full page reload.
 *
 * Usage (in a Blade view):
 *
 *   AppDataTable.init('#teachers-table', {
 *       ajaxUrl: '{{ route("teachers.datatable") }}',
 *       columns: [
 *           { data: 'id', name: 'id' },
 *           { data: 'name', name: 'name' },
 *           { data: 'action', name: 'action', orderable: false, searchable: false }
 *       ]
 *   });
 */
window.AppDataTable = (function ($) {
    'use strict';

    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function notify(type, message) {
        if (typeof toastr !== 'undefined' && toastr[type]) {
            toastr[type](message);
            return;
        }
        alert(message);
    }

    function bindDeleteHandler($table, table) {
        $table.on('click', '.ajax-delete-btn', function () {
            var $btn = $(this);
            var url = $btn.data('url');
            var confirmMsg = $btn.data('confirm') || 'Are you sure you want to delete this record?';

            if (!url || !confirm(confirmMsg)) {
                return;
            }

            $btn.prop('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: csrfToken(),
                    _method: 'DELETE'
                }
            }).done(function (response) {
                table.ajax.reload(null, false);
                notify('success', (response && response.message) || 'Deleted successfully.');
            }).fail(function () {
                notify('error', 'Something went wrong while deleting this record.');
            }).always(function () {
                $btn.prop('disabled', false);
            });
        });
    }

    /**
     * @param {string} selector  Table selector, e.g. '#teachers-table'
     * @param {object} config    {
     *     ajaxUrl:  string (required) — server endpoint returning the DataTables JSON payload
     *     columns:  array  (required) — DataTables column definitions
     *     order:    array  (optional) — default sort, e.g. [[0, 'desc']]
     *     extra:    object (optional) — any extra DataTables options to merge in
     * }
     */
    function init(selector, config) {
        var $table = $(selector);

        var processingTimer = null;

        var options = $.extend(true, {
            processing: true,
            serverSide: true,
            ajax: {
                url: config.ajaxUrl,
                type: 'GET'
            },
            order: config.order || [[0, 'desc']],
            columns: config.columns,
            language: {
                processing: '<div class="app-dt-spinner"></div>',
                emptyTable: 'No records found',
                zeroRecords: 'No matching records found'
            }
        }, config.extra || {});

        var table = $table.DataTable(options);

        // Only reveal the processing indicator if a request genuinely takes
        // longer than 250ms — on fast (server-side indexed) responses it
        // never flashes at all, so the UI feels instant.
        $table.on('processing.dt', function (e, settings, processing) {
            var $indicator = $table.closest('.dataTables_wrapper').find('.dataTables_processing');
            if (processing) {
                processingTimer = setTimeout(function () {
                    $indicator.addClass('is-visible');
                }, 250);
            } else {
                clearTimeout(processingTimer);
                $indicator.removeClass('is-visible');
            }
        });

        bindDeleteHandler($table, table);

        return table;
    }

    return { init: init };
})(jQuery);
