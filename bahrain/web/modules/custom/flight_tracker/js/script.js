//Adding map.
const myMap = L.map('issMap').setView([25.9434256, 50.6014985], 6);
const attribution =
  'Work please';

const tileUrl = 'https://tile.thunderforest.com/cycle/{z}/{x}/{y}.png?apikey=291e459aaefe48f6b1f089392049ef24';
const tiles = L.tileLayer(tileUrl, {attribution});
tiles.addTo(myMap);
//Adding url for json file.
const api_url = 'https://aster0123.github.io/flight_tracker.json';
//Creating marker.
const myIcon = L.icon({
  iconUrl: 'https://www.bahrainairport.bh/images/flighttrack/123.png',
  iconSize: [30, 22],
});

//Function for creating markers with popups.
async function getTracker() {
  const response = await fetch(api_url);
  const data = await response.json();
  console.log(data);

  for (let i = 0; i < data.length; i++) {
    let {
      time,
      origin,
      uri_logo,
      destination,
      latitude,
      longitude,
      direction
    } = data[i];
    let customPopup =
      `<img alt="image" src="${uri_logo}" style="width: 50px;height: 30px;">`
      + '<div class="popupBlock">'
      + '<h3>' + origin + '</h3>' + `<img alt="image" src="https://www.bahrainairport.bh/images/map-flight-icon.jpg" style="width: 30px;height: 30px;">`
      + '<h3>' + destination + '</h3>'
      + '</div>'
      + '<h3 class="time">' + "Estimated time:" + '</br>' + time + '</h3>';

    let marker = L.marker([latitude, longitude])
      .bindPopup(customPopup, {keepInView: true})
      .addTo(myMap);
    marker.setIcon(myIcon);

    let flyPlane = function () {
      if (direction <= 10) {
        latitude = parseFloat(latitude) - 0.005;
        longitude = parseFloat(longitude) + 0.005;
      } else if (direction <= 120) {
        latitude = parseFloat(latitude) + 0.005;
        longitude = parseFloat(longitude) + 0.005;
      } else if (direction <= 180) {
        latitude = parseFloat(latitude) - 0.005;
        longitude = parseFloat(longitude) - 0.005;
      } else if (direction <= 200) {
        latitude = parseFloat(latitude) - 0.005;
        longitude = parseFloat(longitude) + 0.005;
      } else if (direction <= 210) {
        latitude = parseFloat(latitude) - 0.005;
        longitude = parseFloat(longitude) + 0.005;
      }

      function changeLocation() {
        marker.setLatLng([latitude, longitude]);
      }

      changeLocation();
    };
    setTimeout(flyPlane, 0);
    setInterval(flyPlane, 1000);
  }
}

getTracker();
