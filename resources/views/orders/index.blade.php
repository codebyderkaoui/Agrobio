{{-- ═══════════════════════════════════
     resources/views/orders/index.blade.php
═══════════════════════════════════ --}}
@extends('layouts.app')
@section('title', 'Commandes')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Gestion des Commandes</div>
    <div class="page-subtitle">{{ array_sum($stats) }} commandes · {{ $stats['Nouveau'] }} nouvelles</div>
  </div>
  <button class="btn btn-primary" onclick="openNewOrderModal()">+ Nouvelle commande</button>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-top"><div class="stat-icon si-t"></div></div>
    <div class="stat-value" id="cnt-new">{{ $stats['Nouveau'] }}</div>
    <div class="stat-label">Nouvelles</div>
  </div>
  <div class="stat-card">
    <div class="stat-top"><div class="stat-icon si-a"></div></div>
    <div class="stat-value" id="cnt-prog">{{ $stats['En cours'] }}</div>
    <div class="stat-label">En livraison</div>
  </div>
  <div class="stat-card">
    <div class="stat-top"><div class="stat-icon si-g"><span class="stat-trend trend-up">+5</span></div></div>
    <div class="stat-value" id="cnt-done">{{ $stats['Livré'] }}</div>
    <div class="stat-label">Livrées</div>
  </div>
  <div class="stat-card">
    <div class="stat-top"><div class="stat-icon si-c"></div></div>
    <div class="stat-value" id="cnt-cancel">{{ $stats['Annulé'] }}</div>
    <div class="stat-label">Annulées</div>
  </div>
</div>

<div class="card">
  <div class="card-title">
    Toutes les commandes
    <div style="display:flex;gap:8px;flex-wrap:wrap">
      <input class="search-input" type="text" id="order-search"
             placeholder="Rechercher..." oninput="loadOrders()"
             style="min-width:160px;font-size:12px;padding:6px 11px">
      <select class="select-filter" id="order-status-filter"
              onchange="loadOrders()" style="font-size:12px;padding:6px 11px">
        <option value="">Tous statuts</option>
        <option value="Nouveau">Nouveau</option>
        <option value="En cours">En cours</option>
        <option value="Livré">Livré</option>
        <option value="Annulé">Annulé</option>
      </select>
    </div>
  </div>
  <div class="orders-table-wrap">
    <table class="orders-table" id="orders-table"></table>
  </div>
</div>
@endsection

@push('scripts')
<script>
async function loadOrders() {
    const search = document.getElementById('order-search')?.value || '';
    const status = document.getElementById('order-status-filter')?.value || '';
    let url = '/orders?per_page=50';
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (status) url += `&status=${encodeURIComponent(status)}`;
    const data   = await apiRequest('GET', url);
    renderOrders(data.data);
}

function renderOrders(orders) {
    const t = document.getElementById('orders-table');
    if (!orders.length) {
        t.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--gr400)">Aucune commande trouvée</td></tr>`;
        return;
    }
    const statusClass = s => s==='Nouveau'?'os-new':s==='En cours'?'os-progress':s==='Livré'?'os-done':'os-cancel';
    t.innerHTML = `<thead><tr><th>N°</th><th>Client</th><th>Articles</th><th>Mode</th><th>Date</th><th>Montant</th><th>Statut</th><th>Actions</th></tr></thead><tbody>`
        + orders.map(o => `<tr>
          <td class="order-id">#${o.order_number}</td>
          <td><div class="client-cell"><div class="client-initial">${o.client_name.charAt(0)}</div><span style="font-weight:500">${o.client_name}</span></div></td>
          <td style="color:var(--gr400)">${o.items?.length || 0} articles</td>
          <td><span class="tag gray">${o.delivery_mode}</span></td>
          <td style="color:var(--gr400);font-size:12px">${new Date(o.created_at).toLocaleDateString('fr-FR')}</td>
          <td style="font-weight:600;color:var(--g800)">${Number(o.total_amount).toLocaleString('fr-FR')} MAD</td>
          <td><span class="ostatus ${statusClass(o.status)}">${o.status}</span></td>
          <td><div class="order-actions">
            ${o.status !== 'Livré' && o.status !== 'Annulé'
              ? `<button class="btn btn-sm btn-outline" onclick="advanceOrder('${o.id}')">Avancer →</button>`
              : `<button class="btn btn-sm btn-ghost" style="opacity:.5;cursor:default">Terminé</button>`}
          </div></td>
        </tr>`).join('') + '</tbody>';
}

async function advanceOrder(id) {
    try {
        await apiRequest('PATCH', `/orders/${id}/advance`);
        showToast('📦 Commande avancée');
        loadOrders();
    } catch (e) { showToast('❌ ' + e.message); }
}

function openNewOrderModal() {
    openModal('Nouvelle commande', `
      <div class="form-group"><label class="form-label">Client <span class="required">*</span></label><input class="form-input" id="f-oclient" placeholder="Nom du client ou entreprise"></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Mode livraison</label><select class="form-select" id="f-omode"><option>Standard</option><option>Express</option><option>Retrait</option></select></div>
        <div class="form-group"><label class="form-label">Montant total (MAD)</label><input class="form-input" type="number" id="f-oamt" placeholder="1500" min="0"></div>
      </div>
      <div class="form-group"><label class="form-label">Notes</label><textarea class="form-textarea" id="f-onotes" placeholder="Informations supplémentaires..."></textarea></div>
      <div class="form-hint">Le numéro de commande sera généré automatiquement.</div>`,
    async () => {
        const client = document.getElementById('f-oclient')?.value.trim();
        if (!client) { showToast('⚠️ Nom du client requis'); return; }
        try {
            await apiRequest('POST', '/orders', {
                client_name:   client,
                delivery_mode: document.getElementById('f-omode').value,
                total_amount:  parseFloat(document.getElementById('f-oamt').value) || 0,
                notes:         document.getElementById('f-onotes').value || null,
            });
            closeModal();
            showToast('✅ Commande créée');
            loadOrders();
        } catch (e) { showToast('❌ ' + e.message); }
    });
}

loadOrders();
</script>
@endpush
