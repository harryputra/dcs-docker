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
        Schema::create('document_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assigner_id'); // Who assigned it (Kepala Puskesmas)
            $table->unsignedInteger('target_role_id'); // Target role
            $table->unsignedBigInteger('assigned_user_id')->nullable(); // Who accepted it
            
            // Optional: If 'Revisi', link the original document
            $table->unsignedBigInteger('document_id')->nullable(); 
            
            // Task Metadata
            $table->enum('task_type', ['Baru', 'Revisi']);
            $table->string('title');
            $table->text('instruction');
            $table->enum('status', ['Menunggu Ketersediaan', 'Dikerjakan', 'Selesai'])->default('Menunggu Ketersediaan');
            
            $table->timestamps();

            // Foreign Keys
            $table->foreign('assigner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('target_role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('assigned_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_tasks');
    }
};
