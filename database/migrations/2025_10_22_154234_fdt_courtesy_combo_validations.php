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
        Schema::create('fdt_courtesy_combo_validations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_link_combo_id');
            $table->unsignedInteger('validated_qty')->default(0);
            $table->timestamp('validated_at')->nullable()->useCurrent();
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->timestamps();

            // 🔗 Relaciones
            $table->foreign('purchase_link_combo_id')
                ->references('id')
                ->on('fdt_courtesy_combo')
                ->onDelete('cascade');

            $table->foreign('validated_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fdt_courtesy_combo_validations');
    }
};
