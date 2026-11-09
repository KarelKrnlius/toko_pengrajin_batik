<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop check constraint status jika ada di Postgres
        try {
            DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check");
        } catch (\Throwable $e) {
            // Ignore jika constraint tidak ada
        }

        // 2. Tambah constraint baru yang mencakup 'cancelled'
        try {
            DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status::text = ANY (ARRAY['pending'::character varying, 'shipped'::character varying, 'completed'::character varying, 'cancelled'::character varying]::text[]))");
        } catch (\Throwable $e) {
            // Jika status bukan string ber-check constraint (misal varchar biasa), fallback
        }

        // 3. Tambahkan kolom cancellation_reason dan cancelled_at
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('orders', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'cancellation_reason')) {
                $table->dropColumn('cancellation_reason');
            }
            if (Schema::hasColumn('orders', 'cancelled_at')) {
                $table->dropColumn('cancelled_at');
            }
        });

        try {
            DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check");
            DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status::text = ANY (ARRAY['pending'::character varying, 'shipped'::character varying, 'completed'::character varying]::text[]))");
        } catch (\Throwable $e) {
            //
        }
    }
};
