<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>AgroBio — @yield('title', 'Tableau de bord')</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
<style>

*{box-sizing:border-box;margin:0;padding:0}
:root{
  --g50:#EAF3DE;--g100:#C8DF9B;--g200:#97C459;--g300:#7DB53D;--g400:#639922;--g500:#4E7C18;--g600:#3B6D11;--g700:#2F5A0D;--g800:#27500A;--g900:#173404;
  --a50:#FAEEDA;--a100:#FAD499;--a200:#F5B84A;--a400:#BA7517;--a600:#854F0B;
  --t50:#E1F5EE;--t200:#5DCAA5;--t400:#1D9E75;--t600:#0F6E56;
  --gr50:#F4F2EC;--gr100:#E0DDD3;--gr200:#C8C5BB;--gr400:#888780;--gr600:#5F5E5A;--gr700:#48473F;
  --c50:#FAECE7;--c200:#F0997B;--c400:#D85A30;--c600:#993C1D;
  --bg:#F5F3EE;--white:#FFF;--text:#2C2C2A;--text-muted:#888780;
  --border:#E5E2DA;--border2:#D0CEC4;
  --shadow-sm:0 1px 4px rgba(0,0,0,.05);--shadow:0 2px 12px rgba(0,0,0,.07);--shadow-lg:0 8px 32px rgba(0,0,0,.11);
  --radius:10px;--radius-lg:14px;--sidebar-w:220px;--nav-h:60px;--transition:.18s ease;
}
html{scroll-behavior:smooth}
body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;font-size:14px;line-height:1.5;-webkit-font-smoothing:antialiased}
::-webkit-scrollbar{width:5px;height:5px}::-webkit-scrollbar-track{background:transparent}::-webkit-scrollbar-thumb{background:var(--gr200);border-radius:3px}::-webkit-scrollbar-thumb:hover{background:var(--gr400)}
.toast{position:fixed;bottom:1.5rem;left:50%;transform:translateX(-50%) translateY(80px);background:var(--g800);color:#fff;padding:11px 22px;border-radius:10px;font-size:13px;font-weight:500;z-index:600;transition:transform .3s cubic-bezier(.4,0,.2,1);pointer-events:none;box-shadow:var(--shadow-lg);white-space:nowrap;max-width:90vw}.toast.show{transform:translateX(-50%) translateY(0)}
.nav{background:var(--white);border-bottom:1px solid var(--border2);padding:0 1.5rem;display:flex;align-items:center;justify-content:space-between;height:var(--nav-h);position:sticky;top:0;z-index:200;gap:1rem;box-shadow:var(--shadow-sm)}
.nav-brand{display:flex;align-items:center;gap:10px;flex-shrink:0;text-decoration:none;cursor:pointer}
.nav-logo{width:36px;height:36px;background:linear-gradient(135deg,var(--g400),var(--g600));border-radius:10px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(63,109,17,.35)}
.nav-logo svg{width:20px;height:20px;fill:#fff}
.brand-name{font-family:'Playfair Display',serif;font-size:17px;font-weight:700;color:var(--g800)}
.brand-tag{font-size:9px;color:var(--gr400);letter-spacing:1.6px;text-transform:uppercase;display:block;line-height:1.2;margin-top:1px}
.nav-search{flex:1;max-width:360px;position:relative}
.nav-search input{width:100%;border:1.5px solid var(--border);border-radius:9px;padding:8px 13px 8px 36px;font-size:13px;font-family:'DM Sans',sans-serif;background:var(--gr50);color:var(--text);outline:none;transition:all var(--transition)}
.nav-search input:focus{background:var(--white);border-color:var(--g300);box-shadow:0 0 0 3px rgba(99,153,34,.12)}
.nav-search input::placeholder{color:var(--gr400)}
.nav-search-icon{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--gr400);pointer-events:none}
.search-results-dropdown{position:absolute;top:calc(100% + 6px);left:0;right:0;background:var(--white);border:1px solid var(--border2);border-radius:var(--radius);box-shadow:var(--shadow-lg);z-index:300;max-height:280px;overflow-y:auto;display:none}
.search-results-dropdown.open{display:block}
.search-result-item{display:flex;align-items:center;gap:10px;padding:9px 13px;cursor:pointer;transition:background var(--transition);border-bottom:1px solid var(--gr50)}
.search-result-item:last-child{border-bottom:none}
.search-result-item:hover{background:var(--gr50)}
.sri-icon{width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;background:var(--gr50)}
.sri-name{font-size:13px;font-weight:500}
.sri-label{font-size:11px;color:var(--text-muted)}
.nav-links{display:flex;gap:.25rem}
.nav-link{font-size:13px;font-weight:500;color:var(--gr600);text-decoration:none;padding:6px 12px;border-radius:8px;cursor:pointer;transition:all var(--transition);white-space:nowrap}
.nav-link:hover{background:var(--gr50);color:var(--g600)}
.nav-link.active{background:var(--g50);color:var(--g600)}
.nav-actions{display:flex;align-items:center;gap:8px;flex-shrink:0}
.nav-icon-btn{width:36px;height:36px;border-radius:9px;border:1.5px solid var(--border);background:var(--white);display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;transition:all var(--transition);color:var(--gr600)}
.nav-icon-btn:hover{background:var(--gr50);border-color:var(--border2);color:var(--g600)}
.notif-dot{width:8px;height:8px;background:var(--c400);border-radius:50%;position:absolute;top:5px;right:5px;border:2px solid var(--white)}
.cart-count{min-width:18px;height:18px;padding:0 4px;background:var(--g400);border-radius:9px;position:absolute;top:-5px;right:-5px;font-size:9px;font-weight:700;color:#fff;display:flex;align-items:center;justify-content:center;border:2px solid var(--white)}
.avatar-nav{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--g100),var(--g200));display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:var(--g800);cursor:pointer;border:2px solid var(--g200);box-shadow:0 0 0 2px var(--white);transition:all var(--transition)}
.notif-panel{position:absolute;top:calc(100% + 10px);right:0;width:320px;background:var(--white);border:1px solid var(--border2);border-radius:var(--radius-lg);box-shadow:var(--shadow-lg);z-index:300;display:none;overflow:hidden}
.notif-panel.open{display:block}
.notif-panel-header{padding:12px 15px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;font-size:13px;font-weight:600;color:var(--g900)}
.notif-mark-read{font-size:11px;color:var(--t400);cursor:pointer;font-weight:500;border:none;background:none;font-family:'DM Sans',sans-serif}
.notif-item{display:flex;align-items:flex-start;gap:10px;padding:11px 15px;border-bottom:1px solid var(--gr50);cursor:pointer;transition:background var(--transition)}
.notif-item:last-child{border-bottom:none}
.notif-item:hover{background:var(--gr50)}
.notif-dot-item{width:8px;height:8px;border-radius:50%;flex-shrink:0;margin-top:4px}
.notif-text{font-size:12.5px;color:var(--text);line-height:1.4}
.notif-time{font-size:11px;color:var(--gr400);margin-top:2px}
.app-layout{display:grid;grid-template-columns:var(--sidebar-w) 1fr;min-height:calc(100vh - var(--nav-h))}
.sidebar{background:var(--white);border-right:1px solid var(--border2);padding:1.25rem 0;display:flex;flex-direction:column;overflow-y:auto;position:sticky;top:var(--nav-h);height:calc(100vh - var(--nav-h))}
.sidebar-section{margin-bottom:1.5rem}
.sidebar-label{font-size:9.5px;letter-spacing:1.8px;text-transform:uppercase;color:var(--gr400);padding:0 1rem;margin-bottom:.5rem;font-weight:600}
.sidebar-item{display:flex;align-items:center;gap:9px;padding:8px 1rem;font-size:13px;font-weight:400;color:var(--gr600);cursor:pointer;transition:all .13s;border-left:2.5px solid transparent;text-decoration:none}
.sidebar-item:hover{background:var(--gr50);color:var(--g800)}
.sidebar-item.active{background:var(--g50);color:var(--g600);font-weight:500;border-left-color:var(--g400)}
.sidebar-icon{width:15px;height:15px;flex-shrink:0;opacity:.6;transition:opacity var(--transition)}
.sidebar-item:hover .sidebar-icon,.sidebar-item.active .sidebar-icon{opacity:1}
.sbadge{background:var(--a50);color:var(--a600);font-size:10px;font-weight:600;padding:1px 7px;border-radius:9px;margin-left:auto}
.sbadge.green{background:var(--g50);color:var(--g600)}.sbadge.teal{background:var(--t50);color:var(--t600)}
.sidebar-divider{height:1px;background:var(--border);margin:.5rem 1rem}
.sidebar-bottom{padding:1rem;margin-top:auto}
.season-box{background:linear-gradient(135deg,var(--g50) 0%,rgba(200,223,155,.3) 100%);border-radius:var(--radius);padding:.9rem;border:1px solid var(--g100)}
.content{padding:1.75rem 2rem;overflow-y:auto;overflow-x:hidden}
.page-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.75rem;gap:1rem;flex-wrap:wrap}
.page-title{font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--g900);line-height:1.2}
.page-subtitle{font-size:12.5px;color:var(--gr400);margin-top:4px}
.btn{border:none;padding:8px 18px;border-radius:9px;font-size:13px;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:7px;font-family:'DM Sans',sans-serif;transition:all var(--transition);text-decoration:none;white-space:nowrap}
.btn-primary{background:var(--g400);color:#fff;box-shadow:0 1px 3px rgba(63,109,17,.25)}.btn-primary:hover{background:var(--g500);transform:translateY(-1px)}
.btn-outline{background:var(--white);color:var(--g600);border:1.5px solid var(--g200)}.btn-outline:hover{background:var(--g50)}
.btn-ghost{background:transparent;color:var(--gr600);border:1.5px solid var(--border)}.btn-ghost:hover{background:var(--gr50)}
.btn-danger{background:var(--c50);color:var(--c400);border:1.5px solid var(--c200)}.btn-danger:hover{background:var(--c200)}
.btn-sm{padding:5px 12px;font-size:12px;border-radius:7px}.btn-xs{padding:3px 9px;font-size:11px;border-radius:6px}
.btn-group{display:flex;gap:8px;flex-wrap:wrap}
.card{background:var(--white);border-radius:var(--radius-lg);border:1px solid var(--border);padding:1.4rem;transition:box-shadow var(--transition)}
.card-title{font-size:13.5px;font-weight:600;color:var(--g900);margin-bottom:1.1rem;display:flex;justify-content:space-between;align-items:center;gap:8px}
.card-link{font-size:12px;color:var(--t400);cursor:pointer;background:none;border:none;font-family:'DM Sans',sans-serif;white-space:nowrap;text-decoration:none}
.card-link:hover{color:var(--t600);text-decoration:underline}
.tag{font-size:11px;padding:2px 8px;border-radius:5px;font-weight:500;white-space:nowrap;display:inline-block}
.tag.green{background:var(--g50);color:var(--g600)}.tag.amber{background:var(--a50);color:var(--a600)}.tag.coral{background:var(--c50);color:var(--c400)}.tag.teal{background:var(--t50);color:var(--t600)}.tag.gray{background:var(--gr50);color:var(--gr600)}
.progress-bar{height:5px;background:var(--border);border-radius:3px;overflow:hidden}
.progress-fill{height:100%;border-radius:3px;background:var(--g200);transition:width 1.2s cubic-bezier(.4,0,.2,1)}
.pf-amber{background:var(--a200)}.pf-teal{background:var(--t200)}.pf-coral{background:var(--c200)}
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem}
.stat-card{background:var(--white);border-radius:var(--radius-lg);border:1px solid var(--border);padding:1.25rem;transition:all var(--transition)}
.stat-card:hover{transform:translateY(-2px);box-shadow:var(--shadow)}
.stat-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:.75rem}
.stat-icon{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center}
.si-g{background:var(--g50)}.si-a{background:var(--a50)}.si-t{background:var(--t50)}.si-c{background:var(--c50)}
.stat-trend{font-size:10.5px;font-weight:600;padding:2px 8px;border-radius:5px}
.trend-up{background:var(--g50);color:var(--g600)}.trend-dn{background:var(--c50);color:var(--c400)}
.stat-value{font-size:26px;font-weight:500;color:var(--g900);line-height:1;letter-spacing:-.5px}
.stat-value sup{font-size:12px;color:var(--gr400);font-weight:400}
.stat-label{font-size:12px;color:var(--gr400);margin-top:4px}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem}
.grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.25rem}
.grid-2-3{display:grid;grid-template-columns:1fr 1.3fr;gap:1.25rem;margin-bottom:1.25rem}
.gap-col{display:flex;flex-direction:column;gap:1.25rem}
.task-item{display:flex;align-items:flex-start;gap:10px;padding:10px 0;border-bottom:1px solid var(--gr50);transition:all .12s}
.task-item:last-child{border-bottom:none}
.task-item:hover{background:var(--gr50);margin:0 -10px;padding:10px;border-radius:8px;border-bottom:1px solid transparent}
.task-prio{width:5px;height:5px;border-radius:50%;flex-shrink:0;margin-top:8px}
.tp-h{background:var(--c400)}.tp-m{background:var(--a400)}.tp-l{background:var(--t400)}
.task-check{width:18px;height:18px;border-radius:5px;border:1.5px solid var(--gr200);flex-shrink:0;margin-top:1px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s}
.task-check:hover{border-color:var(--g300)}
.task-check.done{background:var(--g400);border-color:var(--g400)}
.task-check.done::after{content:'';display:block;width:4px;height:8px;border:2px solid #fff;border-left:none;border-top:none;transform:rotate(45deg) translate(-1px,-2px)}
.task-info{flex:1;min-width:0}
.task-name{font-size:13px;color:var(--text);line-height:1.4}
.task-name.done-text{text-decoration:line-through;color:var(--gr400)}
.task-meta{display:flex;gap:6px;margin-top:4px;flex-wrap:wrap}
.task-due{font-size:11px;color:var(--gr400);flex-shrink:0;align-self:center}
.task-due.overdue{color:var(--c400);font-weight:500}
.kanban-wrap{overflow-x:auto;padding-bottom:.5rem}
.kanban{display:grid;grid-template-columns:repeat(4,minmax(240px,1fr));gap:1rem;min-width:840px}
.kanban-col{background:var(--gr50);border-radius:var(--radius);padding:1rem;min-height:360px;border:1px solid var(--border)}
.kanban-col.kc-done{background:rgba(234,243,222,.5)}
.kanban-col.drag-target{border-color:var(--g300);background:var(--g50)}
.kanban-col-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem}
.kch-left{display:flex;align-items:center;gap:7px}
.kc-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
.kc-todo .kch-left .kc-dot{background:var(--gr400)}.kc-inprog .kch-left .kc-dot{background:var(--a400)}.kc-review .kch-left .kc-dot{background:var(--t400)}.kc-done .kch-left .kc-dot{background:var(--g400)}
.kanban-col-title{font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:.8px;color:var(--gr600)}
.kanban-count{min-width:22px;height:22px;padding:0 6px;border-radius:6px;background:var(--white);font-size:11px;font-weight:600;display:flex;align-items:center;justify-content:center;color:var(--gr600);border:1px solid var(--border)}
.kcard{background:var(--white);border-radius:9px;border:1px solid var(--border);padding:.85rem;margin-bottom:.6rem;cursor:grab;user-select:none;transition:all .15s}
.kcard:hover{box-shadow:var(--shadow);transform:translateY(-1px)}
.kcard.dragging{opacity:.45;transform:scale(.96)}
.kcard.drag-over{border:2px dashed var(--g400);background:var(--g50)}
.kcard.kc-done-card .kcard-title{text-decoration:line-through;color:var(--gr400)}
.kcard-title{font-size:12.5px;font-weight:500;color:var(--g900);line-height:1.4;margin-bottom:7px}
.kcard-meta{display:flex;gap:5px;flex-wrap:wrap;margin-bottom:9px}
.kcard-footer{display:flex;justify-content:space-between;align-items:center}
.av-sm{width:22px;height:22px;border-radius:50%;background:var(--g100);display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:600;color:var(--g800);border:1.5px solid var(--white)}
.av-group{display:flex}.av-group .av-sm{margin-left:-6px}.av-group .av-sm:first-child{margin-left:0}
.kcard-due{font-size:10.5px;color:var(--gr400)}
.kcard-due.overdue{color:var(--c400);font-weight:500}
.col-add-btn{width:100%;background:none;border:1.5px dashed var(--gr200);border-radius:8px;padding:8px;font-size:12px;color:var(--gr400);cursor:pointer;transition:all .15s;font-family:'DM Sans',sans-serif;display:flex;align-items:center;justify-content:center;gap:5px;margin-top:4px}
.col-add-btn:hover{border-color:var(--g300);color:var(--g500);background:var(--g50)}
.product-controls{display:flex;gap:9px;margin-bottom:1.25rem;flex-wrap:wrap;align-items:center}
.search-input{border:1.5px solid var(--border);border-radius:9px;padding:7px 12px;font-size:13px;font-family:'DM Sans',sans-serif;color:var(--text);outline:none;background:var(--white);min-width:200px;transition:all var(--transition)}
.search-input:focus{border-color:var(--g300);box-shadow:0 0 0 3px rgba(99,153,34,.12)}
.select-filter{border:1.5px solid var(--border);border-radius:9px;padding:7px 12px;font-size:13px;font-family:'DM Sans',sans-serif;color:var(--text);outline:none;background:var(--white);cursor:pointer}
.product-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(205px,1fr));gap:1rem}
.product-grid.list-view{grid-template-columns:1fr}
.product-card{background:var(--white);border-radius:var(--radius-lg);border:1px solid var(--border);overflow:hidden;transition:all var(--transition)}
.product-card:hover{transform:translateY(-3px);box-shadow:var(--shadow-lg);border-color:var(--border2)}
.product-img{height:130px;display:flex;align-items:center;justify-content:center;font-size:40px;position:relative}
.bg1{background:linear-gradient(135deg,#EAF3DE,#C0DD97)}.bg2{background:linear-gradient(135deg,#FAEEDA,#FAC775)}.bg3{background:linear-gradient(135deg,#E1F5EE,#9FE1CB)}.bg4{background:linear-gradient(135deg,#FAECE7,#F5C4B3)}.bg5{background:linear-gradient(135deg,#FBF0DA,#EF9F27)}.bg6{background:linear-gradient(135deg,#EAF3DE,#97C459)}.bg7{background:linear-gradient(135deg,#E1F5EE,#5DCAA5)}.bg8{background:linear-gradient(135deg,#FAECE7,#F0997B)}
.stock-badge{position:absolute;top:8px;right:8px;font-size:10px;font-weight:600;padding:2px 8px;border-radius:5px}
.sb-ok{background:rgba(255,255,255,.85);color:var(--g600)}.sb-low{background:rgba(255,255,255,.85);color:var(--a600)}.sb-out{background:rgba(255,255,255,.85);color:var(--c600)}
.product-body{padding:.95rem}
.product-name{font-size:13px;font-weight:600;color:var(--g900);line-height:1.3}
.product-farm{font-size:11px;color:var(--gr400);margin:3px 0 8px}
.product-footer{display:flex;justify-content:space-between;align-items:center}
.product-price{font-size:16px;font-weight:600;color:var(--g800);line-height:1}
.product-unit{font-size:10.5px;color:var(--gr400);margin-top:1px}
.add-btn{width:30px;height:30px;border-radius:8px;background:var(--g400);border:none;color:#fff;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all var(--transition);line-height:1}
.add-btn:hover{background:var(--g500);transform:scale(1.08)}
.add-btn.in-cart{background:var(--t400)}
.add-btn:disabled{background:var(--gr200);cursor:not-allowed;transform:none}
.product-grid.list-view .product-card{display:flex;align-items:center}
.product-grid.list-view .product-img{width:80px;height:70px;flex-shrink:0;border-radius:0}
.product-grid.list-view .product-body{flex:1;padding:.75rem 1rem;display:flex;align-items:center;gap:1rem}
.product-grid.list-view .product-footer{margin-left:auto;gap:1rem}
.orders-table-wrap{overflow-x:auto}
.orders-table{width:100%;border-collapse:collapse;font-size:13px}
.orders-table th{text-align:left;padding:11px 14px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.8px;color:var(--gr400);border-bottom:1.5px solid var(--border);white-space:nowrap;background:var(--gr50)}
.orders-table td{padding:12px 14px;border-bottom:1px solid var(--gr50);vertical-align:middle}
.orders-table tbody tr:last-child td{border-bottom:none}
.orders-table tbody tr:hover td{background:var(--gr50)}
.order-id{font-weight:600;color:var(--g600);font-size:12px}
.ostatus{font-size:11px;font-weight:500;padding:3px 10px;border-radius:5px;display:inline-block;white-space:nowrap}
.os-new{background:var(--t50);color:var(--t600)}.os-progress{background:var(--a50);color:var(--a600)}.os-done{background:var(--g50);color:var(--g600)}.os-cancel{background:var(--c50);color:var(--c400)}
.order-actions{display:flex;gap:5px}
.client-cell{display:flex;align-items:center;gap:9px}
.client-initial{width:28px;height:28px;border-radius:7px;background:var(--g50);color:var(--g600);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;flex-shrink:0}
.chart-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem}
.chart-tabs{display:flex;gap:4px}
.chart-tab{font-size:12px;padding:4px 10px;border-radius:6px;cursor:pointer;color:var(--gr400);border:none;background:none;font-family:'DM Sans',sans-serif;font-weight:500;transition:all var(--transition)}
.chart-tab.active{background:var(--g50);color:var(--g600)}
.bar-chart{display:flex;align-items:flex-end;gap:4px;height:80px}
.bar{flex:1;border-radius:4px 4px 0 0;background:var(--g100);cursor:pointer;transition:background var(--transition),height .7s cubic-bezier(.4,0,.2,1);position:relative;min-height:4px}
.bar:hover,.bar.bar-active{background:var(--g400)}
.bar-tooltip{position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);background:var(--g800);color:#fff;font-size:10px;padding:3px 7px;border-radius:5px;white-space:nowrap;opacity:0;pointer-events:none;transition:opacity var(--transition);z-index:10}
.bar-tooltip::after{content:'';position:absolute;top:100%;left:50%;transform:translateX(-50%);border:4px solid transparent;border-top-color:var(--g800)}
.bar:hover .bar-tooltip{opacity:1}
.bar-labels{display:flex;justify-content:space-between;margin-top:6px}
.bar-lbl{flex:1;font-size:10.5px;color:var(--gr400);text-align:center}
.donut-wrap{display:flex;align-items:center;gap:1.5rem}
.donut-legend{display:flex;flex-direction:column;gap:8px;flex:1}
.legend-item{display:flex;align-items:center;gap:8px;font-size:12px;color:var(--gr600)}
.legend-dot{width:9px;height:9px;border-radius:50%;flex-shrink:0}
.legend-val{margin-left:auto;font-weight:500;color:var(--text)}
.farm-item{display:flex;align-items:center;gap:11px;padding:9px 0;border-bottom:1px solid var(--gr50)}
.farm-item:last-child{border-bottom:none}
.farm-avatar{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
.farm-info{flex:1;min-width:0}
.farm-name{font-size:13px;font-weight:500;color:var(--g900);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.farm-loc{font-size:11px;color:var(--gr400)}
.farm-sales{font-size:13px;font-weight:600;color:var(--g800);flex-shrink:0}
.farm-bar{height:3px;background:var(--border);border-radius:2px;margin-top:4px;overflow:hidden}
.farm-bar-fill{height:100%;border-radius:2px;background:var(--g200)}
.proj-card{background:var(--white);border-radius:var(--radius-lg);border:1px solid var(--border);padding:1.4rem;transition:all var(--transition)}
.proj-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-lg)}
.proj-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:.6rem;gap:.75rem}
.proj-name{font-size:14px;font-weight:600;color:var(--g900);line-height:1.35}
.proj-desc{font-size:12px;color:var(--gr400);margin-bottom:1rem;line-height:1.5}
.proj-progress-header{display:flex;justify-content:space-between;font-size:12px;margin-bottom:5px}
.proj-pct-g{color:var(--g600);font-weight:600}.proj-pct-a{color:var(--a600);font-weight:600}.proj-pct-t{color:var(--t600);font-weight:600}
.proj-meta{display:flex;justify-content:space-between;align-items:center;margin-top:1rem}
.proj-date{font-size:11px;color:var(--gr400)}
.cart-overlay{position:fixed;inset:0;background:rgba(44,44,42,.4);z-index:400;display:none;align-items:stretch;justify-content:flex-end;backdrop-filter:blur(2px)}
.cart-overlay.open{display:flex}
.cart-drawer{width:380px;max-width:95vw;background:var(--white);display:flex;flex-direction:column;box-shadow:-8px 0 32px rgba(0,0,0,.15)}
.cart-header{padding:1.4rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center}
.cart-title-wrap{display:flex;align-items:center;gap:10px}
.cart-title{font-family:'Playfair Display',serif;font-size:19px;font-weight:600;color:var(--g900)}
.cart-close{width:32px;height:32px;border-radius:8px;border:1.5px solid var(--border);background:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px;color:var(--gr600);transition:all var(--transition)}
.cart-items{flex:1;overflow-y:auto;padding:1rem 1.5rem}
.cart-empty{text-align:center;padding:2.5rem 1rem;color:var(--gr400);line-height:1.7;font-size:13px}
.cart-item{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--gr50)}
.cart-item:last-child{border-bottom:none}
.cart-item-img{width:48px;height:48px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0}
.cart-item-info{flex:1;min-width:0}
.cart-item-name{font-size:13px;font-weight:500;color:var(--g900)}
.cart-item-farm{font-size:11px;color:var(--gr400);margin:1px 0 7px}
.cart-item-controls{display:flex;align-items:center;gap:7px}
.qty-btn{width:24px;height:24px;border-radius:6px;border:1.5px solid var(--border);background:var(--white);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:15px;color:var(--gr600);transition:all var(--transition)}
.qty-btn:hover{border-color:var(--g300);background:var(--g50);color:var(--g600)}
.qty-num{font-size:13px;font-weight:600;min-width:18px;text-align:center}
.remove-item{background:none;border:none;cursor:pointer;font-size:13px;color:var(--gr400);padding:4px;border-radius:5px;transition:all var(--transition)}
.remove-item:hover{color:var(--c400);background:var(--c50)}
.cart-item-price{font-size:13px;font-weight:600;color:var(--g800);flex-shrink:0}
.cart-footer{padding:1.25rem 1.5rem;border-top:1px solid var(--border);background:var(--white)}
.cart-total{display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem}
.cart-total-label{font-size:13px;color:var(--gr600)}
.cart-total-val{font-size:22px;font-weight:600;color:var(--g900)}
.cart-fee-row{display:flex;justify-content:space-between;font-size:12px;color:var(--gr400);margin-bottom:5px}
.cart-subtotals{margin-bottom:1rem}
.checkout-btn{width:100%;padding:12px;font-size:14px;font-weight:600;background:linear-gradient(135deg,var(--g400),var(--g500));color:#fff;border:none;border-radius:10px;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all var(--transition)}
.checkout-btn:hover{transform:translateY(-1px);box-shadow:0 4px 14px rgba(63,109,17,.4)}
.modal-overlay{position:fixed;inset:0;background:rgba(44,44,42,.45);z-index:500;display:none;align-items:center;justify-content:center;padding:1rem;backdrop-filter:blur(3px)}
.modal-overlay.open{display:flex}
.modal{background:var(--white);border-radius:var(--radius-lg);width:100%;max-width:480px;box-shadow:var(--shadow-lg);overflow:hidden}
.modal-header{display:flex;justify-content:space-between;align-items:center;padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);background:var(--gr50)}
.modal-title{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:var(--g900)}
.modal-close{width:32px;height:32px;border-radius:8px;border:1.5px solid var(--border);background:none;cursor:pointer;font-size:17px;color:var(--gr600);display:flex;align-items:center;justify-content:center;transition:all var(--transition)}
.modal-body{padding:1.5rem;max-height:65vh;overflow-y:auto}
.modal-footer{padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:8px;background:var(--gr50)}
.form-group{margin-bottom:1rem}
.form-label{display:block;font-size:12px;font-weight:500;color:var(--gr600);margin-bottom:5px}
.form-label .required{color:var(--c400);margin-left:2px}
.form-input,.form-select,.form-textarea{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:9px 12px;font-size:13px;font-family:'DM Sans',sans-serif;color:var(--text);outline:none;background:var(--white);transition:all var(--transition)}
.form-input:focus,.form-select:focus,.form-textarea:focus{border-color:var(--g300);box-shadow:0 0 0 3px rgba(99,153,34,.12)}
.form-textarea{resize:vertical;min-height:80px;line-height:1.5}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.form-hint{font-size:11px;color:var(--gr400);margin-top:4px}
.empty-state{text-align:center;padding:3rem 1rem;color:var(--gr400);grid-column:1/-1}
.empty-state-icon{font-size:40px;margin-bottom:.75rem;display:block}
.empty-state-title{font-size:14px;font-weight:500;color:var(--gr600);margin-bottom:.4rem}
.analytics-kpi-row{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.25rem}
.kpi-card{background:var(--white);border-radius:var(--radius-lg);border:1px solid var(--border);padding:1.25rem;display:flex;flex-direction:column;gap:.5rem}
.kpi-label{font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:.8px;color:var(--gr400)}
.kpi-val{font-size:28px;font-weight:500;color:var(--g900);letter-spacing:-.5px;line-height:1}
.kpi-trend{display:flex;align-items:center;gap:5px;font-size:12px;font-weight:500}
.kpi-trend.up{color:var(--g500)}.kpi-trend.dn{color:var(--c400)}
.kpi-sub{font-size:12px;color:var(--gr400)}
@media(max-width:900px){.stats-grid{grid-template-columns:1fr 1fr}.grid-2-3{grid-template-columns:1fr}.analytics-kpi-row{grid-template-columns:1fr 1fr}}
@media(max-width:768px){.app-layout{grid-template-columns:1fr}.sidebar{display:none}.nav-links{display:none}.product-grid{grid-template-columns:repeat(2,1fr)}.grid-2{grid-template-columns:1fr}.grid-3{grid-template-columns:1fr 1fr}.form-row{grid-template-columns:1fr}.content{padding:1.25rem 1rem}}
@media(max-width:480px){.stats-grid{grid-template-columns:1fr 1fr}.product-grid{grid-template-columns:1fr}.nav-search{display:none}.analytics-kpi-row{grid-template-columns:1fr}}
</style>
</head>
<body>

