<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();// if it's system admin then the user_id would be 1
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->timestamp('publication_date')->comment('The date and time posts would become availableto the public');
            $table->timestamp('published_at')->nullable()->comment('The date and time post was published by the system');
            $table->boolean('is_published')->default(false)->comment('True if blog post is already published');
            $table->timestamps();

            $table->index(['slug']);
            $table->index(['publication_date', 'published_at']);
            $table->index(['user_id', 'publication_date']);
            $table->index(['is_published', 'published_at', 'publication_date']);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
