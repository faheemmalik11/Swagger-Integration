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
        Schema::create('maze_infos', function (Blueprint $table) {
            $table->id();
            $table->string('logo');
            $table->string('bg-color');
            $table->string('font');
            $table->bigInteger("administration_id")->unsigned()->index()->nullable();
            $table->foreign('administration_id')->references('id')->on('administrations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maze_infos');
    }
};