{{-- TOAST --}}
<div class="toast" id="toast"></div>

{{-- NAV --}}
<nav class="nav">
  <a class="nav-brand" href="{{ route('dashboard') }}">
    <div class="nav-logo">
      <svg viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21c1.13-.15 2.24-.39 3.18-.81C10 18.5 12 15 17 8z"/><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.38 5.07C4.9 13.8 7.7 9.5 12 8c4.3-1.5 8 1 8 5 0 2.76-2.24 5-5 5-1.38 0-2.63-.56-3.53-1.47C9.63 18.37 8 20 6 21.12A9.956 9.956 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
    </div>
    <div><span class="brand-name">AgroBio</span><span class="brand-tag">Fermes &amp; Marchés</span></div>
  </a>

  <div class="nav-search">
    <svg class="nav-search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
    <input type="text" id="global-search" placeholder="Rechercher produits, tâches, commandes..." autocomplete="off">
    <div class="search-results-dropdown" id="search-dropdown"></div>
  </div>

  <div class="nav-links">
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Vue d'ensemble</a>
    <a class="nav-link {{ request()->routeIs('products.index')  ? 'active' : '' }}" href="{{ route('products.index') }}">Catalogue</a>
    <a class="nav-link {{ request()->routeIs('tasks.index')     ? 'active' : '' }}" href="{{ route('tasks.index') }}">Tâches</a>
    <a class="nav-link {{ request()->routeIs('orders.index')    ? 'active' : '' }}" href="{{ route('orders.index') }}">Commandes</a>
    <a class="nav-link {{ request()->routeIs('projects.index')  ? 'active' : '' }}" href="{{ route('projects.index') }}">Projets</a>
    <a class="nav-link {{ request()->routeIs('analytics.index') ? 'active' : '' }}" href="{{ route('analytics.index') }}">Analytics</a>
  </div>

  <div class="nav-actions">
    <div style="position:relative">
      <div class="nav-icon-btn" id="notif-btn" title="Notifications">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
        <div class="notif-dot"></div>
      </div>
      <div class="notif-panel" id="notif-panel">
        <div class="notif-panel-header">Notifications <button class="notif-mark-read">Tout lire</button></div>
        <div class="notif-item"><div class="notif-dot-item" style="background:var(--c400)"></div><div><div class="notif-text">Stock bas : Miel de Thym — 8 unités restantes</div><div class="notif-time">Il y a 12 min</div></div></div>
        <div class="notif-item"><div class="notif-dot-item" style="background:var(--g400)"></div><div><div class="notif-text">Nouvelle commande — Marché Bio Casablanca</div><div class="notif-time">Il y a 45 min</div></div></div>
        <div class="notif-item"><div class="notif-dot-item" style="background:var(--t400)"></div><div><div class="notif-text">Tâche "Pipeline CI/CD" passée en révision</div><div class="notif-time">Il y a 2h</div></div></div>
        <div class="notif-item"><div class="notif-dot-item" style="background:var(--a400)"></div><div><div class="notif-text">Domaine Souissi — contrat à renouveler dans 30j</div><div class="notif-time">Hier</div></div></div>
      </div>
    </div>
    <div class="nav-icon-btn" id="cart-toggle-btn" title="Panier" style="position:relative">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.95-1.56l1.65-7.44H6"/></svg>
      <div class="cart-count" id="cart-badge">0</div>
    </div>
    <div class="avatar-nav" title="{{ auth()->user()->name }}">{{ auth()->user()->initials }}</div>
  </div>
