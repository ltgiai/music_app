<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chuc_nang', function (Blueprint $table) {
            $table->string('ma_chuc_nang', 7)->primary(); // `ma_chuc_nang` - varchar(5), khóa chính, không null
            $table->string('ten_chuc_nang', 50)->nullable(); // `ten_chuc_nang` - varchar(50), nullable
            $table->timestamps(); // Tùy chọn: thêm created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('chuc_nang');
    }
};
