import { MapContainer, TileLayer, Marker, Popup, ScaleControl } from "react-leaflet";
import L from "leaflet";

const position: [number, number] = [
    47.341811344805635,
    0.6309143289699515,
];

const markerIcon = new L.Icon({
    iconUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png",
    shadowUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png",
    iconSize: [25, 41],
    iconAnchor: [12, 41],
});

export function ClubMap() {
    return (
        <section className="maps" aria-labelledby="maps-title">

            <p className="sr-only" id="map-desc">
                Carte indiquant l'emplacement du Billard Club de Joué-Lès-Tours.
            </p>

            <div
                className="map-container"
                role="region"
                aria-labelledby="maps-title"
                aria-describedby="map-desc"
            >
                <MapContainer
                    center={position}
                    zoom={17}
                    scrollWheelZoom={false}
                    className="leaflet-map"
                >
                    <TileLayer
                        attribution="&copy; OpenStreetMap contributors"
                        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                        maxZoom={30}
                    />

                    <Marker position={position} icon={markerIcon}>
                        <Popup>
                            <strong>Billard Club de Joué-Lès-Tours</strong>
                            <br />
                            28 Rue Joseph Cugnot, 37300 Joué-lès-Tours
                            <br />
                            5 Pool
                            <br />
                            5 Carambole
                            <br />
                            1 Snooker
                        </Popup>
                    </Marker>

                    <ScaleControl />
                </MapContainer>
            </div>

            <p className="mt-2">
                <a
                    href="https://www.google.com/maps/search/?api=1&query=Billard+Club+de+Joué-Lès-Tours"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="Ouvrir l’itinéraire dans Google Maps (nouvel onglet)"
                >
                    Ouvrir dans Google Maps
                </a>
            </p>
        </section>
    );
}