<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>RoadGuard AI | Smarter Road Safety</title>

    <meta name="description"
          content="RoadGuard AI helps support road safety through incident reporting and intelligent monitoring.">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #0b1120;
            color: #f1f5f9;
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            width: 90%;
            max-width: 1150px;
            margin: auto;
        }

        /* Navigation */

        .navbar {
            background: #10192b;
            border-bottom: 1px solid #29374d;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-content {
            min-height: 75px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: 800;
            white-space: nowrap;
        }

        .logo span {
            color: #60a5fa;
        }

        nav {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 23px;
        }

        nav a {
            color: #cbd5e1;
            font-size: 14px;
            transition: color 0.2s;
        }

        nav a:hover {
            color: #60a5fa;
        }

        nav .nav-button {
            background: #2563eb;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
        }

        /* Hero section */

        .hero {
            padding: 100px 0;
            background:
                radial-gradient(
                    circle at 80% 20%,
                    rgba(37, 99, 235, 0.2),
                    transparent 40%
                );
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            align-items: center;
            gap: 60px;
        }

        .eyebrow {
            color: #60a5fa;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 3px;
            margin-bottom: 18px;
        }

        .hero h1 {
            font-size: clamp(38px, 5vw, 62px);
            line-height: 1.12;
            margin-bottom: 25px;
        }

        .hero h1 span {
            display: block;
            color: #60a5fa;
        }

        .hero-description {
            color: #b6c2d4;
            font-size: 17px;
            margin-bottom: 28px;
            max-width: 600px;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        .btn {
            display: inline-block;
            padding: 13px 20px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            transition: 0.2s;
        }

        .btn:hover {
            transform: translateY(-3px);
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: #18253a;
            border: 1px solid #3b4b64;
            color: white;
        }

        .small-note {
            color: #94a3b8;
            font-size: 12px;
            margin-top: 22px;
        }

        /* Monitoring card */

        .hero-card {
            background: linear-gradient(145deg, #18253a, #111a2b);
            border: 1px solid #2b3a52;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        }

        .shield-icon {
            font-size: 45px;
            margin-bottom: 15px;
        }

        .hero-card h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .hero-card > p {
            color: #a8b5c8;
            margin-bottom: 22px;
        }

        .status-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 0;
            border-top: 1px solid #2b3a52;
            font-size: 13px;
        }

        .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #22c55e;
            flex-shrink: 0;
        }

        .status-dot.planned {
            background: #f59e0b;
        }

        .status-row strong {
            margin-left: auto;
            color: #86efac;
            font-size: 12px;
        }

        .status-row strong.planned-text {
            color: #fcd34d;
        }

        /* Features */

        .features {
            padding: 85px 0;
        }

        .section-heading {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 45px;
        }

        .section-heading h2 {
            font-size: clamp(28px, 4vw, 40px);
            margin-bottom: 12px;
        }

        .section-heading > p:last-child {
            color: #a8b5c8;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        .feature-card {
            background: #151f32;
            border: 1px solid #2b3a52;
            border-radius: 14px;
            padding: 26px;
            transition: transform 0.2s, border-color 0.2s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: #3b82f6;
        }

        .feature-icon {
            font-size: 32px;
            margin-bottom: 15px;
        }

        .feature-card h3 {
            font-size: 19px;
            margin-bottom: 10px;
        }

        .feature-card p {
            color: #a8b5c8;
            font-size: 14px;
            margin-bottom: 18px;
        }

        .feature-card a {
            color: #60a5fa;
            font-size: 13px;
            font-weight: bold;
        }

        .feature-label {
            display: inline-block;
            color: #fcd34d;
            background: #332719;
            padding: 5px 9px;
            border-radius: 6px;
            font-size: 11px;
        }

        /* Call to action */

        .cta-section {
            padding: 60px 0;
            background: #142746;
            border-top: 1px solid #29374d;
            border-bottom: 1px solid #29374d;
        }

        .cta-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
        }

        .cta-content h2 {
            font-size: clamp(25px, 4vw, 36px);
            margin-bottom: 12px;
        }

        .cta-content p:not(.eyebrow) {
            color: #cbd5e1;
            max-width: 650px;
        }

        .btn-light {
            background: white;
            color: #12305a;
            white-space: nowrap;
        }

        /* Footer */

        .footer {
            padding: 35px 0;
        }

        .footer-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 18px;
        }

        .footer p {
            color: #94a3b8;
            font-size: 13px;
        }

        .copyright {
            width: 100%;
            padding-top: 18px;
            border-top: 1px solid #29374d;
        }

        /* Responsive design */

        @media (max-width: 850px) {
            .nav-content {
                padding: 18px 0;
                align-items: flex-start;
                flex-direction: column;
            }

            .hero-content {
                grid-template-columns: 1fr;
                gap: 35px;
            }

            .feature-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .cta-content {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 520px) {
            nav {
                gap: 12px;
            }

            nav a {
                font-size: 12px;
            }

            .hero {
                padding: 60px 0;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .hero-card {
                padding: 22px;
            }
        }
    </style>
</head>

<body>

<header class="navbar">
    <div class="container nav-content">

        <a href="index.php" class="logo">
            🛡️ RoadGuard <span>AI</span>
        </a>

        <nav>
            <a href="index.php">Home</a>
            <a href="#features">Features</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
            <a href="dashboard.php" class="nav-button">Dashboard</a>
        </nav>

    </div>
</header>

<main>

    <section class="hero">
        <div class="container hero-content">

            <div class="hero-text">
                <p class="eyebrow">SMARTER ROADS. SAFER LIVES.</p>

                <h1>
                    Making Every Journey
                    <span>Safer with AI</span>
                </h1>

                <p class="hero-description">
                    RoadGuard AI is a road safety initiative designed to
                    support accident reporting, hazard monitoring, and
                    future AI-powered incident detection.
                </p>

                <div class="hero-buttons">
                    <a href="report-accident.php" class="btn btn-primary">
                        🚨 Report an Incident
                    </a>

                    <a href="dashboard.php" class="btn btn-secondary">
                        View Dashboard
                    </a>
                </div>

                <p class="small-note">
                    Building toward faster incident awareness and safer roads.
                </p>
            </div>

            <div class="hero-card">
                <div class="shield-icon">🛡️</div>

                <h2>Road Safety Monitor</h2>

                <p>
                    A central platform for incident reports and road safety monitoring.
                </p>

                <div class="status-row">
                    <span class="status-dot"></span>
                    <span>Incident reporting</span>
                    <strong>Available</strong>
                </div>

                <div class="status-row">
                    <span class="status-dot planned"></span>
                    <span>AI detection</span>
                    <strong class="planned-text">Planned</strong>
                </div>

                <div class="status-row">
                    <span class="status-dot planned"></span>
                    <span>Hardware sensors</span>
                    <strong class="planned-text">Planned</strong>
                </div>
            </div>

        </div>
    </section>

    <section class="features" id="features">
        <div class="container">

            <div class="section-heading">
                <p class="eyebrow">OUR CAPABILITIES</p>

                <h2>Technology for Safer Roads</h2>

                <p>
                    A foundation for connecting incident reports,
                    location information, and future intelligent monitoring.
                </p>
            </div>

            <div class="feature-grid">

                <article class="feature-card">
                    <div class="feature-icon">🚨</div>
                    <h3>Incident Reporting</h3>

                    <p>
                        Submit road accident, hazard, and vehicle breakdown
                        reports through a simple form.
                    </p>

                    <a href="report-accident.php">Report an incident →</a>
                </article>

                <article class="feature-card">
                    <div class="feature-icon">📍</div>
                    <h3>Location Information</h3>

                    <p>
                        Record geographic coordinates with incident reports
                        when location information is available.
                    </p>

                    <a href="report-accident.php">Submit a location →</a>
                </article>

                <article class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Incident Dashboard</h3>

                    <p>
                        Provide a central place to review submitted incidents
                        and their reported details.
                    </p>

                    <a href="dashboard.php">Open dashboard →</a>
                </article>

                <article class="feature-card">
                    <div class="feature-icon">🤖</div>
                    <h3>AI Detection</h3>

                    <p>
                        Future development will explore computer vision
                        for identifying potential road hazards.
                    </p>

                    <span class="feature-label">Planned feature</span>
                </article>

                <article class="feature-card">
                    <div class="feature-icon">📡</div>
                    <h3>Sensor Integration</h3>

                    <p>
                        Future ESP32 and GPS integration may provide
                        sensor readings and device location.
                    </p>

                    <span class="feature-label">Planned feature</span>
                </article>

                <article class="feature-card">
                    <div class="feature-icon">🌍</div>
                    <h3>Safer Communities</h3>

                    <p>
                        Support road safety awareness and the goal
                        of reducing preventable harm.
                    </p>

                    <span class="feature-label">Project mission</span>
                </article>

            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container cta-content">

            <div>
                <p class="eyebrow">MISSION ZERO</p>

                <h2>Every report can help build awareness.</h2>

                <p>
                    Try the reporting system with a simulated incident
                    while we continue developing RoadGuard AI.
                </p>
            </div>

            <a href="report-accident.php" class="btn btn-light">
                Try the Reporting Form →
            </a>

        </div>
    </section>

</main>

<footer class="footer">
    <div class="container footer-content">

        <a href="index.php" class="logo">
            🛡️ RoadGuard <span>AI</span>
        </a>

        <p>A road safety prototype by Mission Zero.</p>

        <p class="copyright">
            &copy; <?php echo date("Y"); ?> RoadGuard AI.
            Built for safer roads.
        </p>

    </div>
</footer>

</body>
</html>