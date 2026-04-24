<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users    = User::pluck('id', 'initials');
        $projects = Project::pluck('id', 'name');

        $ecomId  = $projects['Plateforme E-commerce BIO']    ?? null;
        $stockId = $projects['Gestion Stocks & Fermes']      ?? null;
        $cicdId  = $projects['Intégration Jenkins CI/CD']    ?? null;
        $pwaId   = $projects['App Mobile PWA']               ?? null;

        $tasks = [
            ['title' => 'Mise à jour des prix du catalogue printemps',      'category' => 'Catalogue', 'priority' => 'high', 'column' => 'todo',   'due_date' => '2026-04-22', 'assignee' => 'AB', 'project' => $ecomId,  'is_done' => false],
            ['title' => 'Livraison Épicerie Verte Rabat — vérifier adresse','category' => 'Livraison', 'priority' => 'high', 'column' => 'inprog', 'due_date' => '2026-04-21', 'assignee' => 'MA', 'project' => null,     'is_done' => false],
            ['title' => 'Implémenter module paiement Stripe',               'category' => 'Dev',       'priority' => 'med',  'column' => 'inprog', 'due_date' => '2026-04-30', 'assignee' => 'SA', 'project' => $ecomId,  'is_done' => false],
            ['title' => 'Rapport mensuel fournisseurs Q1',                  'category' => 'Rapport',   'priority' => 'med',  'column' => 'review', 'due_date' => '2026-04-25', 'assignee' => 'FZ', 'project' => null,     'is_done' => false],
            ['title' => 'Pipeline CI/CD Jenkins — déploiement prod',        'category' => 'DevOps',    'priority' => 'high', 'column' => 'review', 'due_date' => '2026-04-23', 'assignee' => 'AK', 'project' => $cicdId,  'is_done' => false],
            ['title' => 'Certification BIO — dossier Ferme Benali',         'category' => 'Légal',     'priority' => 'low',  'column' => 'todo',   'due_date' => '2026-05-15', 'assignee' => 'AB', 'project' => $stockId, 'is_done' => false],
            ['title' => 'Optimiser requêtes MySQL dashboard',               'category' => 'Dev',       'priority' => 'med',  'column' => 'todo',   'due_date' => '2026-04-28', 'assignee' => 'SA', 'project' => $ecomId,  'is_done' => false],
            ['title' => 'Design responsive page produit mobile',            'category' => 'Design',    'priority' => 'med',  'column' => 'inprog', 'due_date' => '2026-04-26', 'assignee' => 'FZ', 'project' => $pwaId,   'is_done' => false],
            ['title' => 'Tests unitaires API REST commandes',               'category' => 'QA',        'priority' => 'high', 'column' => 'todo',   'due_date' => '2026-04-24', 'assignee' => 'MA', 'project' => $ecomId,  'is_done' => false],
            ['title' => 'Audit sécurité API — tokens JWT',                  'category' => 'Dev',       'priority' => 'high', 'column' => 'todo',   'due_date' => '2026-04-22', 'assignee' => 'SA', 'project' => $ecomId,  'is_done' => false],
            ['title' => 'Formation équipe commerciale CRM',                 'category' => 'Commercial','priority' => 'low',  'column' => 'done',   'due_date' => '2026-04-10', 'assignee' => 'AB', 'project' => null,     'is_done' => true],
            ['title' => 'Négociation contrat Ferme El Mansouri',            'category' => 'Commercial','priority' => 'med',  'column' => 'done',   'due_date' => '2026-04-15', 'assignee' => 'AK', 'project' => null,     'is_done' => true],
        ];

        $creator = User::where('email', 'ahmed@agrobio.ma')->first();

        foreach ($tasks as $t) {
            Task::firstOrCreate(
                ['title' => $t['title']],
                [
                    'project_id'  => $t['project'],
                    'category'    => $t['category'],
                    'priority'    => $t['priority'],
                    'column'      => $t['column'],
                    'due_date'    => $t['due_date'],
                    'assigned_to' => $users[$t['assignee']] ?? null,
                    'created_by'  => $creator?->id,
                    'is_done'     => $t['is_done'],
                ]
            );
        }
    }
}
