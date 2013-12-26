<div class="page-header">
    <h1>Export</h1>
    The Orienteering Canada database allows you to ...
</div>
<div class="row three-columnw">
    <div class="col-sm-4">
        <header>
            <h4>Display info on your club's website</h4>
        </header>
        Much of the information in the OCNDB can be accessed and displayed in your club's website.
        A number of "widgets" have been developed for displaying information. A little bit of basic web-programming knowledge is required to include them.

        <h5>Calendar</h5>
        The events calendar can be embeded by adding this code to your site:
        <br>
        <code>
            &lt;iframe src="<?=strtoLower(Configure::read('Club.acronym'))?>.whyjustrun.ca/events.embed"&gt;&lt;/iframe&gt;
        </code>

        <h5>Maps</h5>
        Maps can be embeded by adding this code to your site:
        <br>
        <code>
            &lt;iframe src="<?=strtoLower(Configure::read('Club.acronym'))?>.whyjustrun.ca/maps.embed"&gt;&lt;/iframe&gt;
        </code>
    </div>
    <div class="col-sm-4">
        <h4>Roll-your-own widgets</h4>
        <p>A lot of the information in the OCNDB is available via the API, which generally provides data in IOF XML format.</p>
        <p>Experienced web developers can create widgets to display this information. For more information check out the <a href="https://github.com/OrienteerApp/OrienteerApp/wiki/API">API</a>.</p>
    </div>
    <div class="col-sm-4">
        <header>
            <h4>Upgrading to the WhyJustRun system</h4>
        </header>
        In addition to this functionality, each club has the option to use the WhyJustRun (WJR) system for their club. This involves migrating the club website to WJR...
    </div>
</div>

