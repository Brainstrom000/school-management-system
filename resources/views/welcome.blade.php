<!DOCTYPE html>
@php
    // Hardcoded on purpose — doesn't depend on the APP_NAME env var being
    // set correctly on the server, so it can never fall back to "Laravel".
    $siteName = 'School Management System';
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteName }}</title>
 
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
 
        /* ---- Contact ---- */
        .contact {
            background: #fff;
            padding: 80px 5%;
            border-top: 1px solid #eceef5;
        }
        .contact-inner { max-width: 1100px; margin: 0 auto; }
        .contact-head { text-align: center; max-width: 560px; margin: 0 auto 44px; }
        .contact-head h2 { font-size: 30px; font-weight: 800; margin: 0 0 12px; color: #1b1e2b; }
        .contact-head p { color: #6a7086; font-size: 15px; line-height: 1.7; margin: 0; }
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }
        .contact-card {
            text-align: center;
            background: #f6f7fb;
            border: 1px solid #eceef5;
            border-radius: 14px;
            padding: 30px 22px;
        }
        .contact-card .feature-icon { margin: 0 auto 16px; }
        .contact-card h3 { font-size: 15.5px; margin: 0 0 8px; }
        .contact-card p { font-size: 14px; color: #6a7086; margin: 0; }
        .contact-card a { color: var(--brand); font-weight: 600; }
 
        /* ---- Footer ---- */
        footer {
            background: #171a2b;
            padding: 34px 5% 20px;
        }
        .footer-inner {
            max-width: 1180px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .footer-brand { color: #fff; font-weight: 800; font-size: 16px; }
        .footer-links { display: flex; gap: 26px; }
        .footer-links a { color: #b7bccf; font-size: 13.5px; font-weight: 500; }
        .footer-links a:hover { color: #fff; }
        .footer-copy {
            max-width: 1180px;
            margin: 18px auto 0;
            text-align: center;
            color: #6c7188;
            font-size: 12.5px;
        }
    </style>
</head>
<body>
 
    <nav class="nav">
        <div class="brand">
            <div class="brand-badge">{{ Str::of($siteName)->explode(' ')->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('') }}</div>
            {{ $siteName }}
        </div>
        <div class="nav-links" style="display:flex; align-items:center;">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-btn">Go to Dashboard</a>
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
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary-hero">Go to Dashboard →</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary-hero">Portal Login →</a>
                        @endauth
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
 
    <section class="contact" id="contact">
        <div class="contact-inner">
            <div class="contact-head">
                <h2>Get in touch</h2>
                <p>Questions about admissions, records, or accessing the portal? Reach out and our team will get back to you.</p>
            </div>
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="feature-icon">📍</div>
                    <h3>Visit Us</h3>
                    <p>123 School Road, Lahore, Pakistan</p>
                </div>
                <div class="contact-card">
                    <div class="feature-icon">✉️</div>
                    <h3>Email Us</h3>
                    <p><a href="mailto:info@schoolsystem.com">info@schoolsystem.com</a></p>
                </div>
                <div class="contact-card">
                    <div class="feature-icon">📞</div>
                    <h3>Call Us</h3>
                    <p><a href="tel:+920000000000">+92 300 0000000</a></p>
                </div>
            </div>
        </div>
    </section>
 
    <footer>
        <div class="footer-inner">
            <div class="footer-brand">{{ $siteName }}</div>
            <div class="footer-links">
                <a href="#features">Features</a>
                <a href="#contact">Contact</a>
                @if (Route::has('login'))
                    <a href="{{ route('login') }}">Portal Login</a>
                @endif
            </div>
        </div>
        <div class="footer-copy">&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</div>
    </footer>
 
</body>
</html>