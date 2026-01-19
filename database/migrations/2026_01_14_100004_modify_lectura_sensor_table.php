<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('LecturaSensor')) {
            Schema::table('LecturaSensor', function (Blueprint $table) {
                if (!Schema::hasColumn('LecturaSensor', 'origen')) {
                    $table->enum('origen', ['MANUAL', 'PLC', 'API'])->default('MANUAL')->after('valor');
                }
                if (!Schema::hasColumn('LecturaSensor', 'ciclo_numero')) {
                    $table->integer('ciclo_numero')->nullable()->after('origen');
                }
                if (!Schema::hasColumn('LecturaSensor', 'ciclos_mqtt_crudo_id')) {
                    $table->unsignedBigInteger('ciclos_mqtt_crudo_id')->nullable()->after('ciclo_numero');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('LecturaSensor', function (Blueprint $table) {
            if (Schema::hasColumn('LecturaSensor', 'origen')) {
                $table->dropColumn('origen');
            }
            if (Schema::hasColumn('LecturaSensor', 'ciclo_numero')) {
                $table->dropColumn('ciclo_numero');
            }
            if (Schema::hasColumn('LecturaSensor', 'ciclos_mqtt_crudo_id')) {
                $table->dropColumn('ciclos_mqtt_crudo_id');
            }
        });
    }
};
