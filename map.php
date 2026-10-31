<?php
require_once __DIR__ . "/config/db.php";

try {
    $stmt = $pdo->query(
        "SELECT incident_code, incident_type, title, severity,
                latitude, longitude, status
         FROM incidents
         WHERE latitude IS NOT NULL
           AND longitude IS NOT NULL
         ORDER BY incident_time DESC"
    );

    $incidents = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Map query error: " . $e->getMessage());
    $incidents = [];
    $error = "Unable to load incident locations.";
}

$mapIncidents = [];

foreach ($incidents as $incident) {
    if (
        is_numeric($incident["latitude"]) &&
        is_numeric($incident["longitude"]) &&
        (float)$incident["latitude"] >= -90 &&
        (float)$incident["latitude"] <= 90 &&
        (float)$incident["longitude"] >= -180 &&
        (float)$incident["longitude"] <= 180
    ) {
        $mapIncidents[] = $incident;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Map | RoadGuard AI</title>

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    >

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 25px;
            font-family: Arial, sans-serif;
            background: #0b1120;
            color: #f1f5f9;
        }

        .container { max-width: 1200px; margin: auto; }

        a { color: #93c5fd; text-decoration: none; }

        h1 { margin-bottom: 8px; }

        .intro { color: #a8b5c8; margin-bottom: 22px; }

        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 11px 15px;
            border-radius: 8px;
        }

        #map {
            width: 100%;
            height: 550px;
            border: 1px solid #334155;
            border-radius: 12px;
            background: #172033;
        }

        .notice {
            background: #151f32;
            border: 1px solid #2b3a52;
            border-radius: 8px;
            padding: 15px;
            margin: 18px 0;
            color: #cbd5e1;
        }

        .error { color: #fecaca; }

        @media (max-width: 600px) {
            body { padding: 15px; }
            #map { height: 450px; }
        }
    </style>
</head>
<body>
<div class="container">

    <p><a href="index.php">← Back to Home</a></p>

    <h1>📍 RoadGuard AI Incident Map</h1>

    <p class="intro">
        View reported incidents that have valid geographic coordinates.
    </p>

    <div class="toolbar">
        <a class="button" href="dashboard.php">View Dashboard</a>
        <a class="button" href="report-accident.php">Report Incident</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="notice error">
            <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
        </div>
    <?php endif; ?>

    <div class="notice">
        Incidents with coordinates: <strong><?= count($mapIncidents) ?></strong>
        <br>
        Only reports with latitude and longitude can appear on the map.
    </div>

    <div id="map"></div>

    <div class="notice">
        This is a prototype map, not an emergency dispatch service.
    </div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const incidents = <?= json_encode(
        $mapIncidents,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
    ) ?>;

    // Default map center: India
    const map = L.map("map").setView([20.5937, 78.9629], 5);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
        maxZoom: 19
    }).addTo(map);

    const bounds = [];

    incidents.forEach(function (incident) {
        const lat = Number(incident.latitude);
        const lng = Number(incident.longitude);

        const marker = L.marker([lat, lng]).addTo(map);

        const popup = document.createElement("div");

        const title = document.createElement("strong");
        title.textContent = incident.title || "Incident";

        const details = document.createElement("p");
        details.textContent =
            "Reference: " + (incident.incident_code || "") +
            " | Type: " + (incident.incident_type || "") +
            " | Severity: " + (incident.severity || "") +
            " | Status: " + (incident.status || "");

        popup.appendChild(title);
        popup.appendChild(details);

        marker.bindPopup(popup);
        bounds.push([lat, lng]);
    });

    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [30, 30], maxZoom: 13 });
    }
</script>

</body>
</html>