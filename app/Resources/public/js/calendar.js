(function($) {
    "use strict";

    var urlCalendarEvents = Routing.generate('demofony2_calendar_events');

    var options = {
        events_source: urlCalendarEvents,
        view: 'month',
        modal: true,
        tmpl_cache: false,
        tmpl_path: '/calendar/tmpls/',
        language: 'ca-ES',
        onAfterEventsLoad: function(events) {
            if(!events) {
                return;
            }
            var list = $('#eventlist');
            list.html('');

            $.each(events, function(key, val) {
                $(document.createElement('li'))
                    .html('<span class="pull-left event " style="background-color: ' + val.color + '; margin-top: 14px; margin-right: 5px;"></span><a href="' + val.url + '">' + val.title + '</a>')
                    .appendTo(list);
            });
        },
        onAfterViewLoad: function(view) {
            $('#monthName').text(this.getTitle());
            $('.btn-group button').removeClass('active');
            $('button[data-calendar-view="' + view + '"]').addClass('active');
        },
        classes: {
            months: {
                general: 'label'
            }
        }
    };

    $(function() {
        if ($('#calendar').length) {
            var calendar = $('#calendar').calendar(options);

            $('.btn-group button[data-calendar-nav]').each(function () {
                var $this = $(this);
                $this.click(function () {
                    calendar.navigate($this.data('calendar-nav'));
                });
            });

            $('.btn-group button[data-calendar-view]').each(function () {
                var $this = $(this);
                $this.click(function () {
                    calendar.view($this.data('calendar-view'));
                });
            });

            $('#events-modal .modal-header, #events-modal .modal-footer').click(function (e) {
                //e.preventDefault();
                //e.stopPropagation();
            });
        }
    });
}(jQuery));