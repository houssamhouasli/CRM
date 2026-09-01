<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }

    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!$this->indexExists('orders', 'orders_status_idx')) {
                $table->index('status', 'orders_status_idx');
            }
            if (!$this->indexExists('orders', 'orders_type_idx')) {
                $table->index('type', 'orders_type_idx');
            }
            if (!$this->indexExists('orders', 'orders_order_date_idx')) {
                $table->index('order_date', 'orders_order_date_idx');
            }
            if (!$this->indexExists('orders', 'orders_client_status_idx')) {
                $table->index(['client_id', 'status'], 'orders_client_status_idx');
            }
            if (!$this->indexExists('orders', 'orders_type_date_idx')) {
                $table->index(['type', 'order_date'], 'orders_type_date_idx');
            }
        });

        Schema::table('deliveries', function (Blueprint $table) {
            if (!$this->indexExists('deliveries', 'deliveries_status_idx')) {
                $table->index('status', 'deliveries_status_idx');
            }
            if (!$this->indexExists('deliveries', 'deliveries_delivery_date_idx')) {
                $table->index('delivery_date', 'deliveries_delivery_date_idx');
            }
            if (!$this->indexExists('deliveries', 'deliveries_order_status_idx')) {
                $table->index(['order_id', 'status'], 'deliveries_order_status_idx');
            }
            if (!$this->indexExists('deliveries', 'deliveries_status_date_idx')) {
                $table->index(['status', 'delivery_date'], 'deliveries_status_date_idx');
            }
        });

        Schema::table('clients', function (Blueprint $table) {
            if (!$this->indexExists('clients', 'clients_company_name_idx')) {
                $table->index('company_name', 'clients_company_name_idx');
            }
            if (!$this->indexExists('clients', 'clients_phone_idx')) {
                $table->index('phone', 'clients_phone_idx');
            }
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            if (!$this->indexExists('stock_movements', 'stock_movements_moved_at_idx')) {
                $table->index('moved_at', 'stock_movements_moved_at_idx');
            }
            if (!$this->indexExists('stock_movements', 'stock_movements_type_idx')) {
                $table->index('type', 'stock_movements_type_idx');
            }
            if (!$this->indexExists('stock_movements', 'stock_movements_product_type_idx')) {
                $table->index(['product_id', 'type'], 'stock_movements_product_type_idx');
            }
        });

        Schema::table('returns', function (Blueprint $table) {
            if (!$this->indexExists('returns', 'returns_status_idx')) {
                $table->index('status', 'returns_status_idx');
            }
            if (!$this->indexExists('returns', 'returns_validated_at_idx')) {
                $table->index('validated_at', 'returns_validated_at_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if ($this->indexExists('orders', 'orders_status_idx')) $table->dropIndex('orders_status_idx');
            if ($this->indexExists('orders', 'orders_type_idx')) $table->dropIndex('orders_type_idx');
            if ($this->indexExists('orders', 'orders_order_date_idx')) $table->dropIndex('orders_order_date_idx');
            if ($this->indexExists('orders', 'orders_client_status_idx')) $table->dropIndex('orders_client_status_idx');
            if ($this->indexExists('orders', 'orders_type_date_idx')) $table->dropIndex('orders_type_date_idx');
        });

        Schema::table('deliveries', function (Blueprint $table) {
            if ($this->indexExists('deliveries', 'deliveries_status_idx')) $table->dropIndex('deliveries_status_idx');
            if ($this->indexExists('deliveries', 'deliveries_delivery_date_idx')) $table->dropIndex('deliveries_delivery_date_idx');
            if ($this->indexExists('deliveries', 'deliveries_order_status_idx')) $table->dropIndex('deliveries_order_status_idx');
            if ($this->indexExists('deliveries', 'deliveries_status_date_idx')) $table->dropIndex('deliveries_status_date_idx');
        });

        Schema::table('clients', function (Blueprint $table) {
            if ($this->indexExists('clients', 'clients_company_name_idx')) $table->dropIndex('clients_company_name_idx');
            if ($this->indexExists('clients', 'clients_phone_idx')) $table->dropIndex('clients_phone_idx');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            if ($this->indexExists('stock_movements', 'stock_movements_moved_at_idx')) $table->dropIndex('stock_movements_moved_at_idx');
            if ($this->indexExists('stock_movements', 'stock_movements_type_idx')) $table->dropIndex('stock_movements_type_idx');
            if ($this->indexExists('stock_movements', 'stock_movements_product_type_idx')) $table->dropIndex('stock_movements_product_type_idx');
        });

        Schema::table('returns', function (Blueprint $table) {
            if ($this->indexExists('returns', 'returns_status_idx')) $table->dropIndex('returns_status_idx');
            if ($this->indexExists('returns', 'returns_validated_at_idx')) $table->dropIndex('returns_validated_at_idx');
        });
    }
};
