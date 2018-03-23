<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiListTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {




        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name");
            $table->string("description")->nullable();
            $table->string("url")->unique();
            $table->unsignedInteger("user_id");

            $table->timestamps();
            $table->foreign("user_id")->references("id")->on("users")->onDelete('cascade');
        });
        Schema::create("scopes", function(Blueprint $table){
            $table->increments("id");
            $table->string("name");
            $table->string("description");

            $table->unsignedInteger("project_id");
            $table->foreign("project_id")->references("id")->on("projects")->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scopes');
        Schema::dropIfExists('projects');
    }
}
