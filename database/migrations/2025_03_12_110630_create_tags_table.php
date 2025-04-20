<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
   {
       Schema::create('tags', function (Blueprint $table) {
           $table->id();
           $table->string('name')->unique();
           $table->timestamps();
       });

       Schema::create('quote_tag', function (Blueprint $table) {
           $table->foreignId('quote_id')->constrained()->onDelete('cascade');
           $table->foreignId('tag_id')->constrained()->onDelete('cascade');
           $table->primary(['quote_id', 'tag_id']);
       });
   }

   public function down()
   {
       Schema::dropIfExists('quote_tag');
       Schema::dropIfExists('tags');
   }
}; 