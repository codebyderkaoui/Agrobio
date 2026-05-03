<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AgroBio — Accès refusé</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{--g400:#639922;--g500:#4E7C18;--g600:#3B6D11;--g50:#EAF3DE;--g100:#C8DF9B;--g800:#27500A;--g900:#173404;--gr400:#888780;--gr50:#F4F2EC;--border:#E5E2DA;--text:#2C2C2A;--bg:#F5F3EE;--c400:#D85A30;--c50:#FAECE7;--c100:#F0997B}
body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;
background-image:radial-gradient(ellipse 60% 40% at 50% 0%,rgba(216,90,48,.04) 0%,transparent 60%)}
.container{text-align:center;max-width:480px}
.error-code{font-family:'Playfair Display',serif;font-size:120px;font-weight:700;color:var(--c50);line-height:1;margin-bottom:.5rem;letter-spacing:-4px}
.error-emoji{font-size:48px;margin-bottom:1.25rem;display:block}
.error-title{font-family:'Playfair Display',serif;font-size:28px;font-weight:700;color:var(--g900);margin-bottom:.75rem}
.error-sub{font-size:14px;color:var(--gr400);line-height:1.6;margin-bottom:2rem}
.btn-home{
    display:inline-flex;align-items:center;gap:8px;
    background:var(--g400);color:#fff;
    padding:11px 24px;border-radius:10px;
    text-decoration:none;font-size:14px;font-weight:500;
    transition:all .18s;
    box-shadow:0 2px 8px rgba(63,109,17,.3);
}
.btn-home:hover{background:var(--g500);transform:translateY(-1px);box-shadow:0 4px 14px rgba(63,109,17,.4)}
.btn-back{
    display:inline-flex;align-items:center;gap:8px;
    background:var(--gr50);color:var(--gr400);
    padding:11px 24px;border-radius:10px;
    text-decoration:none;font-size:14px;font-weight:500;
    border:1.5px solid var(--border);margin-left:8px;
    transition:all .18s;
}
.btn-back:hover{background:var(--border);color:var(--text)}
.logo{display:flex;align-items:center;gap:10px;justify-content:center;margin-bottom:2.5rem}
.logo-box{width:40px;height:40px;background:linear-gradient(135deg,var(--g400),var(--g600));border-radius:11px;display:flex;align-items:center;justify-content:center}
.logo-box svg{width:22px;height:22px;fill:#fff}
.brand{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:var(--g800)}
.badge{
    display:inline-block;
    background:var(--c50);color:var(--c400);
    font-size:12px;font-weight:600;
    padding:4px 12px;border-radius:6px;
    border:1px solid var(--c100);
    margin-bottom:1.25rem;
}
</style>
</head>
<body>
<div class="container">
    <div class="logo">
        <div class="logo-box">
            <svg viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21c1.13-.15 2.24-.39 3.18-.81C10 18.5 12 15 17 8z"/><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.38 5.07C4.9 13.8 7.7 9.5 12 8c4.3-1.5 8 1 8 5 0 2.76-2.24 5-5 5-1.38 0-2.63-.56-3.53-1.47C9.63 18.37 8 20 6 21.12A9.956 9.956 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
        </div>
        <span class="brand">AgroBio</span>
    </div>

    <div class="error-code">403</div>
    <div class="badge">Accès refusé</div>
    <span class="error-emoji">🔒</span>
    <div class="error-title">Vous n'avez pas accès</div>
    <div class="error-sub">
        Vous n'avez pas les permissions nécessaires pour accéder à cette page.<br>
        Contactez un administrateur si vous pensez que c'est une erreur.
    </div>
    <div>
        <a href="/" class="btn-home">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Tableau de bord
        </a>
        <a href="javascript:history.back()" class="btn-back">← Retour</a>
    </div>
</div>
</body>
</html>
