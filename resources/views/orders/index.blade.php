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
  <button class="btn btn-primary" onclick="openNewOrderModal()">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Nouvelle commande
  </button>
</div>

{{-- KPI stats --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon si-t">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--t600)" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      </div>
    </div>
    <div class="stat-value" id="cnt-new">{{ $stats['Nouveau'] }}</div>
    <div class="stat-label">Nouvelles</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon si-a">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--a600)" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
    </div>
    <div class="stat-value" id="cnt-prog">{{ $stats['En cours'] }}</div>
    <div class="stat-label">En livraison</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon si-g">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--g600)" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <span class="stat-trend trend-up">+5</span>
    </div>
    <div class="stat-value" id="cnt-done">{{ $stats['Livré'] }}</div>
    <div class="stat-label">Livrées</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon si-c">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--c400)" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
      </div>
    </div>
    <div class="stat-value" id="cnt-cancel">{{ $stats['Annulé'] }}</div>
    <div class="stat-label">Annulées</div>
  </div>
</div>

{{-- Orders table --}}
<div class="card">
  <div class="card-title">
    Toutes les commandes
    <div style="display:flex;gap:8px;flex-wrap:wrap">
      <input class="search-input" type="text" id="order-search"
             placeholder="Rechercher client, numéro..." oninput="loadOrders()"
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
    <table class="orders-table" id="orders-table">
      <thead>
        <tr>
          <th>N° Commande</th>
          <th>Client</th>
          <th>Produits</th>
          <th>Mode</th>
          <th>Date</th>
          <th>Montant</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="orders-tbody">
        <tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--gr400)">Chargement...</td></tr>
      </tbody>
    </table>
  </div>
</div>

{{-- ═══════════ NEW ORDER MODAL ═══════════ --}}
<div class="modal-overlay" id="order-modal" onclick="closeOrderModal(event)">
  <div class="modal" style="max-width:620px" onclick="event.stopPropagation()">
    <div class="modal-header">
      <div class="modal-title">Nouvelle commande</div>
      <button class="modal-close" onclick="closeOrderModal()">✕</button>
    </div>
    <div class="modal-body" style="max-height:70vh;overflow-y:auto">

      {{-- Client info --}}
      <div style="background:var(--gr50);border-radius:10px;padding:1rem;margin-bottom:1.25rem;border:1px solid var(--border)">
        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:var(--gr400);margin-bottom:.75rem">Informations client</div>
        <div class="form-row">
          <div class="form-group" style="margin-bottom:0">
            <label class="form-label">Nom du client <span class="required">*</span></label>
            <input class="form-input" id="o-client" placeholder="Ex: Marché Bio Casablanca">
          </div>
          <div class="form-group" style="margin-bottom:0">
            <label class="form-label">Mode de livraison</label>
            <select class="form-select" id="o-mode">
              <option>Standard</option>
              <option>Express</option>
              <option>Retrait</option>
            </select>
          </div>
        </div>
      </div>

      {{-- Product selector --}}
      <div style="margin-bottom:1.25rem">
        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:var(--gr400);margin-bottom:.75rem">Produits commandés</div>

        {{-- Search + add product --}}
        <div style="display:flex;gap:8px;margin-bottom:.75rem">
          <div style="flex:1;position:relative">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--gr400);pointer-events:none" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input class="form-input" id="o-prod-search" placeholder="Rechercher un produit..."
                   style="padding-left:32px" oninput="searchProducts(this.value)" autocomplete="off">
            <div id="o-prod-dropdown" style="position:absolute;top:calc(100% + 4px);left:0;right:0;background:var(--white);border:1px solid var(--border2);border-radius:var(--radius);box-shadow:var(--shadow-lg);z-index:600;display:none;max-height:220px;overflow-y:auto"></div>
          </div>
        </div>

        {{-- Selected products list --}}
        <div id="o-items-list" style="display:flex;flex-direction:column;gap:6px;min-height:40px">
          <div id="o-items-empty" style="text-align:center;padding:1.5rem;color:var(--gr400);font-size:13px;border:1.5px dashed var(--border);border-radius:8px">
            🌿 Aucun produit sélectionné — recherchez ci-dessus
          </div>
        </div>
      </div>

      {{-- Total --}}
      <div style="background:var(--g50);border-radius:10px;padding:1rem;border:1px solid var(--g100)">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
          <span style="font-size:12px;color:var(--gr400)">Sous-total produits</span>
          <span style="font-size:13px;font-weight:500;color:var(--text)" id="o-subtotal">0 MAD</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
          <span style="font-size:12px;color:var(--gr400)">Livraison</span>
          <span style="font-size:12px;color:var(--t600)">Gratuit</span>
        </div>
        <div style="height:1px;background:var(--g100);margin:.5rem 0"></div>
        <div style="display:flex;justify-content:space-between;align-items:center">
          <span style="font-size:14px;font-weight:600;color:var(--g900)">Total à payer</span>
          <span style="font-size:22px;font-weight:600;color:var(--g800)" id="o-total">0 MAD</span>
        </div>
      </div>

      {{-- Notes --}}
      <div class="form-group" style="margin-top:1rem;margin-bottom:0">
        <label class="form-label">Notes internes</label>
        <textarea class="form-textarea" id="o-notes" placeholder="Informations supplémentaires..." style="min-height:60px"></textarea>
      </div>

    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeOrderModal()">Annuler</button>
      <button class="btn btn-primary" onclick="submitOrder()">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        Créer la commande
      </button>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
