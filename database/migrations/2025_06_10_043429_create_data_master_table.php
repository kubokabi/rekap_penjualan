<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_master', function (Blueprint $table) {
            $table->id('id_master');
            $table->timestamp('created_at')->useCurrent();
            $table->string('platform', 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_master');
    }
};
