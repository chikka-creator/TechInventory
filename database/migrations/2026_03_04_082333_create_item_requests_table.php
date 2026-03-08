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
    Schema::create('item_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('item_name');
        $table->string('category')->nullable();
        $table->text('reason');
        $table->string('status')->default('pending');
        $table->timestamps();
    });
}
public function down()
{
    Schema::dropIfExists('item_requests');
}
};
