function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 11,
        center: { lat: 45.277330, lng:  -0.969366 },
    });
    // Define the LatLng coordinates for the polygon's path.
    const triangleCoords = [
        { lat: 45.131779, lng: -0.819495},
        { lat: 45.259457, lng: -0.761997},
        { lat:45.34794430769853, lng:-0.806965171875007},
        { lat: 45.392913, lng: -0.873906},
        { lat: 45.450872, lng: -0.964185},
        { lat: 45.387447, lng: -1.160069},
        { lat: 45.245091131624626, lng: -1.146637256835934},
        { lat: 45.169222, lng: -1.074154},
    ];
    // Construct the polygon.
    const zone = new google.maps.Polygon({
        paths: triangleCoords,
        strokeColor: "#c700ff",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#efbafa",
        fillOpacity: 0.35,
    });

    zone.setMap(map);

    window.zone = zone
}

window.initMap = initMap;