<?php
require_once __DIR__ . "/config/db.php";

try {
    $stmt = $pdo->query(
        "SELECT incident_code, incident_type, title, description,
                severity, status, incident_time, is_simulated
         FROM incidents
         ORDER BY incident_time DESC"
    );

    $incidents = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Dashboard query error: " . $e->getMessage());
    $incidents = [];
    $error = "Unable to load incidents. Please check your database table structure.";
}

function escape($value) {
    return htmlspecialchars((string)($value ?? ""), ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Dashboard | RoadGuard AI</title>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #0b1120;
            color: #f1f5f9;
            line-height: 1.6;
            padding: 30px 16px;
        }

        .container { max-width: 1200px; margin: auto; }

        a { color: #93c5fd; text-decoration: none; }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 28px;
        }

        .topbar h1 { font-size: 30px; }

        .button {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 11px 16px;
            border-radius: 8px;
            font-weight: bold;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }

        .summary-card, .table-card {
            background: #151f32;
            border: 1px solid #2b3a52;
            border-radius: 12px;
            padding: 22px;
        }

        .summary-card p { color: #a8b5c8; font-size: 14px; }
        .summary-card h2 { font-size: 32px; margin-top: 8px; }

        .table-card h2 { margin-bottom: 18px; }

        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        th, td {
            text-align: left;
            padding: 14px 12px;
            border-bottom: 1px solid #2b3a52;
            vertical-align: top;
        }

        th { color: #93c5fd; font-size: 13px; }
        td { font-size: 13px; color: #dbe4f0; }

        .badge {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            background: #263449;
        }

        .critical, .high { color: #fca5a5; background: #4a1f27; }
        .medium { color: #fcd34d; background: #45351a; }
        .low { color: #86efac; background: #143b2a; }

        .empty, .error {
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .empty { background: #18253a; color: #cbd5e1; }
        .error { background: #4a1f27; color: #fecaca; }

        .description {
            max-width: 260px;
            overflow-wrap: anywhere;
        }

        @media (max-width: 650px) {
            .summary { grid-template-columns: 1fr; }
            .topbar h1 { font-size: 24px; }
        }
    </style>
</head>
<body>
<div class="container">

    <header class="topbar">
        <div>
            <p><a href="index.php">← RoadGuard AI Home</a></p>
            <h1>Incident Dashboard</h1>
            <p style="color:#a8b5c8">Review recorded road incident reports.</p>
        </div>

        <a href="report-accident.php" class="button">+ Report Incident</a>
    </header>

    <?php if (isset($error)): ?>
        <div class="error"><?= escape($error) ?></div>
    <?php endif; ?>

    <?php
    $total = count($incidents);
    $open = 0;
    $simulated = 0;

    foreach ($incidents as $incident) {
        if (in_array(strtolower($incident["status"] ?? ""), ["reported", "investigating"], true)) {
            $open++;
        }

        if ((int)($incident["is_simulated"] ?? 0) === 1) {
            $simulated++;
        }
    }
    ?>

    <section class="summary">
        <div class="summary-card">
            <p>Total incidents</p>
            <h2><?= $total ?></h2>
        </div>

        <div class="summary-card">
            <p>Open incidents</p>
            <h2><?= $open ?></h2>
        </div>

        <div class="summary-card">
            <p>Demo incidents</p>
            <h2><?= $simulated ?></h2>
        </div>
    </section>

    <section class="table-card">
        <h2>Recent Reports</h2>

        <?php if (empty($incidents) && !isset($error)): ?>
            <div class="empty">
                No incidents have been reported yet.
                <a href="report-accident.php">Submit a demo report</a> to get started.
            </div>
        <?php elseif (!empty($incidents)): ?>

            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Type</th>
                        <th>Title & Description</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Reported At</th>
                        <th>Demo?</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($incidents as $incident): ?>
                        <?php
                        $severityClass = strtolower($incident["severity"] ?? "medium");
                        if (!in_array($severityClass, ["low", "medium", "high", "critical"], true)) {
                            $severityClass = "medium";
                        }
                        ?>
                        <tr>
                            <td><?= escape($incident["incident_code"]) ?></td>
                            <td><?= escape(ucwords(str_replace("_", " ", $incident["incident_type"] ?? ""))) ?></td>
                            <td>
                                <strong><?= escape($incident["title"]) ?></strong>
                                <div class="description"><?= escape($incident["description"]) ?></div>
                            </td>
                            <td>
                                <span class="badge <?= escape($severityClass) ?>">
                                    <?= escape(ucfirst($severityClass)) ?>
                                </span>
                            </td>
                            <td><?= escape(ucwords(str_replace("_", " ", $incident["status"] ?? ""))) ?></td>
                            <td><?= escape($incident["incident_time"]) ?></td>
                            <td><?= (int)($incident["is_simulated"] ?? 0) === 1 ? "Yes" : "No" ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </section>

    <p style="color:#94a3b8;font-size:12px;margin-top:20px">
        RoadGuard AI is a prototype dashboard. It does not replace emergency services.
    </p>

</div>
</body>
</html>