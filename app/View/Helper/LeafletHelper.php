<?php 
class LeafletHelper extends AppHelper {
        /*
        Marker properties:
                name
                lat
                lng
                content
                content-min-width
                icon-url
                icon-width
                icon-height
                draggable
                events > dragend, other leaflet events

        Map properties:
                center: lat, lng, zoom OR bounds: north, south, east, west (bounds)
         */

    var $helpers = array("Html");

    protected $_defaultOptions = array(
        'div' => array(
            'id' => 'leaflet-map',
            'width' => '100%',
            'height' => '500px',
            'class' => 'map-canvas',
        ),
        'map' => array(
            'center' => array(),
                'bounds' => array(),
        ),
        'layers' => array(
            'Mapnik' => array(
                'url' => 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                'attribution' => 'Map data &copy; 2012 OpenStreetMap contributors',
                'default' => true,
//                '2x' => false,
            ),
/*
                'OpenOrienteeringMap' => array(
                        'url' => 'http://orca.casa.ucl.ac.uk/~ollie/maptiler/oterrain_global/{z}/{x}/{y}.png',
                        'attribution' => 'OpenOrienteeringMap (oobrien.com), Map data &copy; 2011 OpenStreetMap contributors',
                        'minZoom' => 14,
                        'default' => false,
                        '2x' => false,
                ),
            'MapBox Streets' => array(
                'attribution' => 'Map data &copy; 2012 OpenStreetMap contributors',
                'url'  => 'http://{s}.tiles.mapbox.com/v3/russell.map-8bk5s5sh/{z}/{x}/{y}.png',
                'default' => true,
                'maxZoom' => 17,
                '2x' => true,
            ),
*/
        ),
        'markers' => array(

        ),
        'js' => true,
        'html' => true,
        'pan-interaction' => true,
    );

    private function dependencies() {
        $this->Html->script("leaflet/leaflet", array('inline' => false, 'once' => true));
        }

        function map($options = array()) {
            $options = $this->_combineOptions($options);

            $divId = $options['div']['id'];
            $divWidth = $options['div']['width'];
            $divHeight = $options['div']['height'];
            $divClass = $options['div']['class'];
            $map = '';
            if ($options['html'] === true) {
                $map .= "<div id='${divId}' class='${divClass}' style='height: ${divHeight}; width: ${divWidth}'></div>";
                }

                if ($options['js'] === true) {
                    $map .= $this->script($options);
                }
                return $map;
        }

        function script($options = array()) {
            $this->dependencies();
            $options = $this->_combineOptions($options);
            $divId = $options['div']['id'];
            //$script = "$(function() {
            //";
            $script = "var map = new L.Map('${divId}');
            ";

            $script .= $this->_addMarkers($options);
            $script .= $this->_addLayers($options);

            if(!empty($options['map']['bounds'])) {
                $north = $options['map']['bounds']['north'];
                $south = $options['map']['bounds']['south'];
                $east = $options['map']['bounds']['east'];
                $west = $options['map']['bounds']['west'];
                $setView = "fitBounds(new L.LatLngBounds(new L.LatLng(${north}, ${west}), new L.LatLng(${south}, ${east})))";
                } else {
                    $lat = $options['map']['center']['lat'];
                    $lng = $options['map']['center']['lng'];
                    $zoom = $options['map']['center']['zoom'];

                    $setView = "setView(new L.LatLng(${lat}, ${lng}), ${zoom})";
                }


                //$script .= "defaultLayer = (window.devicePixelRatio > 1) ? hidpiDefaultLayer : defaultLayer;
            //";
                $script .= "map.${setView}.addLayer(defaultLayer);
                ";
                if ($options['pan-interaction']) {
                    $script .= 'var layerPicker = new L.Control.Layers(layers);
                    map.addControl(layerPicker);
                    ';
                } else {
                    // disable interaction handlers that impact scrolling
                    $script .= 'map.dragging.disable();
                    map.scrollWheelZoom.disable();
                    ';
                }

                return $this->Html->scriptBlock($script);
        }

        function simpleMarker($lat, $lng, $zoom, $height, $options = array()) {
            $options['div'] = array();
            $options['div']['height'] = $height;
            $options['map'] = array();
            $options['map']['center']['lat'] = $lat;
            $options['map']['center']['lng'] = $lng;
            $options['map']['center']['zoom'] = $zoom;
            $options['markers'] = array();
            $marker = array();
            $marker['lat'] = $lat;
            $marker['lng'] = $lng;
            array_push($options['markers'], $marker);
            return $this->map($options);
        }

        function draggableMarker($latField, $lngField, $zoom, $options = array()) {
            $options['map'] = array();
            $options['map']['center']['lat'] = "$('#${latField}').val()";
            $options['map']['center']['lng'] = "$('#${lngField}').val()";
            $options['map']['center']['zoom'] = $zoom;
            $options['markers'] = array();

            $marker = array();
            $marker['lat'] = "$('#${latField}').val()";
            $marker['lng'] = "$('#${lngField}').val()";
            $marker['draggable'] = true;
            $marker['events']['dragend'] = "function(e) {
                $('#${latField}').val(this.getLatLng().lat);
                $('#${lngField}').val(this.getLatLng().lng);
                                }
                ";
                array_push($options['markers'], $marker);

