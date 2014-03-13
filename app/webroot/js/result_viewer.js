function zeroFill(number, width) {
    width -= number.toString().length;
    if (width > 0) {
        return new Array( width + (/\./.test( number ) ? 2 : 1) ).join( '0' ) + number;
    }
    return number;
}

// Sample: http://whyjustrun.ca/iof/3.0/events/746/result_list.xml
var wjr = {};
wjr.IOF = {};
wjr.IOF.Event = function(id, name, startTime) {
    this.id = id;
    this.name = name;
    this.startTime = startTime;
}

wjr.IOF.Course = function(id, name, results, scoringType, millisecondTiming) {
    this.id = id;
    this.name = name;
    this.results = ko.observableArray(results);
    this.hasComments = ko.computed(function () {
        return _.some(this.results(), function (result) {
          return (result.officialComment != "");
        });
    }, this);
    this.scoringType = scoringType;
    this.isScore = (scoringType === 'Points');
    this.isTimed = (scoringType === 'Timed');
    this.millisecondTiming = millisecondTiming;
}

wjr.IOF.friendlyStatuses = {
    'OK': '',
    'Inactive': 'Inactive',
    'DidNotStart': 'DNS',
    'Active': 'In Progress',
    'Finished': 'Unofficial',
    'MissingPunch': 'MP',
    'DidNotFinish': 'DNF',
    'DidNotEnter': 'DNE',
    'Disqualified': 'DSQ',
    'NotCompeting': 'NC',
    'SportWithdrawal': 'Sport Withdrawal',
    'OverTime': 'Over Time',
    'Moved': 'Moved',
    'MovedUp': 'Moved Up',
    'Cancelled': 'Cancelled'
};

wjr.IOF.Result = function(time, position, status, scores, officialComment, person) {
    if(time != null && !isNaN(time)) {
        this.time = time;
        this.hours = zeroFill((time - time % 3600) / 3600, 2);
        this.minutes = zeroFill((time - time % 60) / 60 - this.hours * 60, 2);
        var millis = Math.round((time % 1) * 1000);
        this.seconds = zeroFill(Math.floor(time) % 60, 2);
        this.milliseconds = zeroFill(millis, 3);
    } else {
        this.time = this.hours = this.minutes = this.seconds = this.milliseconds = null;
    }

    this.status = status;
    this.friendlyStatus = wjr.IOF.friendlyStatuses[status];
    this.position = position;
    this.scores = scores;
    this.person = person;
    this.officialComment = officialComment;
}

wjr.IOF.Person = function(id, givenName, familyName, profileUrl) {
    this.id = id;
    this.givenName = givenName;
    this.familyName = familyName;
    this.profileUrl = profileUrl;
}

wjr.IOF.loadResultsList = function(xml) {
    var resultList = $(xml.documentElement);

    var date = moment(resultList.attr('createTime'));
    var event = resultList.children("Event").first();
    var courses = [];
    resultList.children('ClassResult').each(function(index, element) {
        element = $(element);
        var classElement = element.children('Class');
        var courseId = classElement.children("Id").text();
        var courseName = classElement.children('Name').text();
        var scoringType = element.children("Course").children('Extensions').children('ScoringType').text();
        var results = [];
        var millisecondTiming = false;
        element.children('PersonResult').each(function(index, element) {
            element = $(element);
            var person = element.children("Person").first();
            var personGivenName = person.children("Name").children("Given").text();
            var personFamilyName = person.children("Name").children("Family").text();
            var personProfileUrl = person.children("Contact[type='WebAddress']").text();
            var personId = person.children("Id").text();
            var resultElement = element.children("Result");
            var resultTime = parseFloat(resultElement.children("Time").text());
            var resultStatus = resultElement.children("Status").text();
            var officialComment = resultElement.children("Extensions").children("OfficialComment").text();
            var resultPosition = resultElement.children("Position").text();
            var resultScores = {};
            element.children("Result").children("Score").each(function(index, element) {
                resultScores[$(element).attr("type")] = $(element).text();
            });
            var result = new wjr.IOF.Result(resultTime, resultPosition, resultStatus, resultScores, officialComment, new wjr.IOF.Person(personId, personGivenName, personFamilyName, personProfileUrl));
            if (!millisecondTiming && result.milliseconds != '000' && result.milliseconds != null) {
                millisecondTiming = true;
            }
            results.push(result);
        });
        courses.push(new wjr.IOF.Course(courseId, courseName, results, scoringType, millisecondTiming));
    })
    // TODO-RWP startTime
    return [new wjr.IOF.Event(event.children("Id").text(), event.children("Name").text(), null), courses, date];
}

$(function() {
    var createResultList = function(element, url, mode) {
        var viewModel = {
            event: ko.observable(),
            courses: ko.observableArray(),
            creationDate: ko.observable(),
        };

        ko.applyBindings(viewModel, element);

        var fetchResults = function() {
            $.ajax({
                type: "GET",
                url: url,
                dataType: "xml",
                ifModified: true,
                beforeSend: function() {},
                complete: function(jqXHR, textStatus) {},
                error: function(jqXHR, textStatus, errorThrown) {},
                success: function(xml)
                {
                    result = wjr.IOF.loadResultsList(xml);
                    viewModel.event(result[0]);
                    viewModel.courses(result[1])
                    viewModel.creationDate(result[2].format("dddd, MMMM Do YYYY [at] h:mm:ss a"))
                }
            });
        }

        fetchResults();

        if (mode == 'live') {
            window.setInterval(fetchResults, 5000);
        }
    }

    function loadResultList(index, element) {
        var modeAttr = 'data-result-list-mode';
        var mode = this.hasAttribute(modeAttr) ? this.getAttribute(modeAttr) : 'normal';
        createResultList(element, this.getAttribute("data-result-list-url"), mode);
    }

    $(".result-list").each(loadResultList);
});
