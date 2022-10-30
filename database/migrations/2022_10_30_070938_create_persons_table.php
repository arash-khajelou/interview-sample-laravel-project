<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id();

            $table->string("demonstration_name", 100);
            $table->boolean("is_active");
            $table->string("first_name", 50);
            $table->string("last_name", 50);
            $table->string("social_id", 10);
            $table->timestamp("birth_date");
            $table->string("mobile_number");
            $table->string("mobile_description", 100);
            $table->string("email");
            $table->string("email_description", 100);

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
        Schema::dropIfExists('persons');
    }
};
