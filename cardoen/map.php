<?php
require 'vendor/autoload.php';
require 'enumerations.php';

$json = json_decode(file_get_contents('cardoen_publish.json'), true);
$langs = ['en', 'fr', 'nl'];
$now = new DateTimeImmutable();
$json_schema_validator = new Opis\JsonSchema\Validator();
$json_schema_validator->resolver()->registerFile('https://schemas.aramis.group/catalog/base.schema.json', '../catalog/base.schema.json',);
$json_schema_error_formatter = new Opis\JsonSchema\Errors\ErrorFormatter();
$appsearch_offer_schema = json_decode(file_get_contents('../catalog/vehicle-single-language-searchable-data.schema.json'), false, 512, JSON_THROW_ON_ERROR);
$searchapi_vehicle_schema = json_decode(file_get_contents('../catalog/vehicle-single-language-output.schema.json'), false, 512, JSON_THROW_ON_ERROR);
$iso_countries = new League\ISO3166\ISO3166;

$appsearch_output_files = [];
$appsearch_output_dirty = [];
$api_output_files = [];
$api_output_dirty = [];
foreach ($langs as $lang) {
    $appsearch_output_files[$lang] = 'dist/appsearch_cardoen_' . $lang . '.json';
    $appsearch_output_dirty[$lang] = false;
    $api_output_files[$lang] = 'dist/api_vehicles_cardoen_' . $lang . '.json';
    $api_output_dirty[$lang] = false;
}
$eqq = [];

