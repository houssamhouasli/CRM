<!DOCTYPE html>
<html lang="fr" class="bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon de Livraison #{{ $delivery->id }} - Lesaffre</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0a3b8f;
            --primary-soft: #f0f4fa;
            --secondary: #6c757d;
            --dark: #1e293b;
            --border: #e2e8f0;
            --success: #1a7a4a;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--dark);
            line-height: 1.5;
            margin: 0;
            padding: 40px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.5px;
        }

        .doc-title {
            text-align: right;
        }

        .doc-title h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .box {
            padding: 15px;
            border: 1px solid var(--border);
            border-radius: 8px;
        }

        .box h3 {
            margin-top: 0;
            font-size: 14px;
            text-transform: uppercase;
            color: var(--secondary);
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .info-row {
            margin-bottom: 4px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            background-color: var(--primary-soft);
            color: var(--primary);
            text-align: left;
            padding: 12px;
            font-weight: 600;
            font-size: 13px;
            border-bottom: 2px solid var(--primary);
        }

        td {
            padding: 12px;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
        }

        .text-end { text-align: right; }
        .text-center { text-align: center; }

        .totals {
            margin-left: auto;
            width: 300px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--border);
        }

        .grand-total {
            background-color: var(--primary);
            color: white;
            padding: 12px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 18px;
            margin-top: 10px;
        }

        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-top: 60px;
            padding-top: 20px;
        }

        .signature-box {
            border: 1px dashed var(--secondary);
            height: 120px;
            border-radius: 8px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 10px;
            color: var(--secondary);
            font-size: 12px;
        }

        .footer {
            margin-top: 50px;
            font-size: 10px;
            color: var(--secondary);
            text-align: center;
            border-top: 1px solid var(--border);
            padding-top: 20px;
        }

        @media print {
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <div class="logo">LESAFFRE LOGISTIQUE</div>
            <div style="font-size: 12px; color: var(--secondary); margin-top: 4px;">Pôle d'Excellence Opérationnelle</div>
        </div>
        <div class="doc-title">
            <h1>BON DE LIVRAISON (BL)</h1>
            <div style="font-size: 14px; color: var(--secondary);">Nº : {{ $delivery->id }}</div>
            <div style="font-size: 14px; color: var(--secondary);">Date livraison : {{ $delivery->delivery_date ? $delivery->delivery_date->format('d/m/Y') : date('d/m/Y') }}</div>
            <div style="font-size: 12px; color: var(--secondary); margin-top: 4px;">Commande d'origine : #{{ $delivery->order_id }}</div>
        </div>
    </div>

    <div class="grid">
        <div class="box">
            <h3>CLIENT / DESTINATAIRE</h3>
            <div class="info-row"><strong>{{ $delivery->order->client->company_name }}</strong></div>
            <div class="info-row">{{ $delivery->order->client->address }}</div>
            <div class="info-row">Région : {{ $delivery->order->client->region->name ?? '—' }}</div>
            <div class="info-row">Tél : {{ $delivery->order->client->phone ?? '—' }}</div>
        </div>
        <div class="box">
            <h3>LOGISTIQUE / TRANSPORT</h3>
            <div class="info-row"><strong>Livreur :</strong> {{ $delivery->livreur->name ?? 'Non assigné' }}</div>
            <div class="info-row"><strong>Véhicule :</strong> {{ $delivery->livreur?->truck?->name ?? '—' }}</div>
            <div class="info-row"><strong>Dépôt Expéditeur :</strong> {{ $delivery->depot->name ?? 'Principal' }}</div>
            <div class="info-row"><strong>Statut BL :</strong> <span style="color: var(--success); font-weight: 600;">LIVRÉ</span></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>PRODUIT</th>
                <th class="text-center">CMD / LIVRÉ</th>
                <th class="text-end">P.U. HT</th>
                <th class="text-center">PROMO</th>
                <th class="text-center">TVA</th>
                <th class="text-end">TOTAL HT</th>
                <th class="text-end">TOTAL TTC</th>
            </tr>
        </thead>
        <tbody>
            @foreach($delivery->items as $item)
            <tr>
                <td>
                    <strong>{{ $item->product->name }}</strong><br>
                    <small style="color:var(--secondary)">REF: {{ $item->product->sku ?? '—' }}</small>
                </td>
                <td class="text-center">{{ $item->qty_ordered }} / <strong>{{ $item->qty_delivered }}</strong> {{ $item->product->unit ?? 'Kg' }}</td>
                <td class="text-end">
                    @if($item->promo_type && $item->promo_value > 0)
                        <div>
                            <span style="text-decoration: line-through; color: var(--secondary); font-size: 11px;">{{ number_format($item->unit_price_ht, 2, ',', ' ') }} MAD</span><br>
                            @if($item->promo_type === 'percentage')
                                @php $finalPrice = $item->unit_price_ht * (1 - $item->promo_value / 100); @endphp
                                <strong style="color: var(--success);">{{ number_format($finalPrice, 2, ',', ' ') }} MAD</strong>
                            @else
                                @php $finalPrice = $item->unit_price_ht - $item->promo_value; @endphp
                                <strong style="color: var(--success);">{{ number_format($finalPrice, 2, ',', ' ') }} MAD</strong>
                            @endif
                        </div>
                    @else
                        <strong>{{ number_format($item->unit_price_ht ?? 0, 2, ',', ' ') }} MAD</strong>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->promo_type && $item->promo_value > 0)
                        @if($item->promo_type === 'percentage')
                            <span style="background: #ffc107; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: 600;">-{{ number_format($item->promo_value, 1) }}%</span>
                        @else
                            <span style="background: #ffc107; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: 600;">-{{ number_format($item->promo_value, 2, ',', ' ') }} MAD</span>
                        @endif
                    @else
                        <span style="color: var(--secondary);">—</span>
                    @endif
                </td>
                <td class="text-center">{{ number_format($item->tva_rate ?? 20, 0) }}%</td>
                <td class="text-end">{{ number_format($item->total_ht ?? 0, 2, ',', ' ') }} MAD</td>
                <td class="text-end"><strong>{{ number_format($item->total_ttc ?? 0, 2, ',', ' ') }} MAD</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div style="font-size: 12px; max-width: 400px; color: var(--secondary);">
            <strong>Notes & Remarques :</strong><br>
            Les marchandises énumérées ci-dessus sont reçues en bon état et conformes à la commande.
            Toute réclamation doit être faite au moment de la livraison.
        </div>
        <div class="totals">
            <div class="total-row"><span>Total HT</span><span>{{ number_format($delivery->total_ht ?? 0, 2, ',', ' ') }} MAD</span></div>
            <div class="total-row"><span>Total TVA</span><span>{{ number_format($delivery->total_tva ?? 0, 2, ',', ' ') }} MAD</span></div>
            <div class="grand-total">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 14px; font-weight: 400; opacity: 0.9;">TOTAL TTC</span>
                    <span>{{ number_format($delivery->total_ttc ?? 0, 2, ',', ' ') }} MAD</span>
                </div>
            </div>
        </div>
    </div>

    <div class="signatures">
        <div>
            <div style="font-size: 14px; font-weight: 600; text-align: center; margin-bottom: 10px;">Signature & Cachet Livreur</div>
            <div class="signature-box">Responsable Transport</div>
        </div>
        <div>
            <div style="font-size: 14px; font-weight: 600; text-align: center; margin-bottom: 10px;">Signature & Cachet Client</div>
            <div class="signature-box">Réceptionnaire Final</div>
        </div>
    </div>

    <div class="footer">
        Document BL Nº{{ $delivery->id }} - Généré informatiquement le {{ date('d/m/Y à H:i') }} - LESAFFRE<br>
        <em>Ce document est un titre de transport et de réception officiel.</em>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 600);
        };

        window.onafterprint = function() {
            if (document.referrer) {
                window.location.href = document.referrer;
            } else {
                window.history.back();
            }
        };
    </script>
</body>
</html>
