function zeroFill(number, width) {
    width -= number.toString().length;
    if (width > 0) {
        return new Array( width + (/\./.test( number ) ? 2 : 1) ).join( '0' ) + number;
    }
    return number;
}

function sameYear(date1, date2) {
    return(date1.getFullYear() == date2.getFullYear());
}
function sameMonth(date1, date2) {
    return (date1.getMonth() == date2.getMonth() && date1.getFullYear() == date2.getFullYear());
}

function sameDay(date1, date2) {
    return (date1.getDate() == date2.getDate() && sameMonth(date1, date2));
}

// Sample: http://whyjustrun.ca/iof/3.0/events/746/result_list.xml
var wjr = {};
wjr.eventViewer = {};
wjr.eventViewer.IOF = {};
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
                this.date = startTime.toString('MMMM dS yyyy') + " - " + endTime.toString('MMMM dS, yyyy');
            } else {
                this.date = startTime.toString('MMMM dS') + " - " + endTime.toString('MMMM dS, yyyy');
            }
        } else {
            this.date = startTime.toString('MMMM dS') + " - " + endTime.toString('dS, yyyy');
        }
    }
    else {
        this.date = startTime.toString('MMMM dS, yyyy');
    }
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
        events.push(new wjr.eventViewer.IOF.Event(eventID, eventName, url, new XDate(startDate), new XDate(endDate), classification, {color: seriesColor}, {acronym: clubAcronym}));
    });
    return events;
}

$(function() {
    var createEventList = function(element, url) {
        var viewModel = {
            events : ko.observableArray()
        };
        
        ko.applyBindings(viewModel, element);
    
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
    
    function loadEventList(index, element) {
        createEventList(element, this.getAttribute("data-event-list-url"));
    }
    
    $(".event-list").each(loadEventList);
});
