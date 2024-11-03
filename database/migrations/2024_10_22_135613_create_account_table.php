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
        Schema::create('taikhoan', function (Blueprint $table) {
            $table->string('ma_tk', 15)->primary(); // `ma_tk` - varchar(15), primary key, not null
            $table->string('gmail', 100)->nullable(); // `gmail` - varchar(100), nullable
            $table->string('mat_khau', 50)->nullable(); // `mat_khau` - varchar(50), nullable
            $table->dateTime('ngay_tao')->nullable(); // `ngay_tao` - datetime, nullable
            $table->tinyInteger('trang_thai')->nullable(); // `trang_thai` - tinyint(1), nullable
            $table->string('ma_phan_quyen', 7)->nullable(); // `ma_phanquyen` - varchar(7), nullable
            $table->foreign('ma_phan_quyen')->references('ma_phan_quyen')->on('phan_quyen')->onDelete('cascade'); // Optional: Adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taikhoan');
    }
};
