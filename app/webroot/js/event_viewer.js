function zeroFill(number, width) {
    width -= number.toString().length;
    if (width > 0) {
        return new Array( width + (/\./.test( number ) ? 2 : 1) ).join( '0' ) + number;
    }
    return number;
}

// Sample: http://whyjustrun.ca/iof/3.0/events/746/result_list.xml
var wjr = {};
wjr.eventViewer = {};
wjr.eventViewer.IOF = {};
wjr.eventViewer.IOF.Event = function(id, name, url, startTime, endTime, classification, series) {
    this.id = id;
    this.name = name;
    this.startTime = startTime;
    this.endTime = endTime;
    this.classification = classification;
    this.series = series
    this.url = url
    this.date = startTime.format("ddd mmmm dS yyyy h:MMtt"); // presented date/time
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
        var startDate = startTimeDate.children("Date").text();
        var startTime = startTimeDate.children("Time").text();
        var endTimeDate = $(element).children("EndTime");
        var endDate = endTimeDate.children("Date").text();
        var endTime = endTimeDate.children("Time").text();
        var url = $(element).children('URL').text();
        var classification = $(element).children('Classification').text();
        events.push(new wjr.eventViewer.IOF.Event(eventID, eventName, url, new Date(startDate + " " + startTime), new Date(endDate + " " + endTime), classification, {color: seriesColor}));
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

// Fix CORS for IE8/9

(function( jQuery ) {
        // Create the request object
        // (This is still attached to ajaxSettings for backward compatibility)
        jQuery.ajaxSettings.xdr = function() {
                return (window.XDomainRequest ? new window.XDomainRequest() : null);
        };

        // Determine support properties
        (function( xdr ) {
                jQuery.extend( jQuery.support, { iecors: !!xdr });
        })( jQuery.ajaxSettings.xdr() );

        // Create transport if the browser can provide an xdr
        if ( jQuery.support.iecors ) {

                jQuery.ajaxTransport(function( s ) {
                        var callback;

                        return {
                                send: function( headers, complete ) {
                                        var xdr = s.xdr();

                                        xdr.onload = function() {
                                                var headers = { 'Content-Type': xdr.contentType };
                                                complete(200, 'OK', { text: xdr.responseText }, headers);
                                        };
                                        
                                        // Apply custom fields if provided
                    if ( s.xhrFields ) {
                                                xhr.onerror = s.xhrFields.error;
                                                xhr.ontimeout = s.xhrFields.timeout;
                    }

                                        xdr.open( s.type, s.url );

                                        // XDR has no method for setting headers O_o

                                        xdr.send( ( s.hasContent && s.data ) || null );
                                },

                                abort: function() {
                                        xdr.abort();
                                }
                        };
                });
        }
})( jQuery );