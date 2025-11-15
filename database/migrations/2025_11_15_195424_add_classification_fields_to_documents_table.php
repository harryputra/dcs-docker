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
            $table->unsignedBigInteger('classification_id')->nullable()->after('category_id');
            $table->unsignedBigInteger('sub_classification_id')->nullable()->after('classification_id');
            $table->unsignedBigInteger('sub_sub_classification_id')->nullable()->after('sub_classification_id');
            $table->integer('sequence_number')->nullable()->after('sub_sub_classification_id'); // Nomor urut
            $table->string('puskesmas_code', 20)->default('PKM GRD')->after('sequence_number');

            $table->foreign('classification_id')->references('id')->on('document_classifications')->onDelete('set null');
            $table->foreign('sub_classification_id')->references('id')->on('document_classifications')->onDelete('set null');
            $table->foreign('sub_sub_classification_id')->references('id')->on('document_classifications')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['classification_id']);
            $table->dropForeign(['sub_classification_id']);
            $table->dropForeign(['sub_sub_classification_id']);
            $table->dropColumn(['classification_id', 'sub_classification_id', 'sub_sub_classification_id', 'sequence_number', 'puskesmas_code']);
        });
    }
};
