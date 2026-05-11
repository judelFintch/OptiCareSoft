<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->invoice_number }}</title>
    @include('pdf.partials.document-styles')
</head>
<body>
    <header class="header">
        <p class="clinic-name">{{ $settings['clinic_name'] }}</p>
        <p class="muted">{{ $settings['clinic_slogan'] }}</p>
        <p class="muted">{{ $settings['clinic_address'] }} · {{ $settings['clinic_phone'] }} · {{ $settings['clinic_email'] }}</p>
    </header>

    <h1 class="title">Facture {{ $invoice->invoice_number }}</h1>

    <section class="grid">
        <div class="col">
            <div class="box">
                <strong>Patient</strong><br>
                {{ $invoice->patient?->full_name }}<br>
                Code: {{ $invoice->patient?->patient_code }}<br>
                Téléphone: {{ $invoice->patient?->phone ?: '—' }}
            </div>
        </div>
        <div class="col">
            <div class="box">
                <strong>Détails</strong><br>
                Date: {{ $invoice->issued_at?->format('d/m/Y H:i') }}<br>
                Type: {{ $invoice->invoice_type?->value ?? $invoice->invoice_type }}<br>
                Statut: {{ $invoice->status?->value ?? $invoice->status }}
            </div>
        </div>
    </section>

    <table>
        <thead>
            <tr>
                <th>Libellé</th>
                <th class="right">Qté</th>
                <th class="right">Prix unitaire</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->label }}<br><span class="muted">{{ $item->description }}</span></td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">{{ number_format((float) $item->unit_price, 2) }}</td>
                    <td class="right">{{ number_format((float) $item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <section class="totals">
        <div class="totals-row"><span>Sous-total</span><span class="right">{{ number_format((float) $invoice->subtotal, 2) }}</span></div>
        <div class="totals-row"><span>Payé</span><span class="right">{{ number_format((float) $invoice->paid_amount, 2) }}</span></div>
        <div class="totals-row total"><span>Reste à payer</span><span class="right">{{ number_format((float) $invoice->remaining_amount, 2) }} {{ $invoice->currency?->symbol }}</span></div>
    </section>

    <footer class="footer">{{ $settings['invoice_footer'] }}</footer>
</body>
</html>
