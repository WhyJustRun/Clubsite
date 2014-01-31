function zeroFill(number, width) {
    width -= number.toString().length;
    if (width > 0) {
        return new Array( width + (/\./.test( number ) ? 2 : 1) ).join( '0' ) + number;
    }
    return number;
}

function sameYear(date1, date2) {
    return date1.isSame(date2, 'year');
}
function sameMonth(date1, date2) {
    return date1.isSame(date2, 'month');
}

function sameDay(date1, date2) {
    return date1.isSame(date2, 'day');
}

// Sample: http://whyjustrun.ca/iof/3.0/events/746/result_list.xml
var wjr = {};
wjr.eventViewer = {};
wjr.eventViewer.IOF = {};
wjr.eventViewer.fullCalendar = {};
wjr.eventViewer.IOF.Event = function(id, name, url, startTime, endTime, classification, series, club) {
    this.id = id;
    this.name = name;
    this.startTime = startTime;
    this.endTime = endTime;
    this.classification = classification;
    this.series = series
    this.url = url
    this.clubAcronym = club.acronym
    if (!sameDay(startTime, endTime)) {
        if (!sameMonth(startTime, endTime)) {
            if (!sameYear(startTime, endTime)) {
                this.date = startTime.format('MMMM Do YYYY') + " - " + endTime.format('MMMM dS, YYYY');
            } else {
                this.date = startTime.format('MMMM Do') + " - " + endTime.format('MMMM Do, YYYY');
            }
        } else {
            this.date = startTime.format('MMMM Do') + " - " + endTime.format('Do, YYYY');
        }
    }
    else {
        this.date = startTime.format('MMMM Do, YYYY');
    }
}

wjr.eventViewer.fullCalendar.convertEvents = function (json) {
   var events = [];
   _.each(json, function (element, index, list) {
        events.push(new wjr.eventViewer.IOF.Event(element.id, element.title, element.url, moment.unix(element.start), moment.unix(element.end), element.event_classification.name, {color: element.textColor}, {acronym: element.club.acronym}));
   });
   return events;
}

wjr.eventViewer.IOF.loadEventsList = function(xml) {
    var events = []
    $(xml.documentElement).children("Event").each(function(index, element) {
        var eventID = $(element).children("Id").text();
        var eventName = $(element).children("Name").text();
        var extensions = $(element).children("Extensions")
        var series = extensions.children('Series');
        var seriesColor = series.children('Color').text();
        var startTimeDate = $(element).children("StartTime");
        var startDate = startTimeDate.children("ISODate").text();
        var endTimeDate = $(element).children("EndTime");
        var endDate = endTimeDate.children("ISODate").text();
        var url = $(element).children('URL').text();
        var clubAcronym = $(element).children('Organiser').children('ShortName').text();
        var classification = $(element).children('Classification').text();
        // Need to get dates to be of format: 2011-10-10T14:48:00.000z
        events.push(new wjr.eventViewer.IOF.Event(eventID, eventName, url, moment(startDate), moment(endDate), classification, {color: seriesColor}, {acronym: clubAcronym}));
    });
    return events;
}

$(function() {
    var viewModel = {
        startTime : ko.observable(),
        endTime : ko.observable(),
        events : ko.observableArray(),
    };

    viewModel.formattedStartTime = ko.computed(function() {
        if (this.startTime() == null) return null;
        return this.startTime().format('MMMM Do YYYY');
    }, viewModel);
    viewModel.formattedEndTime = ko.computed(function() {
        if (this.endTime() == null) return null;
        return this.endTime().format('MMMM Do YYYY');
    }, viewModel);
    var createEventList = function(element, url, isFullcalendar) {
        
        ko.applyBindings(viewModel, element);
   
        if (isFullcalendar) {
            var olderButton = $(element.getAttribute('data-event-list-older-button'));
            var newerButton = $(element.getAttribute('data-event-list-newer-button'));
            setupFullcalendar(url, olderButton, newerButton);
        } else {
            $.ajax({
                type: "GET",
                url: url,
                dataType: "xml",
                cache: false,
                success: function(xml)
                {
                    viewModel.events(wjr.eventViewer.IOF.loadEventsList(xml));
                }
            });
        }
    };

    function setupFullcalendar(url, olderButton, newerButton) {
        var startTime = null;
        var endTime = null;
       
        olderButton.click(function () {
            loadEvents(startTime.clone().subtract('months', 6), startTime, olderButton[0]);
        });

        newerButton.click(function () {
            loadEvents(endTime, endTime.clone().add('months', 6), newerButton[0]);
        });

        var loadEvents = function (fetchStartTime, fetchEndTime, button) {
            if (startTime == null || fetchStartTime.isBefore(startTime)) {
                startTime = fetchStartTime;
            }

            if (endTime == null || fetchEndTime.isAfter(endTime)) {
                endTime = fetchEndTime;
            }

            // Spinner on button
            var ladda = (button != undefined) ? Ladda.create(button) : null;
            if (ladda != null) {
                ladda.start();
            }
            $.ajax({
                type: 'GET',
                url: url,
                data: { start: fetchStartTime.unix(),
                        end: fetchEndTime.unix() },
                dataType: 'json',
                cache: false,
                success: function (json) {
                    var events = wjr.eventViewer.fullCalendar.convertEvents(json);
                    var index = 0; 
                    if (endTime != null && fetchEndTime.isAfter(endTime)) {
                        index = -1;
                    }
                    // This adds the new events to the view model.
                    viewModel.events.splice.apply(viewModel.events, [index, 0].concat(events));
                    viewModel.startTime(startTime);
                    viewModel.endTime(endTime);
                },
                complete: function(request, textStatus) {
                    if (ladda != null) {
                        ladda.stop();
                    }
                },
            });
        };
        
        loadEvents(moment.utc().subtract('months', 1), moment.utc().add('months', 2));
    }

    function loadEventList(index, element) {
        var isFullcalendar = false;
        if (element.getAttribute("data-event-list-type") == 'fullcalendar') {
            isFullcalendar = true;
        }
        createEventList(element, element.getAttribute("data-event-list-url"), isFullcalendar);
    }
    
    $(".event-list").each(loadEventList);
});
