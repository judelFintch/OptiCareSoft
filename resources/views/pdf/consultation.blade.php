<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $consultation->consultation_code }}</title>
    @include('pdf.partials.document-styles')
</head>
<body>
    <header class="header">
        <p class="clinic-name">{{ $settings['clinic_name'] }}</p>
        <p class="muted">{{ $settings['clinic_slogan'] }}</p>
        <p class="muted">{{ $settings['clinic_address'] }} · {{ $settings['clinic_phone'] }} · {{ $settings['clinic_email'] }}</p>
    </header>

    <h1 class="title">Fiche consultation {{ $consultation->consultation_code }}</h1>

    <section class="grid">
        <div class="col">
            <div class="box">
                <strong>Patient</strong><br>
                {{ $consultation->patient?->full_name }}<br>
                Code: {{ $consultation->patient?->patient_code }}<br>
                Téléphone: {{ $consultation->patient?->phone ?: '—' }}
            </div>
        </div>
        <div class="col">
            <div class="box">
                <strong>Consultation</strong><br>
                Médecin: {{ $consultation->doctor?->name }}<br>
                Date: {{ $consultation->created_at?->format('d/m/Y H:i') }}<br>
                Statut: {{ $consultation->status?->label() ?? $consultation->status }}
            </div>
        </div>
    </section>

    <section class="box" style="margin-top: 18px;">
        <strong>Motif:</strong> {{ $consultation->chief_complaint ?: '—' }}<br>
        <strong>Histoire:</strong> {{ $consultation->history_of_present_illness ?: '—' }}<br>
        <strong>Antécédents médicaux:</strong> {{ $consultation->medical_history ?: '—' }}<br>
        <strong>Antécédents ophtalmologiques:</strong> {{ $consultation->ophthalmic_history ?: '—' }}
    </section>

    <h2 class="title" style="font-size: 14px;">Examens</h2>
    <table>
        <thead>
            <tr>
                <th>Bloc</th>
                <th>OD</th>
                <th>OG</th>
                <th>Remarques</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Acuité visuelle</td>
                <td>SC {{ $consultation->visualAcuity?->right_eye_sc ?: '—' }} / CC {{ $consultation->visualAcuity?->right_eye_cc ?: '—' }}</td>
                <td>SC {{ $consultation->visualAcuity?->left_eye_sc ?: '—' }} / CC {{ $consultation->visualAcuity?->left_eye_cc ?: '—' }}</td>
                <td>{{ $consultation->visualAcuity?->remarks ?: '—' }}</td>
            </tr>
            <tr>
                <td>Réfraction</td>
                <td>S {{ $consultation->refraction?->right_sphere ?: '—' }} C {{ $consultation->refraction?->right_cylinder ?: '—' }} Axe {{ $consultation->refraction?->right_axis ?: '—' }}</td>
                <td>S {{ $consultation->refraction?->left_sphere ?: '—' }} C {{ $consultation->refraction?->left_cylinder ?: '—' }} Axe {{ $consultation->refraction?->left_axis ?: '—' }}</td>
                <td>{{ $consultation->refraction?->remarks ?: '—' }}</td>
            </tr>
            <tr>
                <td>Pression intraoculaire</td>
                <td>{{ $consultation->eyePressure?->right_eye_pressure ?: '—' }} mmHg</td>
                <td>{{ $consultation->eyePressure?->left_eye_pressure ?: '—' }} mmHg</td>
                <td>{{ $consultation->eyePressure?->measurement_method ?: '—' }}</td>
            </tr>
        </tbody>
    </table>

    <section class="box" style="margin-top: 18px;">
        <strong>Constats cliniques:</strong> {{ $consultation->clinical_findings ?: '—' }}<br>
        <strong>Diagnostic:</strong> {{ $consultation->primary_diagnosis?->value ?? $consultation->primary_diagnosis ?? '—' }}<br>
        <strong>Code ICD:</strong> {{ $consultation->icd_code ?: '—' }}<br>
        <strong>Plan de traitement:</strong> {{ $consultation->treatment_plan ?: '—' }}<br>
        <strong>Recommandations:</strong> {{ $consultation->recommendations ?: '—' }}<br>
        <strong>Prochain rendez-vous:</strong> {{ $consultation->next_appointment_date?->format('d/m/Y') ?: '—' }}
    </section>

    <section style="margin-top: 48px; text-align: right;">
        <p class="muted">Signature et cachet</p>
        <p style="margin-top: 56px;">Dr {{ $consultation->doctor?->name }}</p>
        @if($consultation->signed_at)
            <p class="muted">Signée le {{ $consultation->signed_at->format('d/m/Y H:i') }}</p>
        @endif
    </section>

    <footer class="footer">Fiche générée par OptiCare Soft</footer>
</body>
</html>
