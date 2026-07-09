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
        Schema::create('borrow_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')
                    ->constrained()
                    ->cascadeOnDelete();
            $table->string('borrower_name');
            $table->timestamp('borrowed_at');
            $table->timestamp('returned_at')->nullable();
            $table->enum('status',['borrowed','returned'])
                  ->default('borrowed');      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_records');
    }
};
