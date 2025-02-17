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
        Schema::create('page_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id');
            $table->string('name_en');
            $table->string('name_kh')->nullable();
            $table->string('route_name')->unique();
            $table->string('type');
            $table->string('position')->nullable();  // top, action, other, import
            $table->string('icon')->default('far fa-folder');
            $table->tinyInteger('order')->nullable();
            $table->string('parent')->nullable();
            $table->string('active', 10)->default('Y');
            $table->string('is_param', 10)->default('N');
            $table->json('params')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_actions');
    }
};
