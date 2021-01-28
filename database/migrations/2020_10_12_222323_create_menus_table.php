<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->string('model_name')->unique();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->string('url')->unique();
            $table->foreignId('maker', 20)->nullable()->constrained('users');
            $table->foreignId('modifier', 20)->nullable()->constrained('users');
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
        Schema::dropIfExists('menus', function(Blueprint $table){
            $table->dropForeign('menus_maker_foreign');
            $table->dropForeign('menus_modifier_foreign');
        });
        Schema::dropIfExists('menus');
    }
}
