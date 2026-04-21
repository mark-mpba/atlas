<?php

namespace Modules\Documents\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Categories\Models\Category;
use Modules\Documents\Models\Document;

/**
 * Class DocumentsDatabaseSeeder
 */
class DocumentsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Document::truncate();

        $sentinelCategory = Category::query()->updateOrCreate(
            [
                'slug' => 'sentinel',
            ],
            [
                'name' => 'Sentinel',
                'description' => 'Sentinel documentation',
            ]
        );

        $documents = [
            [
                'file' => 'api_development.md',
                'title' => 'Sentinel API Development',
                'slug' => 'sentinel-api-development',
                'excerpt' => 'Development techniques for building scalable and accessible Sentinel APIs.',
            ],
            [
                'file' => 'as400_http.md',
                'title' => 'AS400 HTTP Server Documentation',
                'slug' => 'as400-http-server-documentation',
                'excerpt' => 'How to configure and copy Apache HTTP server instances on IBM i / AS400.',
            ],
            [
                'file' => 'cron.md',
                'title' => 'IBM i Cron Scheduling',
                'slug' => 'ibm-i-cron-scheduling',
                'excerpt' => 'How to install, configure, run, and monitor cron scheduling on IBM i.',
            ],
            [
                'file' => 'dashboard.md',
                'title' => 'Dashboard View Model Implementation',
                'slug' => 'dashboard-view-model-implementation',
                'excerpt' => 'Using a Laravel view model to remove logic from Blade and improve maintainability.',
            ],
            [
                'file' => 'developer_sites.md',
                'title' => 'Developer Websites',
                'slug' => 'developer-websites',
                'excerpt' => 'Developer host mappings, release process, and environment usage notes.',
            ],
            [
                'file' => 'development.md',
                'title' => 'System Developer Notes',
                'slug' => 'system-developer-notes',
                'excerpt' => 'Branching, deployment, Docker workflow, module creation, and developer process notes.',
            ],
            [
                'file' => 'docker.md',
                'title' => 'Docker Usage for Local Development',
                'slug' => 'docker-usage-for-local-development',
                'excerpt' => 'Local Docker development workflow for Laravel on OSX and other platforms.',
            ],
            [
                'file' => 'environment_switcher.md',
                'title' => 'Laravel Environment Switcher',
                'slug' => 'laravel-environment-switcher',
                'excerpt' => 'Legacy environment switching approach for Laravel using server-defined variables.',
            ],
            [
                'file' => 'git.md',
                'title' => 'GitHub Branching Name Best Practices',
                'slug' => 'github-branching-name-best-practices',
                'excerpt' => 'Recommended Git branch naming conventions and workflow guidance.',
            ],
            [
                'file' => 'helpdesk.md',
                'title' => 'MPBA Helpdesk',
                'slug' => 'mpba-helpdesk',
                'excerpt' => 'MPBA helpdesk access details and ticket usage guidance.',
            ],
            [
                'file' => 'index.md',
                'title' => 'Welcome to Sentinel Docs',
                'slug' => 'welcome-to-sentinel-docs',
                'excerpt' => 'Sentinel development process, branch structure, commit guidance, and developer workflow notes.',
            ],
            [
                'file' => 'indexes.md',
                'title' => 'Indexing DB2 for Speed',
                'slug' => 'indexing-db2-for-speed',
                'excerpt' => 'Why key DB2 fields should be indexed to improve lookup performance.',
            ],
            [
                'file' => 'knowledge_base.md',
                'title' => 'Developer Knowledge Base',
                'slug' => 'developer-knowledge-base',
                'excerpt' => 'Developer notes including Laravel Eloquent SQL debugging techniques.',
            ],
            [
                'file' => 'migrations.md',
                'title' => 'Sentinel Migrations',
                'slug' => 'sentinel-migrations',
                'excerpt' => 'Migration rules, cautions, rollback guidance, and DB2-specific migration notes.',
            ],
            [
                'file' => 'page.md',
                'title' => 'Sentinel EPRO System Information',
                'slug' => 'sentinel-epro-system-information',
                'excerpt' => 'Environment details, branch structure, local development, Docker, Sail, and ODBC setup notes.',
            ],
            [
                'file' => 'queue.md',
                'title' => 'AS400 Queue Operation',
                'slug' => 'as400-queue-operation',
                'excerpt' => 'Queue limitations on IBM Db2, Redis-based queue strategy, and AS400 cron examples.',
            ],
            [
                'file' => 'redis.md',
                'title' => 'Redis Server',
                'slug' => 'redis-server',
                'excerpt' => 'Basic Redis server startup and verification commands for AS400 environments.',
            ],
            [
                'file' => 'schedule.md',
                'title' => 'Task Scheduling',
                'slug' => 'task-scheduling',
                'excerpt' => 'Laravel scheduler usage, frequency options, overlap prevention, background tasks, and task hooks.',
            ],
            [
                'file' => 'spares.md',
                'title' => 'The Spares Module',
                'slug' => 'the-spares-module',
                'excerpt' => 'Operational and developer notes for the Maximo Spares module.',
            ],
            [
                'file' => 'sshd.md',
                'title' => 'SSHD Server',
                'slug' => 'sshd-server',
                'excerpt' => 'How to restart the SSH server on AS400 from a 5250 console.',
            ],
            [
                'file' => 'view_models.md',
                'title' => 'Stop Treating Your Blade Files Like Trash Bins',
                'slug' => 'blade-view-models-and-typed-views',
                'excerpt' => 'Using ViewModels, typed Blade contracts, DTOs, and structured partials to make Laravel views safer and maintainable.',
            ],
        ];

        foreach ($documents as $documentData) {
            if (!Storage::disk('docs')->exists($documentData['file'])) {
                $this->command?->warn('Missing file: ' . $documentData['file']);
                continue;
            }

            $markdownBody = Storage::disk('docs')->get($documentData['file']);

            Document::query()->updateOrCreate(
                [
                    'slug' => $documentData['slug'],
                ],
                [
                    'title' => $documentData['title'],
                    'excerpt' => $documentData['excerpt'],
                    'markdown_body' => $markdownBody,
                    'html_body' => (string) Str::markdown($markdownBody),
                    'status' => 'published',
                    'is_featured' => false,
                    'published_at' => now(),
                    'meta_title' => $documentData['title'],
                    'meta_description' => $documentData['excerpt'],
                    'category_id' => $sentinelCategory->id,
                ]
            );
        }
    }
}
