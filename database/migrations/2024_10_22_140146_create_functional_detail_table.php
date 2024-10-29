<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Schema::create('chi_tiet_phan_quyen', function (Blueprint $table) {
            
        //     $table->string('ma_phan_quyen', 7)->nullable(); // `ma_phan_quyen` - varchar(7), nullable
        //     $table->string('ma_chuc_nang', 7)->nullable(); // `ma_chuc_nang` - varchar(5), nullable
        //     $table->text('mo_ta_vai_tro')->nullable(); // `mo_ta_vai_tro` - text, nullable

        //     // Foreign key constraints (nếu cần)
        //     $table->foreign('ma_phan_quyen')->references('ma_phan_quyen')->on('phan_quyen')->onDelete('cascade');
        //     $table->foreign('ma_chuc_nang')->references('ma_chuc_nang')->on('chuc_nang')->onDelete('cascade');
        //     $table->timestamps(); // Tùy chọn: thêm created_at và updated_at
        // });
        Schema::create('chi_tiet_phan_quyen', function (Blueprint $table) {
            $table->foreignId('ma_phan_quyen')->constrained('phan_quyen')->onDelete('cascade');
            $table->foreignId('ma_chuc_nang')->constrained('chuc_nang')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chi_tiet_phan_quyen');
    }
};
