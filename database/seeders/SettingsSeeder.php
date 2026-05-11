<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Cabinet info
            ['key' => 'clinic_name',    'value' => 'OptiCare Cabinet Ophtalmologique', 'group' => 'clinic',  'type' => 'string'],
            ['key' => 'clinic_slogan',  'value' => 'La solution intelligente pour votre vue', 'group' => 'clinic', 'type' => 'string'],
            ['key' => 'clinic_address', 'value' => '123, Avenue de la Santé, Kinshasa', 'group' => 'clinic',  'type' => 'string'],
            ['key' => 'clinic_phone',   'value' => '+243 XX XXX XXXX',                 'group' => 'clinic',  'type' => 'string'],
            ['key' => 'clinic_email',   'value' => 'contact@opticare.local',           'group' => 'clinic',  'type' => 'string'],
            ['key' => 'clinic_city',    'value' => 'Kinshasa',                         'group' => 'clinic',  'type' => 'string'],
            ['key' => 'clinic_country', 'value' => 'République Démocratique du Congo', 'group' => 'clinic',  'type' => 'string'],

            // System
            ['key' => 'appointment_duration', 'value' => '30',    'group' => 'system', 'type' => 'integer'],
            ['key' => 'currency_code',        'value' => 'CDF',   'group' => 'system', 'type' => 'string'],
            ['key' => 'low_stock_alert',      'value' => '1',     'group' => 'system', 'type' => 'boolean'],
            ['key' => 'expiry_alert_days',    'value' => '30',    'group' => 'system', 'type' => 'integer'],

            // PDF
            ['key' => 'invoice_footer', 'value' => 'Merci de votre confiance. Conservez ce reçu.', 'group' => 'pdf', 'type' => 'string'],
            ['key' => 'prescription_note', 'value' => 'Ordonnance valable 1 an à compter de la date de prescription.', 'group' => 'pdf', 'type' => 'string'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
