<?php

const OFFER_TYPES = [
    'new'        => ['id'=>'new'        ,'en' => 'New'         ,'fr' => 'Voiture neuve'         ,'nl'=>'Nieuw'],
    'used'       => ['id'=>'used'       ,'en' => 'Second-hand' ,'fr' => 'Voiture d\'occasion'	,'nl'=>'Occasie'],
    '0km'        => ['id'=>'0km'        ,'en' => '...'         ,'fr' => 'Voiture 0km'           ,'nl'=>'...'],
    'nearly-new' => ['id'=>'nearly-new' ,'en' => 'Nearly new'  ,'fr' => 'Presque neuf'          ,'nl'=>'Jong gebruikt'],
];

const STANDARD_EQUIPMENTS = [
    '7-seats'           =>  ['id'=>'7-seats'           ,'en'=>'7 seats'                ,'fr'=>'7 places'               ,'nl'=>'7 plaatsen'],
    'air-conditioning'  =>  ['id'=>'air-conditioning'  ,'en'=>'Air conditioning'       ,'fr'=>'Climatisation'          ,'nl'=>'Airco'],
    'gps'               =>  ['id'=>'gps'               ,'en'=>'GPS'                    ,'fr'=>'GPS'                    ,'nl'=>'GPS'],
    'leather'           =>  ['id'=>'leather'           ,'en'=>'Leather'                ,'fr'=>'Intérieur cuir'         ,'nl'=>'...'],
    'leather-alcantara' =>  ['id'=>'leather-alcantara' ,'en'=>'Leather / alcantara'    ,'fr'=>'Cuir / alcantara'       ,'nl'=>'Leder / alcantara'],
    'alloy-wheels'      =>  ['id'=>'alloy-wheels'      ,'en'=>'Alloy wheels'           ,'fr'=>'Jantes alliage'         ,'nl'=>'...'],
    'reverse-radar'     =>  ['id'=>'reverse-radar'     ,'en'=>'Reverse radar'          ,'fr'=>'Radar de recul'         ,'nl'=>'...'],
    'cruise-control'    =>  ['id'=>'cruise-control'    ,'en'=>'Cruise control'         ,'fr'=>'Régulateur de vitesse'  ,'nl'=>'...'],
    'sunroof'           =>  ['id'=>'sunroof'           ,'en'=>'Sunroof'                ,'fr'=>'Toit panoramique'       ,'nl'=>'...'],
    '4x4'               =>  ['id'=>'4x4'               ,'en'=>'4x4'                    ,'fr'=>'4x4'                    ,'nl'=>'...'],
    'bluetooth'         =>  ['id'=>'bluetooth'         ,'en'=>'Bluetooth'              ,'fr'=>'Bluetooth'              ,'nl'=>'...'],
    'reverse-camera'    =>  ['id'=>'reverse-camera'    ,'en'=>'Reverse camera'         ,'fr'=>'Caméra de recul'        ,'nl'=>'...'],
    'apple-carplay'     =>  ['id'=>'apple-carplay'     ,'en'=>'Apple Carplay'          ,'fr'=>'Apple Carplay'          ,'nl'=>'Apple Carplay'],
    'android-auto'      =>  ['id'=>'android-auto'      ,'en'=>'Android Auto'           ,'fr'=>'Android Auto'           ,'nl'=>'Android Auto'],
    'high-seat'         =>  ['id'=>'high-seat'         ,'en'=>'High seat'              ,'fr'=>'Accés facile'           ,'nl'=>'Hoge zit'],
];

