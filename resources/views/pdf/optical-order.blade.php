<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bon de commande — {{ $order->order_number }}</title>
    @include('pdf.partials.document-styles')
    <style>
        .section-title { color: #0f4c81; font-size: 13px; font-weight: bold; margin: 20px 0 8px; padding-bottom: 4px; border-bottom: 1px solid #e2e8f0; text-transform: uppercase; }
        .info-row { display: table; width: 100%; margin-bottom: 4px; }
        .info-label { color: #64748b; display: table-cell; font-size: 11px; width: 45%; }
        .info-value { display: table-cell; font-weight: 500; }
        .status-badge { background: #dbeafe; border-radius: 20px; color: #1e40af; display: inline-block; font-size: 11px; font-weight: bold; padding: 3px 12px; }
        .amount-row { display: table; width: 100%; padding: 4px 0; }
        .amount-label { color: #475569; display: table-cell; }
        .amount-value { display: table-cell; font-weight: 600; text-align: right; }
        .amount-total { border-top: 2px solid #0f4c81; color: #0f4c81; font-size: 14px; font-weight: bold; }
        .remaining { color: #1d4ed8; }
    </style>
</head>
<body>
    <header class="header">
        <p class="clinic-name">{{ $settings['clinic_name'] }}</p>
        <p class="muted">{{ $settings['clinic_slogan'] }}</p>
        <p class="muted">{{ $settings['clinic_address'] }} · {{ $settings['clinic_phone'] }} · {{ $settings['clinic_email'] }}</p>
    </header>

    <h1 class="title">Bon de commande optique</h1>

    <div class="grid">
        <div class="col" style="padding-right:16px">
            <div class="box">
                <strong>Commande</strong><br><br>
                <div class="info-row"><span class="info-label">Numéro</span><span class="info-value">{{ $order->order_number }}</span></div>
                <div class="info-row"><span class="info-label">Date</span><span class="info-value">{{ $order->created_at->format('d/m/Y') }}</span></div>
                @if($order->expected_date)
                    <div class="info-row"><span class="info-label">Date prévue</span><span class="info-value">{{ \Carbon\Carbon::parse($order->expected_date)->format('d/m/Y') }}</span></div>
                @endif
                <div class="info-row"><span class="info-label">Statut</span><span class="info-value"><span class="status-badge">{{ ($order->status instanceof \App\Enums\OpticalOrderStatus ? $order->status : \App\Enums\OpticalOrderStatus::from($order->status))->label() }}</span></span></div>
                @if($order->supplier)
                    <div class="info-row"><span class="info-label">Fournisseur</span><span class="info-value">{{ $order->supplier->name }}</span></div>
                @endif
            </div>
        </div>
        <div class="col">
            <div class="box">
                <strong>Patient</strong><br><br>
                <div class="info-row"><span class="info-label">Nom</span><span class="info-value">{{ $order->patient->full_name }}</span></div>
                <div class="info-row"><span class="info-label">Code</span><span class="info-value">{{ $order->patient->patient_code }}</span></div>
                <div class="info-row"><span class="info-label">Téléphone</span><span class="info-value">{{ $order->patient->phone ?? '—' }}</span></div>
            </div>
        </div>
    </div>

    <p class="section-title">Correction prescrite</p>
    <table>
        <thead>
            <tr>
                <th>Œil</th>
                <th class="right">Sphère</th>
                <th class="right">Cylindre</th>
                <th class="right">Axe (°)</th>
                <th class="right">Addition</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>OD</strong></td>
                <td class="right">{{ $order->right_sphere ?? '—' }}</td>
                <td class="right">{{ $order->right_cylinder ?? '—' }}</td>
                <td class="right">{{ $order->right_axis ?? '—' }}</td>
                <td class="right">{{ $order->right_addition ?? '—' }}</td>
            </tr>
            <tr>
                <td><strong>OG</strong></td>
                <td class="right">{{ $order->left_sphere ?? '—' }}</td>
                <td class="right">{{ $order->left_cylinder ?? '—' }}</td>
                <td class="right">{{ $order->left_axis ?? '—' }}</td>
                <td class="right">{{ $order->left_addition ?? '—' }}</td>
            </tr>
        </tbody>
    </table>
    @if($order->pupillary_distance)
        <p style="margin-top:8px; color:#64748b; font-size:11px;">Distance pupillaire : <strong>{{ $order->pupillary_distance }} mm</strong></p>
    @endif

    <p class="section-title">Équipement</p>
    <table>
        <thead>
            <tr>
                <th>Article</th>
                <th>Détail</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Monture</td>
                <td>{{ $order->frame ? $order->frame->brand . ' ' . $order->frame->model . ' (' . $order->frame->color . ')' : '—' }}</td>
            </tr>
            <tr>
                <td>Verre OD</td>
                <td>{{ $order->rightLens?->full_description ?? '—' }}</td>
            </tr>
            <tr>
                <td>Verre OG</td>
                <td>{{ $order->leftLens?->full_description ?? '—' }}</td>
            </tr>
        </tbody>
    </table>

    @if($order->special_instructions)
        <p style="margin-top:12px; font-size:11px; color:#64748b;">Instructions spéciales : {{ $order->special_instructions }}</p>
    @endif

    <p class="section-title">Tarification</p>
    <div style="margin-left:auto; width:280px;">
        <div class="amount-row"><span class="amount-label">Prix monture</span><span class="amount-value">{{ number_format($order->price_frame, 2) }}</span></div>
        <div class="amount-row"><span class="amount-label">Prix verres</span><span class="amount-value">{{ number_format($order->price_lenses, 2) }}</span></div>
        <div class="amount-row amount-total"><span class="amount-label">Total</span><span class="amount-value">{{ number_format($order->total_price, 2) }}</span></div>
        <div class="amount-row" style="margin-top:8px;"><span class="amount-label">Acompte versé</span><span class="amount-value">{{ number_format($order->deposit_paid, 2) }}</span></div>
        <div class="amount-row remaining"><span class="amount-label"><strong>Reste à payer</strong></span><span class="amount-value"><strong>{{ number_format($order->remaining_amount, 2) }}</strong></span></div>
    </div>

    @if($order->notes)
        <p style="margin-top:20px; font-size:11px; color:#64748b; border-top:1px solid #e2e8f0; padding-top:10px;">Notes : {{ $order->notes }}</p>
    @endif

    <div class="footer">{{ $settings['clinic_name'] }} — Bon de commande {{ $order->order_number }} — Édité le {{ now()->format('d/m/Y') }}</div>
</body>
</html>