// ── State ─────────────────────────────────────────────────────────────────
let allProductsCache = [];   // full catalogue
let orderItems       = {};   // { product_id: { product, quantity } }

// ── Load orders table ─────────────────────────────────────────────────────
async function loadOrders() {
    const search = document.getElementById('order-search')?.value || '';
    const status = document.getElementById('order-status-filter')?.value || '';
    let url = '/orders?per_page=50';
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (status) url += `&status=${encodeURIComponent(status)}`;

    try {
        const data = await apiRequest('GET', url);
        renderOrders(data.data);
    } catch (e) { showToast('❌ ' + e.message); }
}

function renderOrders(orders) {
    const tbody = document.getElementById('orders-tbody');
    if (!orders.length) {
        tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--gr400)">Aucune commande trouvée</td></tr>`;
        return;
    }
    const sc = s => s==='Nouveau'?'os-new':s==='En cours'?'os-progress':s==='Livré'?'os-done':'os-cancel';
    tbody.innerHTML = orders.map(o => `<tr>
        <td class="order-id">#${o.order_number}</td>
        <td>
          <div class="client-cell">
            <div class="client-initial">${o.client_name.charAt(0).toUpperCase()}</div>
            <span style="font-weight:500">${o.client_name}</span>
          </div>
        </td>
        <td style="color:var(--gr400)">${o.items?.length || 0} article${(o.items?.length || 0) !== 1 ? 's' : ''}</td>
        <td><span class="tag gray">${o.delivery_mode}</span></td>
        <td style="color:var(--gr400);font-size:12px">${new Date(o.created_at).toLocaleDateString('fr-FR')}</td>
        <td style="font-weight:600;color:var(--g800)">${Number(o.total_amount).toLocaleString('fr-FR')} MAD</td>
        <td><span class="ostatus ${sc(o.status)}">${o.status}</span></td>
        <td>
          <div class="order-actions">
            ${o.status !== 'Livré' && o.status !== 'Annulé'
              ? `<button class="btn btn-sm btn-outline" onclick="advanceOrder(${o.id})">Avancer →</button>`
              : `<span style="font-size:12px;color:var(--gr400)">—</span>`}
          </div>
        </td>
    </tr>`).join('');
}

