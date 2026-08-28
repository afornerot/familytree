<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FontawsomeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => self::getIcons(),
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public static function getIcons(): array
    {
        return [
            'Globe' => 'fa-globe',
            'Carte' => 'fa-map',
            'Épée' => 'fa-gavel',
            'Bouclier' => 'fa-shield-halved',
            'Dragon' => 'fa-dragon',
            'Château' => 'fa-chess-rook',
            'Couronne' => 'fa-crown',
            'Étoile' => 'fa-star',
            'Livre' => 'fa-book',
            'Skull' => 'fa-skull',
            'Wand' => 'fa-wand-sparkles',
            'Potion' => 'fa-flask',
            'Crystal ball' => 'fa-crystal-ball',
            'Dice' => 'fa-dice-d20',
            'Scroll' => 'fa-scroll',
            'Tent' => 'fa-campground',
            'Moutain' => 'fa-mountain',
            'Forêt' => 'fa-tree',
            'Feu' => 'fa-fire',
            'Eau' => 'fa-water',
            'Lune' => 'fa-moon',
            'Soleil' => 'fa-sun',
            'Boussole' => 'fa-compass',
            'Torche' => 'fa-fire-flame-curved',
            'Hache' => 'fa-axe-battle',
            'Arc' => 'fa-bow-arrow',
            'Horse' => 'fa-horse',
            'Dove' => 'fa-dove',
            'Cat' => 'fa-cat',
            'Dog' => 'fa-dog',
            'Bug' => 'fa-bug',
            'Spider' => 'fa-spider',
            'Fish' => 'fa-fish',
            'Frog' => 'fa-frog',
            'Bird' => 'fa-kiwi-bird',
            'Bolt' => 'fa-bolt',
            'Cloud' => 'fa-cloud',
            'Ghost' => 'fa-ghost',
            'Ring' => 'fa-ring',
            'Gem' => 'fa-gem',
            'Key' => 'fa-key',
            'Lock' => 'fa-lock',
            'Eye' => 'fa-eye',
            'Wizard' => 'fa-hat-wizard',
            'Staff' => 'fa-staff-snake',
            'Broom' => 'fa-broom',
            'Flask-vial' => 'fa-flask-vial',
            'Book-skull' => 'fa-book-skull',
            'Dungeon' => 'fa-dungeon',
            'Fort' => 'fa-fort-awesome',
            'Monument' => 'fa-monument',
            'Map-pin' => 'fa-map-pin',
            'Route' => 'fa-route',
            'Users' => 'fa-users',
            'User-secret' => 'fa-user-secret',
            'User-ninja' => 'fa-user-ninja',
            'Crosshairs' => 'fa-crosshairs',
            'Skull-crossbones' => 'fa-skull-crossbones',
            'Bomb' => 'fa-bomb',
            'Biohazard' => 'fa-biohazard',
            'Radiation' => 'fa-radiation',
            'Exclamation' => 'fa-triangle-exclamation',
            'Hand-fist' => 'fa-hand-fist',
            'Heart' => 'fa-heart',
            'Heart-crack' => 'fa-heart-crack',
            'Fire-flame' => 'fa-fire-flame-curved',
            'Meteor' => 'fa-meteor',
            'Hourglass' => 'fa-hourglass',
            'Clock' => 'fa-clock',
            'Bell' => 'fa-bell',
            'Shield-cross' => 'fa-shield-cross',
            'Cloud-bolt' => 'fa-cloud-bolt',
            'Snowflake' => 'fa-snowflake',
            'Wind' => 'fa-wind',
            'Droplet' => 'fa-droplet',
            'Seedling' => 'fa-seedling',
            'Leaf' => 'fa-leaf',
            'Anchor' => 'fa-anchor',
            'Ship' => 'fa-ship',
            'Sword' => 'fa-shield-halved',
            'Axe' => 'fa-axe-battle',
            'Mace' => 'fa-staff-snake',
            'Bow' => 'fa-bow-arrow',
            'Shield-alt' => 'fa-shield-halved',
            'Helmet-safety' => 'fa-helmet-safety',
            'Shield-halved' => 'fa-shield-halved',
            'Person-running' => 'fa-person-running',
            'Person-walking' => 'fa-person-walking',
            'Person-drowning' => 'fa-person-drowning',
            'Person-falling' => 'fa-person-falling-burst',
            'Skull-crossbones' => 'fa-skull-crossbones',
            'Hand-horns' => 'fa-hand-horns',
            'Claw-marks' => 'fa-paw',
            'Paw' => 'fa-paw',
            'Trident' => 'fa-water',
            'Sitemap' => 'fa-sitemap',
            'Landmark' => 'fa-landmark',
            'Columns' => 'fa-columns',
            'Archway' => 'fa-archway',
            'House' => 'fa-house',
            'Tent' => 'fa-tent',
            'Warehouse' => 'fa-warehouse',
            'Igloo' => 'fa-igloo',
        ];
    }
}
