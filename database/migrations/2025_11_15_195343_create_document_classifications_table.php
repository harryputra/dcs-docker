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
        Schema::create('document_classifications', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10); // HM, HK, KP, dll
            $table->string('name'); // Hubungan Masyarakat, Hukum, dll
            $table->unsignedBigInteger('parent_id')->nullable(); // Untuk hierarchical structure
            $table->integer('level')->default(1); // 1=Klasifikasi, 2=Sub, 3=SubSub
            $table->integer('order')->default(0); // Urutan tampilan
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('document_classifications')->onDelete('cascade');
            $table->index(['parent_id', 'level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_classifications');
    }
};
