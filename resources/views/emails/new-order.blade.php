<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Commande Lesaffre Maroc</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f7f9;
            padding-bottom: 40px;
        }

        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 700px;
            border-spacing: 0;
            color: #4a4a4a;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: linear-gradient(135deg, #0a3b8f 0%, #072763 100%);
            padding: 40px 20px;
            text-align: center;
            color: #ffffff;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .content {
            padding: 40px 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #0a3b8f;
            margin-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 8px;
            text-transform: uppercase;
        }

        .info-grid {
            width: 100%;
            margin-bottom: 30px;
            border-spacing: 0;
        }

        .info-label {
            color: #888888;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 6px 0;
            width: 130px;
        }

        .info-value {
            color: #333333;
            font-size: 14px;
            font-weight: 500;
            padding: 6px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 1px solid #f0f0f0;
        }

        .items-table th {
            text-align: left;
            font-size: 11px;
            color: #888888;
            padding: 10px;
            background-color: #fafbfc;
            border-bottom: 2px solid #f0f0f0;
            text-transform: uppercase;
        }

        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f6f6f6;
            font-size: 13px;
            vertical-align: top;
        }

        .total-box {
            width: 100%;
            border-spacing: 0;
            margin-top: 25px;
        }

        .total-box td {
            padding: 5px 0;
            font-size: 14px;
        }

        .total-label {
            color: #777777;
            text-align: right;
            padding-right: 20px !important;
        }

        .total-value {
            color: #333333;
            font-weight: 600;
            text-align: right;
            width: 120px;
        }

        .grand-total td {
            border-top: 2px solid #0a3b8f;
            padding-top: 15px;
        }

        .footer {
            text-align: center;
            padding: 30px 20px;
            color: #999999;
            font-size: 12px;
            line-height: 1.5;
        }

        .btn {
            display: inline-block;
            background-color: #0a3b8f;
            color: #ffffff !important;
            padding: 12px 25px;
            font-weight: 700;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }

        .badge {
            background: #eef2f7;
            color: #0a3b8f;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <table class="main">
            <tr>
                <td class="header">
                    <h1>🛒 Nouvelle Commande</h1>
                    <div style="margin-top: 10px; opacity: 0.8; font-size: 14px;">Référence: #{{ $order->id }}</div>
                </td>
            </tr>
            <tr>
                <td class="content">
                    <div class="section-title">Informations Générales</div>
                    <table class="info-grid">
                        <tr>
                            <td class="info-label">Client</td>
                            <td class="info-value"><strong>{{ $client->company_name }}</strong></td>
                        </tr>
                        <tr>
                            <td class="info-label">Région</td>
                            <td class="info-value"><span class="badge">{{ $client->region->name ?? 'N/A' }}</span></td>
                        </tr>
                        <tr>
                            <td class="info-label">Créée par</td>
                            <td class="info-value">{{ $creator->name ?? 'Système' }}
                                ({{ ucfirst($creator->role ?? 'admin') }})</td>
                        </tr>
                        <tr>
                            <td class="info-label">Date</td>
                            <td class="info-value">{{ $order->order_date?->format('d/m/Y H:i') ?? '—' }}</td>
                        </tr>
                    </table>

                    <div class="section-title">Articles Commandés</div>
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>DESCRIPTION PRODUIT</th>
                                <th style="text-align: center;">QTÉ</th>
                                <th style="text-align: right;">PRIX U. HT</th>
                                <th style="text-align: right;">TVA</th>
                                <th style="text-align: right;">TOTAL HT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>
                                        <div style="color: #888; font-size: 11px; margin-bottom: 2px;">REF:
                                            {{ $item->product->sku ?? 'N/A' }}</div>
                                        <div style="font-weight: 600; color: #333; margin-bottom: 4px; font-size: 14px;">
                                            {{ $item->product->name }}</div>
                                        @if($item->promo_type && $item->promo_value > 0)
                                            <div
                                                style="font-size: 10px; color: #e74c3c; background: #fff5f5; padding: 2px 6px; display: inline-block; border-radius: 3px; border: 1px solid #ffdada; font-weight: 600;">
                                                🎁 OFFRE :
                                                -{{ $item->promo_type === 'percentage' ? number_format($item->promo_value, 0) . '%' : number_format($item->promo_value, 2) . ' MAD' }}
                                            </div>
                                        @endif
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <span style="font-weight: 600; color: #333;">{{ $item->quantity }}</span>
                                        <small
                                            style="color: #999; display: block; font-size: 10px;">{{ $item->product->unit }}</small>
                                    </td>
                                    <td style="text-align: right; vertical-align: middle;">
                                        @if($item->price_unit_ht > ($item->final_price_ht ?? $item->price_unit_ht))
                                            <div style="text-decoration: line-through; color: #bbb; font-size: 11px;">
                                                {{ number_format($item->price_unit_ht, 2, ',', ' ') }}</div>
                                        @endif
                                        <div style="font-weight: 500;">
                                            {{ number_format($item->final_price_ht ?? $item->price_unit_ht, 2, ',', ' ') }}
                                            <small style="font-size: 9px; color: #999;">DH</small></div>
                                    </td>
                                    <td style="text-align: right; vertical-align: middle; color: #888; font-size: 12px;">
                                        {{ number_format($item->tva_rate ?? 20, 0) }}%</td>
                                    <td
                                        style="text-align: right; vertical-align: middle; font-weight: 700; color: #0a3b8f; font-size: 14px;">
                                        {{ number_format($item->total_ht, 2, ',', ' ') }} <small
                                            style="font-size: 9px;">DH</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="total-box">
                        <tr>
                            <td class="total-label">Sous-total Hors Taxes</td>
                            <td class="total-value">{{ number_format($order->total_ht, 2, ',', ' ') }} MAD</td>
                        </tr>
                        <tr>
                            <td class="total-label">Total TVA</td>
                            <td class="total-value">{{ number_format($order->total_tva, 2, ',', ' ') }} MAD</td>
                        </tr>
                        <tr class="grand-total">
                            <td class="total-label" style="font-size: 16px; color: #0a3b8f;">MONTANT TOTAL TTC</td>
                            <td class="total-value" style="font-size: 18px; color: #0a3b8f;">
                                {{ number_format($order->total_ttc, 2, ',', ' ') }} MAD</td>
                        </tr>
                    </table>

                    <div style="text-align: center; margin-top: 40px;">
                        <a href="{{ url('/depositaire/orders/' . $order->id) }}" class="btn">Visualiser la Commande</a>
                    </div>
                </td>
            </tr>
        </table>
        <div class="footer">
            <p>© {{ date('Y') }} Lesaffre Maroc - CRM Stock & Delivery<br>Ceci est un e-mail automatique, merci de ne
                pas y répondre.</p>
        </div>
    </div>
</body>

</html>