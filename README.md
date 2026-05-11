# OptiCare Soft

Application web de gestion pour centre ophtalmologique et optique.

Le projet couvre le cycle complet: accueil patient, rendez-vous, consultation, prescriptions, commandes optiques, caisse/facturation, pharmacie, stock et reporting.

## Fonctionnalites principales

- Gestion des patients et de leurs documents
- Gestion des rendez-vous (confirmation, annulation)
- Parcours reception/visites
- Consultation ophtalmologique et examens associes
- Prescriptions medicales et optiques (avec generation PDF)
- Commandes optiques et suivi de statut
- Gestion de stock optique (montures, verres)
- Module pharmacie (produits, ventes, stock)
- Caisse, factures, paiements, recus/PDF
- Rapports journaliers, financiers, dettes, export Excel
- Administration: utilisateurs, roles/permissions, services, devises, parametres, journal d'activite

## Stack technique

- PHP 8.3+
- Laravel 13
- Livewire 4
- Tailwind CSS + Vite
- Alpine.js
- spatie/laravel-permission (RBAC)
- spatie/laravel-activitylog
- barryvdh/laravel-dompdf (PDF)
- rap2hpoutre/fast-excel (exports)

## Prerequis

- PHP 8.3+
- Composer 2+
- Node.js 20+ et npm
- Base de donnees (SQLite par defaut dans `.env.example`)

## Installation rapide

```bash
composer install
cp .env.example .env
php artisan key:generate

# Si vous utilisez SQLite
touch database/database.sqlite

php artisan migrate --seed
npm install
npm run build
```

## Demarrage en local

Lancer tout l'environnement de dev (serveur, queue, logs, vite):

```bash
composer run dev
```

Ou en mode separe:

```bash
php artisan serve
php artisan queue:listen --tries=1 --timeout=0
php artisan pail --timeout=0
npm run dev
```

## Comptes de demo (seeders)

Ces comptes sont crees par le seeder principal:

- Admin: `admin@opticare.local` / `Admin@2026!`
- Ophthalmologist: `docteur@opticare.local` / `Doctor@2026!`
- Receptionist: `reception@opticare.local` / `Recept@2026!`
- Cashier: `caisse@opticare.local` / `Cashier@2026!`

## Roles metier

Le systeme RBAC est base sur les roles suivants:

- Admin
- Manager
- Receptionist
- Ophthalmologist
- Optician
- Cashier
- Pharmacist

## Commandes utiles

```bash
# Initialiser rapidement le projet
composer run setup

# Lancer les tests
composer run test

# Seeder uniquement
php artisan db:seed

# Vider les caches
php artisan optimize:clear
```

## Modules fonctionnels (routes)

- Dashboard
- Patients
- Appointments
- Reception
- Consultations
- Optical
- Pharmacy
- Cashier
- Reports
- Admin

## Notes

- L'authentification est basee sur Laravel Breeze.
- Les PDF (ordonnances, factures, recus) utilisent DOMPDF.
- Les exports de rapports utilisent FastExcel.

## Licence

Projet interne OptiCare Soft.