const SIMPLE_COLORS = [
    'white' => ['id' => 'white', 'en' => 'White', 'fr' => 'Blanc', 'es'=>'Blanco', 'nl'=>'Wit'],
    'black' => ['id' => 'black', 'en' => 'Black', 'fr' => 'Noir', 'es'=>'Negro', 'nl'=>'Zwart'],
    'silver' => ['id' => 'silver', 'en' => 'Silver', 'fr' => 'Gris', 'es'=>'Plateado', 'nl'=>'Zilver'],
    'grey' => ['id' => 'grey', 'en' => 'Gray', 'fr' => 'Gris', 'es'=>'Gris', 'nl'=>'Grijs'],
    'blue' => ['id' => 'blue', 'en' => 'Blue', 'fr' => 'Bleu', 'es'=>'Azul', 'nl'=>'Blauw'],
    'red' => ['id' => 'red', 'en' => 'Red', 'fr' => 'Rouge', 'es'=>'Rojo', 'nl'=>'Rood'],
    'green' => ['id' => 'green', 'en' => 'Green', 'fr' => 'Vert', 'es'=>'Verde', 'nl'=>'Groen'],
    'yellow' => ['id' => 'yellow', 'en' => 'Yellow', 'fr' => 'Jaune', 'es'=>'Amarillo', 'nl'=>'Geel'],
    'brown' => ['id' => 'brown', 'en' => 'Brown', 'fr' => 'Marron', 'es'=>'Marrón', 'nl'=>'Bruin'],
    'orange' => ['id' => 'orange', 'en' => 'Orange', 'fr' => 'Orange', 'es'=>'Naranja', 'nl'=>'Oranje'],
    'beige' => ['id' => 'beige', 'en' => 'Beige', 'fr' => 'Beige', 'es'=>'Beige', 'nl'=>'Beige'],
    'other' => ['id' => 'other', 'en' => 'Other', 'fr' => 'Autre', 'es'=>'Otro', 'nl'=>'Ander'],

    // alias purple --> other
    'purple' => ['id' => 'other', 'en' => 'Other', 'fr' => 'Autre', 'es'=>'Otro', 'nl'=>'Ander'],
];

const EQUIPMENT_CATEGORIES = [
    'comfort' => ['id'=>'comfort', 'en' => 'Comfort', 'fr' => 'Confort', 'nl' => 'Comfort'],
    'multimedia' => ['id'=>'multimedia', 'en' => 'Multimedia', 'fr' => 'Multimédia', 'nl' => 'Multimedia'],
    'interior' => ['id'=>'interior', 'en' => 'Interior', 'fr' => 'Interieur', 'nl' => 'Interieurstijl'],
    'exterior' => ['id'=>'exterior', 'en' => 'Exterior', 'fr' => 'Exterieur', 'nl' => 'Exterieurstijl'],
    'security' => ['id'=>'security', 'en' => 'Security', 'fr' => 'Sécurité', 'nl' => 'Veiligheid'],
    'other' => ['id'=>'other', 'en' => 'Other', 'fr' => 'Autre', 'nl' => 'Andere'],
];

const CARDOEN_EQUIPMENT_CATEGORY_CODES = [
    '1' => 'comfort',
    '2' => 'multimedia',
    '3' => 'interior',
    '4' => 'exterior',
    '5' => 'security',
    '0' => 'other'
];

const TRANSMISSIONS = [
    'manual' => ['id' => 'manual', 'en' => 'Manual', 'fr' => 'Manuelle', 'es' => 'Manual', 'nl' => 'Manueel'],
    'automatic' => ['id' => 'automatic', 'en' => 'Automatic', 'fr' => 'Automatique', 'es' => 'Automático', 'nl' => 'Automaat'],
];

const STATUSES = [
    'available' => ['id' => 'available', 'en' => 'Available', 'fr' => 'Disponible', 'nl' => '...'],
    'sold' => ['id' => 'sold', 'en' => 'Sold', 'fr' => 'Vendu', 'nl' => 'Verkocht'],
    'reserved' => ['id' => 'reserved', 'en' => 'Reserved', 'fr' => 'Réservé', 'nl' => 'Gereserveerd'],
    'available-soon' => ['id' => 'available-soon', 'en'=> 'Available soon', 'fr' => 'Bientôt disponible', 'nl' => '...'],
];

