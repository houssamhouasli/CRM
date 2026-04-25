<!DOCTYPE html>
<html lang="fr" class="bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon de Commande #{{ $order->id }} - Lesaffre</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0a3b8f;
            --primary-soft: #f0f4fa;
            --secondary: #6c757d;
            --dark: #1e293b;
            --border: #e2e8f0;
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

        .order-title {
            text-align: right;
        }

        .order-title h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
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

        .footer {
            margin-top: 50px;
            font-size: 12px;
            color: var(--secondary);
            text-align: center;
            border-top: 1px solid var(--border);
            padding-top: 20px;
        }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <div class="logo">LESAFFRE LOGISTIQUE</div>
            <div style="font-size: 12px; color: var(--secondary); margin-top: 4px;">Pôle d'Excellence Opérationnelle</div>
        </div>
        <div class="order-title">
            <h1>BON DE COMMANDE</h1>
            <div style="font-size: 14px; color: var(--secondary);">Nº : {{ $order->id }}</div>
            <div style="font-size: 14px; color: var(--secondary);">Date : {{ $order->order_date->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="grid">
        <div class="box">
            <h3>CLIENT / DESTINATAIRE</h3>
            <div class="info-row"><strong>{{ $order->client->company_name ?? 'Client Direct' }}</strong></div>
            <div class="info-row">{{ $order->client->address ?? 'Adresse de livraison' }}</div>
            <div class="info-row">Région : {{ $order->client->region->name ?? '—' }}</div>
            <div class="info-row">Tél : {{ $order->client->phone ?? '—' }}</div>
        </div>
        <div class="box">
            <h3>ÉMIS PAR / ORIGINE</h3>
            <div class="info-row"><strong>LESAFFRE MAROC</strong></div>
            <div class="info-row">{{ $order->creator->name ?? 'Système' }}</div>
            <div class="info-row">Statut : {{ $order->status_label }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>PRODUIT</th>
                <th class="text-center">QTÉ</th>
                @if($order->type !== 'restock')
                    <th class="text-center">LIVRÉ</th>
                @endif
                <th class="text-end">PRIX UNIT.</th>
                <th class="text-center">TVA</th>
                <th class="text-end">TOTAL HT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    <strong>{{ $item->product->name }}</strong><br>
                    <small style="color:var(--secondary)">REF: {{ $item->product->sku ?? '—' }}</small>
                    @if($item->promo_type && $item->promo_value > 0)
                        <br><small style="color:#dc3545; font-weight:600;">
                            @if($item->promo_type === 'percentage')
                                Promo: -{{ number_format($item->promo_value, 0) }}%
                            @else
                                Promo: -{{ number_format($item->promo_value, 2) }} MAD
                            @endif
                        </small>
                    @endif
                </td>
                <td class="text-center">{{ $item->quantity }} {{ $item->product->unit }}</td>
                @if($order->type !== 'restock')
                    <td class="text-center">{{ $item->delivered }} {{ $item->product->unit }}</td>
                @endif
                <td class="text-end">
                    @if($item->promo_type && $item->promo_value > 0)
                        <span style="text-decoration: line-through; color:var(--secondary); font-size:11px;">{{ number_format($item->price_unit_ht, 2, ',', ' ') }}</span><br>
                        <strong style="color:#dc3545;">{{ number_format($item->final_price_ht ?? ($item->promo_type === 'percentage' ? $item->price_unit_ht * (1 - $item->promo_value/100) : $item->price_unit_ht - $item->promo_value), 2, ',', ' ') }}</strong>
                    @else
                        <strong>{{ number_format($item->price_unit_ht, 2, ',', ' ') }}</strong>
                    @endif
                    MAD
                </td>
                <td class="text-center">{{ number_format($item->tva_rate, 0) }}%</td>
                <td class="text-end">{{ number_format($item->total_ht, 2, ',', ' ') }} MAD</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="total-row"><span>Sous-total HT</span><span>{{ number_format($order->total_ht, 2, ',', ' ') }} MAD</span></div>
        <div class="total-row"><span>Total TVA</span><span>{{ number_format($order->total_tva, 2, ',', ' ') }} MAD</span></div>
        <div class="grand-total">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 14px; font-weight: 400; opacity: 0.9;">TOTAL TTC</span>
                <span>{{ number_format($order->total_ttc, 2, ',', ' ') }} MAD</span>
            </div>
        </div>
    </div>

    <div class="footer">
        Document généré le {{ date('d/m/Y à H:i') }} - Logiciel de Gestion LESAFFRE<br>
        <em>Ce document tient lieu de bon de commande officiel et est prêt pour impression ou sauvegarde PDF.</em>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
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
