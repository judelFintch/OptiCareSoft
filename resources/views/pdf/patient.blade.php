<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Fiche patient — {{ $patient->patient_code }}</title>
    @include('pdf.partials.document-styles')
    <style>
        .section-title { color: #0f4c81; font-size: 13px; font-weight: bold; margin: 20px 0 8px; padding-bottom: 4px; border-bottom: 1px solid #e2e8f0; text-transform: uppercase; }
        .info-row { display: table; width: 100%; margin-bottom: 4px; }
        .info-label { color: #64748b; display: table-cell; font-size: 11px; width: 40%; }
        .info-value { display: table-cell; font-weight: 500; }
    </style>
</head>
<body>
    <header class="header">
        <p class="clinic-name">{{ $settings['clinic_name'] }}</p>
        <p class="muted">{{ $settings['clinic_slogan'] }}</p>
        <p class="muted">{{ $settings['clinic_address'] }} · {{ $settings['clinic_phone'] }} · {{ $settings['clinic_email'] }}</p>
    </header>

    <h1 class="title">Fiche patient</h1>

    <div class="grid">
        <div class="col" style="padding-right:16px">
            <div class="box">
                <strong>Identité</strong><br><br>
                <div class="info-row"><span class="info-label">Code</span><span class="info-value">{{ $patient->patient_code }}</span></div>
                <div class="info-row"><span class="info-label">Nom complet</span><span class="info-value">{{ $patient->full_name }}</span></div>
                <div class="info-row"><span class="info-label">Genre</span><span class="info-value">{{ $patient->gender?->value ?? '—' }}</span></div>
                <div class="info-row"><span class="info-label">Date de naissance</span><span class="info-value">{{ $patient->birth_date ? \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') : '—' }}</span></div>
                <div class="info-row"><span class="info-label">Profession</span><span class="info-value">{{ $patient->profession ?? '—' }}</span></div>
                <div class="info-row"><span class="info-label">Nationalité</span><span class="info-value">{{ $patient->nationality ?? '—' }}</span></div>
            </div>
        </div>
        <div class="col">
            <div class="box">
                <strong>Contact</strong><br><br>
                <div class="info-row"><span class="info-label">Téléphone</span><span class="info-value">{{ $patient->phone ?? '—' }}</span></div>
                <div class="info-row"><span class="info-label">Email</span><span class="info-value">{{ $patient->email ?? '—' }}</span></div>
                <div class="info-row"><span class="info-label">Adresse</span><span class="info-value">{{ $patient->address ?? '—' }}</span></div>
                <div class="info-row"><span class="info-label">Ville</span><span class="info-value">{{ $patient->city ?? '—' }}</span></div>
                <br>
                <strong>Contact d'urgence</strong><br><br>
                <div class="info-row"><span class="info-label">Nom</span><span class="info-value">{{ $patient->emergency_contact_name ?? '—' }}</span></div>
                <div class="info-row"><span class="info-label">Téléphone</span><span class="info-value">{{ $patient->emergency_contact_phone ?? '—' }}</span></div>
            </div>
        </div>
    </div>

    {{-- Consultations --}}
    @if($consultations->count())
        <p class="section-title">Historique consultations ({{ $consultations->count() }})</p>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Code</th>
                    <th>Médecin</th>
                    <th>Diagnostic</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($consultations as $c)
                    <tr>
                        <td>{{ $c->created_at->format('d/m/Y') }}</td>
                        <td>{{ $c->consultation_code }}</td>
                        <td>{{ $c->doctor?->name ?? '—' }}</td>
                        <td>{{ $c->primary_diagnosis ?? '—' }}</td>
                        <td>{{ $c->status ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Ordonnances optiques --}}
    @if($opticalPrescriptions->count())
        <p class="section-title">Ordonnances optiques ({{ $opticalPrescriptions->count() }})</p>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>OD Sph</th>
                    <th>OD Cyl</th>
                    <th>OG Sph</th>
                    <th>OG Cyl</th>
                    <th>DP</th>
                </tr>
            </thead>
            <tbody>
                @foreach($opticalPrescriptions as $rx)
                    <tr>
                        <td>{{ $rx->created_at->format('d/m/Y') }}</td>
                        <td>{{ $rx->right_sphere ?? '—' }}</td>
                        <td>{{ $rx->right_cylinder ?? '—' }}</td>
                        <td>{{ $rx->left_sphere ?? '—' }}</td>
                        <td>{{ $rx->left_cylinder ?? '—' }}</td>
                        <td>{{ $rx->pupillary_distance ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">{{ $settings['clinic_name'] }} — Fiche patient {{ $patient->patient_code }} — Édité le {{ now()->format('d/m/Y') }}</div>
</body>
</html>
