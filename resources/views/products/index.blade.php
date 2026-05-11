{{-- ═══════════════════════════════════
     resources/views/products/index.blade.php
═══════════════════════════════════ --}}
@extends('layouts.app')
@section('title', 'Catalogue Produits')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Catalogue Produits Bio</div>
    <div class="page-subtitle" id="prod-subtitle">{{ \App\Models\Product::active()->count() }} produits certifiés · {{ \App\Models\Farm::where('is_active',true)->count() }} fermes partenaires</div>
  </div>
  <div class="btn-group">
    <button class="btn btn-ghost btn-sm" id="view-toggle" onclick="toggleView()">☰ Liste</button>
    <button class="btn btn-primary" onclick="openAddProductModal()">+ Ajouter produit</button>
  </div>
</div>

<div class="product-controls">
  <input class="search-input" type="text" id="prod-search" placeholder="🔍 Rechercher un produit, une ferme..." oninput="filterProducts()">
  <select class="select-filter" id="prod-cat" onchange="filterProducts()">
    <option value="">Toutes catégories</option>
    @foreach($categories as $cat)
      <option>{{ $cat }}</option>
    @endforeach
  </select>
  <select class="select-filter" id="prod-stock" onchange="filterProducts()">
    <option value="">Tout stock</option>
    <option value="ok">✅ En stock</option>
    <option value="low">⚠️ Stock bas</option>
    <option value="out">❌ Rupture</option>
  </select>
  <select class="select-filter" id="prod-sort" onchange="filterProducts()">
    <option value="name">Trier: Nom</option>
    <option value="price-asc">Prix croissant</option>
    <option value="price-desc">Prix décroissant</option>
  </select>
</div>

<div class="product-grid" id="product-grid"></div>
@endsection

@push('scripts')
<script>
let allProducts = [];
let gridView    = 'grid';

async function loadProducts() {
    const data = await apiRequest('GET', '/products?per_page=100');
    allProducts         = data.data;
    window._products    = allProducts;
    renderProducts();
}

function toggleView() {
    gridView = gridView === 'grid' ? 'list' : 'grid';
    document.getElementById('view-toggle').textContent = gridView === 'grid' ? '☰ Liste' : '⊞ Grille';
    renderProducts();
}

function filterProducts() { renderProducts(); }

function renderProducts() {
    const search = (document.getElementById('prod-search')?.value || '').toLowerCase();
    const cat    = document.getElementById('prod-cat')?.value || '';
    const stock  = document.getElementById('prod-stock')?.value || '';
    const sort   = document.getElementById('prod-sort')?.value || 'name';

    let prods = allProducts.filter(p => {
        if (search && !p.name.toLowerCase().includes(search) && !(p.farm?.name || '').toLowerCase().includes(search)) return false;
        if (cat   && p.category    !== cat)   return false;
        if (stock && p.stock_status !== stock) return false;
        return true;
    });

    if (sort === 'price-asc')  prods.sort((a, b) => a.price - b.price);
    else if (sort === 'price-desc') prods.sort((a, b) => b.price - a.price);
    else prods.sort((a, b) => a.name.localeCompare(b.name, 'fr'));

    const sub = document.getElementById('prod-subtitle');
    if (sub) sub.textContent = `${prods.length} produit${prods.length !== 1 ? 's' : ''} · ${new Set(prods.map(p => p.farm_id)).size} fermes`;

    const el = document.getElementById('product-grid');
    el.className = 'product-grid' + (gridView === 'list' ? ' list-view' : '');

    if (!prods.length) {
        el.innerHTML = `<div class="empty-state"><span class="empty-state-icon">🔍</span><div class="empty-state-title">Aucun produit trouvé</div><div class="empty-state-sub">Essayez d'autres critères</div></div>`;
        return;
    }

    el.innerHTML = prods.map(p => {
        const stockLabel = p.stock_status === 'ok' ? 'En stock' : p.stock_status === 'low' ? 'Stock bas' : 'Rupture';
        const stockClass = p.stock_status === 'ok' ? 'sb-ok'    : p.stock_status === 'low' ? 'sb-low'    : 'sb-out';
        return `<div class="product-card">
                  <div class="product-img ${p.bg_class}">
                    ${p.emoji}
                    <div class="stock-badge ${stockClass}">${stockLabel}</div>
                  </div>
                  <div class="product-body">
                    <div class="product-name">${p.name}</div>
                    <div class="product-farm">${p.farm?.name || ''} · ${p.farm?.city || 'Maroc'}</div>
                    <div class="product-footer">
                      <div>
                        <div class="product-price">${p.price} MAD</div>
                        <div class="product-unit">/ ${p.unit}</div>
                      </div>
                    </div>
                  </div>
                </div>`;
    }).join('');
}

function openAddProductModal() {
    openModal('Ajouter un produit', `
      <div class="form-group"><label class="form-label">Nom <span class="required">*</span></label><input class="form-input" id="f-pname" placeholder="Ex: Huile d\'olive BIO..."></div>
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
          @foreach($farms as $farm)
            <option value="{{ $farm->id }}">{{ $farm->name }}</option>
          @endforeach
        </select>
      </div>`, async () => {
        const name  = document.getElementById('f-pname')?.value.trim();
        const price = parseFloat(document.getElementById('f-price')?.value);
        if (!name || !price) { showToast('⚠️ Nom et prix requis'); return; }
        try {
            await apiRequest('POST', '/products', {
                farm_id: parseInt(document.getElementById('f-pfarm').value),
                name, price,
                unit:         document.getElementById('f-unit').value,
                category:     document.getElementById('f-pcat').value,
                stock_status: document.getElementById('f-pstock').value,
                emoji: '🌿', bg_class: 'bg' + (Math.floor(Math.random() * 8) + 1),
            });
            closeModal();
            showToast('✅ Produit ajouté au catalogue');
            loadProducts();
        } catch (e) { showToast('❌ ' + e.message); }
    });
}

loadProducts();
</script>
@endpush
