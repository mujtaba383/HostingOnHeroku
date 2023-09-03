<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('author_id');
            $table->string('title', 100);
            $table->string('slug', 100);
            $table->string('availability', 100);
            $table->string('price', 100)->nulllable();
            $table->string('rating', 100)->nulllable();
            $table->string('publisher', 100)->nulllable();
            $table->string('country_of_publisher', 100)->nulllable();
            $table->string('isbn', 100)->nulllable();
            $table->string('isbn-10', 100)->nulllable();
            $table->string('audience', 100)->nulllable();
            $table->string('format', 100)->nulllable();
            $table->string('language', 100)->nulllable();
            $table->text('description')->nulllable();
            $table->string('book_upload', 200);
            $table->string('book_img', 200);
            $table->string('total_pages', 100)->nulllable();
            $table->string('downloaded', 100)->nulllable();
            $table->string('edition_number', 100)->nulllable();
            $table->string('recommended', 100)->nulllable();
            $table->string('status', 10)->default('deactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book');
    }
}