const ENERGY_TYPES = [
    'gasoline' => ['id' => 'gasoline', 'en' => 'Gasoline', 'fr' => 'Essence', 'nl' => 'Benzine'],
    'diesel' => ['id' => 'diesel', 'en' => 'Diesel', 'fr' => 'Diesel', 'nl' => 'Diesel'],
    'electric' => ['id' => 'electric', 'en' => 'Electric', 'fr' => 'Electrique', 'nl' => 'Elektrisch'],
    'biodiesel' => ['id' => 'biodiesel', 'en' => 'Biodiesel', 'fr' => '...', 'nl' => '...'],
    'ethanol' => ['id' => 'ethanol', 'en' => 'Ethanol', 'fr' => 'E85', 'nl' => '...'],
    'lpg' => ['id' => 'lpg', 'en' => 'LPG', 'fr' => 'GPL', 'nl' => '...'],
    'cng' => ['id' => 'cng', 'en' => 'CNG', 'fr' => 'GNC', 'nl' => '...'],
    'hydrogen' => ['id' => 'hydrogen', 'en' => 'Hydrogen', 'fr' => '...', 'nl' => '...'],
    'hybrid'                 => ['id' => 'hybrid'                 ,'en' => 'Hybrid'                     ,'fr'=>'Hybride'                          ,'nl' => 'Hybride'],
    'hybrid-gasoline'        => ['id' => 'hybrid-gasoline'        ,'en' => 'Hybrid gasoline'            ,'fr'=>'Hybride essence'                  ,'nl' => 'Hybride benzine'          ,'parent' => 'hybrid'],
    'hybrid-diesel'          => ['id' => 'hybrid-diesel'          ,'en' => 'Hybrid diesel'              ,'fr'=>'Hybride diesel'                   ,'nl' => 'Hybride diesel'           ,'parent' => 'hybrid'],
    'plugin-hybrid'          => ['id' => 'plugin-hybrid'          ,'en' => 'Plug-in hybrid'             ,'fr'=>'Hybride rechargeable'             ,'nl' => 'Plug-in hybride'          ,'parent' => 'hybrid'],
    'plugin-hybrid-gasoline' => ['id' => 'plugin-hybrid-gasoline' ,'en' => 'Plug-in hybrid gazoline'    ,'fr'=>'Hybride rechargeable essence'     ,'nl' => 'Plug-in hybride benzine'  ,'parent' => 'plugin-hybrid'],
    'plugin-hybrid-diesel'   => ['id' => 'plugin-hybrid-diesel'   ,'en' => 'Plug-in hybrid diesel'      ,'fr'=>'Hybride rechargeable diesel'      ,'nl' => 'Plug-in hybride diesel'   ,'parent' => 'plugin-hybrid'],
    'mild-hybrid'            => ['id' => 'mild-hybrid'            ,'en' => 'Mild hybrid'                ,'fr'=>'Micro hybride'                    ,'nl' => 'Mild hybride'             ,'parent' => 'hybrid'],
    'mild-hybrid-gasoline'   => ['id' => 'mild-hybrid-gasoline'   ,'en' => 'Mild hybrid gazoline'       ,'fr'=>'Micro hybride essence'            ,'nl' => 'Mild hybride benzine'     ,'parent' => 'mild-hybrid'],
    'mild-hybrid-diesel'     => ['id' => 'mild-hybrid-diesel'     ,'en' => 'Mild hybrid diesel'         ,'fr'=>'Micro hybride essence diesel'     ,'nl' => 'Mild hybride diesel'      ,'parent' => 'mild-hybrid'],
];

const CATEGORIES = [
    'minivan' => ['id' => 'minivan', 'en' => 'Minivan', 'fr' => 'Monospace', 'es' => 'Monovolumen', 'nl' => '...'],
    'mpv' => ['id' => 'mpv', 'en' => 'MPV', 'fr' => 'Ludospace', 'es' => 'Monovolumen', 'nl' => '...'],
    'sedan' => ['id' => 'sedan', 'en' => 'Sedan', 'fr' => 'Routière', 'es' => 'Berlina', 'nl' => '...'],
    'station-wagon' => ['id' => 'station-wagon', 'en' => 'Station wagon', 'fr' => 'Break', 'es' => 'Familiar', 'nl' => '...'],
    'urban' => ['id' => 'urban', 'en' => 'Urban', 'fr' => 'Citadine', 'es' => 'Compacto', 'nl' => '...'],
    'hatchback' => ['id' => 'hatchback', 'en' => 'Hatchback', 'fr' => 'Berline Compacte', 'es' => 'Compacto', 'nl' => 'Compacte'],
    'sport' => ['id' => 'sport', 'en' => 'Coupe', 'fr' => 'Sport', 'es' => 'Coupé', 'nl' => '...'],
    'convertible' => ['id' => 'convertible', 'en' => 'Convertible', 'fr' => 'Cabriolet', 'es' => 'Cabrio', 'nl' => '...'],
    'suv' => ['id' => 'suv', 'en' => '4x4 / SUV', 'fr' => '4x4 et SUV', 'es' => '4x4 / SUV', 'nl' => '4x4 / SUV'],
    'van' => ['id' => 'van', 'en' => 'Van', 'fr' => 'Utilitaire', 'es' => 'Furgoneta', 'nl' => '...'],
];

