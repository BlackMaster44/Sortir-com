import * as L from 'leaflet'

const iconUrl = `http://${window.location.host}/img/leafletIcons/marker-icon.png`;
const shadowUrl = `http://${window.location.host}/img/leafletIcons/marker-shadow.png`;
const iconDefault = L.icon({
    iconUrl,
    shadowUrl,
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    tooltipAnchor: [16, -28],
    shadowSize: [41, 41]
});
L.Marker.prototype.options.icon = iconDefault;
const latitude = document.querySelector('.latitude');
const longitude = document.querySelector('.longitude');
const map = L.map('mapEdit').setView([48.856614, 2.3522219], 1);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    updateWhenIdle: false,
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);
const {lat, lng} = map.getCenter();
latitude.value = lat;
longitude.value = lng;
let latDyn = lat;
let lngDyn = lng;
const marker = L.marker();

map.on('click', (e) => {
    const {lat, lng} = e.latlng;
    latitude.value = lat;
    longitude.value = lng;
    marker.remove();
    marker.setLatLng(e.latlng).addTo(map);
})
setInterval(function () {
    map.invalidateSize();
}, 100);

map.setView([latitude.value, longitude.value], 13);
[latitude, longitude].forEach(l => l.addEventListener('input', (e)=>{
    e.target.className='latitude' ? latDyn = e.target.value : lngDyn = e.target.value
    map.setView([latDyn, lngDyn], 13);
}))