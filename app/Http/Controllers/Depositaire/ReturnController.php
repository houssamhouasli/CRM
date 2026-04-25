<?php

namespace App\Http\Controllers\Depositaire;

use App\Http\Controllers\Controller;
use App\Models\ReturnModel;
use App\Models\ReturnItem;
use App\Models\DepotStock;
use App\Models\TruckStock; 
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        return view('depositaire.returns.index');
    }

    public function show(ReturnModel $return)
    {
        $return->load(['delivery.order.client', 'returnItems.product', 'livreur', 'validator']);

        return view('depositaire.returns.show', compact('return'));
    }

    public function validate(Request $request, ReturnModel $return)
    {
        $user = auth()->user();

        if ($return->depot_id !== $user->depot_id) {
            abort(403, 'Vous ne pouvez pas valider les retours d\'un autre dépôt.');
        }

        try {
            $this->validateReturn($return, $user->id);

            return redirect()->back()->with('success', 'Retour #' . $return->id . ' validé avec succès. Les stocks ont été mis à jour.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, ReturnModel $return)
    {
        $user = auth()->user();

        if ($return->depot_id !== $user->depot_id) {
            abort(403, 'Vous ne pouvez pas rejeter les retours d\'un autre dépôt.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        try {
            $this->rejectReturn($return, $user->id, $validated['reason']);

            return redirect()->back()->with('success', 'Retour #' . $return->id . ' rejeté.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function validateReturn(ReturnModel $return, int $validatorId): ReturnModel
    {
        return DB::transaction(function () use ($return, $validatorId) {
            if (!$return->isPending()) {
                throw new \Exception('Seuls les retours en attente peuvent être validés.');
            }

            $delivery = $return->delivery;
            $livreur = $return->livreur;
            $truck = $livreur->truck;

            foreach ($return->returnItems as $returnItem) {
                $this->processReturnItem($return, $returnItem, $truck, $validatorId);
            }

            $return->update([
                'status' => 'validated',
                'validator_id' => $validatorId,
                'validated_at' => now(),
            ]);

            return $return->fresh();
        });
    }

    private function rejectReturn(ReturnModel $return, int $validatorId, ?string $reason = null): ReturnModel
    {
        if (!$return->isPending()) {
            throw new \Exception('Seuls les retours en attente peuvent être rejetés.');
        }

        $return->update([
            'status' => 'rejected',
            'validator_id' => $validatorId,
            'validated_at' => now(),
            'rejected_reason' => $reason,
        ]);

        return $return->fresh();
    }

    private function processReturnItem(ReturnModel $return, ReturnItem $returnItem, $truck, int $validatorId): void
    {
        $product = $returnItem->product;
        $quantity = $returnItem->quantity;
        $deliveryItem = $returnItem->deliveryItem;
        $depotId = $return->depot_id;

        $deliveryItem->returned_quantity += $quantity;
        $deliveryItem->save();

        if ($truck) {
            $truckStock = TruckStock::firstOrCreate(
                ['truck_id' => $truck->id, 'product_id' => $product->id],
                ['quantity' => 0]
            );

            if ($truckStock->quantity < $quantity) {
                throw new \Exception(
                    "Stock camion insuffisant pour {$product->name}. " .
                    "Disponible: {$truckStock->quantity}, Demandé: {$quantity}"
                );
            }

            $truckStock->quantity -= $quantity;
            $truckStock->save();
        }

        switch ($returnItem->condition_type) {
            case 'unsold':
                $this->processUnsoldReturn($product, $quantity, $depotId, $return, $validatorId);
                break;

            case 'damaged':
                $this->processDamagedReturn($product, $quantity, $depotId, $return, $validatorId);
                break;

            case 'expired':
                $this->processExpiredReturn($product, $quantity, $depotId, $return, $validatorId);
                break;

            default:
                throw new \Exception("Type de condition invalide: {$returnItem->condition_type}");
        }
    }

    private function processUnsoldReturn($product, int $quantity, ?int $depotId, ReturnModel $return, int $userId): void
    {
        if ($depotId) {
            $depotStock = DepotStock::firstOrCreate(
                ['depot_id' => $depotId, 'product_id' => $product->id],
                ['quantity' => 0]
            );
            $depotStock->quantity += $quantity;
            $depotStock->save();
        }

        StockMovement::create([
            'product_id' => $product->id,
            'depot_id' => $depotId,
            'user_id' => $userId,
            'return_id' => $return->id,
            'type' => 'in',
            'quantity' => $quantity,
            'reason' => 'Retour invendu - remis en stock',
        ]);
    }

    private function processDamagedReturn($product, int $quantity, ?int $depotId, ReturnModel $return, int $userId): void
    {
        StockMovement::create([
            'product_id' => $product->id,
            'depot_id' => $depotId,
            'user_id' => $userId,
            'return_id' => $return->id,
            'type' => 'out',
            'quantity' => $quantity,
            'reason' => 'Retour produit endommagé - perte',
        ]);
    }

    private function processExpiredReturn($product, int $quantity, ?int $depotId, ReturnModel $return, int $userId): void
    {
        StockMovement::create([
            'product_id' => $product->id,
            'depot_id' => $depotId,
            'user_id' => $userId,
            'return_id' => $return->id,
            'type' => 'out',
            'quantity' => $quantity,
            'reason' => 'Retour produit périmé - stock périmé',
        ]);
    }
}
