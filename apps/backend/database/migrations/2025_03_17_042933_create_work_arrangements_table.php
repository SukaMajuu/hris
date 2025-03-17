<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('work_arrangements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['WFA', 'WFO', 'WFH']);
            $table->boolean('is_default')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_arrangements');
    }
};
