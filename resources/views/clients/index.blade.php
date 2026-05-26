@extends('layouts.app')
@section('title', 'Clients')

@section('content')
@php
  use Illuminate\Support\Facades\DB;

  $clients = \App\Models\Order::select(
      'client_name',
      'client_email',
      'client_phone',
      DB::raw('COUNT(*) as total_orders'),
      DB::raw('SUM(total_amount) as total_spent'),
      DB::raw('MAX(created_at) as last_order_at'),
      DB::raw('GROUP_CONCAT(DISTINCT status) as all_statuses')
    )
    ->whereNull('deleted_at')
    ->groupBy('client_name', 'client_email', 'client_phone')
    ->orderByDesc('total_spent')
    ->get();

  $avatarColors = [
    ['bg'=>'var(--g50)',  'fg'=>'var(--g700)'],
    ['bg'=>'var(--t50)',  'fg'=>'var(--t600)'],
    ['bg'=>'var(--a50)',  'fg'=>'var(--a600)'],
    ['bg'=>'var(--c50)',  'fg'=>'var(--c400)'],
    ['bg'=>'var(--gr50)', 'fg'=>'var(--gr600)'],
  ];
@endphp

<div class="page-header">
  <div>
    <div class="page-title">Clients 👥</div>
    <div class="page-subtitle">{{ $clients->count() }} client(s) — dérivés des commandes passées</div>
  </div>
  <div class="btn-group">
    <input type="text" id="client-search" class="search-input" placeholder="🔍 Nom, email, tél...">
    <div class="chart-tabs" style="background:var(--white);border:1.5px solid var(--border);border-radius:9px;padding:3px">
      <button class="chart-tab active" data-filter="all">Tous</button>
      <button class="chart-tab" data-filter="recent">Récents</button>
    </div>
  </div>
</div>

{{-- KPI row --}}
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr)">
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon si-g">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--g600)" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
      </div>
    </div>
    <div class="stat-value">{{ $clients->count() }}</div>
    <div class="stat-label">Clients uniques</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon si-a">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--a600)" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
      </div>
      <span class="stat-trend trend-up">Total</span>
    </div>
    <div class="stat-value">{{ $clients->sum('total_orders') }}</div>
    <div class="stat-label">Commandes totales</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon si-t">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--t600)" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
      </div>
    </div>
    <div class="stat-value" style="font-size:18px">{{ number_format($clients->sum('total_spent'), 0, ',', ' ') }}<sup>MAD</sup></div>
    <div class="stat-label">Chiffre d'affaires</div>
  </div>
</div>

{{-- Table --}}
<div class="card" style="padding:0;overflow:hidden">
  <div class="orders-table-wrap">
    <table class="orders-table">
      <thead>
        <tr>
          <th>Client</th>
          <th>Contact</th>
          <th style="text-align:center">Commandes</th>
          <th style="text-align:right">Total dépensé</th>
          <th>Dernière commande</th>
          <th>Statuts</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="clients-tbody">
        @forelse($clients as $i => $client)
          @php
            $color      = $avatarColors[$i % count($avatarColors)];
            $initials   = collect(explode(' ', $client->client_name))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->join('');
            $daysSince  = \Carbon\Carbon::parse($client->last_order_at)->diffInDays(now());
            $isRecent   = $daysSince <= 30;
            $statuses   = array_unique(array_filter(explode(',', $client->all_statuses ?? '')));
            $statusMap  = ['Nouveau'=>'os-new','En cours'=>'os-progress','Livré'=>'os-done','Annulé'=>'os-cancel'];
          @endphp
          <tr class="client-row"
              data-recent="{{ $isRecent ? '1' : '0' }}"
              data-search="{{ strtolower($client->client_name . ' ' . ($client->client_email ?? '') . ' ' . ($client->client_phone ?? '')) }}">

            <td>
              <div class="client-cell">
                <div class="client-initial" style="background:{{ $color['bg'] }};color:{{ $color['fg'] }};width:32px;height:32px;border-radius:8px;font-size:11px;font-weight:600">
                  {{ $initials }}
                </div>
                <div>
                  <div style="font-size:13px;font-weight:500;color:var(--g900);display:flex;align-items:center;gap:6px">
                    {{ $client->client_name }}
                  </div>
                  @if($client->client_email)
                    <a href="mailto:{{ $client->client_email }}" style="font-size:11px;color:var(--t400);text-decoration:none">
                      {{ $client->client_email }}
                    </a>
                  @endif
                </div>
              </div>
            </td>

            <td>
              @if($client->client_phone)
                <a href="tel:{{ $client->client_phone }}" style="font-size:12.5px;color:var(--gr600);text-decoration:none">
                  📞 {{ $client->client_phone }}
                </a>
              @else
                <span style="color:var(--gr200)">—</span>
              @endif
            </td>

            <td style="text-align:center">
              <span style="font-size:16px;font-weight:600;color:var(--g800)">{{ $client->total_orders }}</span>
            </td>

            <td style="text-align:right">
              <span style="font-size:13px;font-weight:600;color:var(--g900)">
                {{ number_format($client->total_spent, 2, ',', ' ') }} MAD
              </span>
            </td>

            <td>
              <div style="font-size:12.5px;font-weight:500;color:var(--text);display:flex;align-items:center;gap:5px">
                {{ \Carbon\Carbon::parse($client->last_order_at)->format('d/m/Y') }}
                @if($isRecent)
                  <span style="width:7px;height:7px;background:var(--t400);border-radius:50%;display:inline-block" title="Commande récente (30j)"></span>
                @endif
              </div>
              <div style="font-size:11px;color:var(--gr400)">
                {{ \Carbon\Carbon::parse($client->last_order_at)->diffForHumans() }}
              </div>
            </td>

            <td>
              <div style="display:flex;flex-wrap:wrap;gap:4px">
                @foreach($statuses as $s)
                  <span class="ostatus {{ $statusMap[trim($s)] ?? 'os-new' }}">{{ trim($s) }}</span>
                @endforeach
              </div>
            </td>

            <td>
              <button class="btn btn-outline btn-sm"
                      onclick="openClientHistory('{{ addslashes($client->client_name) }}')"
                      title="Voir l'historique des commandes">
                📋 Historique
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align:center;padding:3rem;color:var(--gr400);font-size:13px">
              <div style="font-size:2.5rem;margin-bottom:.75rem">📭</div>
              Aucune commande enregistrée.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- History modal (uses the app.blade.php modal system) --}}

