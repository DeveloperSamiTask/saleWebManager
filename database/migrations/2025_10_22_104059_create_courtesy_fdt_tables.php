<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1️⃣ Tabla principal: fdt_courtesy_link
        Schema::create('fdt_courtesy_link', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->integer('food')->nullable();
            $table->string('lastname', 100)->nullable();
            $table->string('names', 100)->nullable();
            $table->string('document_type', 20)->nullable();
            $table->string('document_number', 30)->nullable();
            $table->string('phone', 20)->nullable();
            $table->dateTime('date_purchase')->nullable();
            $table->date('date_issue')->nullable();
            $table->string('status', 20);
            $table->text('observation')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_auth')->nullable();
            $table->string('code_auth', 25)->nullable();
            $table->dateTime('date_auth')->nullable();
            $table->unsignedBigInteger('user_active')->nullable();
            $table->timestamp('activate_date')->nullable();

            // Nueva columna para distinguir el tipo de cortesía
            $table->enum('audience_type', ['influencer', 'publico_general'])->default('publico_general');

            $table->timestamps();
        });

        // 2️⃣ Tabla de combos relacionados
        Schema::create('fdt_courtesy_combo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_link_id');
            $table->unsignedBigInteger('combo_id');
            $table->integer('quantity');
            $table->timestamps();

            // Llave foránea opcional
            $table->foreign('purchase_link_id')
                ->references('id')
                ->on('fdt_courtesy_link')
                ->onDelete('cascade');
        });

        // 3️⃣ Tabla de miembros del combo
        Schema::create('fdt_courtesy_combo_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_combo_id');
            $table->string('name', 100)->nullable();
            $table->string('dni', 20)->nullable();
            $table->string('status_entrie', 20);
            $table->string('user', 20)->nullable();
            $table->timestamp('issue_entrie')->nullable();
            $table->timestamps();

            // Llave foránea opcional
            $table->foreign('purchase_combo_id')
                ->references('id')
                ->on('fdt_courtesy_combo')
                ->onDelete('cascade');
        });

        // 4️⃣ Tabla de promociones de cortesía
        Schema::create('fdt_promotions_courtesy', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->double('price', 10, 2)->nullable();
            $table->string('description', 200);
            $table->integer('members');
            $table->integer('has_food')->nullable();
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fdt_promotions_courtesy');
        Schema::dropIfExists('fdt_courtesy_combo_members');
        Schema::dropIfExists('fdt_courtesy_combo');
        Schema::dropIfExists('fdt_courtesy_link');
    }
};
