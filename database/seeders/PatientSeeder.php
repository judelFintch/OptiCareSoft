<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::role('Admin')->first() ?? User::first();

        $patients = [
            ['first_name' => 'Jean-Pierre', 'last_name' => 'Mubiayi',    'gender' => 'male',   'birth_date' => '1978-03-15', 'phone' => '+243 081 234 0001', 'email' => 'jp.mubiayi@gmail.com',    'address' => 'Avenue Kasa-Vubu 12, Lingwala',      'city' => 'Kinshasa', 'profession' => 'Enseignant',        'medical_history' => 'Diabète type 2 depuis 2018', 'ophthalmic_history' => 'Myopie progressive'],
            ['first_name' => 'Marie',       'last_name' => 'Kabongo',     'gender' => 'female', 'birth_date' => '1985-07-22', 'phone' => '+243 099 345 0002', 'email' => 'marie.kabongo@yahoo.fr',  'address' => 'Boulevard Lumumba 45, Kintambo',     'city' => 'Kinshasa', 'profession' => 'Infirmière',        'allergies' => 'Pénicilline'],
            ['first_name' => 'Emmanuel',    'last_name' => 'Mutombo',     'gender' => 'male',   'birth_date' => '1960-11-08', 'phone' => '+243 084 567 0003', 'email' => null,                       'address' => 'Rue Shaumba 3, Barumbu',             'city' => 'Kinshasa', 'profession' => 'Comptable retraité','medical_history' => 'Hypertension artérielle', 'ophthalmic_history' => 'Glaucome suivi'],
            ['first_name' => 'Claire',      'last_name' => 'Kasongo',     'gender' => 'female', 'birth_date' => '1992-02-14', 'phone' => '+243 097 890 0004', 'email' => 'claire.kasongo@gmail.com','address' => 'Avenue de la Paix 7, Gombe',         'city' => 'Kinshasa', 'profession' => 'Secrétaire'],
            ['first_name' => 'Patrick',     'last_name' => 'Ntumba',      'gender' => 'male',   'birth_date' => '1975-09-30', 'phone' => '+243 081 111 0005', 'email' => 'p.ntumba@cd.net',          'address' => 'Quartier Matonge, Kalamu',           'city' => 'Kinshasa', 'profession' => 'Commerçant',        'ophthalmic_history' => 'Cataracte OD opérée en 2020'],
            ['first_name' => 'Angélique',   'last_name' => 'Mukendi',     'gender' => 'female', 'birth_date' => '1988-05-19', 'phone' => '+243 082 222 0006', 'email' => null,                       'address' => 'Avenue du Fleuve 22, Kinshasa-Gombe','city' => 'Kinshasa', 'profession' => 'Avocat'],
            ['first_name' => 'Cédric',      'last_name' => 'Lukusa',      'gender' => 'male',   'birth_date' => '2005-01-10', 'phone' => '+243 099 333 0007', 'email' => null,                       'address' => 'Commune de Lemba',                   'city' => 'Kinshasa', 'profession' => 'Étudiant',          'medical_history' => 'Asthme léger'],
            ['first_name' => 'Joséphine',   'last_name' => 'Tshilombo',   'gender' => 'female', 'birth_date' => '1955-12-03', 'phone' => '+243 084 444 0008', 'email' => null,                       'address' => 'Avenue Kabinda 19, Kintambo',        'city' => 'Kinshasa', 'profession' => 'Retraitée',         'ophthalmic_history' => 'Presbytie + cataracte débutante OG'],
            ['first_name' => 'David',       'last_name' => 'Kasonga',     'gender' => 'male',   'birth_date' => '1983-06-28', 'phone' => '+243 097 555 0009', 'email' => 'd.kasonga@opticare.cd',   'address' => 'Boulevard du 30 Juin, Gombe',        'city' => 'Kinshasa', 'profession' => 'Médecin',           'medical_history' => 'Aucun antécédent notable'],
            ['first_name' => 'Sandrine',    'last_name' => 'Kapinga',     'gender' => 'female', 'birth_date' => '1990-08-16', 'phone' => '+243 081 666 0010', 'email' => 's.kapinga@gmail.com',      'address' => 'Rue du Marché 5, Barumbu',           'city' => 'Kinshasa', 'profession' => 'Pharmacienne'],
            ['first_name' => 'Michel',      'last_name' => 'Mulamba',     'gender' => 'male',   'birth_date' => '1968-04-12', 'phone' => '+243 082 777 0011', 'email' => null,                       'address' => 'Commune de Ngaliema, résidence Macampagne','city' => 'Kinshasa','profession' => 'Ingénieur', 'ophthalmic_history' => 'Hypermétropie depuis enfance'],
            ['first_name' => 'Cécile',      'last_name' => 'Nzuzi',       'gender' => 'female', 'birth_date' => '1998-11-25', 'phone' => '+243 099 888 0012', 'email' => 'cecile.nzuzi@gmail.com',  'address' => 'Avenue du Commerce 33, Gombe',       'city' => 'Kinshasa', 'profession' => 'Étudiante',         'allergies' => 'Acide acétylsalicylique'],
            ['first_name' => 'Alain',       'last_name' => 'Tshilumba',   'gender' => 'male',   'birth_date' => '1945-07-04', 'phone' => '+243 084 999 0013', 'email' => null,                       'address' => 'Avenue de la Libération, Kasa-Vubu', 'city' => 'Kinshasa', 'profession' => 'Retraité',          'medical_history' => 'HTA, diabète type 2', 'ophthalmic_history' => 'Rétinopathie diabétique bilatérale'],
            ['first_name' => 'Hortense',    'last_name' => 'Mulumba',     'gender' => 'female', 'birth_date' => '1972-03-08', 'phone' => '+243 081 001 0014', 'email' => null,                       'address' => 'Commune de Bandalungwa',             'city' => 'Kinshasa', 'profession' => 'Enseignante'],
            ['first_name' => 'Serge',       'last_name' => 'Mbaya',       'gender' => 'male',   'birth_date' => '1987-09-21', 'phone' => '+243 097 112 0015', 'email' => 'serge.mbaya@yahoo.fr',    'address' => 'Avenue Kabambare 8, Kalamu',          'city' => 'Kinshasa', 'profession' => 'Informaticien'],
            ['first_name' => 'Grace',       'last_name' => 'Nzinga',      'gender' => 'female', 'birth_date' => '1980-01-17', 'phone' => '+243 082 223 0016', 'email' => null,                       'address' => 'Quartier Righini, Ngaliema',          'city' => 'Kinshasa', 'profession' => 'Commerçante',       'ophthalmic_history' => 'Port de lentilles depuis 5 ans'],
            ['first_name' => 'Franck',      'last_name' => 'Kibwe',       'gender' => 'male',   'birth_date' => '2010-06-05', 'phone' => '+243 099 334 0017', 'email' => null,                       'address' => 'Commune de Limete',                  'city' => 'Kinshasa', 'profession' => 'Élève',             'ophthalmic_history' => 'Strabisme convergent'],
            ['first_name' => 'Thérèse',     'last_name' => 'Kabeya',      'gender' => 'female', 'birth_date' => '1963-10-29', 'phone' => '+243 084 445 0018', 'email' => null,                       'address' => 'Avenue des Aviateurs, Gombe',        'city' => 'Kinshasa', 'profession' => 'Comptable'],
            ['first_name' => 'Honoré',      'last_name' => 'Mbuyi',       'gender' => 'male',   'birth_date' => '1993-04-03', 'phone' => '+243 081 556 0019', 'email' => 'honore.mbuyi@gmail.com',  'address' => 'Commune de Matete',                  'city' => 'Kinshasa', 'profession' => 'Juriste'],
            ['first_name' => 'Espérance',   'last_name' => 'Nganga',      'gender' => 'female', 'birth_date' => '2008-12-20', 'phone' => '+243 097 667 0020', 'email' => null,                       'address' => 'Quartier Victoire, Kalamu',           'city' => 'Kinshasa', 'profession' => 'Élève',             'allergies' => 'Sulfamides'],
            ['first_name' => 'Joseph',      'last_name' => 'Nkemdirim',   'gender' => 'male',   'birth_date' => '1970-08-14', 'phone' => '+243 082 778 0021', 'email' => null,                       'address' => 'Avenue Pumbu, Kinshasa-Est',         'city' => 'Kinshasa', 'profession' => 'Pasteur',           'medical_history' => 'Hypertension traitée'],
            ['first_name' => 'Brigitte',    'last_name' => 'Luhaka',      'gender' => 'female', 'birth_date' => '1982-05-31', 'phone' => '+243 081 889 0022', 'email' => null,                       'address' => 'Boulevard Sendwe, Kalamu',            'city' => 'Kinshasa', 'profession' => 'Sage-femme'],
            ['first_name' => 'Dieudonné',   'last_name' => 'Lukeba',      'gender' => 'male',   'birth_date' => '1956-02-18', 'phone' => '+243 099 990 0023', 'email' => null,                       'address' => 'Commune de Kinshasa (centre)',        'city' => 'Kinshasa', 'profession' => 'Retraité militaire','ophthalmic_history' => 'Cataracte bilatérale en cours d\'évolution'],
            ['first_name' => 'Solange',     'last_name' => 'Mwamba',      'gender' => 'female', 'birth_date' => '1995-09-09', 'phone' => '+243 084 001 0024', 'email' => 'solange.mwamba@gmail.com','address' => 'Avenue Victoire 55, Kasa-Vubu',      'city' => 'Kinshasa', 'profession' => 'Infographiste'],
            ['first_name' => 'Christophe',  'last_name' => 'Katanga',     'gender' => 'male',   'birth_date' => '1977-12-12', 'phone' => '+243 097 112 0025', 'email' => null,                       'address' => 'Commune de Kintambo',                'city' => 'Kinshasa', 'profession' => 'Chef d\'entreprise', 'ophthalmic_history' => 'Astigmatisme bilatéral non corrigé'],
        ];

        $i = 1;
        foreach ($patients as $data) {
            $data['patient_code']  = 'PAT-' . str_pad($i++, 5, '0', STR_PAD_LEFT);
            $data['status']        = 'active';
            $data['created_by']    = $creator?->id;
            $data['nationality']   = 'Congolaise';
            $data['emergency_contact_name']  = $data['emergency_contact_name']  ?? null;
            $data['emergency_contact_phone'] = $data['emergency_contact_phone'] ?? null;
            Patient::firstOrCreate(['phone' => $data['phone']], $data);
        }
    }
}
