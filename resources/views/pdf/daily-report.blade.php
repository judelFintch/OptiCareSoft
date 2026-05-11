<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Rapport journalier {{ $date->format('d/m/Y') }}</title>
    @include('pdf.partials.document-styles')
</head>
<body>
    <div class="header">
        <p class="clinic-name">{{ $settings['clinic_name'] }}</p>
        <div class="muted">{{ $settings['clinic_slogan'] }}</div>
        <div class="muted">{{ $settings['clinic_address'] }} {{ $settings['clinic_phone'] ? ' - ' . $settings['clinic_phone'] : '' }}</div>
        <div class="muted">{{ $settings['clinic_email'] }}</div>
    </div>

    <div class="title">Rapport journalier du {{ $date->format('d/m/Y') }}</div>

    <div class="grid">
        <div class="col">
            <div class="box">
                <strong>Activité cabinet</strong><br>
                Visites ouvertes: {{ $report['visits_opened'] }}<br>
                Visites clôturées: {{ $report['visits_closed'] }}<br>
                Consultations: {{ $report['consultations'] }}<br>
                Nouveaux patients: {{ $report['new_patients'] }}
            </div>
        </div>
        <div class="col">
            <div class="box">
                <strong>Caisse</strong><br>
                Factures émises: {{ $report['invoices_issued'] }}<br>
                Montant facturé: {{ number_format((float) $report['invoiced_total'], 2, ',', ' ') }}<br>
                Encaissements: {{ number_format((float) $report['revenue']['total'], 2, ',', ' ') }}<br>
                Reste à payer: {{ number_format((float) $report['debt_total'], 2, ',', ' ') }}
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Méthode de paiement</th>
                <th class="right">Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Espèces</td><td class="right">{{ number_format((float) $report['revenue']['cash'], 2, ',', ' ') }}</td></tr>
            <tr><td>Mobile Money</td><td class="right">{{ number_format((float) $report['revenue']['mobile'], 2, ',', ' ') }}</td></tr>
            <tr><td>Banque</td><td class="right">{{ number_format((float) $report['revenue']['bank'], 2, ',', ' ') }}</td></tr>
            <tr><td>Carte</td><td class="right">{{ number_format((float) $report['revenue']['card'], 2, ',', ' ') }}</td></tr>
            <tr><td>Autre</td><td class="right">{{ number_format((float) $report['revenue']['other'], 2, ',', ' ') }}</td></tr>
        </tbody>
    </table>

    <div class="title">Paiements</div>
    <table>
        <thead>
            <tr>
                <th>Heure</th>
                <th>Reçu</th>
                <th>Patient</th>
                <th>Méthode</th>
                <th class="right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @forelse($report['payments'] as $payment)
                <tr>
                    <td>{{ $payment->paid_at?->format('H:i') }}</td>
                    <td>{{ $payment->payment_number }}</td>
                    <td>{{ $payment->patient?->full_name }}</td>
                    <td>{{ $payment->payment_method?->label() ?? $payment->payment_method }}</td>
                    <td class="right">{{ number_format((float) $payment->amount, 2, ',', ' ') }} {{ $payment->currency?->code }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="muted">Aucun paiement enregistré.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="title">Factures émises</div>
    <table>
        <thead>
            <tr>
                <th>Facture</th>
                <th>Patient</th>
                <th>Type</th>
                <th>Statut</th>
                <th class="right">Total</th>
                <th class="right">Reste</th>
            </tr>
        </thead>
        <tbody>
            @forelse($report['invoices'] as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->patient?->full_name }}</td>
                    <td>{{ $invoice->invoice_type?->label() ?? $invoice->invoice_type }}</td>
                    <td>{{ $invoice->status?->label() ?? $invoice->status }}</td>
                    <td class="right">{{ number_format((float) $invoice->total_amount, 2, ',', ' ') }} {{ $invoice->currency?->code }}</td>
                    <td class="right">{{ number_format((float) $invoice->remaining_amount, 2, ',', ' ') }} {{ $invoice->currency?->code }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">Aucune facture émise.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Document généré par OptiCare Soft le {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
