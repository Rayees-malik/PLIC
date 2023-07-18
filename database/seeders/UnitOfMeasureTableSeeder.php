<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitOfMeasureTableSeeder extends Seeder
{
    public function run()
    {
        $timestamp = new DateTime;

        $unitsOfMeasure = [
            ['unit' => 'g', 'description' => 'Grams', 'unit_fr' => 'g', 'description_fr' => 'Gramme'],
            ['unit' => 'kg', 'description' => 'Kilograms', 'unit_fr' => 'kg', 'description_fr' => 'Kilogramme'],
            ['unit' => 'ml', 'description' => 'Millilitres', 'unit_fr' => 'ml', 'description_fr' => 'Millilitres'],
            ['unit' => 'l', 'description' => 'Litres', 'unit_fr' => 'l', 'description_fr' => 'Litre'],
            ['unit' => 'caps', 'description' => 'Capsule Containing Powder', 'unit_fr' => 'cap. poud', 'description_fr' => 'Capsule de poudre'],
            ['unit' => 'sg', 'description' => 'Softgel Containing Liquid', 'unit_fr' => 'gél', 'description_fr' => 'Gélule'],
            ['unit' => 'capl', 'description' => 'Hard Caplet', 'unit_fr' => 'compr-cap', 'description_fr' => 'Capsule de poudre'],
            ['unit' => 'tab', 'description' => 'Tablet', 'unit_fr' => 'compr', 'description_fr' => 'Comprimé'],
            ['unit' => 'vcap', 'description' => 'Vegetarian Capsule', 'unit_fr' => 'cap. végé', 'description_fr' => 'Capsule végétale'],
            ['unit' => 'pc', 'description' => 'Pieces', 'unit_fr' => 'pc', 'description_fr' => 'Pièces'],
            ['unit' => 'un', 'description' => 'Units', 'unit_fr' => 'uté', 'description_fr' => 'Unités'],
            ['unit' => 'pk', 'description' => 'Pack', 'unit_fr' => 'paq', 'description_fr' => 'Paquet'],
            ['unit' => 'bg', 'description' => 'Bag', 'unit_fr' => 'sac', 'description_fr' => 'Sac'],
            ['unit' => 'pad', 'description' => 'Pad', 'unit_fr' => 'bloc-n', 'description_fr' => 'Bloc-notes'],
            ['unit' => 'tray', 'description' => 'Tray', 'unit_fr' => 'prés compt', 'description_fr' => 'Présentoir de comptoir'],
            ['unit' => 'ct', 'description' => 'Count', 'unit_fr' => 'ct', 'description_fr' => 'Compte'],
            ['unit' => 'box', 'description' => 'Box', 'unit_fr' => 'bte', 'description_fr' => 'Boîte'],
            ['unit' => 'set', 'description' => 'Set', 'unit_fr' => 'lot', 'description_fr' => 'Lot'],
            ['unit' => 'kit', 'description' => 'Kit', 'unit_fr' => 'tr', 'description_fr' => 'Trousse'],
            ['unit' => 'ea', 'description' => 'Each', 'unit_fr' => 'chaq', 'description_fr' => 'Chaque'],
            ['unit' => 'disp', 'description' => 'Display', 'unit_fr' => 'prés', 'description_fr' => 'Présentoir'],
            ['unit' => 'clstr', 'description' => 'Clipstrip', 'unit_fr' => 'b-aff', 'description_fr' => 'Bande d’affichage'],
            ['unit' => 'ch', 'description' => 'Chew', 'unit_fr' => 'compr croq.', 'description_fr' => 'Comprimé à croquer'],
            ['unit' => 'lz', 'description' => 'Lozenge', 'unit_fr' => 'past', 'description_fr' => 'Pastille'],
            ['unit' => 'm', 'description' => 'Metre', 'unit_fr' => 'm', 'description_fr' => 'Mètre'],
            ['unit' => 'gmy', 'description' => 'Gummy', 'unit_fr' => 'jjb', 'description_fr' => 'Jujube'],
        ];

        DB::table('unit_of_measure')->insert($unitsOfMeasure);
    }
}
