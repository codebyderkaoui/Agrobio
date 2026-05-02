<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AgroBio — Créer un compte</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{--g400:#639922;--g500:#4E7C18;--g600:#3B6D11;--g50:#EAF3DE;--g100:#C8DF9B;--g800:#27500A;--g900:#173404;--gr400:#888780;--border:#E5E2DA;--c400:#D85A30;--c50:#FAECE7;--text:#2C2C2A;--shadow-lg:0 8px 32px rgba(0,0,0,.11)}
body{font-family:'DM Sans',sans-serif;background:linear-gradient(135deg,#EAF3DE 0%,#F5F3EE 50%,#FAEEDA 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;color:var(--text);padding:1rem}
.card{background:#fff;border-radius:20px;padding:2.5rem;width:100%;max-width:420px;box-shadow:var(--shadow-lg);border:1px solid var(--border)}
.login-logo{display:flex;align-items:center;gap:12px;margin-bottom:2rem;justify-content:center}
.logo-box{width:44px;height:44px;background:linear-gradient(135deg,var(--g400),var(--g600));border-radius:12px;display:flex;align-items:center;justify-content:center}
.logo-box svg{width:24px;height:24px;fill:#fff}
.brand-name{font-family:'Playfair Display',serif;font-size:22px;font-weight:700;color:var(--g800)}
.brand-sub{font-size:10px;color:var(--gr400);letter-spacing:1.6px;text-transform:uppercase;display:block}
h1{font-family:'Playfair Display',serif;font-size:22px;font-weight:700;color:var(--g900);margin-bottom:.4rem;text-align:center}
.subtitle{font-size:13px;color:var(--gr400);text-align:center;margin-bottom:2rem}
.form-group{margin-bottom:1rem}
.form-label{display:block;font-size:12px;font-weight:600;color:var(--gr400);margin-bottom:5px;letter-spacing:.3px;text-transform:uppercase}
.form-input{width:100%;border:1.5px solid var(--border);border-radius:10px;padding:11px 14px;font-size:14px;font-family:'DM Sans',sans-serif;color:var(--text);outline:none;transition:all .18s;background:#fafaf8}
.form-input:focus{background:#fff;border-color:var(--g400);box-shadow:0 0 0 3px rgba(99,153,34,.12)}
.form-input::placeholder{color:#bbb}
.error-msg{background:var(--c50);color:var(--c400);border-radius:9px;padding:10px 14px;font-size:13px;margin-bottom:1rem;border:1px solid #f0997b}
.input-error{font-size:11px;color:var(--c400);margin-top:4px}
.submit-btn{width:100%;padding:13px;font-size:14px;font-weight:600;background:linear-gradient(135deg,var(--g400),var(--g500));color:#fff;border:none;border-radius:10px;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .18s;box-shadow:0 2px 8px rgba(63,109,17,.3);margin-top:.5rem}
.submit-btn:hover{transform:translateY(-1px);box-shadow:0 4px 14px rgba(63,109,17,.4)}
.login-link{text-align:center;margin-top:1.25rem;font-size:13px;color:var(--gr400)}
.login-link a{color:var(--g500);font-weight:500;text-decoration:none}
.login-link a:hover{text-decoration:underline}
</style>
</head>
<body>
<div class="card">
  <div class="login-logo">
    <div class="logo-box">
      <svg viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21c1.13-.15 2.24-.39 3.18-.81C10 18.5 12 15 17 8z"/><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.38 5.07C4.9 13.8 7.7 9.5 12 8c4.3-1.5 8 1 8 5 0 2.76-2.24 5-5 5-1.38 0-2.63-.56-3.53-1.47C9.63 18.37 8 20 6 21.12A9.956 9.956 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
    </div>
    <div><span class="brand-name">AgroBio</span><span class="brand-sub">Fermes &amp; Marchés</span></div>
  </div>

  <h1>Créer un compte</h1>
  <p class="subtitle">Rejoignez la plateforme AgroBio</p>

  @if($errors->any())
    <div class="error-msg">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="form-group">
      <label class="form-label" for="name">Nom complet</label>
      <input class="form-input" type="text" id="name" name="name"
             value="{{ old('name') }}"
             placeholder="Ahmed Benali" required autofocus>
      @error('name')<div class="input-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
      <label class="form-label" for="email">Adresse e-mail</label>
      <input class="form-input" type="email" id="email" name="email"
             value="{{ old('email') }}"
             placeholder="ahmed@agrobio.ma" required>
      @error('email')<div class="input-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
      <label class="form-label" for="password">Mot de passe</label>
      <input class="form-input" type="password" id="password" name="password"
             placeholder="8 caractères minimum" required>
      @error('password')<div class="input-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
      <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
      <input class="form-input" type="password" id="password_confirmation"
             name="password_confirmation" placeholder="••••••••" required>
    </div>

    <button type="submit" class="submit-btn">Créer mon compte →</button>
  </form>

  <p class="login-link">
    Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
  </p>
</div>
</body>
</html>
