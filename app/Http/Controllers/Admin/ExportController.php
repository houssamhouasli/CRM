<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Client;
use App\Models\Depot;
use App\Models\Category;
use App\Models\Product;
use App\Models\Truck;
use App\Models\User;
use App\Models\DepotStock;
use App\Models\TruckStock;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\StockMovement;
use App\Models\ReturnModel;
use App\Models\ReturnItem;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Export All Tables (Multi-sheet XLS via XML Spreadsheet)
     */
    public function exportAll()
    {
        $fileName = 'export_global_' . date('Y-m-d_H-i') . '.xls';

        $headers = [
            "Content-Type"        => "application/vnd.ms-excel; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"$fileName\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $callback = function () {
            // XML declaration + Workbook open
            echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            echo '<?mso-application progid="Excel.Sheet"?>' . "\n";
            echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'
                . ' xmlns:o="urn:schemas-microsoft-com:office:office"'
                . ' xmlns:x="urn:schemas-microsoft-com:office:excel"'
                . ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"'
                . ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";

            // ── Styles ──
            echo '<Styles>' . "\n";
            echo ' <Style ss:ID="Default" ss:Name="Normal"><Alignment ss:Vertical="Bottom"/></Style>' . "\n";
            echo ' <Style ss:ID="header">'
                . '<Font ss:Bold="1" ss:Size="11" ss:Color="#FFFFFF"/>'
                . '<Interior ss:Color="#175fdb" ss:Pattern="Solid"/>'
                . '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/>'
                . '</Style>' . "\n";
            echo '</Styles>' . "\n";

            // ── Core Reference Data ──
            $this->renderSheet('Regions', [
                'id', 'name', 'code', 'created_at','updated_at',
            ], Region::all());

            $this->renderSheet('Categories', [
                'id', 'name', 'description', 'created_at','updated_at',
            ], Category::all());

            // ── Locations & Users ──
            $this->renderSheet('Depots', [
                'id', 'name', 'location', 'created_at','updated_at',
            ], Depot::all());

            $this->renderSheet('Trucks', [
                'id', 'livreur_id', 'name', 'capacity', 'created_at','updated_at',
            ], Truck::all());

            $this->renderSheet('Users', [
                'id', 'name', 'email', 'email_verified_at', 'password', 'role', 'region_id', 'depot_id', 'remember_token', 'created_at', 'updated_at',
            ], User::all());

            // ── Clients ──
            $this->renderSheet('Clients', [
                'id','region_id', 'company_name', 'email', 'phone', 'address',  'created_at', 'updated_at',
            ], Client::all());

            // ── Products ──
            $this->renderSheet('Products', [
                'id', 'category_id', 'name', 'sku', 'price_ht', 'tva_rate', 'weight', 'unit',
                'promo_type', 'promo_value', 'promo_min_qty', 'promo_start_date', 'promo_end_date',
                'is_refundable', 'created_at', 'updated_at',
            ], Product::all());

            // ── Inventory ──
            $this->renderSheet('Depot_Stock', [
                'id', 'depot_id', 'product_id', 'quantity', 'created_at', 'updated_at',
            ], DepotStock::all());

            $this->renderSheet('Truck_Stock', [
                'id', 'truck_id', 'product_id', 'quantity', 'created_at', 'updated_at',
            ], TruckStock::all());

            // ── Orders & Items ──
            $this->renderSheet('Orders', [
                'id', 'type', 'client_id', 'created_by', 'status', 'total_ht', 'total_tva', 'total_ttc',
                'order_date', 'created_at', 'updated_at',
            ], Order::all());

            $this->renderSheet('Order_Items', [
                'id', 'order_id', 'product_id', 'quantity', 'price_unit_ht','tva_rate','promo_type','promo_value',
                'final_price_ht', 'discount_amount', 'total_ht', 'total_tva', 'total_ttc', 'created_at', 'updated_at',
            ], OrderItem::all());

            // ── Deliveries & Items ──
            $this->renderSheet('Deliveries', [
                'id', 'order_id', 'livreur_id', 'depot_id', 'status', 'delivery_date',
                'total_ht', 'total_tva', 'total_ttc', 'created_at', 'updated_at',
            ], Delivery::all());

            $this->renderSheet('Delivery_Items', [
                'id', 'delivery_id', 'product_id', 'qty_ordered', 'qty_delivered','returned_quantity',
                'unit_price_ht', 'promo_type', 'promo_value', 'tva_rate',
                'total_ht', 'total_tva', 'total_ttc', 'created_at', 'updated_at',
            ], DeliveryItem::all());

            // ── Returns ──
            $this->renderSheet('Returns', [
                'id', 'delivery_id', 'livreur_id', 'depot_id', 'validator_id', 'status', 'reason',
                'rejected_reason', 'validated_at', 'created_at', 'updated_at',
            ], ReturnModel::all());

            $this->renderSheet('Return_Items', [
                'id', 'return_id','delivery_item_id', 'product_id', 'quantity',
                'condition_type', 'notes', 'created_at', 'updated_at',
            ], ReturnItem::all());

            // ── Stock Movements ──
            $this->renderSheet('Stock_Movements', [
                'id', 'product_id', 'user_id','order_id', 'depot_id',  'return_id', 'truck_id',
                'type', 'quantity', 'reason', 'moved_at', 'created_at', 'updated_at',
            ], StockMovement::all());

            echo '</Workbook>' . "\n";
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Render a single XML Spreadsheet worksheet.
     */
    private function renderSheet(string $name, array $columns, $data): void
    {
        $sheetName = mb_substr($name, 0, 31);

        echo '<Worksheet ss:Name="' . $this->xmlSafe($sheetName) . '">' . "\n";
        echo ' <Table ss:DefaultColumnWidth="120">' . "\n";

        // ── Header row ──
        echo '  <Row ss:AutoFitHeight="1">' . "\n";
        foreach ($columns as $col) {
            echo '   <Cell ss:StyleID="header"><Data ss:Type="String">' . $this->xmlSafe($col) . '</Data></Cell>' . "\n";
        }
        echo '  </Row>' . "\n";

        // ── Data rows ──
        foreach ($data as $row) {
            echo '  <Row>' . "\n";
            foreach ($columns as $col) {
                $val = data_get($row, $col);

                // Null → empty string
                if ($val === null) {
                    echo '   <Cell><Data ss:Type="String"></Data></Cell>' . "\n";
                    continue;
                }

                // Carbon / DateTime → formatted string
                if ($val instanceof \DateTimeInterface) {
                    $val = $val->format('Y-m-d H:i:s');
                    echo '   <Cell><Data ss:Type="String">' . $this->xmlSafe($val) . '</Data></Cell>' . "\n";
                    continue;
                }

                // Boolean
                if (is_bool($val)) {
                    echo '   <Cell><Data ss:Type="Number">' . ($val ? '1' : '0') . '</Data></Cell>' . "\n";
                    continue;
                }

                // Cast to string for processing
                $val = (string) $val;

                // Strip line-breaks that corrupt XML cells
                $val = str_replace(["\r\n", "\r", "\n"], ' ', $val);

                // Numeric detection (integers & decimals)
                if ($val !== '' && is_numeric($val)) {
                    echo '   <Cell><Data ss:Type="Number">' . $this->xmlSafe($val) . '</Data></Cell>' . "\n";
                } else {
                    echo '   <Cell><Data ss:Type="String">' . $this->xmlSafe($val) . '</Data></Cell>' . "\n";
                }
            }
            echo '  </Row>' . "\n";
        }

        echo ' </Table>' . "\n";
        echo '</Worksheet>' . "\n";
    }

    /**
     * Escape a value for safe inclusion in XML.
     */
    private function xmlSafe(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }
}
