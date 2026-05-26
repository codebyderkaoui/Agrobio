@extends('layouts.app')
@section('title', 'Fermes Partenaires')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Fermes Partenaires 🌿</div>
    <div class="page-subtitle">{{ $farms->count() }} ferme(s) enregistrée(s) — produits bio certifiés</div>
  </div>
  <div class="btn-group">
    <input type="text" id="farm-search" class="search-input" placeholder="🔍 Rechercher...">
    <div class="chart-tabs" style="background:var(--white);border:1.5px solid var(--border);border-radius:9px;padding:3px">
      <button class="chart-tab active" data-filter="all">Toutes</button>
      <button class="chart-tab" data-filter="active">Actives</button>
      <button class="chart-tab" data-filter="bio">Bio</button>
      <button class="chart-tab" data-filter="expiring">Contrat ⚠</button>
    </div>
  </div>
</div>

{{-- KPI row --}}
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr)">
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon si-g">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--g600)" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      </div>
    </div>
    <div class="stat-value">{{ $farms->count() }}</div>
    <div class="stat-label">Fermes totales</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon si-t">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--t600)" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
      </div>
    </div>
    <div class="stat-value">{{ $farms->where('is_active', true)->count() }}</div>
    <div class="stat-label">Fermes actives</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon si-g">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--g600)" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
      </div>
    </div>
    <div class="stat-value">{{ $farms->where('bio_certified', true)->count() }}</div>
    <div class="stat-label">Bio certifiées</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon si-a">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--a600)" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
      </div>
    </div>
    <div class="stat-value">{{ $farms->sum(fn($f) => $f->products->count()) }}</div>
    <div class="stat-label">Produits au total</div>
  </div>
</div>

