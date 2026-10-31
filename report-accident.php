<?php
require_once __DIR__ . "/config/db.php";

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $incidentType = $_POST["incident_type"] ?? "";
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $severity = $_POST["severity"] ?? "medium";
    $latitudeInput = trim($_POST["latitude"] ?? "");
    $longitudeInput = trim($_POST["longitude"] ?? "");
    $isSimulated = isset($_POST["is_simulated"]) ? 1 : 0;

    $allowedTypes = [
        "accident",
        "road_hazard",
        "vehicle_breakdown",
        "other"
    ];

    $allowedSeverities = [
        "low",
        "medium",
        "high",
        "critical"
    ];

    if (
        !in_array($incidentType, $allowedTypes, true) ||
        !in_array($severity, $allowedSeverities, true)
    ) {
        $error = "Please select valid incident type and severity.";
    } elseif ($title === "" || $description === "") {
        $error = "Please enter both a title and description.";
    } elseif (
        ($latitudeInput !== "" && !is_numeric($latitudeInput)) ||
        ($longitudeInput !== "" && !is_numeric($longitudeInput))
    ) {
        $error = "Latitude and longitude must be valid numbers.";
    } elseif (
        ($latitudeInput !== "" &&
            ((float)$latitudeInput < -90 || (float)$latitudeInput > 90)) ||
        ($longitudeInput !== "" &&
            ((float)$longitudeInput < -180 || (float)$longitudeInput > 180))
    ) {
        $error = "Latitude must be between -90 and 90, and longitude between -180 and 180.";
    } else {
        try {
            $incidentCode = "RG-" . strtoupper(bin2hex(random_bytes(4)));

            $latitude = $latitudeInput === "" ? null : (float)$latitudeInput;
            $longitude = $longitudeInput === "" ? null : (float)$longitudeInput;

            $sql = "INSERT INTO incidents
                (
                    incident_code,
                    incident_type,
                    title,
                    description,
                    severity,
                    latitude,
                    longitude,
                    incident_source,
                    status,
                    incident_time,
                    is_simulated
                )
                VALUES
                (
                    :incident_code,
                    :incident_type,
                    :title,
                    :description,
                    :severity,
                    :latitude,
                    :longitude,
                    'web_form',
                    'reported',
                    NOW(),
                    :is_simulated
                )";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":incident_code" => $incidentCode,
                ":incident_type" => $incidentType,
                ":title" => $title,
                ":description" => $description,
                ":severity" => $severity,
                ":latitude" => $latitude,
                ":longitude" => $longitude,
                ":is_simulated" => $isSimulated
            ]);

            $success = "Incident submitted successfully! Reference: " . $incidentCode;
            $_POST = [];
        } catch (PDOException $e) {
            error_log("Incident submission error: " . $e->getMessage());
            $error = "We couldn't save the incident. Please check your database table structure.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report an Incident | RoadGuard AI</title>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #0b1120;
            color: #f1f5f9;
            line-height: 1.6;
            padding: 30px 16px;
        }

        .container { max-width: 760px; margin: auto; }

        .back-link {
            display: inline-block;
            margin-bottom: 24px;
            color: #93c5fd;
            text-decoration: none;
        }

        .card {
            background: #151f32;
            border: 1px solid #2b3a52;
            border-radius: 16px;
            padding: 30px;
        }

        h1 { margin-bottom: 8px; font-size: 30px; }

        .intro { color: #a8b5c8; margin-bottom: 24px; }

        label {
            display: block;
            margin: 17px 0 7px;
            font-weight: bold;
            font-size: 14px;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            background: #0b1120;
            border: 1px solid #3b4b64;
            border-radius: 8px;
            color: #f1f5f9;
            font: inherit;
        }

        textarea { min-height: 120px; resize: vertical; }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
            color: #cbd5e1;
            font-size: 14px;
        }

        .checkbox-row input { width: auto; }

        button {
            width: 100%;
            padding: 14px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover { background: #1d4ed8; }

        .message {
            padding: 13px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .success { background: #143b2a; color: #86efac; }
        .error { background: #4a1f27; color: #fecaca; }

        .note { color: #94a3b8; font-size: 12px; margin-top: 16px; }

        @media (max-width: 550px) {
            .grid { grid-template-columns: 1fr; gap: 0; }
            .card { padding: 21px; }
        }
    </style>
</head>
<body>
<div class="container">
    <a class="back-link" href="index.php">← Back to RoadGuard AI</a>

    <div class="card">
        <h1>🚨 Report a Road Incident</h1>
        <p class="intro">Provide the details below to record an incident.</p>

        <?php if ($success !== ""): ?>
            <div class="message success">
                <?= htmlspecialchars($success, ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div class="message error">
                <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="incident_type">Incident Type</label>
            <select id="incident_type" name="incident_type" required>
                <option value="">Select incident type</option>
                <option value="accident">Road Accident</option>
                <option value="road_hazard">Road Hazard</option>
                <option value="vehicle_breakdown">Vehicle Breakdown</option>
                <option value="other">Other</option>
            </select>

            <label for="title">Incident Title</label>
            <input
                type="text"
                id="title"
                name="title"
                maxlength="150"
                placeholder="e.g. Vehicle collision near junction"
                value="<?= htmlspecialchars($_POST["title"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                required
            >

            <label for="description">Description</label>
            <textarea
                id="description"
                name="description"
                maxlength="5000"
                placeholder="Describe what happened..."
                required
            ><?= htmlspecialchars($_POST["description"] ?? "", ENT_QUOTES, "UTF-8") ?></textarea>

            <div class="grid">
                <div>
                    <label for="severity">Severity</label>
                    <select id="severity" name="severity" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>

                <div>
                    <label for="latitude">Latitude (optional)</label>
                    <input
                        type="number"
                        id="latitude"
                        name="latitude"
                        step="any"
                        min="-90"
                        max="90"
                        placeholder="e.g. 19.8762"
                        value="<?= htmlspecialchars($_POST["latitude"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                    >
                </div>
            </div>

            <label for="longitude">Longitude (optional)</label>
            <input
                type="number"
                id="longitude"
                name="longitude"
                step="any"
                min="-180"
                max="180"
                placeholder="e.g. 75.3433"
                value="<?= htmlspecialchars($_POST["longitude"] ?? "", ENT_QUOTES, "UTF-8") ?>"
            >

            <label class="checkbox-row">
                <input type="checkbox" name="is_simulated" value="1" checked>
                This is a simulated/demo incident
            </label>

            <button type="submit">Submit Incident</button>

            <p class="note">
                This prototype records reports in its database. It does not
                automatically contact emergency services.
            </p>
        </form>
    </div>
</div>
</body>
</html>