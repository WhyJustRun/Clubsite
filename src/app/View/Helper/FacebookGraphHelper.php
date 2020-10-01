<?php
class FacebookGraphHelper extends AppHelper {
    function like($pageID) {
        return '<div class="facebook">
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
fjs.parentNode.insertBefore(js, fjs);
                    }(document, \'script\', \'facebook-jssdk\'));</script>
                        <div class="fb-like-box" data-href="'.$pageID.'" data-width="292" data-show-faces="false" data-stream="false" data-header="false"></div>
                        </div>';
    }
}

?>