{{-- Farm cards --}}
<div id="farms-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(420px,1fr));gap:1.25rem">
  @forelse($farms as $farm)
    @php
      $contractDays = $farm->contract_expires_at
        ? now()->diffInDays(\Carbon\Carbon::parse($farm->contract_expires_at), false)
        : null;
      $contractStatus = match(true) {
        is_null($contractDays)  => 'none',
        $contractDays < 0       => 'expired',
        $contractDays <= 30     => 'expiring',
        default                 => 'valid',
      };
    @endphp
    <div class="farm-card card"
         data-active="{{ $farm->is_active ? '1' : '0' }}"
         data-bio="{{ $farm->bio_certified ? '1' : '0' }}"
         data-contract="{{ $contractStatus }}"
         data-search="{{ strtolower($farm->name . ' ' . $farm->city . ' ' . ($farm->region ?? '') . ' ' . ($farm->owner ?? '')) }}">

      {{-- Header --}}
      <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:1rem">
        <div style="width:50px;height:50px;border-radius:12px;background:var(--g50);border:1px solid var(--g100);display:flex;align-items:center;justify-content:center;font-size:26px;flex-shrink:0">
          {{ $farm->emoji }}
        </div>
        <div style="flex:1;min-width:0">
          <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:3px">
            <span style="font-size:15px;font-weight:600;color:var(--g900)">{{ $farm->name }}</span>
            @if($farm->bio_certified)
              <span class="tag green" style="font-size:10px">🌿 Bio</span>
            @endif
            <span class="tag {{ $farm->is_active ? 'teal' : 'gray' }}" style="font-size:10px">
              {{ $farm->is_active ? 'Active' : 'Inactive' }}
            </span>
          </div>
          <div style="font-size:12px;color:var(--gr400)">
            📍 {{ $farm->city }}@if($farm->region), {{ $farm->region }}@endif
          </div>
        </div>

        {{-- Contract badge --}}
        @if($contractStatus === 'valid')
          <span class="tag green" style="font-size:10px;flex-shrink:0">
            ✅ {{ \Carbon\Carbon::parse($farm->contract_expires_at)->format('d/m/Y') }}
          </span>
        @elseif($contractStatus === 'expiring')
          <span class="tag amber" style="font-size:10px;flex-shrink:0">
            ⚠ {{ $contractDays }}j restants
          </span>
        @elseif($contractStatus === 'expired')
          <span class="tag coral" style="font-size:10px;flex-shrink:0">
            ❌ Expiré
          </span>
        @else
          <span class="tag gray" style="font-size:10px;flex-shrink:0">Sans contrat</span>
        @endif
      </div>

      {{-- Contact info --}}
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px 12px;padding:10px 12px;background:var(--gr50);border-radius:9px;margin-bottom:1rem;font-size:12px">
        @if($farm->owner)
          <div>
            <span style="color:var(--gr400)">Responsable</span><br>
            <span style="font-weight:500;color:var(--text)">{{ $farm->owner }}</span>
          </div>
        @endif
        @if($farm->email)
          <div>
            <span style="color:var(--gr400)">Email</span><br>
            <a href="mailto:{{ $farm->email }}" style="color:var(--t400);text-decoration:none;font-weight:500">{{ $farm->email }}</a>
          </div>
        @endif
        @if($farm->phone)
          <div>
            <span style="color:var(--gr400)">Téléphone</span><br>
            <a href="tel:{{ $farm->phone }}" style="color:var(--g600);text-decoration:none;font-weight:500">{{ $farm->phone }}</a>
          </div>
        @endif
        <div>
          <span style="color:var(--gr400)">Contrat expire</span><br>
          <span style="font-weight:500;color:{{ $contractStatus === 'expired' ? 'var(--c400)' : ($contractStatus === 'expiring' ? 'var(--a400)' : 'var(--text)') }}">
            {{ $farm->contract_expires_at ? \Carbon\Carbon::parse($farm->contract_expires_at)->format('d/m/Y') : '—' }}
          </span>
        </div>
      </div>

      {{-- Products --}}
      @if($farm->products->count() > 0)
        <div>
          <div class="card-title" style="margin-bottom:.7rem">
            <span>Produits ({{ $farm->products->count() }})</span>
            <div style="display:flex;gap:5px;flex-wrap:wrap">
              @foreach($farm->products->groupBy('category') as $cat => $items)
                <span class="tag gray" style="font-size:10px">{{ $cat }} · {{ $items->count() }}</span>
              @endforeach
            </div>
          </div>
          <div style="display:flex;flex-direction:column;gap:5px">
            @foreach($farm->products->where('is_active', true)->take(5) as $product)
              <div style="display:flex;align-items:center;gap:9px;padding:7px 10px;background:var(--gr50);border-radius:8px;border:1px solid var(--border)">
                <div class="product-img {{ $product->bg_class }}" style="width:36px;height:36px;border-radius:8px;font-size:18px;flex-shrink:0">
                  {{ $product->emoji }}
                </div>
                <div style="flex:1;min-width:0">
                  <div style="font-size:12.5px;font-weight:500;color:var(--g900);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $product->name }}</div>
                  <div style="font-size:11px;color:var(--gr400)">{{ $product->category }}</div>
                </div>
                <div style="text-align:right;flex-shrink:0">
                  <div style="font-size:13px;font-weight:600;color:var(--g800)">{{ number_format($product->price, 2, ',', '') }} MAD</div>
                  <div style="font-size:10px;color:var(--gr400)">/ {{ $product->unit }}</div>
                </div>
                @php
                  $stockConfig = match($product->stock_status) {
                    'ok'  => ['class' => 'sb-ok',  'label' => 'En stock'],
                    'low' => ['class' => 'sb-low', 'label' => 'Stock bas'],
                    'out' => ['class' => 'sb-out', 'label' => 'Rupture'],
                    default => ['class' => 'sb-ok', 'label' => ''],
                  };
                @endphp
                <span class="ostatus {{ $stockConfig['class'] }}" style="font-size:10px">{{ $stockConfig['label'] }}</span>
              </div>
            @endforeach
            @php $activeCount = $farm->products->where('is_active', true)->count(); @endphp
            @if($activeCount > 5)
              <div style="text-align:center;font-size:12px;color:var(--gr400);padding:5px">
                + {{ $activeCount - 5 }} autres produits actifs
              </div>
            @endif
            @if($farm->products->where('is_active', false)->count() > 0)
              <div style="text-align:center;font-size:11px;color:var(--gr400);padding:3px;font-style:italic">
                {{ $farm->products->where('is_active', false)->count() }} produit(s) inactif(s)
              </div>
            @endif
          </div>
        </div>
      @else
        <div style="text-align:center;padding:1.5rem;color:var(--gr400);font-size:13px;background:var(--gr50);border-radius:9px">
          Aucun produit enregistré pour cette ferme
        </div>
      @endif
    </div>
  @empty
    <div style="grid-column:1/-1;text-align:center;padding:4rem;color:var(--gr400)">
      <div style="font-size:3rem;margin-bottom:1rem">🌾</div>
      <div style="font-size:14px">Aucune ferme enregistrée pour le moment.</div>
    </div>
  @endforelse
</div>

@endsection

@push('scripts')
<script>
(function () {
  const searchEl  = document.getElementById('farm-search');
  const filterBtns = document.querySelectorAll('.chart-tab[data-filter]');
  const cards     = document.querySelectorAll('.farm-card');
  let currentFilter = 'all';

  function applyFilters() {
    const q = searchEl.value.toLowerCase().trim();
    cards.forEach(card => {
      const textMatch    = !q || card.dataset.search.includes(q);
      const activeMatch  = currentFilter !== 'active'   || card.dataset.active   === '1';
      const bioMatch     = currentFilter !== 'bio'      || card.dataset.bio      === '1';
      const expireMatch  = currentFilter !== 'expiring' || ['expiring','expired'].includes(card.dataset.contract);
      const filterOk     = currentFilter === 'all' || activeMatch && bioMatch && expireMatch;
      card.style.display = (textMatch && filterOk) ? '' : 'none';
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
</script>
@endpush
