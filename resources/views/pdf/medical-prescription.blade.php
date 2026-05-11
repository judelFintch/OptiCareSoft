<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $prescription->prescription_number }}</title>
    @include('pdf.partials.document-styles')
</head>
<body>
    <header class="header">
        <p class="clinic-name">{{ $settings['clinic_name'] }}</p>
        <p class="muted">{{ $settings['clinic_slogan'] }}</p>
        <p class="muted">{{ $settings['clinic_address'] }} · {{ $settings['clinic_phone'] }} · {{ $settings['clinic_email'] }}</p>
    </header>

    <h1 class="title">Ordonnance médicale {{ $prescription->prescription_number }}</h1>

    <section class="grid">
        <div class="col">
            <div class="box">
                <strong>Patient</strong><br>
                {{ $prescription->patient?->full_name }}<br>
                Code: {{ $prescription->patient?->patient_code }}<br>
                Téléphone: {{ $prescription->patient?->phone ?: '—' }}
            </div>
        </div>
        <div class="col">
            <div class="box">
                <strong>Médecin</strong><br>
                {{ $prescription->doctor?->name }}<br>
                Date: {{ $prescription->created_at?->format('d/m/Y') }}<br>
                Validité: {{ $prescription->valid_until?->format('d/m/Y') ?: '—' }}
            </div>
        </div>
    </section>

    <table>
        <thead>
            <tr>
                <th>Médicament</th>
                <th>Dosage</th>
                <th>Forme</th>
                <th>Fréquence</th>
                <th>Durée</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prescription->items as $item)
                <tr>
                    <td><strong>{{ $item->drug_name }}</strong><br><span class="muted">{{ $item->instructions }}</span></td>
                    <td>{{ $item->dosage ?: '—' }}</td>
                    <td>{{ $item->form ?: '—' }}</td>
                    <td>{{ $item->frequency ?: '—' }}</td>
                    <td>{{ $item->duration ?: '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($prescription->instructions || $prescription->notes)
        <section class="box" style="margin-top: 18px;">
            <strong>Instructions:</strong> {{ $prescription->instructions ?: '—' }}<br>
            <strong>Notes:</strong> {{ $prescription->notes ?: '—' }}
        </section>
    @endif

    <section style="margin-top: 48px; text-align: right;">
        <p class="muted">Signature et cachet</p>
        <p style="margin-top: 56px;">Dr {{ $prescription->doctor?->name }}</p>
    </section>

    <footer class="footer">{{ $settings['prescription_note'] }}</footer>
</body>
</html>
