<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // Theatres (operation rooms)
        // ---------------------------------------------------------------
        if (!Schema::hasTable('theatres')) {
            Schema::create('theatres', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
                $table->string('name');
                $table->string('code', 20)->unique();
                $table->string('type', 50)->default('General')->comment('General, Orthopaedic, ENT, Ophthalmic, Cardiothoracic, Obstetric, Day Case');
                $table->integer('capacity')->default(1);
                $table->text('equipment')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Seed default theatres
        \DB::table('theatres')->insertOrIgnore([
            ['hospital_id' => 1, 'name' => 'Theatre 1 (General Surgery)', 'code' => 'TH-1', 'type' => 'General', 'capacity' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['hospital_id' => 1, 'name' => 'Theatre 2 (Orthopaedic)', 'code' => 'TH-2', 'type' => 'Orthopaedic', 'capacity' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['hospital_id' => 1, 'name' => 'Theatre 3 (Obstetric / Emergency)', 'code' => 'TH-3', 'type' => 'Obstetric', 'capacity' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['hospital_id' => 1, 'name' => 'Theatre 4 (Day Case)', 'code' => 'TH-4', 'type' => 'Day Case', 'capacity' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('theatres');
    }
};
