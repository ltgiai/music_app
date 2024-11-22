<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chi_tiet_phan_quyen', function (Blueprint $table) {
            $table->string('ma_phan_quyen');
            $table->string('ma_chuc_nang');
            $table->foreign('ma_phan_quyen')
                    ->references('ma_phan_quyen')
                    ->on('phan_quyen')
                    ->onDelete('cascade'); // Tự động xóa khi xóa phân quyền
            $table->foreign('ma_chuc_nang')
                    ->references('ma_chuc_nang')
                    ->on('chuc_nang'); // Không cần xóa, chỉ liên kết
            $table->boolean('xem')->default(false);
            $table->boolean('them')->default(false);
            $table->boolean('sua')->default(false);
            $table->boolean('xoa')->default(false);
            $table->timestamps();                    
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('chi_tiet_phan_quyen');
    }
};
