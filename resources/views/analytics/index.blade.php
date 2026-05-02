@extends('layouts.app')
@section('title', 'Analytics')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Analytics</div>
    <div class="page-subtitle">Tableau de bord analytique — {{ now()->translatedFormat('F Y') }}</div>
  </div>
</div>

{{-- KPI row --}}
<div class="analytics-kpi-row" id="kpi-row">
  <div class="kpi-card">
    <div class="kpi-label">Chiffre d'affaires</div>
    <div class="kpi-val" id="kpi-revenue">—</div>
    <div class="kpi-trend up" id="kpi-revenue-pct">Chargement…</div>
    <div class="progress-bar" style="margin-top:.5rem">
      <div class="progress-fill" id="kpi-revenue-bar" style="width:0%"></div>
    </div>
  </div>
  <div class="kpi-card">
    <div class="kpi-label">Commandes</div>
    <div class="kpi-val" id="kpi-orders">—</div>
    <div class="kpi-trend up" id="kpi-orders-pct">Chargement…</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-label">Panier moyen</div>
    <div class="kpi-val" id="kpi-basket">—</div>
    <div class="kpi-sub">Ce mois</div>
  </div>
</div>

<div class="grid-2" style="margin-bottom:1.25rem">
  <div class="card">
    <div class="card-title">Revenus mensuels {{ now()->year }}</div>
    <div class="bar-chart" id="monthly-chart" style="height:100px"></div>
    <div class="bar-labels" id="monthly-labels"></div>
  </div>
  <div class="card">
    <div class="card-title">Répartition par catégorie</div>
    <div class="donut-wrap">
      <svg viewBox="0 0 100 100" width="100" height="100" style="flex-shrink:0">
        <circle cx="50" cy="50" r="38" fill="none" stroke="var(--border)" stroke-width="14"/>
        <circle cx="50" cy="50" r="38" fill="none" stroke="var(--g300)" stroke-width="14"
                stroke-dasharray="89 150" stroke-dashoffset="25" transform="rotate(-90 50 50)"/>
        <circle cx="50" cy="50" r="38" fill="none" stroke="var(--a200)" stroke-width="14"
                stroke-dasharray="44 195" stroke-dashoffset="-64" transform="rotate(-90 50 50)"/>
        <circle cx="50" cy="50" r="38" fill="none" stroke="var(--t200)" stroke-width="14"
                stroke-dasharray="28 211" stroke-dashoffset="-108" transform="rotate(-90 50 50)"/>
        <circle cx="50" cy="50" r="38" fill="none" stroke="var(--c200)" stroke-width="14"
                stroke-dasharray="18 221" stroke-dashoffset="-136" transform="rotate(-90 50 50)"/>
        <text x="50" y="53" text-anchor="middle" font-size="11" font-weight="600"
              fill="var(--g900)" font-family="DM Sans">100%</text>
      </svg>
      <div class="donut-legend" id="cat-legend">
        <div class="legend-item"><div class="legend-dot" style="background:var(--g300)"></div>Légumes & Fruits<span class="legend-val">—</span></div>
        <div class="legend-item"><div class="legend-dot" style="background:var(--a200)"></div>Huiles & Épices<span class="legend-val">—</span></div>
        <div class="legend-item"><div class="legend-dot" style="background:var(--t200)"></div>Miel & Confiture<span class="legend-val">—</span></div>
        <div class="legend-item"><div class="legend-dot" style="background:var(--c200)"></div>Céréales<span class="legend-val">—</span></div>
      </div>
    </div>
  </div>
</div>

<div class="grid-2">
  <div class="card">
    <div class="card-title">Top 5 produits vendus</div>
    <table class="orders-table" id="top-products-table">
      <thead><tr><th>Produit</th><th>Ferme</th><th>Qté</th><th>Revenus</th></tr></thead>
      <tbody><tr><td colspan="4" style="text-align:center;color:var(--gr400);padding:1.5rem">Chargement…</td></tr></tbody>
    </table>
  </div>
  <div class="card">
    <div class="card-title">Performance par ferme</div>
    <div id="farm-perf-list"><div style="color:var(--gr400);font-size:13px">Chargement…</div></div>
  </div>