</nav>

{{-- LAYOUT --}}
<div class="app-layout">
  {{-- SIDEBAR --}}
  <aside class="sidebar">
    <div class="sidebar-section">
      <div class="sidebar-label">Général</div>
      <a class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        Vue d'ensemble
      </a>
      <a class="sidebar-item {{ request()->routeIs('tasks.index') ? 'active' : '' }}" href="{{ route('tasks.index') }}">
        <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
        Mes tâches
        <span class="sbadge" id="task-badge-sb">{{ \App\Models\Task::where('is_done',false)->count() }}</span>
      </a>
      <a class="sidebar-item {{ request()->routeIs('projects.index') ? 'active' : '' }}" href="{{ route('projects.index') }}">
        <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>
        Projets <span class="sbadge teal">{{ \App\Models\Project::where('status','Actif')->count() }}</span>
      </a>
    </div>
    <div class="sidebar-divider"></div>
    <div class="sidebar-section">
      <div class="sidebar-label">Commerce</div>
      <a class="sidebar-item {{ request()->routeIs('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}">
        <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        Catalogue Bio
      </a>
      <a class="sidebar-item {{ request()->routeIs('orders.index') ? 'active' : '' }}" href="{{ route('orders.index') }}">
        <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Commandes
        <span class="sbadge" id="orders-badge-sb">{{ \App\Models\Order::byStatus('Nouveau')->count() }}</span>
      </a>
      <div class="sidebar-item" id="cart-sidebar-btn">
        <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.95-1.56l1.65-7.44H6"/></svg>
        Panier <span class="sbadge green" id="cart-badge-sb">0</span>
      </div>
    </div>
    <div class="sidebar-divider"></div>
    <div class="sidebar-section">
      <div class="sidebar-label">Analyse</div>
      <a class="sidebar-item {{ request()->routeIs('analytics.index') ? 'active' : '' }}" href="{{ route('analytics.index') }}">
        <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        Analytics
      </a>
    </div>
    <div class="sidebar-bottom">
      <div class="season-box">
        <div style="font-size:22px;margin-bottom:.4rem">🌱</div>
        <div style="font-size:12px;font-weight:600;color:var(--g800);margin-bottom:2px">Printemps 2026</div>
        <div style="font-size:11px;color:var(--g600);margin-bottom:9px">Saison des légumes primeurs</div>
        <div style="font-size:11px;color:var(--gr400);margin-bottom:5px">Récoltes planifiées</div>
        <div class="progress-bar"><div class="progress-fill" style="width:68%"></div></div>
        <div style="font-size:11px;color:var(--g600);margin-top:5px;font-weight:500">68% complétées</div>
      </div>
      <div style="padding:.75rem 0 0;border-top:1px solid var(--border);margin-top:.75rem">
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
          @csrf
          <button type="submit" class="sidebar-item" style="width:100%;background:none;border:none;text-align:left;cursor:pointer;color:var(--c400)">
            <svg class="sidebar-icon" style="opacity:1" viewBox="0 0 24 24" fill="none" stroke="var(--c400)" stroke-width="1.8"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Déconnexion
          </button>
        </form>
      </div>
    </div>
  </aside>

  {{-- PAGE CONTENT --}}
  <main class="content">
    @yield('content')
  </main>
