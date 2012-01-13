<?php
/* Get metadata about a lat, lng point. Also handles caching this information */

class GeocodeHelper extends AppHelper {
    var $cacheStore = 'geocoded';

    function lookUp($lat, $lng) {
    	$key = $lat." ".$lng;
    	$result = Cache::read($key, $this->cacheStore);
    	
    	if($result === false) {
			if(!empty($lat)) {
				// Using Mapquest API based on OpenStreetMap data
				$requestUrl = "http://open.mapquestapi.com/nominatim/v1/reverse?format=json&lat=" . $lat . "&lon=" . $lng;
				$requestResults = json_decode(file_get_contents($requestUrl));
				if(!empty($requestResults->{'address'}->{'suburb'})) { 
					$result["neighbourhood"] = $requestResults->{'address'}->{'suburb'};
				} else {
					$result["neighbourhood"] = "";
				}
				
				if(!empty($requestResults->{'address'}->{'city'})) { 
				    $result["city"] = $requestResults->{'address'}->{'city'};
				} else {
				    $result["city"] = "";
				}
			} else {
				$result["city"] = "";
				$result["neighbourhood"] = "";
			}
			Cache::delete($key, $this->cacheStore);
			Cache::write($key, $result, $this->cacheStore);
		}
		
		return $result;
    }
}

?>
