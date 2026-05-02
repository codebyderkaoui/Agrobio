@extends('layouts.app')
@section('title', 'Projets')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Projets</div>
    <div class="page-subtitle">{{ \App\Models\Project::where('status','Actif')->count() }} projets actifs · Sprint {{ now()->translatedFormat('F Y') }}</div>
  </div>
  <button class="btn btn-primary" onclick="openNewProjectModal()">+ Nouveau projet</button>
</div>

<div class="grid-2" id="projects-grid"></div>
@endsection

@push('scripts')
<script>
async function loadProjects() {
    const projects = await apiRequest('GET', '/projects');
    renderProjects(projects);
}

function renderProjects(projects) {
    const g = document.getElementById('projects-grid');
    if (!projects.length) {
        g.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><span class="empty-state-icon">📁</span><div class="empty-state-title">Aucun projet</div></div>`;
        return;
    }
    g.innerHTML = projects.map(p => {
        const pctClass = p.status_class === 'green' ? 'proj-pct-g' : p.status_class === 'amber' ? 'proj-pct-a' : 'proj-pct-t';
        return `<div class="proj-card">
          <div class="proj-header">
            <div class="proj-name">${p.name}</div>
            <span class="tag ${p.status_class}">${p.status}</span>
          </div>
          <div class="proj-desc">${p.description || ''}</div>
          <div class="proj-progress-header">
            <span style="color:var(--gr400)">Progression</span>
            <span class="${pctClass}">${p.progress}%</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill ${p.progress_fill_class}" style="width:${p.progress}%"></div>
          </div>
          <div class="proj-meta">
            <div style="display:flex">
              ${p.team.map((m, i) => `<div class="av-sm" title="${m.name}" style="${i > 0 ? 'margin-left:-6px' : ''}">${m.initials}</div>`).join('')}
            </div>
            <div class="proj-date">📅 ${p.deadline_label || '—'}</div>
          </div>
        </div>`;
    }).join('');
}

function openNewProjectModal() {
    openModal('Nouveau projet', `
      <div class="form-group"><label class="form-label">Nom du projet <span class="required">*</span></label><input class="form-input" id="f-projname" placeholder="Ex: Module paiement en ligne..."></div>
      <div class="form-group"><label class="form-label">Description</label><textarea class="form-textarea" id="f-projdesc" placeholder="Objectifs, technologies, livrables..."></textarea></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Statut</label><select class="form-select" id="f-projstatus"><option>Actif</option><option>Planifié</option><option>En pause</option></select></div>
        <div class="form-group"><label class="form-label">Date de fin</label><input class="form-input" type="date" id="f-projdate"></div>
      </div>
      <div class="form-group"><label class="form-label">Membres de l'équipe</label>
        <select class="form-select" id="f-projteam" multiple style="min-height:90px">
          @foreach($users as $u)
            <option value="{{ $u->id }}">{{ $u->initials }} — {{ $u->name }}</option>
          @endforeach
        </select>
        <div class="form-hint">Maintenez Ctrl pour sélectionner plusieurs membres.</div>
      </div>`,
    async () => {
        const name = document.getElementById('f-projname')?.value.trim();
        if (!name) { showToast('⚠️ Nom du projet requis'); return; }
        const sel     = document.getElementById('f-projteam');
        const members = Array.from(sel.selectedOptions).map(o => parseInt(o.value));
        try {
            await apiRequest('POST', '/projects', {
                name,
                description: document.getElementById('f-projdesc').value || null,
                status:      document.getElementById('f-projstatus').value,
                deadline:    document.getElementById('f-projdate').value || null,
                member_ids:  members,
            });
            closeModal();
            showToast('✅ Projet créé');
            loadProjects();
        } catch (e) { showToast('❌ ' + e.message); }
    });
}

loadProjects();
</script>
@endpush