</div>

{{-- CART DRAWER --}}
<div class="cart-overlay" id="cart-overlay">
  <div class="cart-drawer">
    <div class="cart-header">
      <div class="cart-title-wrap">
        <div class="cart-title">🛒 Panier</div>
        <div class="cart-count" id="cart-badge-drawer" style="position:relative;top:auto;right:auto">0</div>
      </div>
      <button class="cart-close" id="cart-close-btn">✕</button>
    </div>
    <div class="cart-items" id="cart-items-list">
      <div class="cart-empty">
        <span style="font-size:36px;display:block;margin-bottom:.75rem">🌿</span>
        Votre panier est vide<br><small>Explorez notre catalogue de produits bio</small>
      </div>
    </div>
    <div class="cart-footer" id="cart-footer" style="display:none">
      <div class="cart-subtotals">
        <div class="cart-fee-row"><span>Sous-total</span><span id="cart-subtotal-val">0 MAD</span></div>
        <div class="cart-fee-row"><span>Livraison</span><span style="color:var(--t600)">Gratuit</span></div>
      </div>
      <div class="cart-total">
        <div class="cart-total-label">Total</div>
        <div class="cart-total-val" id="cart-total-val">0 MAD</div>
      </div>
      <button class="checkout-btn" id="checkout-btn">Passer la commande →</button>
    </div>
  </div>
