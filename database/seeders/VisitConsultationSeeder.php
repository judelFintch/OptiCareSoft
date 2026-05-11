<?php

namespace Database\Seeders;

use App\Enums\ConsultationStatus;
use App\Enums\DiagnosisType;
use App\Enums\VisitStatus;
use App\Models\Consultation;
use App\Models\MedicalPrescription;
use App\Models\OpticalPrescription;
use App\Models\Patient;
use App\Models\PrescriptionItem;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VisitConsultationSeeder extends Seeder
{
    public function run(): void
    {
        $doctor    = User::role('Ophthalmologist')->first() ?? User::first();
        $receptionist = User::role('Receptionist')->first() ?? User::first();
        $patients  = Patient::all();

        if ($patients->isEmpty()) return;

        // Données de consultations réalistes
        $cases = [
            [
                'diagnosis'     => DiagnosisType::Myopia,
                'complaint'     => 'Vision floue de loin, difficultés à lire le tableau en classe',
                'findings'      => 'Acuité visuelle OD 4/10 OG 5/10 sans correction. Fond d\'œil normal.',
                'plan'          => 'Correction optique prescrite. Contrôle dans 6 mois.',
                'rx_right'      => ['sphere' => -2.25, 'cylinder' => -0.50, 'axis' => 15,  'type' => 'unifocal', 'usage' => 'Vision de loin permanente'],
                'rx_left'       => ['sphere' => -1.75, 'cylinder' => -0.25, 'axis' => 170, 'type' => 'unifocal', 'usage' => 'Vision de loin permanente'],
                'pd'            => 62.5,
            ],
            [
                'diagnosis'     => DiagnosisType::Presbyopia,
                'complaint'     => 'Difficulté croissante à lire de près depuis 2 ans, tient les livres loin',
                'findings'      => 'Acuité visuelle de loin conservée. Amplitude d\'accommodation réduite.',
                'plan'          => 'Prescription de verres progressifs. Hygiène visuelle conseillée.',
                'rx_right'      => ['sphere' => 1.00, 'cylinder' => null, 'axis' => null, 'addition' => 2.00, 'type' => 'progressive', 'usage' => 'Port permanent'],
                'rx_left'       => ['sphere' => 1.25, 'cylinder' => null, 'axis' => null, 'addition' => 2.00, 'type' => 'progressive', 'usage' => 'Port permanent'],
                'pd'            => 65.0,
            ],
            [
                'diagnosis'     => DiagnosisType::Glaucoma,
                'complaint'     => 'Suivi glaucome à angle ouvert, sous traitement depuis 3 ans',
                'findings'      => 'Pression oculaire OD 18 mmHg OG 16 mmHg. Champ visuel stable. Papille excavée 0.7 bilatérale.',
                'plan'          => 'Continuer collyre Timolol 0.5% x2/j. Contrôle dans 3 mois.',
                'medical_rx'    => true,
                'drugs'         => [['drug' => 'Timolol 0.5%', 'dosage' => '1 goutte', 'frequency' => '2×/jour', 'duration' => '3 mois', 'instructions' => 'Instiller dans chaque œil, matin et soir']],
            ],
            [
                'diagnosis'     => DiagnosisType::Conjunctivitis,
                'complaint'     => 'Rougeur, sécrétions purulentes bilatérales depuis 3 jours, prurit intense',
                'findings'      => 'Conjonctivite purulente bilatérale. Pas d\'atteinte cornéenne.',
                'plan'          => 'Antibiothérapie locale. Mesures d\'hygiène.',
                'medical_rx'    => true,
                'drugs'         => [
                    ['drug' => 'Tobramycine 0.3% collyre', 'dosage' => '1 goutte', 'frequency' => '4×/jour', 'duration' => '7 jours', 'instructions' => 'Dans les deux yeux'],
                    ['drug' => 'Sérum physiologique',      'dosage' => 'Lavage', 'frequency' => '3×/jour', 'duration' => '7 jours', 'instructions' => 'Avant l\'instillation du collyre'],
                ],
            ],
            [
                'diagnosis'     => DiagnosisType::Cataract,
                'complaint'     => 'Baisse progressive de la vision OD depuis 1 an, éblouissement en conduite',
                'findings'      => 'Cataracte nucléaire OD grade III. Acuité OD 2/10 OG 7/10.',
                'plan'          => 'Chirurgie cataracte OD indiquée. Référence au chirurgien ophtalmologiste.',
                'medical_rx'    => false,
            ],
            [
                'diagnosis'     => DiagnosisType::DiabeticRetinopathy,
                'complaint'     => 'Bilan ophtalmologique annuel. Diabète type 2 sous traitement oral.',
                'findings'      => 'Rétinopathie diabétique non proliférante légère bilatérale. Pas d\'œdème maculaire.',
                'plan'          => 'Contrôle glycémique strict. Laser pan-rétinien non nécessaire pour l\'instant. Contrôle dans 6 mois.',
                'medical_rx'    => false,
            ],
            [
                'diagnosis'     => DiagnosisType::Astigmatism,
                'complaint'     => 'Céphalées fréquentes en fin de journée, yeux fatigués au travail sur écran',
                'findings'      => 'Astigmatisme mixte bilatéral non corrigé. Acuité OD 6/10 OG 7/10.',
                'plan'          => 'Correction optique prescrite. Pauses visuelles conseillées (règle 20-20-20).',
                'rx_right'      => ['sphere' => -0.50, 'cylinder' => -1.25, 'axis' => 85,  'type' => 'unifocal', 'usage' => 'Port permanent'],
                'rx_left'       => ['sphere' => -0.25, 'cylinder' => -0.75, 'axis' => 95,  'type' => 'unifocal', 'usage' => 'Port permanent'],
                'pd'            => 64.0,
            ],
            [
                'diagnosis'     => DiagnosisType::DryEye,
                'complaint'     => 'Sensation de brûlure et sécheresse oculaire, travail intensif sur ordinateur',
                'findings'      => 'Test de Schirmer 7 mm/5 min. Œil sec modéré bilatéral.',
                'plan'          => 'Larmes artificielles sans conservateurs. Limitation écran. Humidificateur conseillé.',
                'medical_rx'    => true,
                'drugs'         => [
                    ['drug' => 'Systane Ultra', 'dosage' => '1-2 gouttes', 'frequency' => '4-6×/jour', 'duration' => '3 mois', 'instructions' => 'Dans les deux yeux selon besoin'],
                ],
            ],
            [
                'diagnosis'     => DiagnosisType::Hyperopia,
                'complaint'     => 'Vision floue de près et de loin chez un enfant de 8 ans, mauvais résultats scolaires',
                'findings'      => 'Hypermétropie forte bilatérale +4.00 D sous cycloplégique. Strabisme accommodatif intermittent.',
                'plan'          => 'Correction optique complète. Rééducation orthoptique à envisager.',
                'rx_right'      => ['sphere' => 3.75, 'cylinder' => null, 'axis' => null, 'type' => 'unifocal', 'usage' => 'Port permanent'],
                'rx_left'       => ['sphere' => 4.00, 'cylinder' => null, 'axis' => null, 'type' => 'unifocal', 'usage' => 'Port permanent'],
                'pd'            => 55.0,
            ],
            [
                'diagnosis'     => DiagnosisType::Pterygium,
                'complaint'     => 'Tache blanche qui avance sur l\'œil droit depuis 2 ans, légère gêne',
                'findings'      => 'Ptérygion nasal OD envahissant l\'axe visuel sur 2 mm. Acuité OD 7/10.',
                'plan'          => 'Chirurgie d\'exérèse indiquée. Référence. Collyres lubrifiants en attendant.',
                'medical_rx'    => true,
                'drugs'         => [['drug' => 'Larmes artificielles', 'dosage' => '1-2 gouttes', 'frequency' => '3-4×/jour', 'duration' => 'Jusqu\'à chirurgie', 'instructions' => 'Œil droit uniquement']],
            ],
        ];

        $patientList = $patients->shuffle();
        $consCode = 1;
        $rxCode   = 1;
        $mRxCode  = 1;

        for ($i = 0; $i < min(18, $patientList->count()); $i++) {
            $patient    = $patientList[$i];
            $case       = $cases[$i % count($cases)];
            $daysAgo    = rand(1, 28);
            $visitDate  = Carbon::now()->subDays($daysAgo);

            // Visit
            $visit = Visit::create([
                'visit_code' => 'VIS-' . str_pad($consCode, 5, '0', STR_PAD_LEFT),
                'patient_id' => $patient->id,
                'status'     => VisitStatus::Closed,
                'opened_by'  => $receptionist->id,
                'closed_by'  => $receptionist->id,
                'opened_at'  => $visitDate->copy()->setTime(8, rand(0, 30)),
                'closed_at'  => $visitDate->copy()->setTime(10, rand(0, 59)),
            ]);

            // Consultation
            $consultation = Consultation::create([
                'consultation_code'          => 'CONS-' . str_pad($consCode, 5, '0', STR_PAD_LEFT),
                'patient_id'                 => $patient->id,
                'doctor_id'                  => $doctor->id,
                'visit_id'                   => $visit->id,
                'chief_complaint'            => $case['complaint'],
                'history_of_present_illness' => $case['complaint'],
                'clinical_findings'          => $case['findings'],
                'primary_diagnosis'          => $case['diagnosis'],
                'treatment_plan'             => $case['plan'],
                'status'                     => ConsultationStatus::Signed,
                'signed_at'                  => $visitDate->copy()->setTime(9, rand(0, 59)),
                'created_at'                 => $visitDate,
                'updated_at'                 => $visitDate,
            ]);

            // Ordonnance optique si applicable
            if (isset($case['rx_right'])) {
                $rr = $case['rx_right'];
                $rl = $case['rx_left'];
                OpticalPrescription::create([
                    'prescription_number' => 'RX-OPT-' . str_pad($rxCode++, 4, '0', STR_PAD_LEFT),
                    'consultation_id'     => $consultation->id,
                    'patient_id'          => $patient->id,
                    'doctor_id'           => $doctor->id,
                    'right_sphere'        => $rr['sphere'] ?? null,
                    'right_cylinder'      => $rr['cylinder'] ?? null,
                    'right_axis'          => $rr['axis'] ?? null,
                    'right_addition'      => $rr['addition'] ?? null,
                    'left_sphere'         => $rl['sphere'] ?? null,
                    'left_cylinder'       => $rl['cylinder'] ?? null,
                    'left_axis'           => $rl['axis'] ?? null,
                    'left_addition'       => $rl['addition'] ?? null,
                    'pd_right'            => ($case['pd'] ?? 64) / 2,
                    'pd_left'             => ($case['pd'] ?? 64) / 2,
                    'lens_type'           => $rr['type'] ?? 'Monofocal',
                    'usage'               => $rr['usage'] ?? 'Port permanent',
                    'valid_until'         => $visitDate->copy()->addYear(),
                    'created_at'          => $visitDate,
                    'updated_at'          => $visitDate,
                ]);
            }

            // Ordonnance médicale si applicable
            if (($case['medical_rx'] ?? false) && isset($case['drugs'])) {
                $medRx = MedicalPrescription::create([
                    'prescription_number' => 'RX-MED-' . str_pad($mRxCode++, 4, '0', STR_PAD_LEFT),
                    'consultation_id'     => $consultation->id,
                    'patient_id'          => $patient->id,
                    'doctor_id'           => $doctor->id,
                    'instructions'        => 'Respecter la posologie. Revenir en cas de complication.',
                    'valid_until'         => $visitDate->copy()->addMonths(3),
                    'created_at'          => $visitDate,
                    'updated_at'          => $visitDate,
                ]);

                foreach ($case['drugs'] as $j => $drug) {
                    PrescriptionItem::create([
                        'medical_prescription_id' => $medRx->id,
                        'drug_name'               => $drug['drug'],
                        'dosage'                  => $drug['dosage'],
                        'frequency'               => $drug['frequency'],
                        'duration'                => $drug['duration'],
                        'instructions'            => $drug['instructions'],
                        'sort_order'              => $j + 1,
                    ]);
                }
            }

            $consCode++;
        }
    }
}