</div>
@endsection

@push('scripts')
<script>
async function loadAnalytics() {
    // KPI summary
    const summary = await apiRequest('GET', '/analytics/summary');
    document.getElementById('kpi-revenue').innerHTML = `${Number(summary.revenue).toLocaleString('fr-FR')} <span style="font-size:14px;color:var(--gr400)">MAD</span>`;
    document.getElementById('kpi-orders').textContent  = summary.orders;
    document.getElementById('kpi-basket').innerHTML    = `${Number(summary.avg_basket).toLocaleString('fr-FR')} <span style="font-size:14px;color:var(--gr400)">MAD</span>`;
    const rPct = document.getElementById('kpi-revenue-pct');
    rPct.textContent  = `${summary.revenue_pct >= 0 ? '↑' : '↓'} ${Math.abs(summary.revenue_pct)}% vs mois précédent`;
    rPct.className    = `kpi-trend ${summary.revenue_pct >= 0 ? 'up' : 'dn'}`;
    const oPct = document.getElementById('kpi-orders-pct');
    oPct.textContent  = `${summary.orders_pct >= 0 ? '↑' : '↓'} ${Math.abs(summary.orders_pct)}% vs mois précédent`;
    oPct.className    = `kpi-trend ${summary.orders_pct >= 0 ? 'up' : 'dn'}`;
    const goal = 20000;
    document.getElementById('kpi-revenue-bar').style.width = Math.min(100, Math.round(summary.revenue / goal * 100)) + '%';

    // Monthly chart
    const monthly = await apiRequest('GET', '/analytics/monthly');
    renderBarChart('monthly-chart', 'monthly-labels', monthly.map(d => d.total), monthly.map(d => d.label));

    // Top products
    const products = await apiRequest('GET', '/analytics/top-products');
    const tbody = document.querySelector('#top-products-table tbody');
    tbody.innerHTML = products.map(p => `<tr>
      <td style="font-weight:500">${p.emoji || '🌿'} ${p.name}</td>
      <td style="color:var(--gr400)">${p.farm_name}</td>
      <td style="color:var(--t600)">${p.qty_sold}</td>
      <td style="font-weight:600;color:var(--g800)">${Number(p.revenue).toLocaleString('fr-FR')} MAD</td>
    </tr>`).join('');

    // Top farms
    const farms  = await apiRequest('GET', '/analytics/top-farms');
    const maxRev = Math.max(...farms.map(f => f.revenue), 1);
    const colors = ['var(--g200)', 'var(--a200)', 'var(--t200)', 'var(--g100)', 'var(--a100)'];
    document.getElementById('farm-perf-list').innerHTML = farms.map((f, i) => `
      <div style="margin-bottom:12px">
        <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px">
          <span style="font-weight:500;color:var(--g900)">${f.emoji || '🌿'} ${f.name}</span>
          <span style="font-weight:600;color:var(--g800)">${Number(f.revenue).toLocaleString('fr-FR')} MAD</span>
        </div>
        <div class="progress-bar">
          <div style="height:100%;border-radius:3px;background:${colors[i] || 'var(--g100)'};width:${Math.round(f.revenue / maxRev * 100)}%;transition:width 1.2s ease"></div>
        </div>
      </div>`).join('');

    // Categories
    const cats   = await apiRequest('GET', '/analytics/categories');
    const items  = document.querySelectorAll('#cat-legend .legend-val');
    cats.slice(0, 4).forEach((c, i) => { if (items[i]) items[i].textContent = c.pct + '%'; });
}

loadAnalytics();
</script>
@endpush
