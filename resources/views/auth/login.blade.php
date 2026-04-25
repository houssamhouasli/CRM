<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Lesaffre Maroc CRM</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background: linear-gradient(-45deg, #0a3b8f, #072763, #041230, #1646a3);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Formes animées d'arrière-plan (Mouvement fluide) */
        .glass-orb {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: float 12s infinite ease-in-out;
            z-index: 0;
        }
        .orb-1 { width: 300px; height: 300px; top: -10%; left: -5%; animation-duration: 18s; }
        .orb-2 { width: 450px; height: 450px; bottom: -15%; right: -10%; animation-duration: 22s; animation-delay: -5s; background: rgba(232, 132, 26, 0.02); border-color: rgba(232, 132, 26, 0.1); }
        .orb-3 { width: 150px; height: 150px; top: 40%; left: 80%; animation-duration: 14s; }
        .orb-4 { width: 200px; height: 200px; top: 70%; left: 15%; animation-duration: 16s; animation-delay: -2s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-40px) scale(1.05); }
        }

        /* Card d'authentification */
        .login-card {
            width: 100%;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 32px;
            padding: 3.5rem 3rem;
            position: relative;
            z-index: 10;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.25), 0 0 40px rgba(10, 59, 143, 0.3);
            
            /* Animation d'entrée */
            opacity: 0;
            transform: translateY(40px) scale(0.95);
            animation: slideUpFade 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideUpFade {
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        .login-brand {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-brand .icon {
            width: 72px;
            height: 72px;
            background: #ffffff;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            box-shadow: 0 10px 30px rgba(10, 59, 143, 0.2);
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .login-card:hover .icon {
            transform: scale(1.1);
        }

        .login-brand h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #0e1e3a;
            margin-bottom: 0.25rem;
            letter-spacing: -0.5px;
        }

        .login-brand p {
            font-size: 0.95rem;
            color: #6b7a8d;
            font-weight: 500;
        }

        /* Animation en cascade pour les éléments du formulaire */
        .stagger-item {
            opacity: 0;
            transform: translateY(20px);
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.3s; }
        .delay-3 { animation-delay: 0.4s; }
        .delay-4 { animation-delay: 0.5s; }

        /* Champs du formulaire */
        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #4a5a6e;
            margin-bottom: 0.5rem;
        }

        .input-group-custom {
            display: flex;
            align-items: center;
            background: #f4f6fa;
            border: 2px solid #606264ff;
            border-radius: 14px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .input-group-custom:focus-within {
            background: #ffffff;
            border-color: #0a3b8f;
            box-shadow: 0 8px 20px rgba(10, 59, 143, 0.1);
            transform: translateY(-2px);
        }

        .input-group-text-custom {
            padding: 0.85rem 0.5rem 0.85rem 1.2rem;
            color: #6b7a8d;
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            transition: color 0.3s ease;
        }

        .input-group-custom:focus-within .input-group-text-custom {
            color: #0a3b8f;
        }

        .form-control {
            background: transparent !important;
            border: none !important;
            color: #1a1a2e;
            padding: 0.85rem 1.2rem 0.85rem 0.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            width: 100%;
            box-shadow: none !important;
            outline: none !important;
        }

        .form-control:focus {
            box-shadow: none !important;
        }

        .form-control::placeholder { color: #aab3c0; font-weight: 400; }

        /* Bouton de connexion Premium */
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #0a3b8f, #041230);
            border: none;
            color: #fff;
            padding: 1rem;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.5px;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            z-index: 1;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, #205abe, #0a3b8f);
            opacity: 0;
            z-index: -1;
            transition: opacity 0.4s ease;
        }

        .btn-login:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px rgba(10, 59, 143, 0.4);
            color: #fff;
        }

        .btn-login:hover::before { opacity: 1; }

        .btn-login i {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .btn-login:hover i {
            transform: translateX(4px);
        }

        /* Checkbox & Helpers */
        .auth-helpers {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #0a3b8f;
            border-color: #0a3b8f;
        }

        .text-forgot {
            color: #0a3b8f;
            text-decoration: none;
            transition: color 0.2s;
        }

        .text-forgot:hover {
            color: #e8841a;
            text-decoration: underline;
        }

        /* Erreurs */
        .alert-danger {
            background: rgba(231, 76, 60, 0.1);
            border: 1px solid rgba(231, 76, 60, 0.2);
            color: #c0392b;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 1rem;
        }
    </style>
</head>
<body>

    <!-- Orbes en verre flottantes -->
    <div class="glass-orb orb-1"></div>
    <div class="glass-orb orb-2"></div>
    <div class="glass-orb orb-3"></div>
    <div class="glass-orb orb-4"></div>

    <div class="login-card">
        <div class="login-brand stagger-item">
            <div class="icon">
                <img src="{{ asset('favicon.png') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain; padding: 10px;">
            </div>
            <h1>Lesaffre Maroc</h1>
            <p>Portail de Gestion & CRM</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger mb-4 stagger-item delay-1">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            @foreach($errors->all() as $error)
            <span>{{ $error }}</span><br>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-4 stagger-item delay-2">
                <label class="form-label">Identifiant (Email)</label>
                <div class="input-group-custom" >
                    <span class="input-group-text-custom"><i class="bi bi-envelope-fill"></i></span>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="vous@lesaffre.ma">
                </div>
            </div>

            <div class="mb-4 stagger-item delay-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label mb-0">Mot de passe</label>
                </div>
                <div class="input-group-custom" >
                    <span class="input-group-text-custom"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>
            </div>

            <div class="mb-4 auth-helpers stagger-item delay-3"></div>

            <button type="submit" class="btn btn-login stagger-item delay-4">
                Se connecter <i class="bi bi-arrow-right-short fs-4"></i>
            </button>
            
        </form>
    </div>


</body>
</html>
