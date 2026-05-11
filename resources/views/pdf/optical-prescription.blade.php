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

    <h1 class="title">Ordonnance optique {{ $prescription->prescription_number }}</h1>

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
                <th>Œil</th>
                <th class="right">Sphère</th>
                <th class="right">Cylindre</th>
                <th class="right">Axe</th>
                <th class="right">Addition</th>
                <th class="right">DP</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>OD</strong></td>
                <td class="right">{{ $prescription->right_sphere ?? '—' }}</td>
                <td class="right">{{ $prescription->right_cylinder ?? '—' }}</td>
                <td class="right">{{ $prescription->right_axis ?? '—' }}</td>
                <td class="right">{{ $prescription->right_addition ?? '—' }}</td>
                <td class="right">{{ $prescription->pd_right ?? '—' }}</td>
            </tr>
            <tr>
                <td><strong>OG</strong></td>
                <td class="right">{{ $prescription->left_sphere ?? '—' }}</td>
                <td class="right">{{ $prescription->left_cylinder ?? '—' }}</td>
                <td class="right">{{ $prescription->left_axis ?? '—' }}</td>
                <td class="right">{{ $prescription->left_addition ?? '—' }}</td>
                <td class="right">{{ $prescription->pd_left ?? '—' }}</td>
            </tr>
        </tbody>
    </table>

    <section class="box" style="margin-top: 18px;">
        <strong>Type de verre:</strong> {{ $prescription->lens_type?->label() ?? '—' }}<br>
        <strong>Usage:</strong> {{ $prescription->usage ?: '—' }}<br>
        <strong>Remarques:</strong> {{ $prescription->remarks ?: '—' }}
    </section>

    <section style="margin-top: 48px; text-align: right;">
        <p class="muted">Signature et cachet</p>
        <p style="margin-top: 56px;">Dr {{ $prescription->doctor?->name }}</p>
    </section>

    <footer class="footer">{{ $settings['prescription_note'] }}</footer>
</body>
</html>
