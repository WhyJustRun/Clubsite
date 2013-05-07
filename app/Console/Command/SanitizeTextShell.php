<?php
App::uses('Sanitize', 'Utility');

class SanitizeTextShell extends Shell {
    public $uses = array('Event');
    
    public function main() {
        $events = $this->Event->find('all', array('conditions' => array('Event.id <=' => 719)));
        foreach($events as $event) {
            $this->Event->id = $event['Event']['id'];
            $desc = html_entity_decode(htmlspecialchars(html_entity_decode(htmlentities(html_entity_decode($event['Event']['description'], ENT_COMPAT, 'UTF-8'), 8, 'UTF-8'), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
            mb_detect_encoding($desc, "UTF-8") == "UTF-8" ? : $desc = utf8_encode($desc);
            $this->Event->saveField('description', $desc);
        }

    }
    
    function get_correct_utf8_mysql_string($some_string) 
    { 
        /*
if(empty($s)) return $s; 
        $s = preg_match_all("#[\x09\x0A\x0D\x20-\x7E]| 
    [\xC2-\xDF][\x80-\xBF]| 
    \xE0[\xA0-\xBF][\x80-\xBF]| 
    [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}| 
    \xED[\x80-\x9F][\x80-\xBF]#x", $s, $m ); 
        return implode("",$m[0]); 
*/
    //reject overly long 2 byte sequences, as well as characters above U+10000 and replace with ?
$some_string = preg_replace('/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]'.
 '|[\x00-\x7F][\x80-\xBF]+'.
 '|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*'.
 '|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})'.
 '|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S',
 '?', $some_string );

//reject overly long 3 byte sequences and UTF-16 surrogates and replace with ?
$some_string = preg_replace('/\xE0[\x80-\x9F][\x80-\xBF]'.
 '|\xED[\xA0-\xBF][\x80-\xBF]/S','?', $some_string );
 return $some_string;
    }
    
}
