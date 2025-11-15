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
            // Drop old foreign keys and columns
            $table->dropForeign(['sub_classification_id']);
            $table->dropForeign(['sub_sub_classification_id']);
            $table->dropColumn(['sub_classification_id', 'sub_sub_classification_id']);

            // Rename classification_id to final_classification_id for clarity
            // This will store the deepest/final classification selected by user
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_classification_id')->nullable()->after('classification_id');
            $table->unsignedBigInteger('sub_sub_classification_id')->nullable()->after('sub_classification_id');

            $table->foreign('sub_classification_id')->references('id')->on('document_classifications')->onDelete('set null');
            $table->foreign('sub_sub_classification_id')->references('id')->on('document_classifications')->onDelete('set null');
        });
    }
};
