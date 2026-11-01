<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsArchivedToTasks extends Migration
{
    // 0 = active (default), 1 = archived ("deleted").
    public function up(): void
    {
        $this->forge->addColumn('tasks', [
            'is_archived' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'null' => false],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('tasks', 'is_archived');
    }
}