const SEGMENTS = [
    'family' => ['id' => 'family', 'en' => 'Family', 'fr' => 'Familiale', 'nl' => 'Gezinswagen']
];

const DRIVES = [
    'fwd' => ['id' => 'fwd', 'en' => 'FWD', 'fr' => 'Traction avant', 'es' => '...', 'nl' => '...'],
    'rwd' => ['id' => 'rwd', 'en' => 'RWD', 'fr' => 'Traction arrière', 'es' => '...', 'nl' => '...'],
    '4wd' => ['id' => '4wd', 'en' => '4WD', 'fr' => '4x4', 'es' => '...', 'nl' => '...'],
];

const CARDOEN_CATEGORY_CODES = [
    'rg' => 'sedan',
    'rs' => 'suv',
    'cs'=> 'suv',
    'cg' => 'hatchback'
];

const CARDOEN_CATEGORY_SEGMENT_MAP = [
    'rg' => 'family',
    'rs' => null,
    'cs'=> null,
    'cg' => 'family'
];

const CARDOEN_ENERGY_TYPE_NAMES = [
    'petrol' => 'gasoline',
    'diesel' => 'diesel',
    'electric' => 'electric',
    'mild hybride petrol' => 'mild-hybrid-gasoline',
    'mild hybride diesel' => 'mild-hybrid-diesel',
    'hybrid diesel-electric' => 'hybrid-diesel'
];

const CARDOEN_SIMPLE_COLOR_NAMES = [
    'wit' => ['white', null],
    'wit/zwart' => ['white', 'black'],
    'wit/beige' => ['white', 'beige'],
    'witmet' => ['white', null],
    'witmet/zwart' => ['white', 'black'],
    'grijsmet' => ['grey', null],
    'grijs' => ['grey', null],
    'grijs/zwart' => ['grey', 'black'],
    'grijsmet/zwart' => ['grey', 'black'],
    'grijs/beige' => ['grey', 'beige'],
    'anthracietmet' => ['grey', null],
    'zwart' => ['black', null],
    'zwart/rood' => ['black', 'red'],
    'zwartmet' => ['black', null],
    'zwartmet/wit' => ['black', 'white'],
    'zwartmet/grijs' => ['black', 'grey'],
    'rood' => ['red', null],
    'roodmet' => ['red', null],
    'rood/zwart' => ['red', 'black'],
    'roodmet/zwart' => ['red', 'black'],
    'paars' => ['purple', null],
    'paarsmet' => ['purple', null],
    'zilver' => ['silver', null],
    'zilvermet' => ['silver', null],
    'zilvermet/zwart' => ['silver', 'black'],
    'zilverblauwmet' => ['silver', 'blue'],
    'blauw' => ['blue', null],
    'blauw/zwart' => ['blue', 'black'],
    'blauwmet' => ['blue', null],
    'blauwmet/wit' => ['blue', 'white'],
    'blauwmet/grijs' => ['blue', 'grey'],
    'beige' => ['beige', null],
    'beigemet' => ['beige', null],
    'bruin' => ['brown', null],
    'bruin metaalkleur' => ['brown', null],
    'bordeauxmet' => ['red', null],
    'geel' => ['yellow', null],
    'geelmet' => ['yellow', null],
    'groenmet' => ['green', null],
    'groen' => ['green', null],
    'groen-blauwmet' => ['green', 'blue'],
    'oranje' => ['orange', null],
    'oranjemet' => ['orange', null]
];

const CARDOEN_OFFER_TYPE_IDS = [
    563 => 'new',
    564 => 'used',
    561 => 'nearly-new'
];

const CARDOEN_TRANSMISSION_TYPES = [
    'manual' => 'manual',
    'automatic' => 'automatic'
];

const CARDOEN_EURONORMS = [
    '1' => '1',
    '2' => '2',
    '3' => '3',
    '4' => '4',
    '5' => '5',
    '6' => '6',
    'AM EURO 6' => '6a',
    'AG EURO 6' => '6a',
    'AP EURO 6' => '6a',
    'AX EURO 6' => '6a',
    'AD EURO 6' => '6a',
    'DG EURO 6' => '6a',
    'B EURO 6' => '6b',
    'BG EURO 6' => '6b',
    'C EURO 6' => '6c',
    'D EURO 6' => '6d',
    'W EURO 6' => '6',
    'ZA EURO 6' => '6',
];
