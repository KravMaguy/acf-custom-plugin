

(function ($) {
  console.log("im in the plugin file yes");
  /**
   * initMap
   *
   * Renders a Google Map onto the selected jQuery element
   *
   * @date    22/10/19
   * @since   5.8.6
   *
   * @param   jQuery $el The jQuery element.
   * @return  object The map instance.
   */
   
   
   
  function initMap($el) {
    // Find marker elements within map.
    var $markers = $el.find(".marker");

    // Create gerenic map.
    var mapArgs = {
      zoom: $el.data("zoom") || 16,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
    };
    var map = new google.maps.Map($el[0], mapArgs);

    // Add markers.
    map.markers = [];
    $markers.each(function () {
      initMarker($(this), map);
    });

    // Center map based on markers.
    centerMap(map);

    // Return map instance.
    return map;
  }

  /**
   * initMarker
   *
   * Creates a marker for the given jQuery element and map.
   *
   * @date    22/10/19
   * @since   5.8.6
   *
   * @param   jQuery $el The jQuery element.
   * @param   object The map instance.
   * @return  object The marker instance.
   */

  var infoWindows = [];
  function initMarker($marker, map) {
    var lat = $marker.data("lat");
    var lng = $marker.data("lng");
    const svgMarker = {
      path:
        "M10.453 14.016l6.563-6.609-1.406-1.406-5.156 5.203-2.063-2.109-1.406 1.406zM12 2.016q2.906 0 4.945 2.039t2.039 4.945q0 1.453-0.727 3.328t-1.758 3.516-2.039 3.070-1.711 2.273l-0.75 0.797q-0.281-0.328-0.75-0.867t-1.688-2.156-2.133-3.141-1.664-3.445-0.75-3.375q0-2.906 2.039-4.945t4.945-2.039z",
      fillColor: "#09367A",
      fillOpacity: 0.9,
      strokeWeight: 0,
      rotation: 0,
      scale: 2,
      anchor: new google.maps.Point(15, 30),
    };
    var latLng = { lat: parseFloat(lat), lng: parseFloat(lng) };
    var marker = new google.maps.Marker({
      position: latLng,
      map: map,
      icon: svgMarker,
    });
    map.markers.push(marker);
    if ($marker.html()) {
      var infoWindow = new google.maps.InfoWindow({ content: $marker.html() });
      infoWindows.push(infoWindow);
      google.maps.event.addListener(marker, "click", function () {
        for (var i = 0; i < infoWindows.length; i++) {
          infoWindows[i].close();
        }
        infoWindow.open(map, marker);
      });
      google.maps.event.addListener(map, "click", function () {
        infoWindow.close();
      });
    }
  }

  /**
   * centerMap
   *
   * Centers the map showing all markers in view.
   *
   * @date    22/10/19
   * @since   5.8.6
   *
   * @param   object The map instance.
   * @return  void
   */
  function centerMap(map) {
    // Create map boundaries from all map markers.
    var bounds = new google.maps.LatLngBounds();
    map.markers.forEach(function (marker) {
      bounds.extend({
        lat: marker.position.lat(),
        lng: marker.position.lng(),
      });
    });

    // Case: Single marker.
    if (map.markers.length == 1) {
      map.setCenter(bounds.getCenter());

      // Case: Multiple markers.
    } else {
      map.fitBounds(bounds);
    }
  }

  // Render maps on page load.
  $(document).ready(function () {
    $(".acf-map").each(function () {
      var map = initMap($(this));
    });
  });
})(jQuery);
