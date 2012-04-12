<?php
try {
    App::import("Vendor", "facebook");
} catch(Exception $e) {
    throw new InternalErrorException($e);
}

class FacebookGraphHelper extends AppHelper {
    var $helpers = array("TimePlus");
    var $facebook;
    var $cacheStore = 'view_short';

    function __construct($view) {
        $config = Configure::read('Facebook');
        $this->facebook = new Facebook(array(
          'appId'  => $config['app']['id'],
          'secret' => $config['app']['secret']
        ));
        parent::__construct($view);
    }
    
    function like($feedName) {
        $config = Configure::read('Facebook');
    
        $html = '<div class="facebook">
		<div id="fb-root"></div>
      <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, \'script\', \'facebook-jssdk\'));</script>

      <div class="fb-like-box" data-href="http://www.facebook.com/'.$config['sources'][$feedName]['name'].'" data-width="292" data-show-faces="false" data-stream="false" data-header="false"></div>
      </div>';
      
        return $html;
    }
    
    /**
    * Displays a facebook feed (fetches feed config from app config)
    */
    function feed($feedName, $options) {
        $key = "facebook_feed_${feedName}";
        $html = Cache::read($key, $this->cacheStore);

        if ($html !== false) {
            return $html;
        }
        $config = Configure::read('Facebook');
        $source = $config['sources'][$feedName];
        $name = $source['name'];
        try {
            $page = $this->facebook->api("/${name}");
            $feed = $this->facebook->api("/${name}/feed");
        } catch(Exception $e) {
            //$this->log('Failed connecting to Facebook. Feed: '.$name, 'error');
            //$this->log($e, 'error');
            
            return "<p class='padded'>News is currently unavailable due to a connectivity issue with Facebook. It will be back soon.</p>";
        }
        
        $html = '';
        $i=0;
        $maxItems = empty($options['limit']) ? 5 : $options['limit'];
        foreach($feed['data'] as $news) {
            if($page['id'] === $news['from']['id']) {
                if(empty($news['picture'])) {
                    $news['picture'] = 'https://graph.facebook.com/'.$news['from']['id'].'/picture';
                    $news['picture-link'] = 'https://www.facebook.com/'.$news['from']['id'];
                } else {
                    $news['picture-link'] = $news['link'];
                }
                
                $html .= '<div class="news-item">
                    <div class="pull-left news-image">
                        <a target="_blank" href="'.$news['picture-link'].'"><img width="100%" src="'.$news['picture'].'" /></a>
                    </div>
                    <div class="news-content">';
                if(!empty($news['message'])) {
                    $html .= $news['message'].'<br/>';   
                }
    
                $html .= "<div class='pull-right news-time'>
                    Posted <time class='timeago' datetime='${news['created_time']}' title='${news['created_time']}'></time>
                </div>";
                $html .= !empty($news['link']) ? '<a class="news-link" href="'.$news['link'].'" target="_blank">More…</a>' : null;
                $html .= '</div></div>';
                $i++;
                
                if($i === $maxItems) break;
            }
        }
        // For some reason, the cache doesn't overwrite reliably.. deleting before writing seems to solve this problem. it might be a timezone related issue.
        Cache::delete($key, $this->cacheStore);
        Cache::write($key, $html, $this->cacheStore);
        return $html;
    }
}

?>