</div>

{{-- MODAL --}}
<div class="modal-overlay" id="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="modal-title-text">Nouvelle tâche</div>
      <button class="modal-close" id="modal-close-btn">✕</button>
    </div>
    <div class="modal-body" id="modal-body"></div>
    <div class="modal-footer">
      <button class="btn btn-ghost" id="modal-cancel-btn">Annuler</button>
      <button class="btn btn-primary" id="modal-save-btn">Enregistrer</button>
    </div>
  </div>
</div>

<script>
// ── Global helpers available on every page ──────────────────────────────

const API_BASE = '/api';
const CSRF     = document.querySelector('meta[name="csrf-token"]').content;

async function apiRequest(method, url, data = null) {
    const opts = {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    };
    if (data) opts.body = JSON.stringify(data);
    const res  = await fetch(API_BASE + url, opts);
    const json = await res.json();
    if (!res.ok) throw new Error(json.message || 'Erreur serveur');
    return json;
}

// ── Toast ────────────────────────────────────────────────────────────────
let _toastTimer;
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.classList.add('show');
    clearTimeout(_toastTimer);
    _toastTimer = setTimeout(() => t.classList.remove('show'), 2800);
}

// ── Notifications panel ──────────────────────────────────────────────────
document.getElementById('notif-btn').addEventListener('click', e => {
    e.stopPropagation();
    document.getElementById('notif-panel').classList.toggle('open');
});
document.querySelector('.notif-mark-read').addEventListener('click', () => {
    document.getElementById('notif-panel').classList.remove('open');
    showToast('✉️ Notifications marquées comme lues');
});
document.addEventListener('click', e => {
    if (!e.target.closest('#notif-panel') && !e.target.closest('#notif-btn')) {
        document.getElementById('notif-panel')?.classList.remove('open');
    }
    if (!e.target.closest('.nav-search')) {
        document.getElementById('search-dropdown')?.classList.remove('open');
    }
});