async function advanceOrder(id) {
    try {
        await apiRequest('PATCH', `/orders/${id}/advance`);
        showToast('📦 Commande avancée');
        loadOrders();
    } catch (e) { showToast('❌ ' + e.message); }
}

// ── New order modal ───────────────────────────────────────────────────────
async function openNewOrderModal() {
    // Reset state
    orderItems = {};
    document.getElementById('o-client').value  = '';
    document.getElementById('o-notes').value   = '';
    document.getElementById('o-prod-search').value = '';
    document.getElementById('o-prod-dropdown').style.display = 'none';
    renderOrderItems();

    // Load products catalogue if not cached
    if (!allProductsCache.length) {
        try {
            const data = await apiRequest('GET', '/products?per_page=200');
            allProductsCache = data.data.filter(p => p.stock_status !== 'out');
        } catch (e) { showToast('❌ Impossible de charger les produits'); return; }
    }

    document.getElementById('order-modal').classList.add('open');
    setTimeout(() => document.getElementById('o-client').focus(), 100);
}

function closeOrderModal(e) {
    if (e && e.target !== document.getElementById('order-modal')) return;
    document.getElementById('order-modal').classList.remove('open');
    document.getElementById('o-prod-dropdown').style.display = 'none';
}

// ── Product search dropdown ───────────────────────────────────────────────
function searchProducts(v) {
    const dd = document.getElementById('o-prod-dropdown');
    if (v.length < 1) { dd.style.display = 'none'; return; }

    const results = allProductsCache
        .filter(p => p.name.toLowerCase().includes(v.toLowerCase()) ||
                     (p.farm?.name || '').toLowerCase().includes(v.toLowerCase()))
        .slice(0, 8);

    if (!results.length) {
        dd.innerHTML = `<div style="padding:12px 14px;font-size:12px;color:var(--gr400)">Aucun produit trouvé</div>`;
        dd.style.display = 'block';
        return;
    }

    dd.innerHTML = results.map(p => `
        <div onclick="addOrderItem(${p.id})"
             style="display:flex;align-items:center;gap:10px;padding:9px 13px;cursor:pointer;border-bottom:1px solid var(--gr50);transition:background .12s"
             onmouseover="this.style.background='var(--gr50)'"
             onmouseout="this.style.background='transparent'">
          <div style="width:32px;height:32px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0" class="${p.bg_class}">${p.emoji}</div>
          <div style="flex:1;min-width:0">
            <div style="font-size:13px;font-weight:500;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${p.name}</div>
            <div style="font-size:11px;color:var(--gr400)">${p.farm?.name || ''} · ${p.price} MAD / ${p.unit}</div>
          </div>
          <div style="font-size:11px;font-weight:600;color:var(--g600);flex-shrink:0">${p.price} MAD</div>
        </div>`).join('');
    dd.style.display = 'block';
}

// Close dropdown when clicking outside
document.addEventListener('click', e => {
    if (!e.target.closest('#o-prod-search') && !e.target.closest('#o-prod-dropdown')) {
        document.getElementById('o-prod-dropdown').style.display = 'none';
    }
});

// ── Order items management ────────────────────────────────────────────────
function addOrderItem(productId) {
    const product = allProductsCache.find(p => p.id === productId);
    if (!product) return;

    if (orderItems[productId]) {
        orderItems[productId].quantity += 1;
    } else {
        orderItems[productId] = { product, quantity: 1 };
    }

    document.getElementById('o-prod-search').value = '';
    document.getElementById('o-prod-dropdown').style.display = 'none';
    renderOrderItems();
    showToast(`✅ ${product.name} ajouté`);
}

function changeItemQty(productId, delta) {
    if (!orderItems[productId]) return;
    const newQty = orderItems[productId].quantity + delta;
    if (newQty <= 0) {
        delete orderItems[productId];
    } else {
        orderItems[productId].quantity = newQty;
    }
    renderOrderItems();
}

