<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMotorcyclesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'customer_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'plate_number' => ['type' => 'VARCHAR', 'constraint' => 20, 'unique' => true],
            'brand' => ['type' => 'VARCHAR', 'constraint' => 100],
            'model' => ['type' => 'VARCHAR', 'constraint' => 100],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('customer_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('motorcycles');
    }

    public function down()
    {
        $this->forge->dropTable('motorcycles');
    }
}