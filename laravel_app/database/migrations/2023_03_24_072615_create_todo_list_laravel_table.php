<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CreateTodoListLaravelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lists', function (Blueprint $table) {
            $table->id();
            $table->text('texts');
            $table->bigInteger('unquid_id')->nullable();
            // $table->bigInteger('unquid_id')->default(Carbon::now()->timestamp * 1000);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('showEditingbox')->default(0);
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
        Schema::dropIfExists('todo_list_laravel');
    }
}
