<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'School Management System') }}</title>
 
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
 
    <style>
        :root {
            --brand: #1F3BB3;
            --brand-dark: #16297f;
            --brand-light: #4a63d1;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            color: #1b1e2b;
            background: #f6f7fb;
        }
        a { text-decoration: none; }
 
        /* ---- Nav ---- */
        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 5%;
            background: #fff;
            border-bottom: 1px solid #eceef5;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            font-size: 19px;
            color: #1b1e2b;
        }
        .brand-badge {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand), var(--brand-light));
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 15px;
        }
        .nav-links a {
            color: #444a5e;
            margin-left: 28px;
            font-weight: 500;
            font-size: 14.5px;
        }
        .nav-btn {
            background: var(--brand);
            color: #fff !important;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            margin-left: 28px;
        }
        .nav-btn:hover { background: var(--brand-dark); }
 
        /* ---- Hero ---- */
        .hero {
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
            padding: 90px 5% 110px;
            position: relative;
            overflow: hidden;
        }
        .hero::after {
            content: "";
            position: absolute;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            right: -150px; top: -200px;
        }
        .hero-inner {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 50px;
            max-width: 1180px;
            margin: 0 auto;
            position: relative;
        }
        .hero-text { flex: 1 1 420px; }
        .badge-pill {
            display: inline-block;
            background: rgba(255,255,255,0.14);
            color: #dfe4fd;
            padding: 7px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .04em;
            margin-bottom: 22px;
        }
        .hero-text h1 {
            font-size: 44px;
            line-height: 1.18;
            font-weight: 800;
            color: #fff;
            margin: 0 0 18px;
        }
        .hero-text h1 span { color: #b7c3ff; }
        .hero-text p {
            color: #dbe0fb;
            font-size: 16.5px;
            line-height: 1.7;
            max-width: 480px;
            margin-bottom: 32px;
        }
        .hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }
        .btn-primary-hero {
            background: #fff;
            color: var(--brand-dark) !important;
            padding: 14px 26px;
            border-radius: 9px;
            font-weight: 700;
            font-size: 15px;
        }
        .btn-outline-hero {
            border: 1.5px solid rgba(255,255,255,0.5);
            color: #fff !important;
            padding: 14px 26px;
            border-radius: 9px;
            font-weight: 700;
            font-size: 15px;
        }
        .btn-outline-hero:hover { background: rgba(255,255,255,0.1); }
 
        .stats-grid {
            flex: 1 1 380px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }
        .stat-card {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 14px;
            padding: 24px;
            backdrop-filter: blur(6px);
        }
        .stat-card .num { font-size: 32px; font-weight: 800; color: #fff; }
        .stat-card .label { font-size: 13.5px; color: #cdd5fa; margin-top: 4px; }
 
        /* ---- Features ---- */
        .features {
            max-width: 1180px;
            margin: -55px auto 90px;
            padding: 0 5%;
            position: relative;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 22px;
        }
        .feature-card {
            background: #fff;
            border-radius: 14px;
            padding: 28px 24px;
            box-shadow: 0 10px 30px rgba(31,59,179,0.08);
            border: 1px solid #eceef5;
        }
        .feature-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            background: #eaefff;
            color: var(--brand);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            margin-bottom: 16px;
        }
        .feature-card h3 { font-size: 16px; margin: 0 0 8px; }
        .feature-card p { font-size: 13.5px; color: #6a7086; line-height: 1.6; margin: 0; }
 
        footer {
            text-align: center;
            padding: 26px 5%;
            color: #9298ab;
            font-size: 13px;
        }
    </style>
</head>
<body>
 
    <nav class="nav">
        <div class="brand">
            <div class="brand-badge">{{ Str::of(config('app.name', 'SMS'))->explode(' ')->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('') }}</div>
            {{ config('app.name', 'School Management System') }}
        </div>
        <div class="nav-links" style="display:flex; align-items:center;">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                    <a href="{{ route('login') }}" class="nav-btn">Go to Portal</a>
                @else
                    <a href="{{ route('login') }}" class="nav-btn">Portal Login</a>
                @endauth
            @endif
        </div>
    </nav>
 
    <section class="hero">
        <div class="hero-inner">
            <div class="hero-text">
                <span class="badge-pill">STUDENT INFORMATION SYSTEM</span>
                <h1>Manage your school <span>smarter</span>, not harder.</h1>
                <p>One platform for admissions, attendance, fees, results and communication — built to handle everything from a single classroom to thousands of students, reliably and fast.</p>
                <div class="hero-actions">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="btn-primary-hero">Portal Login →</a>
                    @endif
                    <a href="#features" class="btn-outline-hero">Explore Features</a>
                </div>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="num">{{ number_format($students ?? 0) }}+</div>
                    <div class="label">Enrolled Students</div>
                </div>
                <div class="stat-card">
                    <div class="num">{{ number_format($teachers ?? 0) }}+</div>
                    <div class="label">Faculty Members</div>
                </div>
                <div class="stat-card">
                    <div class="num">{{ number_format($classes ?? 0) }}</div>
                    <div class="label">Active Classes</div>
                </div>
                <div class="stat-card">
                    <div class="num">99.9%</div>
                    <div class="label">Uptime & Reliability</div>
                </div>
            </div>
        </div>
    </section>
 
    <section class="features" id="features">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🎓</div>
                <h3>Student Records</h3>
                <p>Complete profiles, enrollment history and academic results in one place.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📅</div>
                <h3>Attendance Tracking</h3>
                <p>Daily attendance with trend charts, so nothing slips through the cracks.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💳</div>
                <h3>Fee Management</h3>
                <p>Track collections, dues, and generate reports without spreadsheets.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Insightful Dashboard</h3>
                <p>Real-time stats for admins — attendance, fees, results, at a glance.</p>
            </div>
        </div>
    </section>
 
    <footer>
        &copy; {{ date('Y') }} {{ config('app.name', 'School Management System') }}. All rights reserved.
    </footer>
 
</body>
</html>