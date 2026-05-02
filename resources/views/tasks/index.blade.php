@extends('layouts.app')
@section('title', 'Gestion des Tâches')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Gestion des Tâches</div>
    <div class="page-subtitle">Kanban Board — Sprint {{ now()->translatedFormat('F Y') }}</div>
  </div>
  <div class="btn-group">
    <input class="search-input" type="text" id="task-search"
           placeholder="Filtrer tâches..." oninput="filterKanban(this.value)"
           style="min-width:160px">
    <button class="btn btn-primary" onclick="openNewTaskModal()">+ Nouvelle tâche</button>
  </div>
</div>

<div class="kanban-wrap">
  <div class="kanban" id="kanban-board"></div>
</div>
@endsection

@push('scripts')
<script>
const COLS      = { todo: 'À faire', inprog: 'En cours', review: 'En révision', done: 'Terminé' };
let allTasks    = {};
let dragTaskId  = null;

async function loadKanban(filter = '') {
    const url = filter ? `/tasks?search=${encodeURIComponent(filter)}` : '/tasks';
    allTasks  = await apiRequest('GET', url);
    renderKanban();
}

function renderKanban() {
    const board = document.getElementById('kanban-board');
    board.innerHTML = Object.entries(COLS).map(([col, label]) => {
        const tasks = allTasks[col] || [];
        return `<div class="kanban-col kc-${col}${col === 'done' ? ' kc-done' : ''}"
                     data-col="${col}"
                     ondragover="dragOver(event)"
                     ondrop="drop(event,'${col}')"
                     ondragenter="this.classList.add('drag-target')"
                     ondragleave="this.classList.remove('drag-target')">
          <div class="kanban-col-header">
            <div class="kch-left">
              <div class="kc-dot"></div>
              <div class="kanban-col-title">${label}</div>
            </div>
            <div class="kanban-count">${tasks.length}</div>
          </div>
          ${tasks.map(t => `
            <div class="kcard${t.is_done ? ' kc-done-card' : ''}"
                 draggable="true"
                 data-tid="${t.id}"
                 ondragstart="dragStart(event,${t.id})"
                 ondragend="this.classList.remove('dragging')">
              <div class="kcard-title">${t.title}</div>
              <div class="kcard-meta">
                <span class="tag ${t.category_color}">${t.category}</span>
                <span class="tag ${t.priority_color}">${t.priority_label}</span>
              </div>
              <div class="kcard-footer">
                <div class="av-sm" title="${t.assignee?.name || 'Non assigné'}">${t.assignee?.initials || '?'}</div>
                <div class="kcard-due${t.is_overdue ? ' overdue' : ''}">${t.due_label || '—'}</div>
              </div>
            </div>`).join('')}
          <button class="col-add-btn" onclick="openNewTaskModal('${col}')">
            + Ajouter
          </button>
        </div>`;
    }).join('');
}

function filterKanban(v) { loadKanban(v); }

function dragStart(e, tid) {
    dragTaskId = tid;
    setTimeout(() => e.currentTarget.classList.add('dragging'), 0);
}
function dragOver(e) { e.preventDefault(); }

async function drop(e, col) {
    e.preventDefault();
    document.querySelectorAll('.kanban-col').forEach(c => c.classList.remove('drag-target'));
    if (!dragTaskId) return;
    try {
        await apiRequest('PATCH', `/tasks/${dragTaskId}`, { column: col });
        showToast(`📋 Déplacé → ${COLS[col]}`);
        loadKanban();
    } catch (err) { showToast('❌ ' + err.message); }
    dragTaskId = null;
}

function openNewTaskModal(col = 'todo') {
    openModal('Nouvelle tâche', `
      <div class="form-group"><label class="form-label">Titre <span class="required">*</span></label><input class="form-input" id="f-title" placeholder="Ex: Mettre à jour le stock..."></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Priorité</label><select class="form-select" id="f-prio"><option value="high">🔴 Haute</option><option value="med" selected>🟡 Moyenne</option><option value="low">🟢 Basse</option></select></div>
        <div class="form-group"><label class="form-label">Catégorie</label><select class="form-select" id="f-cat"><option>Stock</option><option>Livraison</option><option>Dev</option><option>Commercial</option><option>Rapport</option><option>Qualité</option><option>Légal</option><option>DevOps</option><option>Design</option><option>QA</option></select></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Échéance</label><input class="form-input" type="date" id="f-due"></div>
        <div class="form-group"><label class="form-label">Assigné à</label>
          <select class="form-select" id="f-who">
            @foreach($users as $u)
              <option value="{{ $u->id }}">{{ $u->initials }} — {{ $u->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group"><label class="form-label">Description</label><textarea class="form-textarea" id="f-desc" placeholder="Détails..."></textarea></div>
      <div class="form-group"><label class="form-label">Projet</label>
        <select class="form-select" id="f-proj">
          <option value="">— Aucun projet —</option>
          @foreach($projects as $proj)
            <option value="{{ $proj->id }}">{{ $proj->name }}</option>
          @endforeach
        </select>
      </div>
      <input type="hidden" id="f-col" value="${col}">`,
    async () => {
        const title = document.getElementById('f-title')?.value.trim();
        if (!title) { showToast('⚠️ Titre requis'); return; }
        try {
            await apiRequest('POST', '/tasks', {
                title,
                category:    document.getElementById('f-cat').value,
                priority:    document.getElementById('f-prio').value,
                column:      document.getElementById('f-col').value,
                due_date:    document.getElementById('f-due').value || null,
                assigned_to: document.getElementById('f-who').value || null,
                project_id:  document.getElementById('f-proj').value || null,
                description: document.getElementById('f-desc').value || null,
            });
            closeModal();
            showToast('✅ Tâche créée');
            loadKanban();
        } catch (e) { showToast('❌ ' + e.message); }
    });
}

loadKanban();
</script>
@endpush
