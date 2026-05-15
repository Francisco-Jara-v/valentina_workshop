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
        Schema::table('inventario_tienda', function (Blueprint $table) {
            $table->integer('cantidad_enviada')->default(0)->after('producto_id');
            $table->integer('cantidad_vendida')->default(0)->after('cantidad_actual');
            $table->integer('cantidad_devuelta')->default(0)->after('cantidad_vendida');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventario_tienda', function (Blueprint $table) {
            $table->dropColumn([
                'cantidad_enviada',
                'cantidad_vendida',
                'cantidad_devuelta'
            ]);
        });
    }
};
