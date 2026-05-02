@extends('layouts.app')
@section('title', 'Tableau de bord')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Bonjour, {{ auth()->user()->name }} 👋</div>
    <div class="page-subtitle">
      {{ now()->translatedFormat('l d F Y') }} — Voici vos métriques du jour
    </div>
  </div>
  <div class="btn-group">
    <button class="btn btn-outline" onclick="openTaskModal()">+ Tâche</button>
    <button class="btn btn-primary" onclick="openProductModal()">+ Produit</button>
  </div>
</div>

{{-- KPI Stats --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon si-g">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--g600)" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
      </div>
      <span class="stat-trend trend-up">+12%</span>
    </div>
    <div class="stat-value">{{ number_format($stats['products_active']) }}</div>
    <div class="stat-label">Produits actifs</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon si-a">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--a600)" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
      </div>
      <span class="stat-trend trend-up">+8%</span>
    </div>
    <div class="stat-value">{{ $stats['orders_this_month'] }}</div>
    <div class="stat-label">Commandes ce mois</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon si-t">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--t600)" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
      </div>
      <span class="stat-trend trend-up">+23%</span>
    </div>
    <div class="stat-value">{{ number_format($stats['revenue_this_month'], 0, ',', ' ') }}<sup>MAD</sup></div>
    <div class="stat-label">Chiffre d'affaires</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon si-c">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--c400)" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
      </div>
    </div>
    <div class="stat-value">{{ $stats['tasks_done'] }}<sup>/{{ $stats['tasks_total'] }}</sup></div>
    <div class="stat-label">Tâches complétées</div>
  </div>
</div>

{{-- Main grid --}}
<div class="grid-2-3">
  {{-- Priority tasks --}}
  <div class="card">
    <div class="card-title">
      Tâches prioritaires
      <a class="card-link" href="{{ route('tasks.index') }}">Voir tout →</a>
    </div>
    @forelse($priorityTasks as $task)
      <div class="task-item" id="task-row-{{ $task->id }}">
        <div class="task-prio tp-{{ $task->priority[0] }}"></div>
        <div class="task-check {{ $task->is_done ? 'done' : '' }}"
             onclick="quickToggle({{ $task->id }}, this)"></div>
        <div class="task-info">
          <div class="task-name {{ $task->is_done ? 'done-text' : '' }}">{{ $task->title }}</div>
          <div class="task-meta">
            <span class="tag {{ $task->category_color }}">{{ $task->category }}</span>
            <span class="tag {{ $task->priority_color }}">{{ $task->priority_label }}</span>
          </div>
        </div>
        <div class="task-due {{ $task->isOverdue() ? 'overdue' : '' }}">
          {{ $task->due_label }}
        </div>
      </div>
    @empty
      <div style="text-align:center;padding:1.5rem;color:var(--gr400);font-size:13px">
        🎉 Toutes les tâches sont terminées !
      </div>
    @endforelse
  </div>

  <div class="gap-col">
    {{-- Sales chart --}}
    <div class="card">
      <div class="chart-header">
        <div style="font-size:13.5px;font-weight:600;color:var(--g900)">Ventes</div>
        <div class="chart-tabs">
          <button class="chart-tab active" onclick="setChartPeriod('week',this)">7j</button>
          <button class="chart-tab" onclick="setChartPeriod('month',this)">Mois</button>
        </div>
      </div>
      <div class="bar-chart" id="dash-chart"></div>
      <div class="bar-labels" id="dash-chart-labels"></div>
    </div>

    {{-- Top farms --}}
    <div class="card">
      <div class="card-title">Top fermes partenaires</div>
      @php $maxRev = $topFarms->max('total_revenue') ?: 1; @endphp
      @foreach($topFarms as $farm)
        <div class="farm-item">
          <div class="farm-avatar" style="background:var(--g50)">{{ $farm->emoji }}</div>
          <div class="farm-info">
            <div class="farm-name">{{ $farm->name }}</div>
            <div class="farm-loc">{{ $farm->city }} · {{ $farm->products->first()?->category ?? 'Bio' }}</div>
            <div class="farm-bar">
              <div class="farm-bar-fill"
                   style="width:{{ round($farm->total_revenue / $maxRev * 100) }}%"></div>
            </div>
          </div>
          <div class="farm-sales">{{ number_format($farm->total_revenue, 0, ',', ' ') }} MAD</div>
        </div>
      @endforeach
    </div>
  </div>
</div>

{{-- Recent orders --}}
<div class="card">
  <div class="card-title">
    Commandes récentes
    <a class="card-link" href="{{ route('orders.index') }}">Voir tout →</a>
  </div>
  <div class="orders-table-wrap">
    <table class="orders-table">
      <thead>
        <tr>
          <th>N° Commande</th><th>Client</th><th>Détails</th><th>Montant</th><th>Statut</th>
        </tr>
      </thead>
      <tbody>
        @foreach($recentOrders as $order)
          <tr>
            <td class="order-id">#{{ $order->order_number }}</td>
            <td>
              <div class="client-cell">
                <div class="client-initial">{{ substr($order->client_name, 0, 1) }}</div>
                {{ $order->client_name }}
              </div>
            </td>
            <td style="color:var(--gr400);font-size:12px">
              {{ $order->items->count() }} produits · {{ $order->delivery_mode }}
            </td>
            <td style="font-weight:600;color:var(--g800)">
              {{ number_format($order->total_amount, 0, ',', ' ') }} MAD
            </td>
            <td><span class="ostatus {{ $order->status_class }}">{{ $order->status }}</span></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
const weeklyData  = @json($weeklyData);
const weekDays    = weeklyData.map(d => d.label);
const weekValues  = weeklyData.map(d => d.total);

// Fetch monthly on demand
let monthlyData = null;

function setChartPeriod(period, btn) {
    document.querySelectorAll('.chart-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    if (period === 'week') {
        renderBarChart('dash-chart', 'dash-chart-labels', weekValues, weekDays);
    } else {
        if (monthlyData) {
            renderBarChart('dash-chart', 'dash-chart-labels', monthlyData.map(d => d.total), monthlyData.map(d => d.label));
        } else {
            fetch('/api/analytics/monthly').then(r => r.json()).then(data => {
                monthlyData = data;
                renderBarChart('dash-chart', 'dash-chart-labels', data.map(d => d.total), data.map(d => d.label));
            });
        }
    }
}

// Quick toggle task done/undone
async function quickToggle(id, el) {
    const isDone = el.classList.contains('done');
    try {
        await apiRequest('PATCH', `/tasks/${id}`, { is_done: !isDone });
        el.classList.toggle('done');
        const nameEl = el.nextElementSibling?.querySelector('.task-name');
        if (nameEl) nameEl.classList.toggle('done-text', !isDone);
        showToast(!isDone ? '✅ Tâche complétée !' : '↩ Tâche réouverte');
    } catch (e) { showToast('❌ ' + e.message); }
}

function openTaskModal() {
    openModal('Nouvelle tâche', `
      <div class="form-group"><label class="form-label">Titre <span class="required">*</span></label><input class="form-input" id="f-title" placeholder="Ex: Mettre à jour le catalogue..."></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Priorité</label><select class="form-select" id="f-prio"><option value="high">🔴 Haute</option><option value="med" selected>🟡 Moyenne</option><option value="low">🟢 Basse</option></select></div>
        <div class="form-group"><label class="form-label">Catégorie</label><select class="form-select" id="f-cat"><option>Stock</option><option>Livraison</option><option>Dev</option><option>Commercial</option><option>Rapport</option><option>Qualité</option><option>Légal</option></select></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Échéance</label><input class="form-input" type="date" id="f-due"></div>
        <div class="form-group"><label class="form-label">Assigné à</label>
          <select class="form-select" id="f-who">
            @foreach(\App\Models\User::all() as $u)
              <option value="{{ $u->id }}">{{ $u->initials }} — {{ $u->name }}</option>
            @endforeach
          </select>
        </div>
      </div>`, async () => {
        const title = document.getElementById('f-title')?.value.trim();
        if (!title) { showToast('⚠️ Titre requis'); return; }
        try {
            await apiRequest('POST', '/tasks', {
                title, category: document.getElementById('f-cat').value,
                priority: document.getElementById('f-prio').value,
                due_date: document.getElementById('f-due').value || null,
                assigned_to: document.getElementById('f-who').value || null,
            });
            closeModal(); showToast('✅ Tâche créée');
            setTimeout(() => location.reload(), 800);
        } catch (e) { showToast('❌ ' + e.message); }
    });
}

function openProductModal() {
    openModal('Ajouter un produit', `
      <div class="form-group"><label class="form-label">Nom <span class="required">*</span></label><input class="form-input" id="f-pname" placeholder="Ex: Huile d'olive BIO..."></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Prix (MAD) <span class="required">*</span></label><input class="form-input" type="number" id="f-price" min="1"></div>
        <div class="form-group"><label class="form-label">Unité</label><select class="form-select" id="f-unit"><option>kg</option><option>pièce</option><option>500ml</option><option>250g</option><option>100ml</option><option>litre</option></select></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Catégorie</label><select class="form-select" id="f-pcat"><option>Légumes</option><option>Fruits</option><option>Huiles & Épices</option><option>Miel & Confiture</option><option>Céréales</option></select></div>
        <div class="form-group"><label class="form-label">Stock</label><select class="form-select" id="f-pstock"><option value="ok">En stock</option><option value="low">Stock bas</option><option value="out">Rupture</option></select></div>
      </div>
      <div class="form-group"><label class="form-label">Ferme partenaire</label>
        <select class="form-select" id="f-pfarm">
          @foreach(\App\Models\Farm::where('is_active',true)->get() as $farm)
            <option value="{{ $farm->id }}">{{ $farm->name }}</option>
          @endforeach
        </select>
      </div>`, async () => {
        const name  = document.getElementById('f-pname')?.value.trim();
        const price = parseFloat(document.getElementById('f-price')?.value);
        if (!name || !price) { showToast('⚠️ Nom et prix requis'); return; }
        try {
            await apiRequest('POST', '/products', {
                farm_id: document.getElementById('f-pfarm').value,
                name, price, unit: document.getElementById('f-unit').value,
                category: document.getElementById('f-pcat').value,
                stock_status: document.getElementById('f-pstock').value,
                emoji: '🌿', bg_class: 'bg1',
            });
            closeModal(); showToast('✅ Produit ajouté');
        } catch (e) { showToast('❌ ' + e.message); }
    });
}

// Init chart
renderBarChart('dash-chart', 'dash-chart-labels', weekValues, weekDays);
</script>
@endpush