function removeOrderItem(productId) {
    delete orderItems[productId];
    renderOrderItems();
}

function renderOrderItems() {
    const list  = document.getElementById('o-items-list');
    const empty = document.getElementById('o-items-empty');
    const keys  = Object.keys(orderItems);

    if (!keys.length) {
        list.innerHTML = `<div id="o-items-empty" style="text-align:center;padding:1.5rem;color:var(--gr400);font-size:13px;border:1.5px dashed var(--border);border-radius:8px">🌿 Aucun produit sélectionné — recherchez ci-dessus</div>`;
        updateOrderTotal();
        return;
    }

    list.innerHTML = keys.map(pid => {
        const { product: p, quantity: qty } = orderItems[pid];
        const subtotal = p.price * qty;
        return `<div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:var(--white);border:1px solid var(--border);border-radius:9px">
          <div style="width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0" class="${p.bg_class}">${p.emoji}</div>
          <div style="flex:1;min-width:0">
            <div style="font-size:13px;font-weight:500;color:var(--g900);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${p.name}</div>
            <div style="font-size:11px;color:var(--gr400)">${p.farm?.name || ''} · ${p.price} MAD / ${p.unit}</div>
          </div>
          <div style="display:flex;align-items:center;gap:6px;flex-shrink:0">
            <button onclick="changeItemQty(${pid},-1)" style="width:24px;height:24px;border-radius:6px;border:1.5px solid var(--border);background:var(--white);cursor:pointer;font-size:15px;display:flex;align-items:center;justify-content:center;color:var(--gr600)">−</button>
            <span style="font-size:13px;font-weight:600;min-width:20px;text-align:center">${qty}</span>
            <button onclick="changeItemQty(${pid},1)" style="width:24px;height:24px;border-radius:6px;border:1.5px solid var(--border);background:var(--white);cursor:pointer;font-size:15px;display:flex;align-items:center;justify-content:center;color:var(--gr600)">+</button>
          </div>
          <div style="font-size:13px;font-weight:600;color:var(--g800);min-width:80px;text-align:right;flex-shrink:0">${subtotal.toLocaleString('fr-FR')} MAD</div>
          <button onclick="removeOrderItem(${pid})" style="background:none;border:none;cursor:pointer;color:var(--gr400);font-size:14px;padding:4px;border-radius:5px;flex-shrink:0" onmouseover="this.style.color='var(--c400)'" onmouseout="this.style.color='var(--gr400)'">✕</button>
        </div>`;
    }).join('');

    updateOrderTotal();
}

function updateOrderTotal() {
    const total = Object.values(orderItems).reduce((sum, { product: p, quantity: q }) => sum + p.price * q, 0);
    document.getElementById('o-subtotal').textContent = total.toLocaleString('fr-FR') + ' MAD';
    document.getElementById('o-total').textContent    = total.toLocaleString('fr-FR') + ' MAD';
}

// ── Submit order ──────────────────────────────────────────────────────────
async function submitOrder() {
    const client = document.getElementById('o-client')?.value.trim();
    if (!client) { showToast('⚠️ Nom du client requis'); return; }
    if (!Object.keys(orderItems).length) { showToast('⚠️ Ajoutez au moins un produit'); return; }

    const items = Object.values(orderItems).map(({ product, quantity }) => ({
        product_id: product.id,
        quantity,
    }));

    try {
        await apiRequest('POST', '/orders', {
            client_name:   client,
            delivery_mode: document.getElementById('o-mode').value,
            notes:         document.getElementById('o-notes').value || null,
            items,
        });
        document.getElementById('order-modal').classList.remove('open');
        showToast('🎉 Commande créée avec succès !');
        orderItems = {};
        loadOrders();
    } catch (e) { showToast('❌ ' + e.message); }
}

// ── Init ──────────────────────────────────────────────────────────────────
loadOrders();
</script>
@endpush