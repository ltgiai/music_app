<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->string('ma_tk', 15)->primary();
            $table->foreign('ma_tk')->references('ma_tk')->on('tai_khoan')->onDelete('cascade');
            $table->string('ten_user', 50); // `ten_user` - varchar(50), nullable
            $table->text('anh_dai_dien')->nullable(); // `anh_dai_dien` - text, nullable, used for image URL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