// ── Cart ─────────────────────────────────────────────────────────────────
let cart = {};

function toggleCart() {
    const ov = document.getElementById('cart-overlay');
    ov.classList.toggle('open');
    if (ov.classList.contains('open')) renderCartDrawer();
}
document.getElementById('cart-toggle-btn').addEventListener('click', toggleCart);
document.getElementById('cart-sidebar-btn')?.addEventListener('click', toggleCart);
document.getElementById('cart-close-btn').addEventListener('click', toggleCart);
document.getElementById('cart-overlay').addEventListener('click', e => {
    if (e.target === document.getElementById('cart-overlay')) toggleCart();
});

function updateCartUI() {
    const total = Object.values(cart).reduce((a, b) => a + b, 0);
    document.getElementById('cart-badge').textContent = total;
    document.getElementById('cart-badge-sb').textContent = total;
    document.getElementById('cart-badge-drawer').textContent = total;
}

function renderCartDrawer() {
    const el     = document.getElementById('cart-items-list');
    const footer = document.getElementById('cart-footer');
    const keys   = Object.keys(cart);
    if (keys.length === 0) {
        el.innerHTML = `<div class="cart-empty"><span style="font-size:36px;display:block;margin-bottom:.75rem">🌿</span>Votre panier est vide<br><small>Explorez notre catalogue de produits bio</small></div>`;
        footer.style.display = 'none'; return;
    }
    footer.style.display = 'block';
    let total = 0;
    el.innerHTML = keys.map(pid => {
        const p = window._products?.find(x => x.id == pid); if (!p) return '';
        const qty = cart[pid], sub = p.price * qty; total += sub;
        return `<div class="cart-item">
          <div class="cart-item-img ${p.bg_class}">${p.emoji}</div>
          <div class="cart-item-info">
            <div class="cart-item-name">${p.name}</div>
            <div class="cart-item-farm">${p.farm?.name || ''}</div>
            <div class="cart-item-controls">
              <button class="qty-btn" onclick="changeQty(${pid},-1)">−</button>
              <span class="qty-num">${qty}</span>
              <button class="qty-btn" onclick="changeQty(${pid},1)">+</button>
              <button class="remove-item" onclick="removeFromCart(${pid})">✕</button>
            </div>
          </div>
          <div class="cart-item-price">${sub.toLocaleString('fr-FR')} MAD</div>
        </div>`;
    }).join('');
    document.getElementById('cart-subtotal-val').textContent = total.toLocaleString('fr-FR') + ' MAD';
    document.getElementById('cart-total-val').textContent    = total.toLocaleString('fr-FR') + ' MAD';
}

