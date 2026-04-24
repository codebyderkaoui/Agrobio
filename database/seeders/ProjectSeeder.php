<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id', 'initials');

        $projects = [
            [
                'name'        => 'Plateforme E-commerce BIO',
                'description' => 'Full-stack Laravel + MySQL avec API REST et architecture MVC. Gestion catalogue, commandes, paiements en ligne.',
                'status'      => 'Actif',
                'progress'    => 72,
                'deadline'    => '2026-06-30',
                'members'     => ['MA', 'SA', 'FZ', 'AK'],
            ],
            [
                'name'        => 'Gestion Stocks & Fermes',
                'description' => 'Module traçabilité, certifications BIO, gestion fournisseurs et alertes de stock temps réel.',
                'status'      => 'En pause',
                'progress'    => 45,
                'deadline'    => '2026-07-31',
                'members'     => ['AK', 'MA'],
            ],
            [
                'name'        => 'App Mobile PWA',
                'description' => 'Version progressive mobile-first, Bootstrap responsive, notifications push, mode hors-ligne.',
                'status'      => 'Planifié',
                'progress'    => 18,
                'deadline'    => '2026-09-30',
                'members'     => ['FZ', 'SA'],
            ],
            [
                'name'        => 'Intégration Jenkins CI/CD',
                'description' => 'Pipeline automatisé déploiement continu, tests unitaires, Git hooks et monitoring.',
                'status'      => 'Actif',
                'progress'    => 88,
                'deadline'    => '2026-04-25',
                'members'     => ['MA', 'AK'],
            ],
        ];

        $owner = User::where('email', 'ahmed@agrobio.ma')->first();

        foreach ($projects as $p) {
            if (Project::where('name', $p['name'])->exists()) continue;

            $project = Project::create([
                'name'        => $p['name'],
                'description' => $p['description'],
                'status'      => $p['status'],
                'progress'    => $p['progress'],
                'deadline'    => $p['deadline'],
                'owner_id'    => $owner?->id,
            ]);

            $memberIds = collect($p['members'])
                ->map(fn($init) => $users[$init] ?? null)
                ->filter()
                ->toArray();

            $project->members()->sync($memberIds);
        }
    }
}
