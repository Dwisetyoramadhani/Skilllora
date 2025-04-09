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
        Schema::create('courses', function (Blueprint $table) {
            $table->id('course_id');
            $table->string('course_title');
            $table->unsignedBigInteger('category_id');
            $table->text('description');
            $table->string('thumbnail')->default('default.jpg');
            $table->unsignedBigInteger('author');
            $table->string('price');
            $table->string('vidio_link');
            $table->timestamps();

            $table->foreign('category_id')
                ->references('category_id')
                ->on('categories')
                ->onDelete('cascade');

            $table->foreign('author')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