function addToCart(pid) {
    const p = window._products?.find(x => x.id == pid);
    if (!p || p.stock_status === 'out') return;
    cart[pid] = (cart[pid] || 0) + 1;
    updateCartUI();
    if (typeof renderProducts === 'function') renderProducts();
    showToast(`🛒 ${p.name} ajouté`);
}
function removeFromCart(pid) { delete cart[pid]; updateCartUI(); renderCartDrawer(); if (typeof renderProducts === 'function') renderProducts(); }
function changeQty(pid, d) { const n = (cart[pid] || 0) + d; if (n <= 0) removeFromCart(pid); else { cart[pid] = n; updateCartUI(); renderCartDrawer(); } }

document.getElementById('checkout-btn').addEventListener('click', async () => {
    if (!Object.keys(cart).length) return;
    const items = Object.entries(cart).map(([product_id, quantity]) => ({ product_id: parseInt(product_id), quantity }));
    try {
        await apiRequest('POST', '/orders', { client_name: 'Commande en ligne', delivery_mode: 'Standard', items });
        cart = {}; updateCartUI(); toggleCart();
        showToast('🎉 Commande passée avec succès !');
    } catch (e) { showToast('❌ ' + e.message); }
});

// ── Modal helpers ─────────────────────────────────────────────────────────
function openModal(title, html, onSave) {
    document.getElementById('modal-title-text').textContent = title;
    document.getElementById('modal-body').innerHTML = html;
    document.getElementById('modal-overlay').classList.add('open');
    document.getElementById('modal-overlay')._onSave = onSave;
    setTimeout(() => document.querySelector('#modal-body .form-input')?.focus(), 100);
}
function closeModal() { document.getElementById('modal-overlay').classList.remove('open'); }
document.getElementById('modal-close-btn').addEventListener('click', closeModal);
document.getElementById('modal-cancel-btn').addEventListener('click', closeModal);
document.getElementById('modal-overlay').addEventListener('click', e => { if (e.target === document.getElementById('modal-overlay')) closeModal(); });
document.getElementById('modal-save-btn').addEventListener('click', () => {
    const fn = document.getElementById('modal-overlay')._onSave;
    if (typeof fn === 'function') fn();
});