                return $this->map($options);
        }

        function _addLayers($options) {
            $code = "var layers = {}, defaultLayer, hidpiDefaultLayer;
            ";
            $notAttributes = array('name', 'url', '2x');

            foreach($options['layers'] as $name => $layerOptions) {
                $url = $layerOptions['url'];

                $isHiDPI = !empty($layerOptions['2x']) && $layerOptions['2x'];
                if($isHiDPI) {
                    $layerOptions['detectRetina'] = true;
                        }

                        $layerName = "layers[".json_encode($name)."]";

                        if($isHiDPI) $code .= 'if(window.devicePixelRatio > 1) {';

                        foreach($notAttributes as $attribute) {
                            unset($layerOptions[$attribute]);
                        }
                        $code .= $layerName." = new L.TileLayer('${url}', ".json_encode($layerOptions).");
                        ";
                        if($layerOptions['default'] === true || count($options["layers"]) === 1) {
                            $defaultVariableName = $isHiDPI ? "hidpiDefaultLayer" : "defaultLayer";
                            $code .= $defaultVariableName." = ".$layerName.";
                            ";
                        }

                        if($isHiDPI) $code .= '}';
                }

                return $code;
        }

        function _addMarkers($options) {
            $code = "var addMarker = function (map, marker, contents, contentsMinWidth, eventCallbacks) {
                if(contents) {
                    marker.bindPopup(contents, {minWidth: contentsMinWidth});
                        }

                        for(var callback in eventCallbacks) {
                            if(eventCallbacks.hasOwnProperty(callback)) {
                                marker.on(callback, eventCallbacks[callback]);
                                }
                        }

                        map.addLayer(marker);
                }
                ";
                foreach($options["markers"] as $marker) {
                    $lat = $marker['lat'];
                    $lng = $marker['lng'];
                    $options = 'undefined';
                    $contents = 'undefined';
                    $contentsMinWidth = 'undefined';
                    $iconUrl = null;
                    $iconWidth = null;
                    $iconHeight = null;
                    $anchorX = null;
                    $anchorY = null;
                    $eventCallbacks = '{}';

                    if(!empty($marker["content"])) {
                        $contents = json_encode($marker["content"]);
                        $contentsMinWidth = $marker["content-min-width"];
                        }

                        if(!empty($marker['draggable']) && $marker['draggable'] === true) {
                            $options = json_encode(array('draggable' => true));
                        }

                        if(!empty($marker["icon-url"])) {
                            $iconUrl = $marker["icon-url"];
                            $iconWidth = $marker["icon-width"];
                            $iconHeight = $marker["icon-height"];

                            // Correctly align the point of the marker
                            $anchorX = floor($marker["icon-width"]/2);
                            $anchorY = $marker["icon-height"];
                            $shadowSize = "";
                            if(!empty($marker["shadow-width"])) {
                                $shadowWidth = $marker["shadow-width"];
                                $shadowHeight = $marker["shadow-height"];
                                $shadowSize = "shadowSize: new L.Point(${shadowWidth}, ${shadowHeight})";
                                }
                                $options = "{icon: new L.Icon({
                                    iconUrl: '${iconUrl}',
                                        iconSize: new L.Point(${iconWidth}, ${iconHeight}),
                                        iconAnchor: new L.Point(${anchorX}, ${anchorY}),
                                        $shadowSize
                                })}";
                        }

                        if(!empty($marker["events"]) && count($marker["events"]) > 0) {
                            $eventCallbacks = "{";
                            foreach($marker["events"] as $key => $value) {
                                $eventCallbacks .= '"'.$key.'": '.$value.',';
                                }
                                $eventCallbacks .= "}";
                        }

                        $code .= "addMarker(map, new L.Marker(new L.LatLng(${lat}, ${lng}), ${options}), ${contents}, ${contentsMinWidth}, ${eventCallbacks});
                        ";
                }
                return $code;
        }

        function _combineOptions(&$options) {
            $result = $this->_defaultOptions;
            foreach($result as $key => $option) {
                if(isset($options[$key])) {
                    if(is_array($options[$key])) {
                        $result[$key] = array_merge($result[$key], $options[$key]);
                        } else {
                            $result[$key] = $options[$key];
                        }
                        }

                }
                return $result;
        }

        function _merge($a1, $a2) {  

            foreach($a1 as $k => $v) {
                if(!array_key_exists($k,$a2)) continue;
                if(is_array($v) && is_array($a2[$k])){
                    $a1[$k] = $this->_merge($v,$a2[$k]);
                        }else{
                            $a1[$k] = $a2[$k];
                        }
                }
                return $a1;
        }

}