@endsection

@push('scripts')
<script>
// ── Search + filter ─────────────────────────────────────────
(function () {
  const searchEl   = document.getElementById('client-search');
  const filterBtns = document.querySelectorAll('.chart-tab[data-filter]');
  const rows       = document.querySelectorAll('.client-row');
  let currentFilter = 'all';

  function applyFilters() {
    const q = searchEl.value.toLowerCase().trim();
    rows.forEach(row => {
      const textMatch   = !q || row.dataset.search.includes(q);
      const recentMatch = currentFilter !== 'recent' || row.dataset.recent === '1';
      row.style.display = (textMatch && (currentFilter === 'all' || recentMatch)) ? '' : 'none';
    });
  }

  searchEl.addEventListener('input', applyFilters);
  filterBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      filterBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      currentFilter = this.dataset.filter;
      applyFilters();
    });
  });
})();

// ── Client order history modal ──────────────────────────────
async function openClientHistory(clientName) {
  const statusMap = {
    'Nouveau':  'os-new',
    'En cours': 'os-progress',
    'Livré':    'os-done',
    'Annulé':   'os-cancel',
  };

  const deliveryIcons = { 'Standard': '🚚', 'Express': '⚡', 'Retrait': '🏪' };

  openModal('Historique — ' + clientName, '<div style="text-align:center;padding:2rem;color:var(--gr400)">Chargement...</div>', null);

  // Hide the save button — this is read-only
  document.getElementById('modal-save-btn').style.display = 'none';
  document.getElementById('modal-cancel-btn').textContent = 'Fermer';

  try {
    const data = await apiRequest('GET', '/clients/' + encodeURIComponent(clientName) + '/orders');
    const orders = data.orders || [];

    if (!orders.length) {
      document.getElementById('modal-body').innerHTML = '<div style="text-align:center;padding:2rem;color:var(--gr400);font-size:13px">Aucune commande trouvée.</div>';
      return;
    }

    const html = orders.map(o => {
      const statusClass = statusMap[o.status] || 'os-new';
      const icon = deliveryIcons[o.delivery_mode] || '🚚';
      const date = new Date(o.created_at).toLocaleDateString('fr-FR');
      const amount = parseFloat(o.total_amount).toLocaleString('fr-FR', { minimumFractionDigits: 2 });

      return `<div style="padding:12px;background:var(--gr50);border-radius:10px;border:1px solid var(--border);margin-bottom:10px">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:6px;margin-bottom:8px">
          <span class="order-id">#${o.order_number}</span>
          <span class="ostatus ${statusClass}">${o.status}</span>
          <span style="font-size:14px;font-weight:600;color:var(--g800);margin-left:auto">${amount} MAD</span>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:8px;font-size:11.5px;color:var(--gr400);margin-bottom:${o.notes ? '8px' : '0'}">
          <span>📅 ${date}</span>
          <span>${icon} ${o.delivery_mode}</span>
          ${o.delivery_address ? `<span>📍 ${o.delivery_address}</span>` : ''}
        </div>
        ${o.notes ? `<div style="font-size:12px;color:var(--gr600);background:var(--white);padding:7px 10px;border-radius:7px;border:1px solid var(--border);font-style:italic">💬 ${o.notes}</div>` : ''}
      </div>`;
    }).join('');

    document.getElementById('modal-body').innerHTML = `
      <div style="font-size:12px;color:var(--gr400);margin-bottom:12px">${orders.length} commande(s) au total</div>
      ${html}`;

  } catch (e) {
    document.getElementById('modal-body').innerHTML = `<div style="text-align:center;padding:2rem;color:var(--c400);font-size:13px">❌ ${e.message}</div>`;
  }
}

// Restore modal save button when closed
document.getElementById('modal-close-btn').addEventListener('click', () => {
  document.getElementById('modal-save-btn').style.display = '';
  document.getElementById('modal-cancel-btn').textContent = 'Annuler';
});
document.getElementById('modal-cancel-btn').addEventListener('click', () => {
  document.getElementById('modal-save-btn').style.display = '';
  document.getElementById('modal-cancel-btn').textContent = 'Annuler';
});
</script>
@endpush
