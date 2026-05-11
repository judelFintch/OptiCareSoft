<?php

namespace App\Enums;

enum DiagnosisType: string
{
    case Myopia              = 'myopia';
    case Hyperopia           = 'hyperopia';
    case Astigmatism         = 'astigmatism';
    case Presbyopia          = 'presbyopia';
    case Cataract            = 'cataract';
    case Glaucoma            = 'glaucoma';
    case Conjunctivitis      = 'conjunctivitis';
    case Keratitis           = 'keratitis';
    case DiabeticRetinopathy = 'diabetic_retinopathy';
    case MacularDegeneration = 'macular_degeneration';
    case RetinalDetachment   = 'retinal_detachment';
    case Strabismus          = 'strabismus';
    case Amblyopia           = 'amblyopia';
    case DryEye              = 'dry_eye';
    case Pterygium           = 'pterygium';
    case Uveitis             = 'uveitis';
    case Other               = 'other';

    public function label(): string
    {
        return match($this) {
            self::Myopia              => 'Myopie',
            self::Hyperopia           => 'Hypermétropie',
            self::Astigmatism         => 'Astigmatisme',
            self::Presbyopia          => 'Presbytie',
            self::Cataract            => 'Cataracte',
            self::Glaucoma            => 'Glaucome',
            self::Conjunctivitis      => 'Conjonctivite',
            self::Keratitis           => 'Kératite',
            self::DiabeticRetinopathy => 'Rétinopathie diabétique',
            self::MacularDegeneration => 'Dégénérescence maculaire',
            self::RetinalDetachment   => 'Décollement de rétine',
            self::Strabismus          => 'Strabisme',
            self::Amblyopia           => 'Amblyopie',
            self::DryEye              => 'Sécheresse oculaire',
            self::Pterygium           => 'Ptérygion',
            self::Uveitis             => 'Uvéite',
            self::Other               => 'Autre',
        };
    }
}
