<?php
class JuicerFeedHelper extends AppHelper {
    function feed($feedID) {
        return '<script src="https://assets.juicer.io/embed.js" type="text/javascript"></script>
            <link href="https://assets.juicer.io/embed.css" media="all" rel="stylesheet" type="text/css" />
            <ul class="juicer-feed" data-feed-id="'.$feedID.'" data-per="3" data-truncate="250" data-columns="1"></ul>
            <h4 class="referral" style="text-align: center; margin-top: 0; margin-bottom: 24px;"><a href="https://www.juicer.io">Feed powered by Juicer.io</a></h4>';
    }
}

?>
