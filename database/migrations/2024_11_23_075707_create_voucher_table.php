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
        Schema::create('goi_premium', function (Blueprint $table) {
            $table->id();
            $table->string('ma_goi')->unique();  // Mã gói, ví dụ: GOI0011
            $table->string('ten_goi');            // Tên gói
            $table->integer('thoi_han');          // Thời hạn gói
            $table->decimal('gia_goi', 15, 3);    // Giá gói, ví dụ: 10000.000
            $table->decimal('doanh_thu', 15, 3)->default(0.000);  // Doanh thu, mặc định 0.000
            $table->text('mo_ta');                // Mô tả gói, lưu dưới dạng chuỗi
            $table->tinyInteger('trang_thai');    // Trạng thái gói, ví dụ: 1 (hoạt động) hoặc 0 (ngừng hoạt động)
            $table->timestamps();                // Thời gian tạo và cập nhật
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher');
    }
};