foreach ($json as $offer) {
    foreach ($langs as $lang) {
        $search = ['$schema' => 'https://schemas.aramis.group/catalog/vehicle-single-language-searchable-data.schema.json'];
        $api_vehicle = ['$schema' => 'https://schemas.aramis.group/catalog/vehicle-single-language-output.schema.json'];

        $search['offer_id'] = $offer['offer_hash'];
        $api_vehicle['offerId'] = $offer['offer_hash'];

        $api_vehicle['variants'] = array_map(
            function($product) use ($lang) {

                $simple_color = SIMPLE_COLORS[CARDOEN_SIMPLE_COLOR_NAMES[$product['product']['technical']['color']['name']][0]] ?? null;
                $simple_color2 = SIMPLE_COLORS[CARDOEN_SIMPLE_COLOR_NAMES[$product['product']['technical']['color']['name']][1]] ?? null;
                if ($simple_color === null || $simple_color[$lang] === '...') {
                    var_dump($product['product']['technical']['color']);
                    die;
                }
                return [
                    'vehicleId' => $product['id'],
                    'manufacturerColor' => $product['product']['technical']['color']['manufacturer_code'] ?? $simple_color[$lang],
                    'simpleColor' => ['id' => $simple_color['id'], 'label' => $simple_color[$lang]],
                    'simpleColor2' => ['id' => $simple_color2['id'] ?? null, 'label' => $simple_color2[$lang] ?? null],
                    'photo' => ['url' => $product['product']['media']['images'][0]['url'] ?? 'http://missing'] // todo: what to do if there is no image?
                ];
            },
            $offer['products']
        );

        $search['vehicle_ids'] = array_map(
            function($product) { return $product['id']; },
            $offer['products']
        );

        $search['manufacturer_colors'] = array_values(array_filter(array_map(
            function($product) { return $product['product']['technical']['color']['manufacturer_code'] ?? null; }, // TODO: Missing manufacturer colors
            $offer['products']
        ), fn ($x) => $x !== null));

        $status = STATUSES['available']; // TODO: missing statuses
        $search['status_id'] = $status['id'];
        $api_vehicle['status'] = ['id' => 'available', 'label' => $status[$lang]];

        $api_vehicle['warrantyExpirationDate'] = null; // TODO: missing warrantyExpirationDate

        $simple_colors = array_map(
            function ($product) use ($lang) {
                $simple_color = SIMPLE_COLORS[CARDOEN_SIMPLE_COLOR_NAMES[$product['product']['technical']['color']['name']][0]] ?? null;
                if ($simple_color === null || $simple_color[$lang] === '...') {
                    var_dump($product['product']['technical']['color']);
                    die;
                }
                return $simple_color;
            }
            , $offer['products']);
        $search['simple_color_ids'] = array_map(fn($x) => $x['id'], $simple_colors);
        $search['simple_color_labels'] = array_map(fn($x) => $x[$lang], $simple_colors);

        foreach ($offer['products'] as $product) {
            $vin = strtoupper($product['product']['vehicle_identification_number']);
            $api_vehicle['vin'] = preg_match('/^[A-Z0-9]{17}$/', $vin) ? $vin : null;
            $api_vehicle['vehicleId'] = $product['id'];

            $api_vehicle['isMarketplace'] = $product['supplier_name'] !== 'cardoen';

            $country = $iso_countries->alpha2($product['product']['country_origin']);
            $api_vehicle['originCountry'] = ['id' => $country['alpha3'], 'label' => $country['name']]; // in english only, todo: translate to the other languages

            $offer_type = OFFER_TYPES[CARDOEN_OFFER_TYPE_IDS[$product['product']['vehicle_type']['id']]];
            if ($offer_type === null || $offer_type[$lang] === '...') {
                var_dump($product['product']['vehicle_type']);
                die;
            }
            $search['offer_type_id'] = $offer_type['id'];
            $search['offer_type_label'] = $offer_type[$lang];
            $api_vehicle['offerType'] = ['id' => $offer_type['id'], 'label' => $offer_type[$lang]];

            $x = $product['product']['technical']['entry_into_service'] ?? null;
            if ($x === null) {
                $search['first_circulation_date'] = null;
                $search['first_circulation_year'] = null;
                $api_vehicle['firstCirculationDate'] = null;
            } else {
                $date = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', $x);
                $search['first_circulation_date'] = $date->format('Y-m-d');
                $search['first_circulation_year'] = intval($date->format('Y'));
                $api_vehicle['firstCirculationDate'] = $date->format('Y-m-d');
            }

            $search['mileage_km'] = $product['product']['technical']['mileage'];
            $search['mileage_km_slice'] = floor($search['mileage_km'] / 1000) * 1000;
            $api_vehicle['mileage'] = ['km' => $product['product']['technical']['mileage']];

            $x = $product['product']['technical']['fuel_economy']['euronorm'];
            if ($x === '') {
                $search['euronorm'] = null;
                $api_vehicle['euronorm'] = null;
            }
            else {
                $euronorm = CARDOEN_EURONORMS[$x];
                if ($euronorm === null) {
                    var_dump($product['product']['technical']['fuel_economy']['euronorm']);
                    die;
                }
                $search['euronorm'] = $euronorm;
                $api_vehicle['euronorm'] = $euronorm;
            }
            $search['critair'] = null;
            $api_vehicle['critair'] = null;


            $api_vehicle['frenchFiscalPower'] = null;
            $api_vehicle['frenchGenre'] = null;

            $api_vehicle['belgianFiscalPower'] = $product['product']['technical']['taxes'][0]['value'] ?? null;
            $api_vehicle['belgianOtoto'] = ['url' => $product['product']['media']['ototo'] ?? null];
            $api_vehicle['belgianCarpass'] = ['url' => $product['product']['media']['carpass'] ?? null];

            $search['maker'] = $product['product']['model']['make'];
            $search['model'] = $product['product']['model']['model'];
            $search['engine'] = $product['product']['model']['motorization']['label'];
            if ($search['engine'] === "") $search['engine'] = null;
            $search['finish'] = $product['product']['model']['finish'];
            if ($search['finish'] === "") $search['finish'] = null;
            $search['version'] = "";
            if ($search['version'] === "") $search['version'] = null;
            $search['interior'] = "";
            if ($search['interior'] === "") $search['interior'] = null;

            $api_vehicle['maker'] = $product['product']['model']['make'];
            $api_vehicle['model'] = $product['product']['model']['model'];
            $api_vehicle['engine'] = $product['product']['model']['motorization']['label'];
            $api_vehicle['finish'] = $product['product']['model']['finish'];
            $api_vehicle['version'] = "";
            $api_vehicle['interior'] = "";
            $api_vehicle['expertComments'] = "";

            $simple_color = SIMPLE_COLORS[CARDOEN_SIMPLE_COLOR_NAMES[$product['product']['technical']['color']['name']][0]] ?? null;
            $simple_color2 = SIMPLE_COLORS[CARDOEN_SIMPLE_COLOR_NAMES[$product['product']['technical']['color']['name']][1]] ?? null;
            if ($simple_color === null || $simple_color[$lang] === '...') {
                var_dump($product['product']['technical']['color']);
                die;
            }
            $api_vehicle['manufacturerColor'] = $product['product']['technical']['color']['manufacturer_code'] ?? $simple_color[$lang];
            $api_vehicle['simpleColor'] = ['id'=>$simple_color['id'],'label'=>$simple_color[$lang]];
            $api_vehicle['simpleColor2'] = ['id'=>$simple_color2['id'] ?? null,'label'=>$simple_color2[$lang] ?? null];

            $simple_color = SIMPLE_COLORS[CARDOEN_SIMPLE_COLOR_NAMES[$product['product']['interior']['color']['name']][0]] ?? null;
            if ($simple_color === null || $simple_color[$lang] === '...') {
                var_dump($product['product']['interior']['color']);
                die;
            }
            $api_vehicle['interiorSimpleColor'] = ['id'=>$simple_color['id'],'label'=>$simple_color[$lang]];

            $api_vehicle['media'] = array_map(
                fn($image) => ['type' => 'photo', 'url' => $image['url']],
                $product['product']['media']['images'] ?? []
            );
            $video_url = $product['product']['media']['video'] ?? null;
            if ($video_url !== null && $video_url !== '') {
                $api_vehicle['media'][] = ['type' => 'video', 'url' => $video_url];
            }

            $api_vehicle['photo'] = ['url' => $product['product']['media']['images'][0]['url'] ?? 'http://missing']; // todo: what to do if there is no image?

            $category = CATEGORIES[CARDOEN_CATEGORY_CODES[$product['product']['model']['category']['code']]];
            if ($category === null) {
                var_dump('Category missing');
                var_dump($product['product']['model']['category']);
                var_dump($product['product']['model']['make'] . ' ' . $product['product']['model']['model']);
                die;
            }
            $search['category_id'] = $category['id'];
            $search['category_label'] = $category[$lang];
            $api_vehicle['category'] = ['id' => $category['id'], 'label' => $category[$lang]];

            $x = $product['product']['model']['category']['code'];
            if (array_key_exists($x, CARDOEN_CATEGORY_SEGMENT_MAP)) {
                $segment = SEGMENTS[CARDOEN_CATEGORY_SEGMENT_MAP[$x] ?? null] ?? null;
                $search['segment_id'] = $segment === null ? null : $segment['id'];
                $search['segment_label'] = $segment === null ? null :  $segment[$lang];
                $api_vehicle['segment'] = ['id'=>$segment === null ? null : $segment['id'],'label'=>$segment === null ? null :  $segment[$lang]];
            }
            else {
                var_dump('Segment missing');
                var_dump($product['product']['model']['category']);
                var_dump($product['product']['model']['make'] . ' ' . $product['product']['model']['model']);
                die;
            }

            $search['doors'] = $product['product']['technical']['doors_count'];
            $search['seats'] = $product['product']['technical']['number_of_seats'];
            $api_vehicle['doors'] = $product['product']['technical']['doors_count'];
            $api_vehicle['seats'] = $product['product']['technical']['number_of_seats'];
            $api_vehicle['gears'] = null; // TODO: number of gears missing

            $energy_type = ENERGY_TYPES[CARDOEN_ENERGY_TYPE_NAMES[$product['product']['technical']['fuel']['name']]];
            if ($energy_type === null || $energy_type[$lang] === '...') {
                var_dump($product['product']['technical']['fuel']);
                die;
            }
            $search['energy_type_ids'] = [];
            $search['energy_type_labels'] = [];
            for ($e = $energy_type; $e !== null; $e = ENERGY_TYPES[$e['parent'] ?? null] ?? null) {
                $search['energy_type_ids'][] = $e['id'];
                $search['energy_type_labels'][] = $e[$lang];
            }
            $api_vehicle['energyType'] = ['id' => $energy_type['id'], 'label' => $energy_type[$lang]];

            $transmission = TRANSMISSIONS[CARDOEN_TRANSMISSION_TYPES[$product['product']['technical']['gearbox']['type']]];
            if ($transmission === null || $transmission[$lang] === '...') {
                var_dump($product['product']['technical']['gearbox']);
                die;
            }
            $search['transmission_id'] = $transmission['id'];
            $search['transmission_label'] = $transmission[$lang];
            $api_vehicle['transmission'] = ['id' => $transmission['id'], 'label' => $transmission[$lang]];

            $drive = DRIVES['fwd']; // todo: missing drives
            $search['drive_id'] = $drive['id'];
            $search['drive_label'] = $drive[$lang];
            $api_vehicle['drive'] = ['id' => $drive['id'], 'label' => $drive[$lang]];

            $search['equipment_labels'] = array_map(
                fn($eq) => array_values(array_filter($eq['localizations'], fn($loc) => $loc['language'] === $lang))[0]['label'],
                array_values(array_filter($product['product']['equipments']['standard'], fn ($eq) => $eq['type'] === 1))
            );

            foreach ($search['equipment_labels'] as $eq) {
                $eqq[$eq] = true;
            }

            $api_vehicle['equipments'] = array_map(
                function ($eq) use ($lang) {
                    $category = EQUIPMENT_CATEGORIES[CARDOEN_EQUIPMENT_CATEGORY_CODES[$eq['category_code']]];
                    return [
                        'label' => array_values(array_filter($eq['localizations'], fn($loc) => $loc['language'] === $lang))[0]['label'],
                        'category' => ['id' => $category['id'], 'label' => $category[$lang]]
                    ];
                },
                array_values(array_filter($product['product']['equipments']['standard'], fn ($eq) => $eq['type'] === 1))
            );

            $api_vehicle['options'] = array_map(
                function ($eq) use ($lang) {
                    $category = EQUIPMENT_CATEGORIES[CARDOEN_EQUIPMENT_CATEGORY_CODES[$eq['category_code']]];
                    return [
                        'label' => array_values(array_filter($eq['localizations'], fn($loc) => $loc['language'] === $lang))[0]['label'],
                        'priceWithTaxes' => $eq['price'] ?? 0.0
                    ];
                },
                array_values(array_filter($product['product']['equipments']['standard'], fn ($eq) => $eq['type'] === 2))
            );

            $search['simple_equipment_ids'] = [];
            $search['simple_equipment_labels'] = [];
            $api_vehicle['simpleEquipments'] = [];
            foreach ($search['equipment_labels'] as $eq) {
                $key = null;
                switch ($eq) {
                    case "7 seats":
                    case "7 places":
                    case "7 plaatsen":
                        $key = '7-seats';
                        break;
                    case 'elec. sliding sunroof':
                    case 'toît ouvrant électrique':
                    case 'schuifdak elektr.':
                    case 'toit panoramique (fixe)':
                    case 'panoramic roof (fixed)':
                    case 'panoramisch dak (vast)':
                    case 'toit ouvrant élec. panoramique':
                    case 'panoramisch elek. schuifdak.':
                    case 'panoramic elec. sliding roof':
                        $key = 'sunroof';
                        break;
                    case 'airco (semi autom.)':
                    case 'climatisation (semi autom.)':
                    case 'airconditioning (semi autom.)':
                    case 'airconditioning (full autom.)':
                    case 'climatisation (automatique)':
                    case 'airconditioning (vol autom.)':
                    case 'air conditioning (manual)':
                    case 'climatisation (manuelle)':
                    case 'airconditioning (manueel)':
                        $key = 'air-conditioning';
                        break;
                    case 'leather interior':
                    case 'revêtement des sièges en cuir':
                    case 'bekleding zetels leder':
                    case 'intérieur semi cuir':
                    case 'interior part. Leather':
                    case 'bekleding zetels gedeel.leder':
                    case 'interior part. alcantara / leather':
                    case 'revêtement des sièges cuir/alcantara':
                    case 'bekleding zetels ged.leder/alcantara':
                        $key = 'leather-alcantara';
                        break;
                }
                if ($key !== null) {
                    $search['simple_equipment_ids'][] = $key;
                    $search['simple_equipment_labels'][] = STANDARD_EQUIPMENTS[$key][$lang];
                    $api_vehicle['simpleEquipments'][] = ['id' => $key, 'label' => STANDARD_EQUIPMENTS[$key][$lang]];
                }
            }


            $search['power_ch'] = $product['product']['model']['motorization']['horse_power'];
            $api_vehicle['power'] = [
                'ch' => $product['product']['model']['motorization']['horse_power'],
                'kw' => $product['product']['model']['motorization']['power']
            ];
            $api_vehicle['cylinders'] = $product['product']['model']['motorization']['cylinder'];
            if ($api_vehicle['cylinders'] <= 0) $api_vehicle['cylinders'] = null;
            $api_vehicle['engineCapacity'] = ['cc' => $product['product']['model']['motorization']['engine_displacement']];
            if ($api_vehicle['engineCapacity']['cc'] <= 0) $api_vehicle['engineCapacity']['cc'] = null;

            $search['combined_cycle_consumption_lt_per_100km'] = $product['product']['technical']['fuel_economy']['fuel_economy_combined'] > 0
                ? $product['product']['technical']['fuel_economy']['fuel_economy_combined']
                : null;
            $search['combined_cycle_consumption_kwh_per_100km'] = null; // todo: electric energy consumption

            $api_vehicle['urbanCycleConsumption'] = [
                'ltPer100Km' => $product['product']['technical']['fuel_economy']['fuel_economy_urbain'] > 0
                     ? $product['product']['technical']['fuel_economy']['fuel_economy_urbain']
                     : null,
                'kwhPer100Km' => null // todo: electric energy consumption
            ];
            $api_vehicle['extraUrbanCycleConsumption'] = [
                'ltPer100Km' => $product['product']['technical']['fuel_economy']['fuel_economy_extra_urbain'] > 0
                    ? $product['product']['technical']['fuel_economy']['fuel_economy_extra_urbain']
                    : null,
                'kwhPer100Km' => null // todo: electric energy consumption
            ];
            $api_vehicle['combinedCycleConsumption'] = [
                'ltPer100Km' => $product['product']['technical']['fuel_economy']['fuel_economy_combined'] > 0
                    ? $product['product']['technical']['fuel_economy']['fuel_economy_combined']
                    : null,
                'kwhPer100Km' => null // todo: electric energy consumption
            ];

            $api_vehicle['co2EmissionsNedc'] = ['gramsPerKm' => $product['product']['technical']['fuel_economy']['co2_emission'] ?? null];
            if ($api_vehicle['co2EmissionsNedc']['gramsPerKm'] <= 0) $api_vehicle['co2EmissionsNedc']['gramsPerKm'] = null;
            $api_vehicle['co2EmissionsWltp'] = ['gramsPerKm' => $product['product']['technical']['fuel_economy']['co2_emission_wltp'] ?? null];
            if ($api_vehicle['co2EmissionsWltp']['gramsPerKm'] <= 0) $api_vehicle['co2EmissionsWltp']['gramsPerKm'] = null;

            $api_vehicle['height'] = ['meters' => $product['product']['technical']['dimension']['height'] ?? null];
            $api_vehicle['width'] = ['meters' => $product['product']['technical']['dimension']['width'] ?? null];
            $api_vehicle['length'] = ['meters' => $product['product']['technical']['dimension']['length'] ?? null];
            $api_vehicle['wheelbase'] = ['meters' => null]; // todo: missing wheelbase
            $api_vehicle['unloadedWeight'] = ['kg' => $product['product']['technical']['dimension']['weight'] ?? null];
            $api_vehicle['totalAuthorizedWeight'] = ['kg' => null]; // todo: missing totalAuthorizedWeight
            $api_vehicle['minBootSpaceBackCapacity'] = ['lt' => $product['product']['technical']['dimension']['boot_space'] ?? null];
            $api_vehicle['maxBootSpaceBackCapacity'] = ['lt' => $product['product']['technical']['dimension']['boot_space'] ?? null];
            $api_vehicle['bootSpaceFrontCapacity'] = ['lt' => null];
            $api_vehicle['maxSpeed'] = ['kmPerHour' => $product['product']['technical']['performance']['max_speed'] ?? null];

            $api_vehicle['electricRangeNedc'] = ['km' => null];
            $api_vehicle['electricRangeWltp'] = ['km' => null];
            $api_vehicle['electricBatteryWarrantyPeriod'] = ['km' => null, 'years' => null];
            $api_vehicle['electricBatteryStateOfHealth'] = ['percent' => null];
            $api_vehicle['electricBatteryCapacity'] = ['kwh' => null];
            $api_vehicle['electricBatteryType'] = null;
            $api_vehicle['electricBatterySlowChargePower'] = ['kw' => null];
            $api_vehicle['electricBatterySlowChargeTime'] = ['minutes' => null];
            $api_vehicle['electricBatteryMediumChargePower'] = ['kw' => null];
            $api_vehicle['electricBatteryMediumChargeTime'] = ['minutes' => null];
            $api_vehicle['electricBatteryQuickChargePower'] = ['kw' => null];
            $api_vehicle['electricBatteryQuickChargeTime'] = ['minutes' => null];
            $api_vehicle['electricBatteryCost'] = null;
            $api_vehicle['electricMaximumPowerCarElectricalOutlet'] = ['kw' => null];
            $api_vehicle['electricMaximumPowerCarDirectCurrent'] = ['amperes' => null];

            break;
        }

        $search['publication_date'] = $now->format('Y-m-d');

        $search['selling_price_with_taxes'] = $offer['price']['final_price'];
        $search['selling_price_with_taxes_slice'] = floor($search['selling_price_with_taxes'] / 1000) * 1000;
        $search['selling_price_with_takeover_with_taxes'] = $offer['price']['final_price_takeover'];
        $search['selling_price_with_takeover_with_taxes_slice'] = floor($search['selling_price_with_takeover_with_taxes'] / 1000) * 1000;
        $search['indicative_loan_monthly_installment'] = $offer['price']['finance_loan_option']['monthly_price'];
        $search['indicative_loan_monthly_installment_slice'] = floor($search['indicative_loan_monthly_installment'] / 5) * 5;

        $api_vehicle['catalogPriceWithOptionsWithTaxes'] = $offer['price']['retailer_price'];
        $api_vehicle['takeoverPremium'] = $offer['price']['takeover_premium'];

        $api_vehicle['sellingPriceWithTaxes'] = $offer['price']['final_price'];
        $d = ($api_vehicle['catalogPriceWithOptionsWithTaxes'] - $api_vehicle['sellingPriceWithTaxes']) / $api_vehicle['catalogPriceWithOptionsWithTaxes'];
        $api_vehicle['discount'] = ['percent' => max(0, $d * 100)];

        $api_vehicle['sellingPriceWithTakeoverWithTaxes'] = $offer['price']['final_price_takeover'];
        $d = ($api_vehicle['catalogPriceWithOptionsWithTaxes'] - $api_vehicle['sellingPriceWithTakeoverWithTaxes']) / $api_vehicle['catalogPriceWithOptionsWithTaxes'];
        $api_vehicle['discountWithTakeover'] = ['percent' => max(0, $d * 100)];
        $api_vehicle['indicativeLoan'] = [
            'monthlyInstallment' => $offer['price']['finance_loan_option']['monthly_price'],
            'months' => $offer['price']['finance_loan_option']['month_duration']
        ];

        $search['promotion_ids'] = []; // TODO: missing promotions
        $api_vehicle['promotions'] = [];

        $json_search = json_encode($search, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $validation_result = $json_schema_validator->validate(json_decode($json_search), $appsearch_offer_schema);
        if ($validation_result->hasError()) {
            print_r($json_schema_error_formatter->format($validation_result->error()));
//            echo json_encode($search, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
//            var_dump($offer);
            die;
        }
        if ($appsearch_output_dirty[$lang]) {
            file_put_contents( $appsearch_output_files[$lang], "\n," . $json_search, FILE_APPEND );
        }
        else {
            file_put_contents( $appsearch_output_files[$lang], "[" . $json_search );
            $appsearch_output_dirty[$lang] = true;
        }

        $json_api_vehicle = json_encode($api_vehicle, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $validation_result = $json_schema_validator->validate(json_decode($json_api_vehicle), $searchapi_vehicle_schema);
        if ($validation_result->hasError()) {
            print_r($json_schema_error_formatter->format($validation_result->error()));
//            echo json_encode($search, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
//            var_dump($offer);
            die;
        }
        if ($api_output_dirty[$lang]) {
            file_put_contents( $api_output_files[$lang], "\n," . $json_api_vehicle, FILE_APPEND );
        }
        else {
            file_put_contents( $api_output_files[$lang], "[" . $json_api_vehicle );
            $api_output_dirty[$lang] = true;
        }


    }
}

var_dump(array_keys($eqq));

foreach ($langs as $lang) {
    file_put_contents( $appsearch_output_files[$lang], "\n]", FILE_APPEND );
    file_put_contents( $api_output_files[$lang], "\n]", FILE_APPEND );
}
