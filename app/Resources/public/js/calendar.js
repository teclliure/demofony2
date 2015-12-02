'use strict';

$(function() {
    if ($('#calendar').length) {
        var calendar = $('#calendar').calendar(
            {
                tmpl_path: '/calendar/tmpls/',
                language: 'es-ES',
                events_source: function () {
                    return [];
                }
            });
    }
});