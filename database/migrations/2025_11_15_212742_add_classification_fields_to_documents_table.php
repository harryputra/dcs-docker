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
            $table->foreignId('classification_id')->nullable()->after('category_id')->constrained('classifications')->onDelete('set null');
            $table->integer('sequence_number')->nullable()->after('classification_id');
            $table->string('puskesmas_code', 50)->nullable()->after('sequence_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['classification_id']);
            $table->dropColumn(['classification_id', 'sequence_number', 'puskesmas_code']);
        });
    }
};
