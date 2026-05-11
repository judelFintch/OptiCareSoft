<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Reçu {{ $invoice->invoice_number }}</title>
    @include('pdf.partials.document-styles')
</head>
<body>
    <header class="header">
        <p class="clinic-name">{{ $settings['clinic_name'] }}</p>
        <p class="muted">{{ $settings['clinic_address'] }} · {{ $settings['clinic_phone'] }}</p>
    </header>

    <h1 class="title">Reçu de paiement</h1>

    <section class="grid">
        <div class="col">
            <div class="box">
                <strong>Patient</strong><br>
                {{ $invoice->patient?->full_name }}<br>
                Code: {{ $invoice->patient?->patient_code }}
            </div>
        </div>
        <div class="col">
            <div class="box">
                <strong>Facture</strong><br>
                {{ $invoice->invoice_number }}<br>
                Date: {{ now()->format('d/m/Y H:i') }}<br>
                Statut: {{ $invoice->status?->value ?? $invoice->status }}
            </div>
        </div>
    </section>

    <table>
        <thead>
            <tr>
                <th>Numéro paiement</th>
                <th>Méthode</th>
                <th>Reçu par</th>
                <th class="right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice->payments as $payment)
                <tr>
                    <td>{{ $payment->payment_number }}</td>
                    <td>{{ $payment->payment_method?->value ?? $payment->payment_method }}</td>
                    <td>{{ $payment->receiver?->name }}</td>
                    <td class="right">{{ number_format((float) $payment->amount, 2) }} {{ $invoice->currency?->symbol }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="muted">Aucun paiement enregistré.</td></tr>
            @endforelse
        </tbody>
    </table>

    <section class="totals">
        <div class="totals-row"><span>Total facture</span><span class="right">{{ number_format((float) $invoice->total_amount, 2) }}</span></div>
        <div class="totals-row"><span>Total payé</span><span class="right">{{ number_format((float) $invoice->paid_amount, 2) }}</span></div>
        <div class="totals-row total"><span>Solde</span><span class="right">{{ number_format((float) $invoice->remaining_amount, 2) }} {{ $invoice->currency?->symbol }}</span></div>
    </section>

    <footer class="footer">{{ $settings['invoice_footer'] }}</footer>
</body>
</html>
