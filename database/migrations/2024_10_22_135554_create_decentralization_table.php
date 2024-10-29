<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('phan_quyen', function (Blueprint $table) {
            $table->string('ma_phan_quyen', 7)->primary(); // `ma_phan_quyen` - varchar(7), khóa chính, không null
            $table->string('ten_quyen_han', 50)->nullable(); // `ten_quyen_han` - varchar(50), nullable
            $table->dateTime('ngay_tao')->nullable(); // `ngay_tao` - datetime, nullable
            $table->tinyInteger('tinh_trang')->nullable(); // `tinh_trang` - tinyint(1), nullable
            $table->timestamps(); // Tùy chọn: thêm created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('phan_quyen');
    }
};
