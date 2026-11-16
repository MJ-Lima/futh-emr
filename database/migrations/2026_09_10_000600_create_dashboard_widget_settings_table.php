<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('dashboard_widget_settings')) {
            Schema::create('dashboard_widget_settings', function (Blueprint $table) {
                $table->id();
                $table->string('role_name', 100);
                $table->string('widget_key', 100);
                $table->boolean('is_visible')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                $table->unique(['role_name', 'widget_key']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_widget_settings');
    }
};
