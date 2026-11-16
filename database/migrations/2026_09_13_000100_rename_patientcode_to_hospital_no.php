<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rename the column from patientcode to hospital_no
        if (Schema::hasColumn('patients', 'patientcode') && !Schema::hasColumn('patients', 'hospital_no')) {
            // For MySQL, we can use CHANGE COLUMN directly
            DB::statement("ALTER TABLE patients CHANGE COLUMN patientcode hospital_no VARCHAR(200) NOT NULL UNIQUE");
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('patients', 'hospital_no') && !Schema::hasColumn('patients', 'patientcode')) {
            DB::statement("ALTER TABLE patients CHANGE COLUMN hospital_no patientcode VARCHAR(200) NOT NULL UNIQUE");
        }
    }
};
