'use strict';

$(function() {
    if ($('#calendar').length) {
        var calendar = $('#calendar').calendar(
            {
                tmpl_path: '/calendar/tmpls/',
                language: 'ca-ES',
                events_source: function () {
                    return [];
                }
            });
    }
});