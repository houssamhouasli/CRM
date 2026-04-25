<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Client;
use App\Models\Depot;
use App\Models\DepotStock;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Region;
use App\Models\Truck;
use App\Models\TruckStock;
use App\Models\User;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\StockMovement;
use App\Models\ReturnModel;
use App\Models\ReturnItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // 1. REGIONS (12 régions du Maroc)
        // ============================================
        $regions = [
            ['name' => 'Tanger-Tétouan-Al Hoceïma', 'code' => 'TTA'],
            ['name' => 'Oriental', 'code' => 'ORIENTAL'],
            ['name' => 'Fès-Meknès', 'code' => 'FES'],
            ['name' => 'Rabat-Salé-Kénitra', 'code' => 'RABAT'],
            ['name' => 'Béni Mellal-Khénifra', 'code' => 'BENI'],
            ['name' => 'Casablanca-Settat', 'code' => 'CASA'],
            ['name' => 'Marrakech-Safi', 'code' => 'MARRAKECH'],
            ['name' => 'Drâa-Tafilalet', 'code' => 'DRAA'],
            ['name' => 'Souss-Massa', 'code' => 'SOUSS'],
            ['name' => 'Guelmim-Oued Noun', 'code' => 'GUELMIM'],
            ['name' => 'Laâyoune-Sakia El Hamra', 'code' => 'LAAYOUNE'],
            ['name' => 'Dakhla-Oued Eddahab', 'code' => 'DAKHLA'],
        ];
        foreach ($regions as $r) { Region::create($r); }
        $regionTTA = Region::where('code', 'TTA')->first();
        $regionOriental = Region::where('code', 'ORIENTAL')->first();
        $regionFes = Region::where('code', 'FES')->first();
        $regionRabat = Region::where('code', 'RABAT')->first();
        $regionBeni = Region::where('code', 'BENI')->first();
        $regionCasa = Region::where('code', 'CASA')->first();
        $regionMarrakech = Region::where('code', 'MARRAKECH')->first();
        $regionDraa = Region::where('code', 'DRAA')->first();
        $regionSouss = Region::where('code', 'SOUSS')->first();
        $regionGuelmim = Region::where('code', 'GUELMIM')->first();
        $regionLaayoune = Region::where('code', 'LAAYOUNE')->first();
        $regionDakhla = Region::where('code', 'DAKHLA')->first();

        // ============================================
        // 2. DEPOTS (6 dépôts dans différentes régions)
        // ============================================
        $depotTanger = Depot::create(['name' => 'Dépôt Tanger Méditerranée', 'location' => 'Zone Franche Tanger Med']);
        $depotOujda = Depot::create(['name' => 'Dépôt Oriental', 'location' => 'Zone Industrielle Oujda']);
        $depotFes = Depot::create(['name' => 'Dépôt Fès Nord', 'location' => 'Quartier Sidi Brahim, Fès']);
        $depotRabat = Depot::create(['name' => 'Dépôt Rabat Agdal', 'location' => 'Zone Agdal, Rabat']);
        $depotBeniMellal = Depot::create(['name' => 'Dépôt Béni Mellal', 'location' => 'Zone Industrille Béni Mellal']);
        $depotCasa = Depot::create(['name' => 'Dépôt Casablanca Central', 'location' => 'Zone Industrielle Tit Mellil']);
        $depotMarrakech = Depot::create(['name' => 'Dépôt Marrakech', 'location' => 'Zone Industrielle Sidi Ghanem']);
        $depotAgadir = Depot::create(['name' => 'Dépôt Agadir', 'location' => 'Zone Industrielle Agadir']);
        $depotLaayoune = Depot::create(['name' => 'Dépôt Laâyoune', 'location' => 'Zone Industrielle Laâyoune']);

        // ============================================
        // 3. CATEGORIES & PRODUITS (4 produits)
        // ============================================
        $catLevure = Category::create(['name' => 'Levure Fraîche']);
        $catFarine = Category::create(['name' => 'Farine']);
        $catAdditif = Category::create(['name' => 'Additifs Boulangerie']);

        // Produit 1: Levure remboursable
        $p1 = Product::create([
            'category_id' => $catLevure->id,
            'name' => 'L\'Hirondelle Fraîche 500g',
            'sku' => 'HIR-500',
            'price_ht' => 25.00,
            'tva_rate' => 20.00,
            'weight' => 0.5,
            'is_refundable' => true,
            'promo_type' => 'percentage',
            'promo_value' => 10.00,
            'promo_min_qty' => 5,
            'promo_start_date' => now()->subDays(30),
            'promo_end_date' => now()->subDays(10),
        ]);

        // Produit 2: Levure remboursable
        $p2 = Product::create([
            'category_id' => $catLevure->id,
            'name' => 'L\'Hirondelle Fraîche 1kg',
            'sku' => 'HIR-1KG',
            'price_ht' => 45.00,
            'tva_rate' => 20.00,
            'weight' => 1.0,
            'is_refundable' => true,
            'promo_type' => 'fixed',
            'promo_value' => 5.00,
            'promo_min_qty' => 10,
            'promo_start_date' => now()->subDay(),
            'promo_end_date' => now()->addDays(15),
        ]);

        // Produit 3: Farine remboursable
        $p3 = Product::create([
            'category_id' => $catFarine->id,
            'name' => 'Farine T55 Professionnelle 25kg',
            'sku' => 'FAR-T55-25',
            'price_ht' => 180.00,
            'tva_rate' => 20.00,
            'weight' => 25.0,
            'is_refundable' => true,
        ]);

        // Produit 4: NON remboursable
        $p4 = Product::create([
            'category_id' => $catAdditif->id,
            'name' => 'Améliorant Sur Mesure Spécial',
            'sku' => 'AMEL-CUSTOM',
            'price_ht' => 350.00,
            'tva_rate' => 20.00,
            'weight' => 5.0,
            'is_refundable' => false,
        ]);

        // ============================================
        // 4. CLIENTS (12 clients répartis dans les régions)
        // ============================================
        $clients = [
            ['company_name' => 'Boulangerie Al Madina', 'email' => 'almadina@gmail.com', 'phone' => '0522334455', 'address' => '12 Rue Hassan II, Casablanca', 'region_id' => $regionCasa->id],
            ['company_name' => 'Pâtisserie Royal Fès', 'email' => 'royalfes@gmail.com', 'phone' => '0535336677', 'address' => '45 Avenue des FAR, Fès', 'region_id' => $regionFes->id],
            ['company_name' => 'Boulangerie Moderne Rabat', 'email' => 'modernerabat@gmail.com', 'phone' => '0537123456', 'address' => '78 Avenue Mohammed V, Rabat', 'region_id' => $regionRabat->id],
            ['company_name' => 'Les Délices de Marrakech', 'email' => 'delices.marrakech@gmail.com', 'phone' => '0524445566', 'address' => '25 Rue Moulay Ali, Marrakech', 'region_id' => $regionMarrakech->id],
            ['company_name' => 'Boulangerie Tangerine', 'email' => 'tangarine@gmail.com', 'phone' => '0539900112', 'address' => '8 Boulevard Mohammed VI, Tanger', 'region_id' => $regionTTA->id],
            ['company_name' => 'Pains du Sud Agadir', 'email' => 'pains.agadir@gmail.com', 'phone' => '0528833445', 'address' => '56 Boulevard Hassan II, Agadir', 'region_id' => $regionSouss->id],
            ['company_name' => 'Boulangerie Oujda', 'email' => 'oujda.pain@gmail.com', 'phone' => '0536688990', 'address' => '33 Rue Ibn Sina, Oujda', 'region_id' => $regionOriental->id],
            ['company_name' => 'Le Fournil Béni Mellal', 'email' => 'fournil.beni@gmail.com', 'phone' => '0523487654', 'address' => '12 Avenue Mohamed VI, Béni Mellal', 'region_id' => $regionBeni->id],
            ['company_name' => 'Boulangerie Ouarzazate', 'email' => 'ouarzazate@gmail.com', 'phone' => '0524887733', 'address' => '5 Rue des Roses, Ouarzazate', 'region_id' => $regionDraa->id],
            ['company_name' => 'Pâtisserie Guelmim', 'email' => 'guelmim.pat@gmail.com', 'phone' => '0528877665', 'address' => '22 Avenue Hassan II, Guelmim', 'region_id' => $regionGuelmim->id],
            ['company_name' => 'Boulangerie du Sud Laâyoune', 'email' => 'laayoune.pain@gmail.com', 'phone' => '0528993344', 'address' => '15 Avenue Smara, Laâyoune', 'region_id' => $regionLaayoune->id],
            ['company_name' => 'Les Pains de Dakhla', 'email' => 'dakhla.pains@gmail.com', 'phone' => '0528991122', 'address' => '8 Avenue Al Wahda, Dakhla', 'region_id' => $regionDakhla->id],
        ];
        foreach ($clients as $c) { Client::create($c); }
        $clientCasa = Client::where('company_name', 'Boulangerie Al Madina')->first();
        $clientFes = Client::where('company_name', 'Pâtisserie Royal Fès')->first();
        $clientRabat = Client::where('company_name', 'Boulangerie Moderne Rabat')->first();
        $clientMarrakech = Client::where('company_name', 'Les Délices de Marrakech')->first();
        $clientTanger = Client::where('company_name', 'Boulangerie Tangerine')->first();
        $clientAgadir = Client::where('company_name', 'Pains du Sud Agadir')->first();

        // ============================================
        // 5. USERS (rôles multiples dans différentes régions)
        // ============================================
        $admin = User::create(['name' => 'Admin System', 'email' => 'admin@crm.ma', 'password' => Hash::make('password'), 'role' => 'admin']);
        $commercial1 = User::create(['name' => 'Commercial Casa', 'email' => 'com.casa@crm.ma', 'password' => Hash::make('password'), 'role' => 'commercial', 'region_id' => $regionCasa->id]);
        $commercial2 = User::create(['name' => 'Commercial Fès', 'email' => 'com.fes@crm.ma', 'password' => Hash::make('password'), 'role' => 'commercial', 'region_id' => $regionFes->id]);
        $commercial3 = User::create(['name' => 'Commercial Marrakech', 'email' => 'com.marrakech@crm.ma', 'password' => Hash::make('password'), 'role' => 'commercial', 'region_id' => $regionMarrakech->id]);
        $commercial4 = User::create(['name' => 'Commercial Tanger', 'email' => 'com.tanger@crm.ma', 'password' => Hash::make('password'), 'role' => 'commercial', 'region_id' => $regionTTA->id]);
        $commercial5 = User::create(['name' => 'Commercial Agadir', 'email' => 'com.agadir@crm.ma', 'password' => Hash::make('password'), 'role' => 'commercial', 'region_id' => $regionSouss->id]);
        $commercial6 = User::create(['name' => 'Commercial Rabat', 'email' => 'com.rabat@crm.ma', 'password' => Hash::make('password'), 'role' => 'commercial', 'region_id' => $regionRabat->id]);
        $depositaire1 = User::create(['name' => 'Lamine Depo Casa', 'email' => 'depo.casa@crm.ma', 'password' => Hash::make('password'), 'role' => 'depositaire', 'depot_id' => $depotCasa->id]);
        $depositaire2 = User::create(['name' => 'Karim Depo Fès', 'email' => 'depo.fes@crm.ma', 'password' => Hash::make('password'), 'role' => 'depositaire', 'depot_id' => $depotFes->id]);
        $depositaire3 = User::create(['name' => 'Youssef Depo Marrakech', 'email' => 'depo.marrakech@crm.ma', 'password' => Hash::make('password'), 'role' => 'depositaire', 'depot_id' => $depotMarrakech->id]);
        $livreur1 = User::create(['name' => 'Hassan Livreur Casa', 'email' => 'livreur.casa@crm.ma', 'password' => Hash::make('password'), 'role' => 'livreur', 'depot_id' => $depotCasa->id]);
        $livreur2 = User::create(['name' => 'Ahmed Livreur Fès', 'email' => 'livreur.fes@crm.ma', 'password' => Hash::make('password'), 'role' => 'livreur', 'depot_id' => $depotFes->id]);
        $livreur3 = User::create(['name' => 'Omar Livreur Rabat', 'email' => 'livreur.rabat@crm.ma', 'password' => Hash::make('password'), 'role' => 'livreur', 'depot_id' => $depotRabat->id]);
        $livreur4 = User::create(['name' => 'Mehdi Livreur Marrakech', 'email' => 'livreur.marrakech@crm.ma', 'password' => Hash::make('password'), 'role' => 'livreur', 'depot_id' => $depotMarrakech->id]);
        $livreur2 = User::create(['name' => 'Ahmed Livreur', 'email' => 'livreurfes@crm.ma', 'password' => Hash::make('password'), 'role' => 'livreur', 'depot_id' => $depotFes->id]);
        $livreur3 = User::create(['name' => 'Omar Livreur', 'email' => 'livreurrabat@crm.ma', 'password' => Hash::make('password'), 'role' => 'livreur', 'depot_id' => $depotRabat->id]);

        // ============================================
        // 6. TRUCKS (camions dans différents dépôts)
        // ============================================
        $truck1 = Truck::create(['livreur_id' => $livreur1->id, 'name' => 'Camion Isuzu 5T Casa', 'capacity' => 5000]);
        $truck2 = Truck::create(['livreur_id' => $livreur2->id, 'name' => 'Camion Renault 3T Fès', 'capacity' => 3000]);
        $truck3 = Truck::create(['livreur_id' => $livreur3->id, 'name' => 'Camion Mercedes 4T Rabat', 'capacity' => 4000]);
        $truck4 = Truck::create(['livreur_id' => $livreur4->id, 'name' => 'Camion Volvo 5T Marrakech', 'capacity' => 5000]);

        // ============================================
        // 7. STOCKS (dans tous les dépôts)
        // ============================================
        $allDepots = [$depotTanger, $depotOujda, $depotFes, $depotRabat, $depotBeniMellal, $depotCasa, $depotMarrakech, $depotAgadir, $depotLaayoune];
        foreach ($allDepots as $depot) {
            DepotStock::create(['depot_id' => $depot->id, 'product_id' => $p1->id, 'quantity' => 500]);
            DepotStock::create(['depot_id' => $depot->id, 'product_id' => $p2->id, 'quantity' => 300]);
            DepotStock::create(['depot_id' => $depot->id, 'product_id' => $p3->id, 'quantity' => 100]);
            DepotStock::create(['depot_id' => $depot->id, 'product_id' => $p4->id, 'quantity' => 50]);
        }
        TruckStock::create(['truck_id' => $truck1->id, 'product_id' => $p1->id, 'quantity' => 100]);
        TruckStock::create(['truck_id' => $truck1->id, 'product_id' => $p2->id, 'quantity' => 50]);
        TruckStock::create(['truck_id' => $truck2->id, 'product_id' => $p1->id, 'quantity' => 80]);
        TruckStock::create(['truck_id' => $truck3->id, 'product_id' => $p2->id, 'quantity' => 60]);

        // ============================================
        // 8. COMMANDES (statuts variés)
        // ============================================
        // Order 1: Pending - pas encore livrée (Client Casa)
        $order1 = Order::create(['type' => 'sale', 'client_id' => $clientCasa->id, 'created_by' => $commercial1->id, 'status' => 'pending', 'total_ht' => 250.00, 'total_tva' => 50.00, 'total_ttc' => 300.00]);
        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p1->id,
            'quantity' => 10,
            'price_unit_ht' => 25.00,
            'promo_type' => null,
            'promo_value' => 0,
            'final_price_ht' => 25.00,
            'discount_amount' => 0,
            'tva_rate' => 20.00,
            'total_ht' => 250.00,
            'total_tva' => 50.00,
            'total_ttc' => 300.00
        ]);

        // Order 2: Livrée complète avec promo fixed (Client Fès)
        $order2 = Order::create(['type' => 'sale', 'client_id' => $clientFes->id, 'created_by' => $commercial2->id, 'status' => 'livrer', 'total_ht' => 400.00, 'total_tva' => 80.00, 'total_ttc' => 480.00]);
        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $p2->id,
            'quantity' => 10,
            'price_unit_ht' => 45.00,
            'promo_type' => 'fixed',
            'promo_value' => 5.00,
            'final_price_ht' => 40.00,
            'discount_amount' => 50.00,
            'tva_rate' => 20.00,
            'total_ht' => 400.00,
            'total_tva' => 80.00,
            'total_ttc' => 480.00
        ]);

        // Order 3: Livrée complète sans promo (Client Rabat)
        $order3 = Order::create(['type' => 'sale', 'client_id' => $clientRabat->id, 'created_by' => $commercial1->id, 'status' => 'livrer', 'total_ht' => 180.00, 'total_tva' => 36.00, 'total_ttc' => 216.00]);
        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $p3->id,
            'quantity' => 1,
            'price_unit_ht' => 180.00,
            'promo_type' => null,
            'promo_value' => 0,
            'final_price_ht' => 180.00,
            'discount_amount' => 0,
            'tva_rate' => 20.00,
            'total_ht' => 180.00,
            'total_tva' => 36.00,
            'total_ttc' => 216.00
        ]);

        // Order 4: Livrée complète avec produit non remboursable (Client Casa)
        $order4 = Order::create(['type' => 'sale', 'client_id' => $clientCasa->id, 'created_by' => $commercial1->id, 'status' => 'livrer', 'total_ht' => 350.00, 'total_tva' => 70.00, 'total_ttc' => 420.00]);
        OrderItem::create([
            'order_id' => $order4->id,
            'product_id' => $p4->id,
            'quantity' => 1,
            'price_unit_ht' => 350.00,
            'promo_type' => null,
            'promo_value' => 0,
            'final_price_ht' => 350.00,
            'discount_amount' => 0,
            'tva_rate' => 20.00,
            'total_ht' => 350.00,
            'total_tva' => 70.00,
            'total_ttc' => 420.00
        ]);

        // ============================================
        // 9. LIVRAISONS (coherent avec les commandes)
        // ============================================
        // Delivery 1: Livrée complète pour order2 (10/10)
        $delivery1 = Delivery::create(['order_id' => $order2->id, 'livreur_id' => $livreur1->id, 'depot_id' => $depotCasa->id, 'status' => 'livrer', 'delivery_date' => now()->subDays(5), 'total_ht' => 400.00, 'total_tva' => 80.00, 'total_ttc' => 480.00]);
        $di1 = DeliveryItem::create(['delivery_id' => $delivery1->id, 'product_id' => $p2->id, 'qty_ordered' => 10, 'qty_delivered' => 10, 'returned_quantity' => 2, 'unit_price_ht' => 50.00, 'promo_type' => 'fixed', 'promo_value' => 10.00, 'tva_rate' => 20.00, 'total_ht' => 400.00, 'total_tva' => 80.00, 'total_ttc' => 480.00]);

        // Delivery 2: Livrée complète pour order3 (1/1)
        $delivery2 = Delivery::create(['order_id' => $order3->id, 'livreur_id' => $livreur2->id, 'depot_id' => $depotFes->id, 'status' => 'livrer', 'delivery_date' => now()->subDays(3), 'total_ht' => 180.00, 'total_tva' => 36.00, 'total_ttc' => 216.00]);
        $di2 = DeliveryItem::create(['delivery_id' => $delivery2->id, 'product_id' => $p3->id, 'qty_ordered' => 1, 'qty_delivered' => 1, 'returned_quantity' => 0, 'unit_price_ht' => 180.00, 'promo_type' => null, 'promo_value' => 0.00, 'tva_rate' => 20.00, 'total_ht' => 180.00, 'total_tva' => 36.00, 'total_ttc' => 216.00]);

        // Delivery 3: Livrée complète pour order4 (1/1)
        $delivery3 = Delivery::create(['order_id' => $order4->id, 'livreur_id' => $livreur1->id, 'depot_id' => $depotCasa->id, 'status' => 'livrer', 'delivery_date' => now()->subDays(2), 'total_ht' => 350.00, 'total_tva' => 70.00, 'total_ttc' => 420.00]);
        $di3 = DeliveryItem::create(['delivery_id' => $delivery3->id, 'product_id' => $p4->id, 'qty_ordered' => 1, 'qty_delivered' => 1, 'returned_quantity' => 0, 'unit_price_ht' => 350.00, 'promo_type' => null, 'promo_value' => 0.00, 'tva_rate' => 20.00, 'total_ht' => 350.00, 'total_tva' => 70.00, 'total_ttc' => 420.00]);

        // Delivery 4: En attente pour order1 (0/10)
        $delivery4 = Delivery::create(['order_id' => $order1->id, 'livreur_id' => $livreur3->id, 'depot_id' => $depotRabat->id, 'status' => 'pending', 'delivery_date' => now()->addDays(2), 'total_ht' => 0, 'total_tva' => 0, 'total_ttc' => 0]);
        DeliveryItem::create(['delivery_id' => $delivery4->id, 'product_id' => $p1->id, 'qty_ordered' => 10, 'qty_delivered' => 0, 'returned_quantity' => 0, 'unit_price_ht' => 25.00, 'promo_type' => null, 'promo_value' => 0.00, 'tva_rate' => 20.00, 'total_ht' => 0, 'total_tva' => 0, 'total_ttc' => 0]);

        // ============================================
        // 10. RETOURS (cohérents: total retours <= qty_delivered)
        // ============================================
        // Retours sur delivery1 (10 livrés, 2 retournés max)
        // Return 1: Pending - 1 unité invendu
        $return1 = ReturnModel::create(['delivery_id' => $delivery1->id, 'livreur_id' => $livreur1->id, 'depot_id' => $depotCasa->id, 'status' => 'pending', 'reason' => 'Client a refusé 1 unité', 'created_at' => now()->subDays(2)]);
        ReturnItem::create(['return_id' => $return1->id, 'product_id' => $p2->id, 'delivery_item_id' => $di1->id, 'quantity' => 1, 'condition_type' => 'unsold', 'notes' => 'Produit en parfait état']);

        // Return 2: Validated - 1 unité invendu (mis à jour dans returned_quantity)
        $return2 = ReturnModel::create(['delivery_id' => $delivery1->id, 'livreur_id' => $livreur1->id, 'depot_id' => $depotCasa->id, 'status' => 'validated', 'reason' => 'Trop de quantité commandée', 'validated_by' => $depositaire1->id, 'validated_at' => now()->subDays(3), 'created_at' => now()->subDays(4)]);
        ReturnItem::create(['return_id' => $return2->id, 'product_id' => $p2->id, 'delivery_item_id' => $di1->id, 'quantity' => 1, 'condition_type' => 'unsold', 'notes' => 'Client n\'a pas besoin de toute la quantité']);
        $di1->returned_quantity = 2; $di1->save(); // 1 (pending) + 1 (validated)

        // Return 3: Rejected
        $return3 = ReturnModel::create(['delivery_id' => $delivery1->id, 'livreur_id' => $livreur1->id, 'depot_id' => $depotCasa->id, 'status' => 'rejected', 'reason' => 'Retour après délai', 'validated_by' => $depositaire1->id, 'validated_at' => now()->subDay(), 'rejected_reason' => 'Délai dépassé.', 'created_at' => now()->subDays(8)]);
        ReturnItem::create(['return_id' => $return3->id, 'product_id' => $p2->id, 'delivery_item_id' => $di1->id, 'quantity' => 1, 'condition_type' => 'damaged', 'notes' => 'Retour tardif']);

        // Retours sur delivery2 (1 livré, 0 retourné - pas de retours)
        // (Pas de retours pour order3 - livraison complète acceptée)

        // ============================================
        // 11. MOUVEMENTS DE STOCK
        // ============================================
        StockMovement::create(['product_id' => $p1->id, 'depot_id' => $depotCasa->id, 'user_id' => $admin->id, 'type' => 'in', 'quantity' => 1000, 'reason' => 'Stock initial', 'moved_at' => now()->subDays(30)->setTime(12, 0, 0)]);
        StockMovement::create(['product_id' => $p2->id, 'depot_id' => $depotCasa->id, 'user_id' => $admin->id, 'type' => 'in', 'quantity' => 500, 'reason' => 'Stock initial', 'moved_at' => now()->subDays(30)->setTime(12, 0, 0)]);
        StockMovement::create(['product_id' => $p2->id, 'depot_id' => $depotCasa->id, 'user_id' => $livreur1->id, 'order_id' => $order2->id, 'type' => 'out', 'quantity' => 10, 'reason' => 'Livraison client', 'moved_at' => now()->subDays(5)->setTime(12, 0, 0)]);
        StockMovement::create(['product_id' => $p2->id, 'depot_id' => $depotCasa->id, 'user_id' => $depositaire1->id, 'return_id' => $return2->id, 'type' => 'in', 'quantity' => 1, 'reason' => 'Retour invendu validé', 'moved_at' => now()->subDays(3)->setTime(12, 0, 0)]);

        // 50 mouvements aléatoires
        $reasons_in = ['Réception arrivage', 'Ajustement (+)', 'Retour client', 'Correction', 'Transfert'];
        $reasons_out = ['Livraison', 'Ajustement (-)', 'Périmé retiré', 'Échantillon', 'Transfert', 'Détruit'];
        $activeDepots = [$depotCasa, $depotFes, $depotRabat, $depotMarrakech, $depotTanger, $depotAgadir];
        $activeUsers = [$admin, $depositaire1, $depositaire2, $depositaire3, $livreur1, $livreur2, $livreur3, $livreur4];
        for ($i = 0; $i < 400; $i++) {
            $depot = $activeDepots[array_rand($activeDepots)];
            $product = [$p1, $p2, $p3][rand(0, 2)];
            $user = $activeUsers[array_rand($activeUsers)];
            $type = rand(0, 1) ? 'in' : 'out';
            StockMovement::create(['product_id' => $product->id, 'depot_id' => $depot->id, 'user_id' => $user->id, 'type' => $type, 'quantity' => rand(5, 100), 'reason' => $type === 'in' ? $reasons_in[array_rand($reasons_in)] : $reasons_out[array_rand($reasons_out)], 'moved_at' => now()->subDays(rand(1, 60))->setTime(rand(8, 17), 0, 0)]);
        }

        // ============================================
        // 12. GENERATION DE DONNEES MASSIVES (Commandes, Livraisons, Retours)
        // ============================================
        $this->command->info('Generation des donnees massives...');
        $faker = \Faker\Factory::create('fr_FR');
        $allClients = Client::all();
        $allProducts = Product::all();
        $commercials = User::where('role', 'commercial')->get();
        $livreurs = User::where('role', 'livreur')->get();
        $depositaires = User::where('role', 'depositaire')->get();
        $allDepotsCollection = Depot::all();

        for ($i = 0; $i < 100; $i++) {
            $client = $allClients->random();
            $commercial = $commercials->random();
            $status = $faker->randomElement(['pending', 'livrer', 'livrer', 'livrer']); 
            $orderDate = $faker->dateTimeBetween('-6 months', 'now');
            
            $numItems = rand(1, 3);
            $orderItemsData = [];
            $totalHt = 0;
            $totalTva = 0;
            $totalTtc = 0;
            
            $orderProducts = $allProducts->random($numItems);
            
            foreach ($orderProducts as $prod) {
                $qty = rand(1, 20);
                $priceHt = $prod->price_ht;
                $tvaRate = $prod->tva_rate;
                $promo_type = $prod->promo_type;
                $promo_value = $prod->promo_value;
                
                $discount = 0;
                $finalPriceHt = $priceHt;
                if ($prod->promo_value > 0 && $prod->promo_type) {
                    if ($prod->promo_type === 'percentage') {
                        $discount = ($priceHt * $prod->promo_value / 100);
                        $finalPriceHt = $priceHt - $discount;
                    } elseif ($prod->promo_type === 'fixed') {
                        $discount = $prod->promo_value;
                        $finalPriceHt = $priceHt - $discount;
                    }
                }
                
                $lineTotalHt = $finalPriceHt * $qty;
                $lineTotalTva = $lineTotalHt * ($tvaRate / 100);
                $lineTotalTtc = $lineTotalHt + $lineTotalTva;
                
                $totalHt += $lineTotalHt;
                $totalTva += $lineTotalTva;
                $totalTtc += $lineTotalTtc;
                
                $orderItemsData[] = [
                    'product_id' => $prod->id,
                    'quantity' => $qty,
                    'price_unit_ht' => $priceHt,
                    'promo_type' => $promo_type,
                    'promo_value' => $promo_value,
                    'final_price_ht' => $finalPriceHt,
                    'discount_amount' => $discount * $qty,
                    'tva_rate' => $tvaRate,
                    'total_ht' => $lineTotalHt,
                    'total_tva' => $lineTotalTva,
                    'total_ttc' => $lineTotalTtc,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ];
            }
            
            $order = Order::create([
                'type' => 'sale',
                'client_id' => $client->id,
                'created_by' => $commercial->id,
                'status' => $status,
                'total_ht' => $totalHt,
                'total_tva' => $totalTva,
                'total_ttc' => $totalTtc,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);
            
            foreach ($orderItemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }
            
            if ($status === 'livrer') {
                $livreur = $livreurs->random();
                $depot = $allDepotsCollection->random();
                
                $deliveryDate = clone $orderDate;
                $deliveryDate->modify('+' . rand(1, 3) . ' days');
                if ($deliveryDate > now()) $deliveryDate = now();
                
                $delivery = Delivery::create([
                    'order_id' => $order->id,
                    'livreur_id' => $livreur->id,
                    'depot_id' => $depot->id,
                    'status' => 'livrer',
                    'delivery_date' => $deliveryDate,
                    'total_ht' => $totalHt,
                    'total_tva' => $totalTva,
                    'total_ttc' => $totalTtc,
                    'created_at' => $deliveryDate,
                    'updated_at' => $deliveryDate,
                ]);
                
                foreach ($orderItemsData as $itemData) {
                    $qtyOrdered = $itemData['quantity'];
                    $qtyDelivered = (rand(1, 100) > 90) ? rand(min(1, $qtyOrdered), $qtyOrdered) : $qtyOrdered;
                    
                    $lineTotalHt = $itemData['final_price_ht'] * $qtyDelivered;
                    $lineTotalTva = $lineTotalHt * ($itemData['tva_rate'] / 100);
                    $lineTotalTtc = $lineTotalHt + $lineTotalTva;
                    
                    $di = DeliveryItem::create([
                        'delivery_id' => $delivery->id,
                        'product_id' => $itemData['product_id'],
                        'qty_ordered' => $qtyOrdered,
                        'qty_delivered' => $qtyDelivered,
                        'returned_quantity' => 0,
                        'unit_price_ht' => $itemData['final_price_ht'],
                        'promo_type' => $itemData['promo_type'],
                        'promo_value' => $itemData['promo_value'],
                        'tva_rate' => $itemData['tva_rate'],
                        'total_ht' => $lineTotalHt,
                        'total_tva' => $lineTotalTva,
                        'total_ttc' => $lineTotalTtc,
                        'created_at' => $deliveryDate,
                        'updated_at' => $deliveryDate,
                    ]);
                    
                    if ($qtyDelivered > 0 && rand(1, 100) > 85) {
                        $retQty = rand(1, $qtyDelivered);
                        $di->returned_quantity = $retQty;
                        $di->save();
                        
                        $retStatus = $faker->randomElement(['pending', 'validated', 'rejected']);
                        $retDate = clone $deliveryDate;
                        $retDate->modify('+' . rand(1, 5) . ' days');
                        if ($retDate > now()) $retDate = now();
                        
                        $retValidator = $retStatus !== 'pending' ? $depositaires->random()->id : null;
                        
                        $returnM = ReturnModel::create([
                            'delivery_id' => $delivery->id,
                            'livreur_id' => $livreur->id,
                            'depot_id' => $depot->id,
                            'status' => $retStatus,
                            'reason' => $faker->sentence(),
                            'validated_by' => $retValidator,
                            'validated_at' => $retStatus !== 'pending' ? clone $retDate : null,
                            'rejected_reason' => $retStatus === 'rejected' ? 'Non conforme' : null,
                            'created_at' => clone $retDate,
                            'updated_at' => clone $retDate,
                        ]);
                        
                        ReturnItem::create([
                            'return_id' => $returnM->id,
                            'product_id' => $itemData['product_id'],
                            'delivery_item_id' => $di->id,
                            'quantity' => $retQty,
                            'condition_type' => $faker->randomElement(['unsold', 'damaged']),
                            'notes' => $faker->sentence(),
                        ]);
                    }
                }
            }
        }

        $this->command->info('Seeding completed!');
        $this->command->info('Login: admin@crm.ma / password');
    }
}