// ── Global search ─────────────────────────────────────────────────────────
document.getElementById('global-search')?.addEventListener('input', async function () {
    const v = this.value.trim();
    const dd = document.getElementById('search-dropdown');
    if (v.length < 2) { dd.classList.remove('open'); return; }
    const products = window._products || [];
    const results  = products.filter(p => p.name.toLowerCase().includes(v.toLowerCase())).slice(0, 5);
    if (!results.length) { dd.innerHTML = `<div style="padding:12px 14px;font-size:12px;color:var(--gr400)">Aucun résultat pour «${v}»</div>`; dd.classList.add('open'); return; }
    dd.innerHTML = results.map(p => `<div class="search-result-item" onclick="window.location='/products'">
      <div class="sri-icon ${p.bg_class}">${p.emoji}</div>
      <div><div class="sri-name">${p.name}</div><div class="sri-label">${p.price} MAD · ${p.farm?.name || ''}</div></div>
    </div>`).join('');
    dd.classList.add('open');
});

// ── Keyboard shortcuts ────────────────────────────────────────────────────
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeModal();
        document.getElementById('notif-panel')?.classList.remove('open');
        document.getElementById('cart-overlay')?.classList.remove('open');
    }
});

// ── Bar chart renderer ────────────────────────────────────────────────────
function renderBarChart(containerId, labelId, data, labels) {
    const el  = document.getElementById(containerId);
    const lel = document.getElementById(labelId);
    if (!el) return;
    const max = Math.max(...data.filter(v => v > 0), 1);
    el.innerHTML = data.map((v, i) => v > 0
        ? `<div class="bar${i === data.length - 1 ? ' bar-active' : ''}" style="height:${Math.round((v / max) * 100)}%"><div class="bar-tooltip">${Number(v).toLocaleString('fr-FR')} MAD</div></div>`
        : `<div class="bar" style="height:3%;background:var(--border)"></div>`
    ).join('');
    if (lel) lel.innerHTML = labels.map(l => `<span class="bar-lbl">${l}</span>`).join('');
}
</script>

@stack('scripts')
</body>
</html>
