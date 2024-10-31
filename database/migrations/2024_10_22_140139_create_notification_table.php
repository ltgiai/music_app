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
        Schema::create('thong_bao', function (Blueprint $table) {
            $table->string('ma_tb', 10)->primary(); // `ma_tb` - varchar(10), primary key, not null
            $table->string('ma_tk', 15)->nullable(); // `ma_tk` - varchar(15), foreign key
            $table->string('ten_tb', 50)->nullable(); // `ten_tb` - varchar(50), nullable
            $table->text('noi_dung_tb')->nullable(); // `noi_dung_tb` - text, nullable
            
            // Foreign key constraint
            $table->foreign('ma_tk')->references('ma_tk')->on('taikhoan')->onDelete('set null');

            $table->timestamps(); // Optional: Adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_bao');
    }
};
