<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the legacy Nigerian geography lookup tables (`lgas` and `states_tb`)
 * using the exact same column names/types the LIVE database uses, so that a
 * fresh `php artisan migrate` reproduces the LIVE schema without depending on a
 * seeder to run first.
 *
 * The actual data is loaded by `ReferenceDataSqlImporterSeeder` from
 * `database/seeders/sql/States and LGAs.sql`. The app reads `lgas.NAME` and
 * `lgas.STATE_CODE` (PatientForm.php LGA dropdown); `states_tb` is the
 * state-side pair joined on `lgas.STATE_CODE = states_tb.code`.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('states_tb')) {
            Schema::create('states_tb', function (Blueprint $table) {
                // Legacy PK column used by the LIVE DB
                $table->integer('stateID')->primary();
                $table->char('code', 2);
                $table->string('stateNm', 50)->nullable();
            });
        }

        if (! Schema::hasTable('lgas')) {
            Schema::create('lgas', function (Blueprint $table) {
                // Legacy PK/FK columns used by the LIVE DB
                $table->integer('LGID')->primary();
                $table->string('NAME', 50);
                $table->char('STATE_CODE', 2)->nullable();
                $table->integer('STATEID')->nullable();
                $table->index('STATE_CODE');
                $table->index('STATEID');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lgas');
        Schema::dropIfExists('states_tb');
    }
};
