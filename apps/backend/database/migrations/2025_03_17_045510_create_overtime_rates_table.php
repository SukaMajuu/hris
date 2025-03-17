<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('overtime_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('overtime_type_id')->constrained('overtime_types')->onDelete('cascade');
            $table->decimal('hours_threshold', 5, 2);
            $table->decimal('multiplier', 3, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('overtime_rates');
    }
};

