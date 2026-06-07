<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Lesaffre Maroc CRM — Système de gestion de stock et relation client pour la distribution de produits.">

    <title>Lesaffre Maroc CRM — Gestion de Stock Intelligente</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════
           DESIGN TOKENS
        ═══════════════════════════════════════════ */
        :root {
            --primary: #0a3b8f;
            --primary-dark: #072763;
            --primary-deeper: #0e1e3a;
            --primary-light: #205abe;
            --accent: #E8841A;
            --accent-light: #f5a623;
            --accent-glow: rgba(232, 132, 26, 0.35);
            --text-white: #f0f2f5;
            --text-muted: #8a99b0;
            --glass-bg: rgba(255, 255, 255, 0.06);
            --glass-border: rgba(255, 255, 255, 0.1);
            --glass-hover: rgba(255, 255, 255, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #060d1a;
            color: var(--text-white);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ═══════════════════════════════════════════
           NAVBAR
        ═══════════════════════════════════════════ */
        .navbar-welcome {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar-welcome.scrolled {
            background: rgba(6, 13, 26, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            padding: 0.75rem 2rem;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: #fff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 900;
            color: var(--primary);
            box-shadow: 0 4px 20px var(--accent-glow);
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover .brand-icon {
            transform: scale(1.08) rotate(-3deg);
        }

        .brand-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.15;
        }

        .brand-sub {
            display: block;
            font-size: 0.65rem;
            font-weight: 400;
            color: var(--accent-light);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-login {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.5rem;
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            color: #fff;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-login:hover {
            border-color: var(--accent);
            color: var(--accent-light);
            background: rgba(232, 132, 26, 0.08);
        }

        .btn-register {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 10px;
            color: #fff;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            background: linear-gradient(135deg, var(--accent), #d4740f);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px var(--accent-glow);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px var(--accent-glow);
            color: #fff;
        }

        /* ═══════════════════════════════════════════
           HERO SECTION
        ═══════════════════════════════════════════ */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 7rem 2rem 4rem;
            overflow: hidden;
        }

        /* Animated gradient background */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 40%, rgba(10, 59, 143, 0.4) 0%, transparent 70%),
                radial-gradient(ellipse 60% 50% at 80% 30%, rgba(232, 132, 26, 0.15) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 50% 90%, rgba(10, 59, 143, 0.2) 0%, transparent 50%);
            animation: heroGradient 12s ease-in-out infinite alternate;
        }

        @keyframes heroGradient {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.05); }
            100% { opacity: 1; transform: scale(1); }
        }

        /* Grid pattern overlay */
        .hero-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse 70% 60% at center, black, transparent);
            -webkit-mask-image: radial-gradient(ellipse 70% 60% at center, black, transparent);
        }

        /* Floating particles */
        .particles {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: var(--accent-light);
            opacity: 0;
            animation: particleFloat linear infinite;
        }

        @keyframes particleFloat {
            0% {
                opacity: 0;
                transform: translateY(100vh) scale(0);
            }
            10% {
                opacity: 0.6;
            }
            90% {
                opacity: 0.6;
            }
            100% {
                opacity: 0;
                transform: translateY(-10vh) scale(1);
            }
        }

        /* Orbital rings */
        .orbital {
            position: absolute;
            border: 1px solid rgba(232, 132, 26, 0.08);
            border-radius: 50%;
            animation: orbitalSpin linear infinite;
        }

        .orbital:nth-child(1) {
            width: 500px; height: 500px;
            top: 10%; right: -10%;
            animation-duration: 30s;
        }

        .orbital:nth-child(2) {
            width: 350px; height: 350px;
            top: 20%; right: -5%;
            animation-duration: 20s;
            animation-direction: reverse;
        }

        .orbital:nth-child(3) {
            width: 700px; height: 700px;
            bottom: -20%; left: -15%;
            animation-duration: 40s;
            border-color: rgba(10, 59, 143, 0.1);
        }

        @keyframes orbitalSpin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-text {
            max-width: 580px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1rem;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--accent-light);
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .hero-badge .dot {
            width: 6px;
            height: 6px;
            background: var(--accent);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.5); }
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 3.8rem);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -0.03em;
        }

        .hero h1 .gradient-text {
            background: linear-gradient(135deg, var(--accent-light), var(--accent), #ff9a3c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-description {
            font-size: 1.1rem;
            line-height: 1.7;
            color: var(--text-muted);
            margin-bottom: 2.5rem;
            max-width: 480px;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.9rem 2rem;
            background: linear-gradient(135deg, var(--accent), #d4740f);
            color: #fff;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 700;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 25px var(--accent-glow);
            position: relative;
            overflow: hidden;
        }

        .btn-hero-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 40px var(--accent-glow);
            color: #fff;
        }

        .btn-hero-primary:hover::before {
            transform: translateX(100%);
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.9rem 2rem;
            background: var(--glass-bg);
            color: #fff;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 14px;
            border: 1px solid var(--glass-border);
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .btn-hero-secondary:hover {
            background: var(--glass-hover);
            border-color: rgba(255,255,255,0.2);
            transform: translateY(-3px);
            color: #fff;
        }

        /* Hero visual — Dashboard mockup */
        .hero-visual {
            position: relative;
        }

        .dashboard-mockup {
            background: rgba(14, 30, 58, 0.7);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.5rem;
            backdrop-filter: blur(20px);
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255,255,255,0.05);
            transform: perspective(1000px) rotateY(-5deg) rotateX(3deg);
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            animation: mockupFloat 6s ease-in-out infinite;
        }

        .dashboard-mockup:hover {
            transform: perspective(1000px) rotateY(0deg) rotateX(0deg);
        }

        @keyframes mockupFloat {
            0%, 100% { transform: perspective(1000px) rotateY(-5deg) rotateX(3deg) translateY(0); }
            50% { transform: perspective(1000px) rotateY(-5deg) rotateX(3deg) translateY(-12px); }
        }

        .mockup-topbar {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .mockup-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .mockup-dot.red { background: #ff5f56; }
        .mockup-dot.yellow { background: #ffbd2e; }
        .mockup-dot.green { background: #27c93f; }

        .mockup-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .mockup-stat {
            background: var(--glass-bg);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
        }

        .mockup-stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--accent-light);
        }

        .mockup-stat-label {
            font-size: 0.65rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .mockup-chart {
            background: var(--glass-bg);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 1rem;
            height: 140px;
            display: flex;
            align-items: flex-end;
            gap: 6px;
        }

        .chart-bar {
            flex: 1;
            border-radius: 4px 4px 0 0;
            transition: height 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glow-accent {
            position: absolute;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, var(--accent-glow), transparent 70%);
            border-radius: 50%;
            top: -50px;
            right: -50px;
            pointer-events: none;
            animation: accentPulse 4s ease-in-out infinite;
        }

        @keyframes accentPulse {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.2); }
        }

        /* ═══════════════════════════════════════════
           FEATURES SECTION
        ═══════════════════════════════════════════ */
        .section {
            padding: 6rem 2rem;
            position: relative;
        }

        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 4rem;
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.9rem;
            background: rgba(232, 132, 26, 0.1);
            border: 1px solid rgba(232, 132, 26, 0.2);
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--accent-light);
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .section-subtitle {
            font-size: 1.05rem;
            color: var(--text-muted);
            line-height: 1.7;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: rgba(14, 30, 58, 0.5);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            cursor: default;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .feature-card:hover {
            border-color: rgba(232, 132, 26, 0.3);
            transform: translateY(-6px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1.25rem;
            position: relative;
        }

        .feature-icon.stock {
            background: linear-gradient(135deg, rgba(10, 59, 143, 0.3), rgba(10, 59, 143, 0.1));
            color: #5da6ff;
        }
        .feature-icon.orders {
            background: linear-gradient(135deg, rgba(232, 132, 26, 0.3), rgba(232, 132, 26, 0.1));
            color: var(--accent-light);
        }
        .feature-icon.delivery {
            background: linear-gradient(135deg, rgba(39, 174, 96, 0.3), rgba(39, 174, 96, 0.1));
            color: #2ecc71;
        }
        .feature-icon.analytics {
            background: linear-gradient(135deg, rgba(142, 68, 173, 0.3), rgba(142, 68, 173, 0.1));
            color: #bb8fce;
        }
        .feature-icon.clients {
            background: linear-gradient(135deg, rgba(41, 128, 185, 0.3), rgba(41, 128, 185, 0.1));
            color: #5dade2;
        }
        .feature-icon.security {
            background: linear-gradient(135deg, rgba(200, 16, 46, 0.3), rgba(200, 16, 46, 0.1));
            color: #ff6b6b;
        }

        .feature-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.6rem;
        }

        .feature-desc {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.7;
        }

        /* ═══════════════════════════════════════════
           ROLES SECTION
        ═══════════════════════════════════════════ */
        .roles-section {
            background: linear-gradient(180deg, transparent, rgba(10, 59, 143, 0.08), transparent);
        }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .role-card {
            background: rgba(14, 30, 58, 0.6);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .role-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            border-radius: 3px 3px 0 0;
            transition: width 0.4s ease;
        }

        .role-card:hover::after {
            width: 60%;
        }

        .role-card.admin::after { background: linear-gradient(90deg, #ff6b6b, #ee5a24); }
        .role-card.commercial::after { background: linear-gradient(90deg, #5dade2, #3498db); }
        .role-card.depositaire::after { background: linear-gradient(90deg, #2ecc71, #27ae60); }
        .role-card.livreur::after { background: linear-gradient(90deg, var(--accent-light), var(--accent)); }

        .role-card:hover {
            transform: translateY(-8px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }

        .role-avatar {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin: 0 auto 1.25rem;
            position: relative;
        }

        .role-card.admin .role-avatar {
            background: linear-gradient(135deg, rgba(200, 16, 46, 0.25), rgba(200, 16, 46, 0.08));
            color: #ff6b6b;
        }
        .role-card.commercial .role-avatar {
            background: linear-gradient(135deg, rgba(41, 128, 185, 0.25), rgba(41, 128, 185, 0.08));
            color: #5dade2;
        }
        .role-card.depositaire .role-avatar {
            background: linear-gradient(135deg, rgba(39, 174, 96, 0.25), rgba(39, 174, 96, 0.08));
            color: #2ecc71;
        }
        .role-card.livreur .role-avatar {
            background: linear-gradient(135deg, rgba(232, 132, 26, 0.25), rgba(232, 132, 26, 0.08));
            color: var(--accent-light);
        }

        .role-name {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .role-desc {
            font-size: 0.8rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 1.25rem;
        }

        .role-features {
            list-style: none;
            text-align: left;
        }

        .role-features li {
            font-size: 0.8rem;
            color: var(--text-muted);
            padding: 0.35rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .role-features li i {
            color: var(--accent-light);
            font-size: 0.7rem;
        }

        /* ═══════════════════════════════════════════
           STATS COUNTER SECTION
        ═══════════════════════════════════════════ */
        .stats-section {
            padding: 5rem 2rem;
            position: relative;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(10, 59, 143, 0.15), rgba(232, 132, 26, 0.08));
            border-top: 1px solid var(--glass-border);
            border-bottom: 1px solid var(--glass-border);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            max-width: 1000px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            font-weight: 900;
            background: linear-gradient(135deg, #fff, var(--accent-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ═══════════════════════════════════════════
           CTA SECTION
        ═══════════════════════════════════════════ */
        .cta-section {
            padding: 6rem 2rem;
        }

        .cta-box {
            max-width: 800px;
            margin: 0 auto;
            background: linear-gradient(135deg, rgba(10, 59, 143, 0.4), rgba(14, 30, 58, 0.8));
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 4rem 3rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-box::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 40%, var(--accent-glow), transparent 40%);
            animation: ctaGlow 8s ease-in-out infinite alternate;
            pointer-events: none;
        }

        @keyframes ctaGlow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(30deg); }
        }

        .cta-title {
            font-size: clamp(1.8rem, 3.5vw, 2.5rem);
            font-weight: 800;
            margin-bottom: 1rem;
            position: relative;
            z-index: 2;
        }

        .cta-subtitle {
            font-size: 1.05rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
            position: relative;
            z-index: 2;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            position: relative;
            z-index: 2;
            flex-wrap: wrap;
        }

        /* ═══════════════════════════════════════════
           FOOTER
        ═══════════════════════════════════════════ */
        .footer {
            padding: 3rem 2rem 1.5rem;
            border-top: 1px solid var(--glass-border);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .footer-brand-icon {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.08);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--accent-light);
        }

        .footer-brand-text {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .footer-copy {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-links a {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: var(--accent-light);
        }

        /* ═══════════════════════════════════════════
           SCROLL REVEAL ANIMATIONS
        ═══════════════════════════════════════════ */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }
        .reveal-delay-5 { transition-delay: 0.5s; }
        .reveal-delay-6 { transition-delay: 0.6s; }

        /* ═══════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════ */
        @media (max-width: 992px) {
            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-text {
                max-width: 100%;
            }

            .hero-description {
                max-width: 100%;
            }

            .hero-cta {
                justify-content: center;
            }

            .hero-visual {
                max-width: 500px;
                margin: 0 auto;
            }

            .dashboard-mockup {
                transform: perspective(1000px) rotateY(0deg) rotateX(3deg);
            }

            @keyframes mockupFloat {
                0%, 100% { transform: perspective(1000px) rotateY(0deg) rotateX(3deg) translateY(0); }
                50% { transform: perspective(1000px) rotateY(0deg) rotateX(3deg) translateY(-8px); }
            }
        }

        @media (max-width: 768px) {
            .navbar-welcome {
                padding: 0.75rem 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }

            .cta-box {
                padding: 3rem 1.5rem;
            }

            .mockup-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .mockup-stats .mockup-stat:last-child {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 480px) {
            .hero {
                padding: 6rem 1rem 3rem;
            }

            .section {
                padding: 4rem 1rem;
            }

            .navbar-actions .btn-login span {
                display: none;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <!-- ═══════════════════════════════════════════
         NAVBAR
    ═══════════════════════════════════════════ -->
    <nav class="navbar-welcome" id="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">
            <img src="{{ asset('favicon.png') }}" alt="Logo" class="brand-icon">
            <div>
                <span class="brand-text">Lesaffre Maroc</span>
                <span class="brand-sub">CRM Stock</span>
            </div>
        </a>
        <div class="navbar-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-register">
                    <i class="bi bi-grid-1x2-fill"></i>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-login">
                    <i class="bi bi-person"></i>
                    <span>Se connecter</span>
                </a>
            @endauth
        </div>
    </nav>

    <!-- ═══════════════════════════════════════════
         HERO
    ═══════════════════════════════════════════ -->
    <section class="hero" id="hero">
        <div class="hero-grid"></div>

        <!-- Orbital rings -->
        <div class="orbital"></div>
        <div class="orbital"></div>
        <div class="orbital"></div>
 
        <!-- Particles -->
        <div class="particles" id="particles"></div>

        <div class="hero-content">
            <div class="hero-text">
                <div class="hero-badge reveal">
                    <span class="dot"></span>
                    Plateforme CRM 
                </div>

                <h1 class="reveal reveal-delay-1">
                    Gérez votre stock<br>
                    <span class="gradient-text">avec intelligence</span>
                </h1>

                <p class="hero-description reveal reveal-delay-2">
                    Solution complète de gestion de stock multi-dépôts, commandes clients et livraisons.
                    Optimisez votre chaîne logistique en temps réel.
                </p>

                <div class="hero-cta reveal reveal-delay-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-hero-primary">
                            <i class="bi bi-arrow-right-circle"></i>
                            Accéder au Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-hero-primary">
                            <i class="bi bi-arrow-right-circle"></i>
                            Commencer maintenant
                        </a>
                    @endauth
                    <a href="#features" class="btn-hero-secondary">
                        <i class="bi bi-play-circle"></i>
                        Découvrir
                    </a>
                </div>
            </div>

            <div class="hero-visual reveal reveal-delay-4">
                <div class="glow-accent"></div>
                <div class="dashboard-mockup">
                    <div class="mockup-topbar">
                        <span class="mockup-dot red"></span>
                        <span class="mockup-dot yellow"></span>
                        <span class="mockup-dot green"></span>
                    </div>
                    <div class="mockup-stats">
                        <div class="mockup-stat">
                            <div class="mockup-stat-value" data-target="1247">0</div>
                            <div class="mockup-stat-label">Commandes</div>
                        </div>
                        <div class="mockup-stat">
                            <div class="mockup-stat-value" data-target="89">0</div>
                            <div class="mockup-stat-label">Clients</div>
                        </div>
                        <div class="mockup-stat">
                            <div class="mockup-stat-value" data-target="342">0</div>
                            <div class="mockup-stat-label">Produits</div>
                        </div>
                    </div>
                    <div class="mockup-chart" id="mockupChart"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
         FEATURES
    ═══════════════════════════════════════════ -->
    <section class="section" id="features">
        <div class="section-header">
            <div class="section-tag reveal">
                <i class="bi bi-stars"></i>
                Fonctionnalités
            </div>
            <h2 class="section-title reveal reveal-delay-1">
                Tout ce dont vous avez besoin
            </h2>
            <p class="section-subtitle reveal reveal-delay-2">
                Un écosystème complet pour piloter votre activité de distribution de A à Z.
            </p>
        </div>

        <div class="features-grid">
            <div class="feature-card reveal">
                <div class="feature-icon stock">
                    <i class="bi bi-boxes"></i>
                </div>
                <div class="feature-title">Gestion de Stock</div>
                <div class="feature-desc">
                    Pilotez vos stocks en temps réel sur plusieurs dépôts et camions. Traçabilité complète des mouvements.
                </div>
            </div>

            <div class="feature-card reveal reveal-delay-1">
                <div class="feature-icon orders">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div class="feature-title">Commandes & Facturation</div>
                <div class="feature-desc">
                    Créez et suivez les commandes avec calcul automatique HT/TVA/TTC et gestion des promotions.
                </div>
            </div>

            <div class="feature-card reveal reveal-delay-2">
                <div class="feature-icon delivery">
                    <i class="bi bi-truck"></i>
                </div>
                <div class="feature-title">Livraisons</div>
                <div class="feature-desc">
                    Gérez les livraisons complètes ou partielles. Suivi en temps réel et génération de bons de livraison.
                </div>
            </div>

            <div class="feature-card reveal reveal-delay-3">
                <div class="feature-icon analytics">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="feature-title">Tableaux de Bord</div>
                <div class="feature-desc">
                    Visualisez vos KPIs avec des dashboards interactifs et des rapports exportables.
                </div>
            </div>

            <div class="feature-card reveal reveal-delay-4">
                <div class="feature-icon clients">
                    <i class="bi bi-people"></i>
                </div>
                <div class="feature-title">Gestion Clients</div>
                <div class="feature-desc">
                    Base clients par région avec historique complet des commandes et des interactions.
                </div>
            </div>

            <div class="feature-card reveal reveal-delay-5">
                <div class="feature-icon security">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div class="feature-title">Sécurité & Rôles</div>
                <div class="feature-desc">
                    Contrôle d'accès granulaire par rôle : Admin, Commercial, Dépositaire et Livreur.
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
         ROLES
    ═══════════════════════════════════════════ -->
    <section class="section roles-section" id="roles">
        <div class="section-header">
            <div class="section-tag reveal">
                <i class="bi bi-person-gear"></i>
                Espaces dédiés
            </div>
            <h2 class="section-title reveal reveal-delay-1">
                Un espace pour chaque métier
            </h2>
            <p class="section-subtitle reveal reveal-delay-2">
                Chaque profil dispose d'une interface optimisée pour ses besoins spécifiques.
            </p>
        </div>

        <div class="roles-grid">
            <div class="role-card admin reveal">
                <div class="role-avatar">
                    <i class="bi bi-person-fill-gear"></i>
                </div>
                <div class="role-name">Administrateur</div>
                <div class="role-desc">Contrôle total du système et supervision globale</div>
                <ul class="role-features">
                    <li><i class="bi bi-check-circle-fill"></i> Gestion des utilisateurs</li>
                    <li><i class="bi bi-check-circle-fill"></i> Catalogue produits</li>
                    <li><i class="bi bi-check-circle-fill"></i> Rapports & exports</li>
                    <li><i class="bi bi-check-circle-fill"></i> Suivi global des commandes</li>
                </ul>
            </div>

            <div class="role-card commercial reveal reveal-delay-1">
                <div class="role-avatar">
                    <i class="bi bi-briefcase-fill"></i>
                </div>
                <div class="role-name">Commercial</div>
                <div class="role-desc">Gestion clientèle et suivi commercial par région</div>
                <ul class="role-features">
                    <li><i class="bi bi-check-circle-fill"></i> CRUD clients</li>
                    <li><i class="bi bi-check-circle-fill"></i> Création commandes vente</li>
                    <li><i class="bi bi-check-circle-fill"></i> Suivi livraisons</li>
                    <li><i class="bi bi-check-circle-fill"></i> Consultation catalogue</li>
                </ul>
            </div>

            <div class="role-card depositaire reveal reveal-delay-2">
                <div class="role-avatar">
                    <i class="bi bi-building"></i>
                </div>
                <div class="role-name">Dépositaire</div>
                <div class="role-desc">Gestion du dépôt et des réapprovisionnements</div>
                <ul class="role-features">
                    <li><i class="bi bi-check-circle-fill"></i> Stock dépôt en temps réel</li>
                    <li><i class="bi bi-check-circle-fill"></i> Réapprovisionnements</li>
                    <li><i class="bi bi-check-circle-fill"></i> Préparation livraisons</li>
                    <li><i class="bi bi-check-circle-fill"></i> Mouvements de stock</li>
                </ul>
            </div>

            <div class="role-card livreur reveal reveal-delay-3">
                <div class="role-avatar">
                    <i class="bi bi-truck"></i>
                </div>
                <div class="role-name">Livreur</div>
                <div class="role-desc">Interface mobile pour les chauffeurs-livreurs</div>
                <ul class="role-features">
                    <li><i class="bi bi-check-circle-fill"></i> Livraisons du jour</li>
                    <li><i class="bi bi-check-circle-fill"></i> Stock camion</li>
                    <li><i class="bi bi-check-circle-fill"></i> Bons de livraison</li>
                    <li><i class="bi bi-check-circle-fill"></i> Gestion des retours</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
         STATS
    ═══════════════════════════════════════════ -->
    <section class="stats-section" id="stats">
        <div class="stats-grid">
            <div class="stat-item reveal">
                <div class="stat-number" data-count="4">0</div>
                <div class="stat-label">Rôles Utilisateurs</div>
            </div>
            <div class="stat-item reveal reveal-delay-1">
                <div class="stat-number" data-count="12">0</div>
                <div class="stat-label">Modules Intégrés</div>
            </div>
            <div class="stat-item reveal reveal-delay-2">
                <div class="stat-number" data-count="100">0%</div>
                <div class="stat-label">Temps Réel</div>
            </div>
            <div class="stat-item reveal reveal-delay-3">
                <div class="stat-number" data-count="24">0/7</div>
                <div class="stat-label">Disponibilité</div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
         CTA
    ═══════════════════════════════════════════ -->
    <section class="cta-section">
        <div class="cta-box reveal">
            <h2 class="cta-title">
                Prêt à optimiser votre<br>
                <span style="background: linear-gradient(135deg, var(--accent-light), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">chaîne logistique ?</span>
            </h2>
            <p class="cta-subtitle">
                Connectez-vous pour accéder à votre espace dédié et commencer à gérer vos opérations.
            </p>
            <div class="cta-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-hero-primary">
                        <i class="bi bi-speedometer2"></i>
                        Mon Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-hero-primary">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Se connecter
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════ -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-brand">
                <img src="{{ asset('favicon.png') }}" alt="Logo Lesaffre Maroc" class="footer-logo">    
                <span class="footer-brand-text">Lesaffre Maroc</span>
            </div>
            <div class="footer-copy">
                &copy; {{ date('Y') }} Lesaffre Maroc CRM. Tous droits réservés.
            </div>
            <div class="footer-links">
                <a href="#features">Fonctionnalités</a>
                <a href="#roles">Rôles</a>
                @guest
                    <a href="{{ route('login') }}">Connexion</a>
                @endguest
            </div>
        </div>
    </footer>

    <!-- ═══════════════════════════════════════════
         SCRIPTS
    ═══════════════════════════════════════════ -->
    <script>
        // ── Navbar scroll effect ──
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });

        // ── Scroll Reveal ──
        const revealElements = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

        revealElements.forEach(el => revealObserver.observe(el));

        // ── Particle generator ──
        function createParticles() {
            const container = document.getElementById('particles');
            const count = window.innerWidth < 768 ? 12 : 25;

            for (let i = 0; i < count; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                particle.style.left = Math.random() * 100 + '%';
                particle.style.width = (Math.random() * 3 + 2) + 'px';
                particle.style.height = particle.style.width;
                particle.style.animationDuration = (Math.random() * 12 + 8) + 's';
                particle.style.animationDelay = (Math.random() * 10) + 's';
                particle.style.opacity = Math.random() * 0.4 + 0.1;

                const colors = ['var(--accent-light)', '#5da6ff', '#2ecc71', 'rgba(255,255,255,0.5)'];
                particle.style.background = colors[Math.floor(Math.random() * colors.length)];

                container.appendChild(particle);
            }
        }
        createParticles();

        // ── Mockup chart bars ──
        function createChart() {
            const chart = document.getElementById('mockupChart');
            const heights = [40, 65, 50, 80, 55, 90, 70, 45, 75, 85, 60, 95];
            const colors = [
                'linear-gradient(180deg, rgba(93, 166, 255, 0.8), rgba(93, 166, 255, 0.2))',
                'linear-gradient(180deg, rgba(232, 132, 26, 0.8), rgba(232, 132, 26, 0.2))',
            ];

            heights.forEach((h, i) => {
                const bar = document.createElement('div');
                bar.classList.add('chart-bar');
                bar.style.height = '0%';
                bar.style.background = colors[i % 2];
                chart.appendChild(bar);

                setTimeout(() => {
                    bar.style.height = h + '%';
                }, 800 + i * 100);
            });
        }
        createChart();

        // ── Counter animation ──
        function animateCounters() {
            // Mockup stat values
            document.querySelectorAll('.mockup-stat-value[data-target]').forEach(el => {
                const target = parseInt(el.dataset.target);
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;

                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    el.textContent = Math.floor(current).toLocaleString('fr-FR');
                }, 16);
            });

            // Section stat numbers
            const statNumbers = document.querySelectorAll('.stat-number[data-count]');
            const statsObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const target = parseInt(el.dataset.count);
                        const suffix = el.textContent.replace(/[0-9]/g, '');
                        const duration = 1500;
                        const step = target / (duration / 16);
                        let current = 0;

                        const timer = setInterval(() => {
                            current += step;
                            if (current >= target) {
                                current = target;
                                clearInterval(timer);
                            }
                            el.textContent = Math.floor(current) + suffix;
                        }, 16);

                        statsObserver.unobserve(el);
                    }
                });
            }, { threshold: 0.5 });

            statNumbers.forEach(el => statsObserver.observe(el));
        }
        animateCounters();

        // ── Smooth scroll for anchor links ──
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
