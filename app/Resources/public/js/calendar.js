(function($) {
    "use strict";

    var urlCalendarEvents = Routing.generate('demofony2_calendar_events');

    var options = {
        events_source: urlCalendarEvents,
        view: 'month',
        tmpl_cache: false,
        tmpl_path: '/calendar/tmpls/',
        modal: "#events-modal",
        modal_type: "ajax",
        language: 'ca-ES',
        modal_title: function(e) {
            $('#modalTitle').text(e.title);
        },
        onAfterEventsLoad: function(events) {
            if(!events) {
                return;
            }
            var list = $('#eventlist');
            list.html('');

            $.each(events, function(key, val) {
                $(document.createElement('li'))
                    .html('<span class="pull-left event " style="background-color: ' + val.color + '; margin-top: 14px; margin-right: 5px;"></span><a  data-toggle="modal"  data-remote="false" data-target="#events-modalExternal" href="' + val.url + '">' + val.title + '</a>')
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

            // Fill modal with content from link href
            $("#events-modalExternal").on("show.bs.modal", function(e) {
                var link = $(e.relatedTarget);
                $('#modalTitleExternal').text(link.text());
                $(this).find(".modal-body").load(link.attr("href"));
            });

            $("body").tooltip({ selector: '[data-toggle=tooltip]' });
        }
    });
}(jQuery));