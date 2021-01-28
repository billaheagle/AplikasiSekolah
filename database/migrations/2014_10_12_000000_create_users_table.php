<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('phone_number', 15)->nullable();
            $table->string('alternative_phone_number', 15)->nullable();
            $table->string('address', 255)->nullable();
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
        Schema::dropIfExists('users', function(Blueprint $table){
            $table->dropForeign('users_maker_foreign');
            $table->dropForeign('users_modifier_foreign');
        });
        Schema::dropIfExists('users');
    }
}
