<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('replaced_by_id')->nullable()->constrained('documents')->onDelete('set null');
            $table->enum('status_document', ['Aktif', 'Diganti', 'Dicabut', 'Kadaluarsa'])->default('Aktif');
            $table->date('expired_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['replaced_by_id']);
            $table->dropColumn(['replaced_by_id', 'status_document', 'expired_at']);
        });
    }
};
