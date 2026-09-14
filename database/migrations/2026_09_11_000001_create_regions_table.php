<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['provinsi', 'kabupaten', 'kecamatan', 'kelurahan'])->index();
            $table->foreignId('parent_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->string('code', 50)->nullable()->index();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
