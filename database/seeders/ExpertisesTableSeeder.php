<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExpertisesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('expertises')->delete();
        
        \DB::table('expertises')->insert(array (
            0 => 
            array (
                'id' => '01hdkrfhagze71pcb1hmg8tp53',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => '3D Geological Modeling',
                'name_fr' => 'Modélisation géologique 3D',
            ),
            1 => 
            array (
                'id' => '01hdkrfhakd0223zj4n4bfg5mf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Abiotic Stresses in Crop Plants',
                'name_fr' => 'Stress abiotiques chez les plantes cultivées',
            ),
            2 => 
            array (
                'id' => '01hdkrfham0yq0naqn3wxzmhsa',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aboriginal forestry',
                'name_fr' => 'Foresterie autochtone',
            ),
            3 => 
            array (
                'id' => '01hdkrfhan37jw8j9qxdna7p7k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Access',
                'name_fr' => 'Accès',
            ),
            4 => 
            array (
                'id' => '01hdkrfhaqzbvk0pp01cw2847v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Acid rain',
                'name_fr' => 'Pluies acides',
            ),
            5 => 
            array (
                'id' => '01hdkrfharfn9em8yd9md8aedw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Acidification and acid rain',
                'name_fr' => 'Acidification et pluies acides',
            ),
            6 => 
            array (
                'id' => '01hdkrfhatrgtx1bt59myh00c0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Acoustics, Ultrasound and Vibration',
                'name_fr' => 'Acoustique, ultrasons et vibrations',
            ),
            7 => 
            array (
                'id' => '01hdkrfhavjwygdpx7mt1tkzxq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Acquiring',
                'name_fr' => 'Acquisition',
            ),
            8 => 
            array (
                'id' => '01hdkrfhawztj2jq57k7cd574p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Acquisitions',
                'name_fr' => 'Acquisitions',
            ),
            9 => 
            array (
                'id' => '01hdkrfhaxrp7kdv98x3tbh1hn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Adaptation',
                'name_fr' => 'Adaptation',
            ),
            10 => 
            array (
                'id' => '01hdkrfhaynahdec2yp0mb60vp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Adaptation &amp; Impacts',
                'name_fr' => 'Adaptation et incidence',
            ),
            11 => 
            array (
                'id' => '01hdkrfhazb1pct8r850gqdsv1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Additive Manufacturing',
                'name_fr' => 'Fabrication additive',
            ),
            12 => 
            array (
                'id' => '01hdkrfhb03jaf4vjcgfyfy2h1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Additives',
                'name_fr' => 'Additifs',
            ),
            13 => 
            array (
                'id' => '01hdkrfhb1byx6dzq0va2hpjc9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Adhesive bonding',
                'name_fr' => 'Collage',
            ),
            14 => 
            array (
                'id' => '01hdkrfhb22w82m0164345ek3v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Advanced / Alternative Fuels',
                'name_fr' => 'Carburants de replacement / de pointe',
            ),
            15 => 
            array (
                'id' => '01hdkrfhb3awt02tx7metbbpv6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Advanced imaging',
                'name_fr' => 'Imagerie de pointe',
            ),
            16 => 
            array (
                'id' => '01hdkrfhb4axvvww0hmapenm4k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Advanced Manufacturing',
                'name_fr' => 'Fabrication de pointe',
            ),
            17 => 
            array (
                'id' => '01hdkrfhb5d3sh1y288sbqkcms',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Advanced photonics sensing',
                'name_fr' => 'Détection photonique avancée',
            ),
            18 => 
            array (
                'id' => '01hdkrfhb6r2g9xx6k20496j74',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aerial insectivore birds',
                'name_fr' => 'Oiseaux insectivores aériens',
            ),
            19 => 
            array (
                'id' => '01hdkrfhb7s005epptwy3a6078',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aerial Photography',
                'name_fr' => 'Photographie aérienne',
            ),
            20 => 
            array (
                'id' => '01hdkrfhb893270jfvf6ev7mk8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aerodynamics',
                'name_fr' => 'Aérodynamique',
            ),
            21 => 
            array (
                'id' => '01hdkrfhb893270jfvf6ev7mk9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aeronautical Engineering',
                'name_fr' => 'Génie aéronautique',
            ),
            22 => 
            array (
                'id' => '01hdkrfhb9pz9ndh5d6e007jdf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aerosol-cloud interactions',
                'name_fr' => 'Interactions entre les nuages et les aérosols',
            ),
            23 => 
            array (
                'id' => '01hdkrfhbag0ycwpr0a01w9nnw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aerosols',
                'name_fr' => 'Aérosols',
            ),
            24 => 
            array (
                'id' => '01hdkrfhbag0ycwpr0a01w9nnx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aerospace industry',
                'name_fr' => 'Industrie de l&#039;aérospatiale',
            ),
            25 => 
            array (
                'id' => '01hdkrfhbbb7jxvqszry7rn29p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agri-ecosystem',
                'name_fr' => 'Agroécosystèmes',
            ),
            26 => 
            array (
                'id' => '01hdkrfhbczwmbmw6zqcpzbvex',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agri-Food',
                'name_fr' => 'Secteur agroalimentaire',
            ),
            27 => 
            array (
                'id' => '01hdkrfhbczwmbmw6zqcpzbvey',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agri-food industry',
                'name_fr' => 'Industrie agro-alimentaire',
            ),
            28 => 
            array (
                'id' => '01hdkrfhbdrwk1w1cg3xz0pzjc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agricultural',
                'name_fr' => 'Agricole',
            ),
            29 => 
            array (
                'id' => '01hdkrfhbee1y8a9w62wd2zz5d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agricultural economics',
                'name_fr' => 'Agroéconomie',
            ),
            30 => 
            array (
                'id' => '01hdkrfhbee1y8a9w62wd2zz5e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agricultural impacts',
                'name_fr' => 'Incidence de l&#039;agriculture',
            ),
            31 => 
            array (
                'id' => '01hdkrfhbfyp26a0qx5gr6et8c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agricultural pests',
                'name_fr' => 'Organismes nuisibles de l’agriculture',
            ),
            32 => 
            array (
                'id' => '01hdkrfhbghfq19axrmxc3shvj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agricultural technology',
                'name_fr' => 'Technologie agricole',
            ),
            33 => 
            array (
                'id' => '01hdkrfhbhvf6asra5mhgy8q09',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agriculturally-significant photosynthetic bacteria',
                'name_fr' => 'Bactéries photosynthétiques d’importance agricole',
            ),
            34 => 
            array (
                'id' => '01hdkrfhbhvf6asra5mhgy8q0a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agriculture',
                'name_fr' => 'Agriculture',
            ),
            35 => 
            array (
                'id' => '01hdkrfhbj44wvhazbk49sxw77',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agriculture-environment interactions',
                'name_fr' => 'Interactions entre l’agriculture et l’environnement',
            ),
            36 => 
            array (
                'id' => '01hdkrfhbkge08bdnxf54hzz66',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agro-Ecosystem productivity and health',
                'name_fr' => 'Productivité des écosystèmes agricoles et santé',
            ),
            37 => 
            array (
                'id' => '01hdkrfhbms89bsebrmjb8yxkz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agro-micrometeorology',
                'name_fr' => 'Agromicrométéorologie',
            ),
            38 => 
            array (
                'id' => '01hdkrfhbn103w3h82j4ynhqew',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agroclimatology',
                'name_fr' => 'Agroclimatologie',
            ),
            39 => 
            array (
                'id' => '01hdkrfhbn103w3h82j4ynhqex',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agroforestry',
                'name_fr' => 'Agroforesterie',
            ),
            40 => 
            array (
                'id' => '01hdkrfhbpkdqyednbskqpn0a0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agrometeorology',
                'name_fr' => 'Agrométéorologie',
            ),
            41 => 
            array (
                'id' => '01hdkrfhbqmcmxyq4nze9berkz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Agronomy',
                'name_fr' => 'Agronomie',
            ),
            42 => 
            array (
                'id' => '01hdkrfhbqmcmxyq4nze9berm0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Air',
                'name_fr' => 'Air',
            ),
            43 => 
            array (
                'id' => '01hdkrfhbr9qtr6drxrat1gwcc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Air Emissions',
                'name_fr' => 'Émissions atmosphériques',
            ),
            44 => 
            array (
                'id' => '01hdkrfhbsnk29370p0xnsfgjg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Air Pollution &amp; Quality',
                'name_fr' => 'Pollution atmosphérique et qualité de l&#039;air',
            ),
            45 => 
            array (
                'id' => '01hdkrfhbsnk29370p0xnsfgjh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Air quality',
                'name_fr' => 'Qualité de l’air',
            ),
            46 => 
            array (
                'id' => '01hdkrfhbt65pg7s1brmtd0se3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Airborne measurements',
                'name_fr' => 'Mesures aériennes',
            ),
            47 => 
            array (
                'id' => '01hdkrfhbv3x17akxn06vb6p5q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aircraft',
                'name_fr' => 'Aviation',
            ),
            48 => 
            array (
                'id' => '01hdkrfhbv3x17akxn06vb6p5r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Alcohol',
                'name_fr' => 'Alcool',
            ),
            49 => 
            array (
                'id' => '01hdkrfhbw3fsvh9b7dd4h57g0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Alfalfa',
                'name_fr' => 'La luzerne',
            ),
            50 => 
            array (
                'id' => '01hdkrfhbw3fsvh9b7dd4h57g1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Algae',
                'name_fr' => 'Algues',
            ),
            51 => 
            array (
                'id' => '01hdkrfhbx13ka5e5y0qbjjtyx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Algal biotechnologies',
                'name_fr' => 'Biotechnologies algales',
            ),
            52 => 
            array (
                'id' => '01hdkrfhbytngf289m6dzhbbhw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Algal blooms',
                'name_fr' => 'Prolifération d&#039;algues',
            ),
            53 => 
            array (
                'id' => '01hdkrfhbytngf289m6dzhbbhx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Algal cultivation',
                'name_fr' => 'Culture des algues',
            ),
            54 => 
            array (
                'id' => '01hdkrfhbzsgd2f69egdcya2tw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Algal ecology',
                'name_fr' => 'Écologie des algues',
            ),
            55 => 
            array (
                'id' => '01hdkrfhc06e989m9qfk774as8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Algal genomics',
                'name_fr' => 'Génomique des algues',
            ),
            56 => 
            array (
                'id' => '01hdkrfhc06e989m9qfk774as9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Algal Technologies',
                'name_fr' => 'Technologies algales',
            ),
            57 => 
            array (
                'id' => '01hdkrfhc1a9882jxzmpmrym88',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Alien invasive species',
                'name_fr' => 'Espèces exotiques envahissantes',
            ),
            58 => 
            array (
                'id' => '01hdkrfhc1a9882jxzmpmrym89',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Alien Species',
                'name_fr' => 'Espèces exotiques',
            ),
            59 => 
            array (
                'id' => '01hdkrfhc2nmssaj20s378hhjx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Allergens',
                'name_fr' => 'Allèrgenes',
            ),
            60 => 
            array (
                'id' => '01hdkrfhc3desekywh4z5c8txw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Alpine birds',
                'name_fr' => 'Oiseaux alpins',
            ),
            61 => 
            array (
                'id' => '01hdkrfhc4jpc7gp157xbyxsar',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Alpine habitats',
                'name_fr' => 'Habitats alpins',
            ),
            62 => 
            array (
                'id' => '01hdkrfhc5t59arqqy3nzstwmn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Alternative crops and diversification',
                'name_fr' => 'Cultures de remplacement et diversification',
            ),
            63 => 
            array (
                'id' => '01hdkrfhc5t59arqqy3nzstwmp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Alternative sources',
                'name_fr' => 'Sources de remplacement',
            ),
            64 => 
            array (
                'id' => '01hdkrfhc600f6szfchjjdc2bq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aluminium',
                'name_fr' => 'Aluminium',
            ),
            65 => 
            array (
                'id' => '01hdkrfhc71yvh9ae8b9864yp9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Amphibians',
                'name_fr' => 'Amphibiens',
            ),
            66 => 
            array (
                'id' => '01hdkrfhc8tbqwe00wndeapd7x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Analytical chemistry',
                'name_fr' => 'Chimie analytique',
            ),
            67 => 
            array (
                'id' => '01hdkrfhc8tbqwe00wndeapd7y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Analytical Method Development',
                'name_fr' => 'Élaboration de méthodes analytiques',
            ),
            68 => 
            array (
                'id' => '01hdkrfhc96bpdymfve953wqmh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Analyzing',
                'name_fr' => 'Analyse',
            ),
            69 => 
            array (
                'id' => '01hdkrfhc96bpdymfve953wqmj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Animal',
                'name_fr' => 'Animale',
            ),
            70 => 
            array (
                'id' => '01hdkrfhcan5wq01csg125z10v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Animal cryobiology',
                'name_fr' => 'Cryobiologie animale',
            ),
            71 => 
            array (
                'id' => '01hdkrfhcbwabecmnsgd676x13',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Animal diseases',
                'name_fr' => 'Maladies animales',
            ),
            72 => 
            array (
                'id' => '01hdkrfhcbwabecmnsgd676x14',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Animal metabolism',
                'name_fr' => 'Métabolisme animal',
            ),
            73 => 
            array (
                'id' => '01hdkrfhccrj5cta07rdhx0trk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Animal Microbiology',
                'name_fr' => 'Microbiologie animale',
            ),
            74 => 
            array (
                'id' => '01hdkrfhcd52xnzfn51ftfhfyk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Animal Parasitology',
                'name_fr' => 'Parasitologie animale',
            ),
            75 => 
            array (
                'id' => '01hdkrfhcd52xnzfn51ftfhfym',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Animal welfare',
                'name_fr' => 'Bien-être des animaux',
            ),
            76 => 
            array (
                'id' => '01hdkrfhcee3t9dzarzq99n0e9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Animals',
                'name_fr' => 'Animal',
            ),
            77 => 
            array (
                'id' => '01hdkrfhcee3t9dzarzq99n0ea',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Anomaly Detection',
                'name_fr' => 'Détection d&#039;anomalies',
            ),
            78 => 
            array (
                'id' => '01hdkrfhcf6rk2z13p82665njv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Anti-microbial resistance of microbes in food production',
                'name_fr' => 'Résistance antimicrobienne des microbes dans la production alimentaire',
            ),
            79 => 
            array (
                'id' => '01hdkrfhcf6rk2z13p82665njw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Anti-nutrients',
                'name_fr' => 'Anti-nutriments',
            ),
            80 => 
            array (
                'id' => '01hdkrfhcg33pfrfhwfz5vn6s1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Antioxidant',
                'name_fr' => 'Agents antioxydants',
            ),
            81 => 
            array (
                'id' => '01hdkrfhchw3032rpxh9c6j3pn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Antioxidant chemistry',
                'name_fr' => 'Chimie des agents antioxydants',
            ),
            82 => 
            array (
                'id' => '01hdkrfhchw3032rpxh9c6j3pp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Apiculture',
                'name_fr' => 'Apiculture',
            ),
            83 => 
            array (
                'id' => '01hdkrfhcjf6r4rt2kemmecfp6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Appalachians',
                'name_fr' => 'Appalaches',
            ),
            84 => 
            array (
                'id' => '01hdkrfhcjf6r4rt2kemmecfp7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Applied',
                'name_fr' => 'Appliquée',
            ),
            85 => 
            array (
                'id' => '01hdkrfhckp24n3r8s9ndzgrqp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Applied Psychology',
                'name_fr' => 'Psychologie appliquée',
            ),
            86 => 
            array (
                'id' => '01hdkrfhcmwmnszs1t0hf7j21n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aquaculture',
                'name_fr' => 'Aquaculture',
            ),
            87 => 
            array (
                'id' => '01hdkrfhcns743ebcc2kvs5paw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aquatic Animal Health',
                'name_fr' => 'Santé de la faune aquatique',
            ),
            88 => 
            array (
                'id' => '01hdkrfhcns743ebcc2kvs5pax',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aquatic Animal Health incl. Veterinary',
                'name_fr' => 'Santé des animaux aquatiques, y compris vétérinaire',
            ),
            89 => 
            array (
                'id' => '01hdkrfhcp9wrv1z0jxqwdgw6g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aquatic Ecosystems',
                'name_fr' => 'Écosystèmes aquatiques',
            ),
            90 => 
            array (
                'id' => '01hdkrfhcqz2gfkpwxjc7pbedj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aquatic ecosystems science',
                'name_fr' => 'Science des écosystèmes aquatiques',
            ),
            91 => 
            array (
                'id' => '01hdkrfhcqz2gfkpwxjc7pbedk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aquatic habitat/ Aquatic Environmental Science',
                'name_fr' => 'Habitat aquatique/ Science de l’environnement aquatique',
            ),
            92 => 
            array (
                'id' => '01hdkrfhcrccs5jhcr144aahr6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aquatic invasive species',
                'name_fr' => 'Espèces aquatiques envahissantes',
            ),
            93 => 
            array (
                'id' => '01hdkrfhcrccs5jhcr144aahr7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aquatic organisms',
                'name_fr' => 'Organismes aquatiques',
            ),
            94 => 
            array (
                'id' => '01hdkrfhcs35r9g0h24b9dagsm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aquatic plant ecology',
                'name_fr' => 'Écologie des plantes aquatiques',
            ),
            95 => 
            array (
                'id' => '01hdkrfhcs35r9g0h24b9dagsn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aquatic Science',
                'name_fr' => 'Sciences aquatiques',
            ),
            96 => 
            array (
                'id' => '01hdkrfhctwaccrym1tggy6t13',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aquifer',
                'name_fr' => 'Aquifère',
            ),
            97 => 
            array (
                'id' => '01hdkrfhctwaccrym1tggy6t14',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Aquifers Assessment and Evaluation',
                'name_fr' => 'Charactérisation &amp; évaluation des aquifères',
            ),
            98 => 
            array (
                'id' => '01hdkrfhcvq1kvz49ypmhaawrk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Arbuscular mycorrhizal fungi',
                'name_fr' => 'Des champignons mycorhiziens arbusculaires',
            ),
            99 => 
            array (
                'id' => '01hdkrfhcvq1kvz49ypmhaawrm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Arctic',
                'name_fr' => 'Arctique',
            ),
            100 => 
            array (
                'id' => '01hdkrfhcw07cc1vmv6vkgj16r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Arctic &amp; Northern',
                'name_fr' => 'Arctique et Nord',
            ),
            101 => 
            array (
                'id' => '01hdkrfhcw07cc1vmv6vkgj16s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Arctic animal ecology',
                'name_fr' => 'Écologie animale de l&#039;Arctique',
            ),
            102 => 
            array (
                'id' => '01hdkrfhcxmw2mjdhk6mej40xe',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Arctic environment',
                'name_fr' => 'Milieu arctique',
            ),
            103 => 
            array (
                'id' => '01hdkrfhcxmw2mjdhk6mej40xf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Arctic Ocean',
                'name_fr' => 'Océan Arctique',
            ),
            104 => 
            array (
                'id' => '01hdkrfhcy0xcxwc6ncs3pze1t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Armour',
                'name_fr' => 'Blindage',
            ),
            105 => 
            array (
                'id' => '01hdkrfhcy0xcxwc6ncs3pze1v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Arthropod',
                'name_fr' => 'Arthropodes',
            ),
            106 => 
            array (
                'id' => '01hdkrfhczajbbmz1q5kh72s5z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Artificial insemination',
                'name_fr' => 'Insémination artificielle',
            ),
            107 => 
            array (
                'id' => '01hdkrfhd06e3n4fcbkdtxwp7s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Artificial Intelligence',
                'name_fr' => 'Intelligence artificielle',
            ),
            108 => 
            array (
                'id' => '01hdkrfhd06e3n4fcbkdtxwp7t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Assembling',
                'name_fr' => 'Assemblage',
            ),
            109 => 
            array (
                'id' => '01hdkrfhd1vhbcnvr3zz9y125a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Assessment',
                'name_fr' => 'Évaluation',
            ),
            110 => 
            array (
                'id' => '01hdkrfhd1vhbcnvr3zz9y125b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Assessment and Quality System',
                'name_fr' => 'Système d&#039;évaluation et Système qualité',
            ),
            111 => 
            array (
                'id' => '01hdkrfhd26mtsb2j949a27kwm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Asset Management Services',
                'name_fr' => 'Services de gestion des biens',
            ),
            112 => 
            array (
                'id' => '01hdkrfhd26mtsb2j949a27kwn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Assisted Human Reproduction',
                'name_fr' => 'Procréation assistée au Canada',
            ),
            113 => 
            array (
                'id' => '01hdkrfhd37x96kq7rzrz98www',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Assistive Technologies',
                'name_fr' => 'Technologie d&#039;aide',
            ),
            114 => 
            array (
                'id' => '01hdkrfhd37x96kq7rzrz98wwx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Astronaut training',
                'name_fr' => 'Entraînement des astronautes',
            ),
            115 => 
            array (
                'id' => '01hdkrfhd4w8sswyq378e0k8q4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Astronomy',
                'name_fr' => 'Astronomie',
            ),
            116 => 
            array (
                'id' => '01hdkrfhd5zt0nc0mv997qyq9j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Astrophysics',
                'name_fr' => 'Astrophysique',
            ),
            117 => 
            array (
                'id' => '01hdkrfhd5zt0nc0mv997qyq9k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Atlantic Canada',
                'name_fr' => 'Canada Atlantique',
            ),
            118 => 
            array (
                'id' => '01hdkrfhd6rmbbyjh95e992nqk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Atlantic Ocean',
                'name_fr' => 'Océan Atlantique',
            ),
            119 => 
            array (
                'id' => '01hdkrfhd7xr1vbzbg05h1gvb7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Atmospheric chemistry',
                'name_fr' => 'Chimie atmosphérique',
            ),
            120 => 
            array (
                'id' => '01hdkrfhd7xr1vbzbg05h1gvb8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Atmospheric circulation',
                'name_fr' => 'Circulation atmosphérique',
            ),
            121 => 
            array (
                'id' => '01hdkrfhd81593qhpa5kzfyd72',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Atmospheric environment',
                'name_fr' => 'Environnement atmosphérique',
            ),
            122 => 
            array (
                'id' => '01hdkrfhd81593qhpa5kzfyd73',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Atmospheric Modelling',
                'name_fr' => 'Modélisation de l&#039;atmosphère',
            ),
            123 => 
            array (
                'id' => '01hdkrfhd81593qhpa5kzfyd74',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Atmospheric models',
                'name_fr' => 'Modèles atmosphériques',
            ),
            124 => 
            array (
                'id' => '01hdkrfhd9xk9mdcd2t8szzv49',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Atmospheric science',
                'name_fr' => 'Science de l’atmosphère',
            ),
            125 => 
            array (
                'id' => '01hdkrfhd9xk9mdcd2t8szzv4a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Atmospheric transport',
                'name_fr' => 'Transport atmosphérique',
            ),
            126 => 
            array (
                'id' => '01hdkrfhdavnvv9m4yfhnexnv3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Atomics, molecular and optical physics',
                'name_fr' => 'Physique atomique, moléculaire et optique',
            ),
            127 => 
            array (
                'id' => '01hdkrfhdavnvv9m4yfhnexnv4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Attributes',
                'name_fr' => 'Caractéristiques',
            ),
            128 => 
            array (
                'id' => '01hdkrfhdbab92v5kwazpckgxv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Audiovisual equipment',
                'name_fr' => 'Équipement audiovisuel',
            ),
            129 => 
            array (
                'id' => '01hdkrfhdbab92v5kwazpckgxw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Automated Decision Making Process',
                'name_fr' => 'Prise de décision automatisée',
            ),
            130 => 
            array (
                'id' => '01hdkrfhdc051v9y3jxhzpws2p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Automation',
                'name_fr' => 'Automatisation',
            ),
            131 => 
            array (
                'id' => '01hdkrfhdc051v9y3jxhzpws2q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Automobile industry',
                'name_fr' => 'Industrie de l&#039;automobile',
            ),
            132 => 
            array (
                'id' => '01hdkrfhddj0f8vwpfbvec08dh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Automotive',
                'name_fr' => 'Automobile',
            ),
            133 => 
            array (
                'id' => '01hdkrfhddj0f8vwpfbvec08dj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Automotive Engineering',
                'name_fr' => 'Ingénierie automobile',
            ),
            134 => 
            array (
                'id' => '01hdkrfhdesrmtdx4pb3x72eaq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Autonomous cars',
                'name_fr' => 'Véhicules autonomes',
            ),
            135 => 
            array (
                'id' => '01hdkrfhdesrmtdx4pb3x72ear',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Avian (bird) nutrition',
                'name_fr' => 'Nutrition des oiseaux',
            ),
            136 => 
            array (
                'id' => '01hdkrfhdf0q268xf19905sg5d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bacteria',
                'name_fr' => 'Bactéries',
            ),
            137 => 
            array (
                'id' => '01hdkrfhdf0q268xf19905sg5e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bacterial ecology',
                'name_fr' => 'Écologie bactérienne',
            ),
            138 => 
            array (
                'id' => '01hdkrfhdgzrn4gz0e56cm7tqx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bacterial genetics',
                'name_fr' => 'Génétique bactérienne',
            ),
            139 => 
            array (
                'id' => '01hdkrfhdgzrn4gz0e56cm7tqy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bacterial genomics',
                'name_fr' => 'Génomique bactérienne',
            ),
            140 => 
            array (
                'id' => '01hdkrfhdhjkk2pd9j1ne6q3jf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bacterial pathogens',
                'name_fr' => 'Agents pathogènes d&#039;origine bactérienne',
            ),
            141 => 
            array (
                'id' => '01hdkrfhdhjkk2pd9j1ne6q3jg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bacterial phylogenetics and systematics',
                'name_fr' => 'Systématique et phylogénétique bactériennes',
            ),
            142 => 
            array (
                'id' => '01hdkrfhdj397eckxswq4ecpty',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bacterial population ecology and genetics',
                'name_fr' => 'Écologie et génétique des populations bactériennes',
            ),
            143 => 
            array (
                'id' => '01hdkrfhdj397eckxswq4ecptz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bacteriology',
                'name_fr' => 'Bactériologie',
            ),
            144 => 
            array (
                'id' => '01hdkrfhdkbcvghnvcbr8pna3c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bacteriophages',
            'name_fr' => 'Bactériophages (virus bactériens) ',
            ),
            145 => 
            array (
                'id' => '01hdkrfhdkbcvghnvcbr8pna3d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Baffin Bay',
                'name_fr' => 'Baie de Baffin',
            ),
            146 => 
            array (
                'id' => '01hdkrfhdmz0p1w24m1hjt69q6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Baffin Shelf',
                'name_fr' => 'Plate-forme de Baffin',
            ),
            147 => 
            array (
                'id' => '01hdkrfhdmz0p1w24m1hjt69q7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Barents and Kara Seas',
                'name_fr' => 'Mers de Barents et Kara',
            ),
            148 => 
            array (
                'id' => '01hdkrfhdmz0p1w24m1hjt69q8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Barley',
                'name_fr' => 'L&#039;orge',
            ),
            149 => 
            array (
                'id' => '01hdkrfhdnxz588e2wqnbqwncf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Base Metals',
                'name_fr' => 'Métaux de base',
            ),
            150 => 
            array (
                'id' => '01hdkrfhdpb2zpp1c3a5h5n43c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Basin Analysis',
                'name_fr' => 'Analyse de bassin',
            ),
            151 => 
            array (
                'id' => '01hdkrfhdpb2zpp1c3a5h5n43d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Basins - Offshore or Frontier',
                'name_fr' => 'Bassins extracôtiers ou limitrophes',
            ),
            152 => 
            array (
                'id' => '01hdkrfhdqykmkhbeebgd2wt4j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Batteries',
                'name_fr' => 'Batteries',
            ),
            153 => 
            array (
                'id' => '01hdkrfhdqykmkhbeebgd2wt4k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bay of Fundy',
                'name_fr' => 'Baie de Fundy',
            ),
            154 => 
            array (
                'id' => '01hdkrfhdrv2c7b19kpwm5vc9c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bayesian Statistics',
                'name_fr' => 'Statistiques bayésiennes',
            ),
            155 => 
            array (
                'id' => '01hdkrfhdrv2c7b19kpwm5vc9d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'BC Fjords/Coastal Waterways',
                'name_fr' => 'Les eaux côtières/Fjord CB',
            ),
            156 => 
            array (
                'id' => '01hdkrfhdsdpva415v1ffm4nf7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bean',
                'name_fr' => 'Haricots',
            ),
            157 => 
            array (
                'id' => '01hdkrfhdt3bkdkxws8hr180cb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Beaufort Sea',
                'name_fr' => 'Mer de Beaufort',
            ),
            158 => 
            array (
                'id' => '01hdkrfhdt3bkdkxws8hr180cc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bee',
                'name_fr' => 'Des abeilles',
            ),
            159 => 
            array (
                'id' => '01hdkrfhdv9pa145c9ecnhnpa0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Beef',
                'name_fr' => 'Bœuf',
            ),
            160 => 
            array (
                'id' => '01hdkrfhdv9pa145c9ecnhnpa1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Beneficial bacteria',
                'name_fr' => 'Bactéries bénéfiques',
            ),
            161 => 
            array (
                'id' => '01hdkrfhdv9pa145c9ecnhnpa2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Beneficial Bioinoculants (Bacteria)',
            'name_fr' => 'Bio-inoculants bénéfiques (bactéries)',
            ),
            162 => 
            array (
                'id' => '01hdkrfhdwwqc4300ae913b8w8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Beneficial management practices',
                'name_fr' => 'Pratiques de gestion bénéfique',
            ),
            163 => 
            array (
                'id' => '01hdkrfhdwwqc4300ae913b8w9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Benthic',
                'name_fr' => 'Benthique',
            ),
            164 => 
            array (
                'id' => '01hdkrfhdxqk9gzh5fdkt0ny6p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Benthic macroinvertebrates',
                'name_fr' => 'Macro-invertébrés benthiques',
            ),
            165 => 
            array (
                'id' => '01hdkrfhdxqk9gzh5fdkt0ny6q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Benthic Species',
                'name_fr' => 'Espéces benthiques',
            ),
            166 => 
            array (
                'id' => '01hdkrfhdyvcqt8n008b8e6d1t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Big Data',
                'name_fr' => 'Mégadonnées',
            ),
            167 => 
            array (
                'id' => '01hdkrfhdyvcqt8n008b8e6d1v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bio-based products',
                'name_fr' => 'Produits biologiques',
            ),
            168 => 
            array (
                'id' => '01hdkrfhdzftzb6s5w87v854cd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bioaccumulation/biomagnification',
                'name_fr' => 'Bioaccumulation/bioamplification',
            ),
            169 => 
            array (
                'id' => '01hdkrfhdzftzb6s5w87v854ce',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bioactive characterization',
                'name_fr' => 'Caractérisation des substances bioactives',
            ),
            170 => 
            array (
                'id' => '01hdkrfhdzftzb6s5w87v854cf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bioactives',
                'name_fr' => 'Composés bioactifs',
            ),
            171 => 
            array (
                'id' => '01hdkrfhe05pmtz2s84yd7pq3z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bioassay',
                'name_fr' => 'Épreuve biologique',
            ),
            172 => 
            array (
                'id' => '01hdkrfhe05pmtz2s84yd7pq40',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bioassays, biomarkers',
                'name_fr' => 'Essais biologiques, biomarqueurs',
            ),
            173 => 
            array (
                'id' => '01hdkrfhe17tbp3yb4xra0jh17',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biochemistry',
                'name_fr' => 'Biochimie',
            ),
            174 => 
            array (
                'id' => '01hdkrfhe17tbp3yb4xra0jh18',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bioclimatic',
                'name_fr' => 'Bioclimatique',
            ),
            175 => 
            array (
                'id' => '01hdkrfhe20v1c3nv7kr6nhqd3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bioclimatology',
                'name_fr' => 'Bioclimatologie',
            ),
            176 => 
            array (
                'id' => '01hdkrfhe20v1c3nv7kr6nhqd4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biocontrol',
                'name_fr' => 'Lutte biologique',
            ),
            177 => 
            array (
                'id' => '01hdkrfhe3xhp4tt07mqbe3byw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biodiversity',
                'name_fr' => 'Biodiversité',
            ),
            178 => 
            array (
                'id' => '01hdkrfhe3xhp4tt07mqbe3byx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biodiversity science',
                'name_fr' => 'Science de la biodiversité',
            ),
            179 => 
            array (
                'id' => '01hdkrfhe49kkppkqbasx5g348',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bioeconomy',
                'name_fr' => 'Bioéconomie',
            ),
            180 => 
            array (
                'id' => '01hdkrfhe49kkppkqbasx5g349',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bioenergy',
                'name_fr' => 'Bioénergie',
            ),
            181 => 
            array (
                'id' => '01hdkrfhe5ptjsx0gcf9hg7pze',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biofuel',
                'name_fr' => 'Biocombustible',
            ),
            182 => 
            array (
                'id' => '01hdkrfhe5ptjsx0gcf9hg7pzf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biofuels',
                'name_fr' => 'Biocarburants',
            ),
            183 => 
            array (
                'id' => '01hdkrfhe67fc3e96hqhhg7fmj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biogeochemistry',
                'name_fr' => 'Biogéochimie',
            ),
            184 => 
            array (
                'id' => '01hdkrfhe72q4se1vpjxsn9jzc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bioindicator species',
                'name_fr' => 'Espèces indicatrices',
            ),
            185 => 
            array (
                'id' => '01hdkrfhe72q4se1vpjxsn9jzd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biological control of agricultural pests',
                'name_fr' => 'Lutte biologique aux organismes nuisibles de l’agriculture',
            ),
            186 => 
            array (
                'id' => '01hdkrfhe8py6yncj73nfn4kw6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biological nitrogen fixation',
                'name_fr' => 'Fixation biologique de l’azote',
            ),
            187 => 
            array (
                'id' => '01hdkrfhe8py6yncj73nfn4kw7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biological oceanography',
                'name_fr' => 'Océanographie biologique',
            ),
            188 => 
            array (
                'id' => '01hdkrfhe9468makt8tt1edabx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biological testing',
                'name_fr' => 'Essai biologique',
            ),
            189 => 
            array (
                'id' => '01hdkrfhe9468makt8tt1edaby',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biological/genomics',
                'name_fr' => 'Biologie/génomique',
            ),
            190 => 
            array (
                'id' => '01hdkrfhea6mxs6v4wc68hjq5p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biologics and Radiopharmaceuticals',
                'name_fr' => 'Produits biologiques et radiopharmaceutiques',
            ),
            191 => 
            array (
                'id' => '01hdkrfhea6mxs6v4wc68hjq5q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biology',
                'name_fr' => 'Biologie',
            ),
            192 => 
            array (
                'id' => '01hdkrfhea6mxs6v4wc68hjq5r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biomass',
                'name_fr' => 'Biomasse',
            ),
            193 => 
            array (
                'id' => '01hdkrfhebxwzk4cv9nbf3ywq7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biomass characterization',
                'name_fr' => 'Caractérisation de la biomasse',
            ),
            194 => 
            array (
                'id' => '01hdkrfhebxwzk4cv9nbf3ywq8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biomass co-firing',
                'name_fr' => 'Cocuisson de la biomass',
            ),
            195 => 
            array (
                'id' => '01hdkrfhec80d8e2e91bkp3j54',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biomaterials',
                'name_fr' => 'Biomatériaux',
            ),
            196 => 
            array (
                'id' => '01hdkrfhec80d8e2e91bkp3j55',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biomathematics',
                'name_fr' => 'Biomathématiques',
            ),
            197 => 
            array (
                'id' => '01hdkrfhedyex10ttwp9zey9z6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biomechanical Models',
                'name_fr' => 'Biomechanical Models',
            ),
            198 => 
            array (
                'id' => '01hdkrfhedyex10ttwp9zey9z7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biomedical Data Science',
                'name_fr' => 'Science des données biomédicales',
            ),
            199 => 
            array (
                'id' => '01hdkrfheepgqd5d2ensvt156r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biomedical Experiments',
                'name_fr' => 'Expérimentation biomédicale',
            ),
            200 => 
            array (
                'id' => '01hdkrfheepgqd5d2ensvt156s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biomedical Mechatronics',
                'name_fr' => 'Mécatronique biomédicale',
            ),
            201 => 
            array (
                'id' => '01hdkrfhefdg45pwd1w8t7cvcb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biomedical Software',
                'name_fr' => 'Logiciels biomédicaux',
            ),
            202 => 
            array (
                'id' => '01hdkrfhefdg45pwd1w8t7cvcc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biometrics for National Security',
                'name_fr' => 'Biométrie pour la sécurité nationale',
            ),
            203 => 
            array (
                'id' => '01hdkrfhefdg45pwd1w8t7cvcd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biometry',
                'name_fr' => 'Biométrie',
            ),
            204 => 
            array (
                'id' => '01hdkrfhegxs1hxmja1r58zr2j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biomonitoring',
                'name_fr' => 'Biosurveillance',
            ),
            205 => 
            array (
                'id' => '01hdkrfhegxs1hxmja1r58zr2k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biopesticides',
                'name_fr' => 'Biopesticides',
            ),
            206 => 
            array (
                'id' => '01hdkrfhehmdvc09bed50v1r1w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biopolymers',
                'name_fr' => 'Biopolymères',
            ),
            207 => 
            array (
                'id' => '01hdkrfhehmdvc09bed50v1r1x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bioprocessing',
                'name_fr' => 'Biotransformation',
            ),
            208 => 
            array (
                'id' => '01hdkrfhejp9h2phmmngd0qfwn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bioproducts',
                'name_fr' => 'Bioproduits',
            ),
            209 => 
            array (
                'id' => '01hdkrfhejp9h2phmmngd0qfwp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biorefinery',
                'name_fr' => 'Bioraffinerie',
            ),
            210 => 
            array (
                'id' => '01hdkrfhekqfzfvtvktzrc61vv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bioresources',
                'name_fr' => 'Les bioressources',
            ),
            211 => 
            array (
                'id' => '01hdkrfhekqfzfvtvktzrc61vw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biosimilars',
                'name_fr' => 'Produits biosimilaires',
            ),
            212 => 
            array (
                'id' => '01hdkrfhem82xa1jswapy05c9q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biostatistics',
                'name_fr' => 'Biostatistiques',
            ),
            213 => 
            array (
                'id' => '01hdkrfhem82xa1jswapy05c9r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biosystems',
                'name_fr' => 'Biosystèmes',
            ),
            214 => 
            array (
                'id' => '01hdkrfhen1cvp7f0xhrpvfbhg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biotechnology',
                'name_fr' => 'Biotechnologie',
            ),
            215 => 
            array (
                'id' => '01hdkrfhen1cvp7f0xhrpvfbhh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biotechnology/genomics',
                'name_fr' => 'Biotechnologie/génomique',
            ),
            216 => 
            array (
                'id' => '01hdkrfhen1cvp7f0xhrpvfbhj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biotherapeutics',
                'name_fr' => 'Produits biothérapeutiques',
            ),
            217 => 
            array (
                'id' => '01hdkrfhep9vhq8scr0rqy6xtp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Biotic Stresses in Crop Plants',
                'name_fr' => 'Stress biotiques chez les plantes cultivées',
            ),
            218 => 
            array (
                'id' => '01hdkrfheqs2s1x8kqrktzngzj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bird populations',
                'name_fr' => 'Population d&#039;oiseaux',
            ),
            219 => 
            array (
                'id' => '01hdkrfheqs2s1x8kqrktzngzk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Birds',
                'name_fr' => 'Oiseaux',
            ),
            220 => 
            array (
                'id' => '01hdkrfherj9fedvcnge8hsdjz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Bitumen',
                'name_fr' => 'Bitume',
            ),
            221 => 
            array (
                'id' => '01hdkrfherj9fedvcnge8hsdk0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Blast Effects',
                'name_fr' => 'Effets des explosifs',
            ),
            222 => 
            array (
                'id' => '01hdkrfhesaah2qx7chsn4ez2r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Blockchain',
                'name_fr' => 'Chaîne de blocs',
            ),
            223 => 
            array (
                'id' => '01hdkrfhesaah2qx7chsn4ez2s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Blue-green algae',
                'name_fr' => 'Algues bleues',
            ),
            224 => 
            array (
                'id' => '01hdkrfhetrn44s7zfpqs8t82b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Border and Transportation Security',
                'name_fr' => 'Sécurité des frontières et du transport',
            ),
            225 => 
            array (
                'id' => '01hdkrfhetrn44s7zfpqs8t82c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Boreal Forest',
                'name_fr' => 'Forêt boréale',
            ),
            226 => 
            array (
                'id' => '01hdkrfhev0qe6b3rxx7ynnnv5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Botany',
                'name_fr' => 'Botanique',
            ),
            227 => 
            array (
                'id' => '01hdkrfhev0qe6b3rxx7ynnnv6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Braconid (parasitic) wasp',
            'name_fr' => 'Des braconidés (parasitoïdes)',
            ),
            228 => 
            array (
                'id' => '01hdkrfhew0weg095s0peyyc9e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Brain-computer interface',
                'name_fr' => 'Interface cervaux-machine',
            ),
            229 => 
            array (
                'id' => '01hdkrfhew0weg095s0peyyc9f',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Brassicaceae',
                'name_fr' => 'Des brassicacées',
            ),
            230 => 
            array (
                'id' => '01hdkrfhew0weg095s0peyyc9g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Brassicaceae biosafety',
                'name_fr' => 'Biosécurité des brassicacées',
            ),
            231 => 
            array (
                'id' => '01hdkrfhexc60dh3s9jet7w30p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Breeding',
                'name_fr' => 'Sélection',
            ),
            232 => 
            array (
                'id' => '01hdkrfhexc60dh3s9jet7w30q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Broadcasting',
                'name_fr' => 'Radiodiffusion',
            ),
            233 => 
            array (
                'id' => '01hdkrfheypqtcjh9sfahkbq1t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Broadcasting industry',
                'name_fr' => 'Industrie de la radiodiffusion',
            ),
            234 => 
            array (
                'id' => '01hdkrfheypqtcjh9sfahkbq1v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Brominated flame retardants (BFRs)',
            'name_fr' => 'Ignifugeants bromés (IB)',
            ),
            235 => 
            array (
                'id' => '01hdkrfhez0gzp0jzfwjthjdpp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Building codes, regulations and standards',
                'name_fr' => 'Codes, réglementation et normes du bâtiment',
            ),
            236 => 
            array (
                'id' => '01hdkrfhez0gzp0jzfwjthjdpq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Building materials',
                'name_fr' => 'Matériaux de construction',
            ),
            237 => 
            array (
                'id' => '01hdkrfhf0sb220jv0zm1w13x1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Building occupant safety and security',
                'name_fr' => 'Sécurité des occupants des bâtiments',
            ),
            238 => 
            array (
                'id' => '01hdkrfhf0sb220jv0zm1w13x2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Business understanding',
                'name_fr' => 'Compréhension du monde des affaires',
            ),
            239 => 
            array (
                'id' => '01hdkrfhf17336pjekvhndrnv3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cable television',
                'name_fr' => 'Câblodistribution',
            ),
            240 => 
            array (
                'id' => '01hdkrfhf17336pjekvhndrnv4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Calibration',
                'name_fr' => 'Étalonnage',
            ),
            241 => 
            array (
                'id' => '01hdkrfhf254ff72gj1pzp9np7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Calibration/Validation',
                'name_fr' => 'Calibration/Validation',
            ),
            242 => 
            array (
                'id' => '01hdkrfhf254ff72gj1pzp9np8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Canada Health Act Administration',
                'name_fr' => 'Administration de le Loi canadienne sur la santé',
            ),
            243 => 
            array (
                'id' => '01hdkrfhf254ff72gj1pzp9np9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Canada Wildlife Act',
                'name_fr' => 'Loi sur les espèces sauvages au Canada',
            ),
            244 => 
            array (
                'id' => '01hdkrfhf34saybxscyn4x09fb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Canada&#039;s Extended Continental Shelf',
                'name_fr' => 'Plateau continental étendu du Canada',
            ),
            245 => 
            array (
                'id' => '01hdkrfhf34saybxscyn4x09fc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Canada-wide Strategy for the Management of Municipal Wastewater Effluent',
                'name_fr' => 'Stratégie pancanadienne sur la gestion des effluents d&#039;eaux usées municipales',
            ),
            246 => 
            array (
                'id' => '01hdkrfhf4ygk9wf2rr5n17k3h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Canadian crop and crop relative',
                'name_fr' => 'Des cultures canadiennes et apparentées',
            ),
            247 => 
            array (
                'id' => '01hdkrfhf4ygk9wf2rr5n17k3j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Canadian Environmental Assessment Act',
                'name_fr' => 'Loi canadienne sur l&#039;évaluation environnementale',
            ),
            248 => 
            array (
                'id' => '01hdkrfhf5j8071ye86kkrbtcn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Canadian Environmental Protection Act (1999)',
            'name_fr' => 'Loi canadienne sur la protection de l&#039;environnement (1999)',
            ),
            249 => 
            array (
                'id' => '01hdkrfhf5j8071ye86kkrbtcp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Canadian Health System',
                'name_fr' => 'Système de santé au Canada',
            ),
            250 => 
            array (
                'id' => '01hdkrfhf632q9yb3gh3ms9k7a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cancer',
                'name_fr' => 'Cancer',
            ),
            251 => 
            array (
                'id' => '01hdkrfhf632q9yb3gh3ms9k7b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cancer Risk Assessment',
                'name_fr' => 'Évaluation des risques de cancer',
            ),
            252 => 
            array (
                'id' => '01hdkrfhf7htaq86yjr501xwcr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Canola',
                'name_fr' => 'Canola',
            ),
            253 => 
            array (
                'id' => '01hdkrfhf8nf722e7eenmx3h5s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Carbohydrate',
                'name_fr' => 'Des glucides',
            ),
            254 => 
            array (
                'id' => '01hdkrfhf8nf722e7eenmx3h5t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Carbon budget',
                'name_fr' => 'Bilan du carbone',
            ),
            255 => 
            array (
                'id' => '01hdkrfhf8nf722e7eenmx3h5v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Carbon capture, utilization &amp; storage',
                'name_fr' => 'Stockage, utilisation &amp; séquestration du carbonne',
            ),
            256 => 
            array (
                'id' => '01hdkrfhf9p19cf00dhr0220b8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Carbon sequestering',
                'name_fr' => 'Séquestration de carbone',
            ),
            257 => 
            array (
                'id' => '01hdkrfhf9p19cf00dhr0220b9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Carcass grading',
                'name_fr' => 'Classification des carcasses',
            ),
            258 => 
            array (
                'id' => '01hdkrfhfar146mv7wn23ftwyk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Caribou',
                'name_fr' => 'Caribou',
            ),
            259 => 
            array (
                'id' => '01hdkrfhfar146mv7wn23ftwym',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cartography',
                'name_fr' => 'Cartographie',
            ),
            260 => 
            array (
                'id' => '01hdkrfhfb2yytregyxnw55zz3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Casting',
                'name_fr' => 'Moulage',
            ),
            261 => 
            array (
                'id' => '01hdkrfhfb2yytregyxnw55zz4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Catalyst',
                'name_fr' => 'Catalysateur',
            ),
            262 => 
            array (
                'id' => '01hdkrfhfck3gqpprjmjshycp3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cavity-nesting species',
                'name_fr' => 'Oiseaux cavernicoles',
            ),
            263 => 
            array (
                'id' => '01hdkrfhfck3gqpprjmjshycp4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cellular interactions',
                'name_fr' => 'Interactions cellulaires',
            ),
            264 => 
            array (
                'id' => '01hdkrfhfddjd833k5yte01d7r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cellular telephones',
                'name_fr' => 'Téléphone cellulaire',
            ),
            265 => 
            array (
                'id' => '01hdkrfhfddjd833k5yte01d7s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Central Canada',
                'name_fr' => 'Canada central',
            ),
            266 => 
            array (
                'id' => '01hdkrfhfddjd833k5yte01d7t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cereal',
                'name_fr' => 'Céréales',
            ),
            267 => 
            array (
                'id' => '01hdkrfhfe1bqk5w5370gr0dq5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cereal Chemistry',
                'name_fr' => 'Chimie céréalière',
            ),
            268 => 
            array (
                'id' => '01hdkrfhfe1bqk5w5370gr0dq6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cereal molecular',
                'name_fr' => 'Moléculaire des céréales',
            ),
            269 => 
            array (
                'id' => '01hdkrfhffn2kfnr5dhq4kcs1b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cereal stem rust',
                'name_fr' => 'Rouille de la tige des céréales',
            ),
            270 => 
            array (
                'id' => '01hdkrfhffn2kfnr5dhq4kcs1c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cereals',
                'name_fr' => 'Les céréales',
            ),
            271 => 
            array (
                'id' => '01hdkrfhfgz9rat6ddrdmsr7x6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cereals and pulses',
                'name_fr' => 'Céréales et grain',
            ),
            272 => 
            array (
                'id' => '01hdkrfhfgz9rat6ddrdmsr7x7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cereals quality',
                'name_fr' => 'Qualité des céréales',
            ),
            273 => 
            array (
                'id' => '01hdkrfhfhfa6gm3gd1ckf16yr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Characterization',
                'name_fr' => 'Caractérisation',
            ),
            274 => 
            array (
                'id' => '01hdkrfhfhfa6gm3gd1ckf16ys',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Chargers',
                'name_fr' => 'Chargeurs de batteries',
            ),
            275 => 
            array (
                'id' => '01hdkrfhfjd10rswsnjykqe057',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cheese technology',
                'name_fr' => 'Technologie fromagère',
            ),
            276 => 
            array (
                'id' => '01hdkrfhfjd10rswsnjykqe058',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Chemical',
                'name_fr' => 'Chimique',
            ),
            277 => 
            array (
                'id' => '01hdkrfhfjd10rswsnjykqe059',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Chemical analysis',
                'name_fr' => 'Analyses chimiques',
            ),
            278 => 
            array (
                'id' => '01hdkrfhfkzmqx29bq9wjk0bbt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Chemical and biological analysis',
                'name_fr' => 'Analyse chimique et biologique',
            ),
            279 => 
            array (
                'id' => '01hdkrfhfkzmqx29bq9wjk0bbv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Chemical ecology',
                'name_fr' => 'Écologie chimique',
            ),
            280 => 
            array (
                'id' => '01hdkrfhfmckmm9jvb9gq1tx95',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Chemical industry',
                'name_fr' => 'Industrie chimique',
            ),
            281 => 
            array (
                'id' => '01hdkrfhfmckmm9jvb9gq1tx96',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Chemical Management',
                'name_fr' => 'Gestion des produits chimiques',
            ),
            282 => 
            array (
                'id' => '01hdkrfhfns77sv372h7nzr5m2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Chemical Oceanography',
                'name_fr' => 'Océanographie chimique',
            ),
            283 => 
            array (
                'id' => '01hdkrfhfns77sv372h7nzr5m3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Chemicals Management Plan',
                'name_fr' => 'Plan de gestion des produits chimiques',
            ),
            284 => 
            array (
                'id' => '01hdkrfhfpp381c7a306db8c5v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cheminformatics',
                'name_fr' => 'Chimio-informatique',
            ),
            285 => 
            array (
                'id' => '01hdkrfhfpp381c7a306db8c5w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Chemistry',
                'name_fr' => 'Chimie',
            ),
            286 => 
            array (
                'id' => '01hdkrfhfqqv3hr8yggngvhx5d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Chemistry - inorganic',
                'name_fr' => 'Chimie - inorganique',
            ),
            287 => 
            array (
                'id' => '01hdkrfhfqqv3hr8yggngvhx5e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Chemistry and Biology',
                'name_fr' => 'Chimie et biologie',
            ),
            288 => 
            array (
                'id' => '01hdkrfhfr7tr1h3js980fm3dg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Child health',
                'name_fr' => 'Santé infantile',
            ),
            289 => 
            array (
                'id' => '01hdkrfhfsqgvdf9xn6j73k5fr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Chronic diseases',
                'name_fr' => 'Maladie chronique',
            ),
            290 => 
            array (
                'id' => '01hdkrfhfsqgvdf9xn6j73k5fs',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Churchill River',
                'name_fr' => 'Rivière Churchill',
            ),
            291 => 
            array (
                'id' => '01hdkrfhftw4vsm1tksnxy3ypy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Circulation',
                'name_fr' => 'Circulation',
            ),
            292 => 
            array (
                'id' => '01hdkrfhftw4vsm1tksnxy3ypz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Circumpolar Region',
                'name_fr' => 'Région circumpolaire',
            ),
            293 => 
            array (
                'id' => '01hdkrfhfv64abswz9y4ty1gpd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Civil engineering',
                'name_fr' => 'Génie civil',
            ),
            294 => 
            array (
                'id' => '01hdkrfhfv64abswz9y4ty1gpe',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Classical Statistics',
                'name_fr' => 'Statistiques classiques',
            ),
            295 => 
            array (
                'id' => '01hdkrfhfwqdvgjaepgzsrz678',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Classification',
                'name_fr' => 'Classification',
            ),
            296 => 
            array (
                'id' => '01hdkrfhfwqdvgjaepgzsrz679',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Clean Air Regulatory Agenda',
                'name_fr' => 'Programme de réglementation de la qualité de l&#039;air',
            ),
            297 => 
            array (
                'id' => '01hdkrfhfxbpvk9vmp2tat9625',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Clean Coal',
                'name_fr' => 'Charbon propre',
            ),
            298 => 
            array (
                'id' => '01hdkrfhfxbpvk9vmp2tat9626',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Clean Electricity',
                'name_fr' => 'Électricité propre',
            ),
            299 => 
            array (
                'id' => '01hdkrfhfyt8rtc0yjg3kfs39j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cleanup and decontamination',
                'name_fr' => 'Nettoyage et décontamination',
            ),
            300 => 
            array (
                'id' => '01hdkrfhfyt8rtc0yjg3kfs39k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Climate',
                'name_fr' => 'Climat',
            ),
            301 => 
            array (
                'id' => '01hdkrfhfyt8rtc0yjg3kfs39m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Climate Change',
                'name_fr' => 'Changement climatique',
            ),
            302 => 
            array (
                'id' => '01hdkrfhfzc17njzv613s358dd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Climate Change Adaptation',
                'name_fr' => 'Adaptation aux changements climatiques',
            ),
            303 => 
            array (
                'id' => '01hdkrfhfzc17njzv613s358de',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Climate Change and Processes',
                'name_fr' => 'Changements et processus climatiques',
            ),
            304 => 
            array (
                'id' => '01hdkrfhg0a4z5zzsbnc2x3q3v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Climate change impacts',
                'name_fr' => 'Incidence des changements climatiques',
            ),
            305 => 
            array (
                'id' => '01hdkrfhg0a4z5zzsbnc2x3q3w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Climate change mitigation',
                'name_fr' => 'Atténuation du changement climatique',
            ),
            306 => 
            array (
                'id' => '01hdkrfhg1mnt83g4t066z1k55',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Climate Change Risk Analysis',
                'name_fr' => 'Analyse des risques associés aux changements climatiques',
            ),
            307 => 
            array (
                'id' => '01hdkrfhg1mnt83g4t066z1k56',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Climate Impact',
                'name_fr' => 'Impacts du changement climatique',
            ),
            308 => 
            array (
                'id' => '01hdkrfhg2h90z0vae70kw8k2q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Climate Modeling',
                'name_fr' => 'Modélisation climatique',
            ),
            309 => 
            array (
                'id' => '01hdkrfhg2h90z0vae70kw8k2r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Climate resilience and sustainability',
                'name_fr' => 'Résilience climatique et durabilité',
            ),
            310 => 
            array (
                'id' => '01hdkrfhg3revp82f4q1shj5mk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Climatic controls',
                'name_fr' => 'Modifications climatiques',
            ),
            311 => 
            array (
                'id' => '01hdkrfhg3revp82f4q1shj5mm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Clinical decision support systems ',
                'name_fr' => 'Systèmes de soutien à la prise de décision clinique',
            ),
            312 => 
            array (
                'id' => '01hdkrfhg3revp82f4q1shj5mn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cloning',
                'name_fr' => 'Clonage',
            ),
            313 => 
            array (
                'id' => '01hdkrfhg4048ndwq7ansn6vcq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cloud modelling',
                'name_fr' => 'Modélisation des nuages',
            ),
            314 => 
            array (
                'id' => '01hdkrfhg4048ndwq7ansn6vcr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cloud physics',
                'name_fr' => 'Physique des nuages',
            ),
            315 => 
            array (
                'id' => '01hdkrfhg5gvpyeg4gw6wmbe5h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cloud processes',
                'name_fr' => 'Processus liés aux nuages',
            ),
            316 => 
            array (
                'id' => '01hdkrfhg6cd27w6s60f4rwqxq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'CloudSat',
                'name_fr' => 'CloudSat',
            ),
            317 => 
            array (
                'id' => '01hdkrfhg6cd27w6s60f4rwqxr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Clustering',
                'name_fr' => 'Agrégation',
            ),
            318 => 
            array (
                'id' => '01hdkrfhg7r2adr3k6szfh1hwt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Co-generation',
                'name_fr' => 'Cogénération',
            ),
            319 => 
            array (
                'id' => '01hdkrfhg7r2adr3k6szfh1hwv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Coal and Coal Products',
                'name_fr' => 'Charbon et produits du charbon',
            ),
            320 => 
            array (
                'id' => '01hdkrfhg832q9gds16sjwbzhf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Coal Bed Methane',
                'name_fr' => 'Méthane de houille',
            ),
            321 => 
            array (
                'id' => '01hdkrfhg832q9gds16sjwbzhg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Coastal - Arctic Canada',
                'name_fr' => 'Maritime - Canada arctique',
            ),
            322 => 
            array (
                'id' => '01hdkrfhg93razspxax46a2vgv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Coastal - Beaufort Sea',
                'name_fr' => 'Maritime - Mer de Beaufort',
            ),
            323 => 
            array (
                'id' => '01hdkrfhg93razspxax46a2vgw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Coastal - Eastern Canada',
                'name_fr' => 'Maritime - l&#039;est du Canada',
            ),
            324 => 
            array (
                'id' => '01hdkrfhganvjbrrmcz18w9v0x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Coastal - Western Canada',
                'name_fr' => 'Maritime - l&#039;ouest du Canada',
            ),
            325 => 
            array (
                'id' => '01hdkrfhganvjbrrmcz18w9v0y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Coastal Engineering',
                'name_fr' => 'Génie côtier',
            ),
            326 => 
            array (
                'id' => '01hdkrfhgbmbdvzy8scbpbbcsw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Coastal habitats',
                'name_fr' => 'Habitats côtiers',
            ),
            327 => 
            array (
                'id' => '01hdkrfhgc6cgz2tzdd1t94502',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Coatings',
                'name_fr' => 'Revêtements',
            ),
            328 => 
            array (
                'id' => '01hdkrfhgc6cgz2tzdd1t94503',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cold and drought tolerance',
                'name_fr' => 'Tolérance au froid et à la sécheresse',
            ),
            329 => 
            array (
                'id' => '01hdkrfhgd02h3e3ajx75v173y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cold Region Engineering',
                'name_fr' => 'Ingénierie en régions froides',
            ),
            330 => 
            array (
                'id' => '01hdkrfhgd02h3e3ajx75v173z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cold spray additive manufacturing',
                'name_fr' => 'Fabrication additive par projection à froid',
            ),
            331 => 
            array (
                'id' => '01hdkrfhge3jxfbhfbtdg8qyj6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Combined Heat and Power',
                'name_fr' => 'Congénération',
            ),
            332 => 
            array (
                'id' => '01hdkrfhge3jxfbhfbtdg8qyj7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Combined sewer overflow',
                'name_fr' => 'Déversoirs d&#039;orage',
            ),
            333 => 
            array (
                'id' => '01hdkrfhgfpntxw7wy845w7eb6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Communication Protocol',
                'name_fr' => 'Protocole de communication',
            ),
            334 => 
            array (
                'id' => '01hdkrfhgfpntxw7wy845w7eb7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Communications',
                'name_fr' => 'Communications',
            ),
            335 => 
            array (
                'id' => '01hdkrfhgfpntxw7wy845w7eb8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Communications equipment',
                'name_fr' => 'Équipement de communications',
            ),
            336 => 
            array (
                'id' => '01hdkrfhggbyam5r572q0m6735',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Communications strategy',
                'name_fr' => 'Stratégie de communications',
            ),
            337 => 
            array (
                'id' => '01hdkrfhggbyam5r572q0m6736',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Community radio',
                'name_fr' => 'Radio communautaire',
            ),
            338 => 
            array (
                'id' => '01hdkrfhghm4xbxznpay4fmqxy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Comparative genomics',
                'name_fr' => 'Génomique comparative',
            ),
            339 => 
            array (
                'id' => '01hdkrfhghm4xbxznpay4fmqxz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Compliance and Enforcement',
                'name_fr' => 'Conformité et exécution de la loi',
            ),
            340 => 
            array (
                'id' => '01hdkrfhgjj68841pbnvyc9rdy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Composites',
                'name_fr' => 'Matériaux composites',
            ),
            341 => 
            array (
                'id' => '01hdkrfhgjj68841pbnvyc9rdz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Compound detection',
                'name_fr' => 'Détection de composés',
            ),
            342 => 
            array (
                'id' => '01hdkrfhgkt2d0tr2227p5b0r1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Computational linguistics',
                'name_fr' => 'Linguistique informatique',
            ),
            343 => 
            array (
                'id' => '01hdkrfhgkt2d0tr2227p5b0r2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Computer analysis and modelling',
                'name_fr' => 'Analyse et modélisation par ordinateur',
            ),
            344 => 
            array (
                'id' => '01hdkrfhgmra5asp24mx72pyev',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Computer networks',
                'name_fr' => 'Réseau informatique',
            ),
            345 => 
            array (
                'id' => '01hdkrfhgmra5asp24mx72pyew',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Computer programming',
                'name_fr' => 'Programmation d&#039;ordinateur',
            ),
            346 => 
            array (
                'id' => '01hdkrfhgmra5asp24mx72pyex',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Computer science',
                'name_fr' => 'Informatique',
            ),
            347 => 
            array (
                'id' => '01hdkrfhgnc1yvsbhea1rs9c9q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Computer security',
                'name_fr' => 'Sécurité informatique',
            ),
            348 => 
            array (
                'id' => '01hdkrfhgnc1yvsbhea1rs9c9r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Computer services',
                'name_fr' => 'Services informatiques',
            ),
            349 => 
            array (
                'id' => '01hdkrfhgppc8vscfnzhscq6gc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Computer systems',
                'name_fr' => 'Système informatique',
            ),
            350 => 
            array (
                'id' => '01hdkrfhgppc8vscfnzhscq6gd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Computer Vision',
                'name_fr' => 'Vision par ordinateur',
            ),
            351 => 
            array (
                'id' => '01hdkrfhgqj76x686jjahkbkaz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Computer-aided design',
                'name_fr' => 'Conception assistée par ordinateur',
            ),
            352 => 
            array (
                'id' => '01hdkrfhgqj76x686jjahkbkb0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Computers',
                'name_fr' => 'Ordinateurs',
            ),
            353 => 
            array (
                'id' => '01hdkrfhgrgmt1bgfv7hzhpdd3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Computing Power',
                'name_fr' => 'Capacité de traitement',
            ),
            354 => 
            array (
                'id' => '01hdkrfhgrgmt1bgfv7hzhpdd4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Concrete',
                'name_fr' => 'Béton',
            ),
            355 => 
            array (
                'id' => '01hdkrfhgsp87d9rcqr0gf84sy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Condensing Heat Exchanger',
                'name_fr' => 'Échangeur de chaleur-évaporateur',
            ),
            356 => 
            array (
                'id' => '01hdkrfhgt6sy4vx0g0qcatbq7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Condiment mustard',
                'name_fr' => 'Moutarde condimentaire',
            ),
            357 => 
            array (
                'id' => '01hdkrfhgt6sy4vx0g0qcatbq8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Connectivity',
                'name_fr' => 'Connectivité',
            ),
            358 => 
            array (
                'id' => '01hdkrfhgt6sy4vx0g0qcatbq9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Conservation biology',
                'name_fr' => 'Biologie de la conservation',
            ),
            359 => 
            array (
                'id' => '01hdkrfhgv4w91tp2sgdbczjyq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Conservation of forest biodiversity',
                'name_fr' => 'Conservation de la biodiversité des forêts',
            ),
            360 => 
            array (
                'id' => '01hdkrfhgv4w91tp2sgdbczjyr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Construction',
                'name_fr' => 'Construction',
            ),
            361 => 
            array (
                'id' => '01hdkrfhgwv2gnrt89fw7kmkpp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Consumer Product Safety',
                'name_fr' => 'Sécurité des produits de consommation',
            ),
            362 => 
            array (
                'id' => '01hdkrfhgwv2gnrt89fw7kmkpq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Consumer Products',
                'name_fr' => 'Produits de consommation',
            ),
            363 => 
            array (
                'id' => '01hdkrfhgxdq2ky2vjmrdmpy61',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Consumer Protection',
                'name_fr' => 'Protection du consommateur',
            ),
            364 => 
            array (
                'id' => '01hdkrfhgxdq2ky2vjmrdmpy62',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Consumer sciences',
                'name_fr' => 'Des sciences de la consommation',
            ),
            365 => 
            array (
                'id' => '01hdkrfhgyrqfztd58bx64e9yx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Contaminants',
                'name_fr' => 'Contaminants',
            ),
            366 => 
            array (
                'id' => '01hdkrfhgyrqfztd58bx64e9yy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Contaminated Sites',
                'name_fr' => 'Sites contaminés',
            ),
            367 => 
            array (
                'id' => '01hdkrfhgz253856fg47m7pdgz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Contours',
                'name_fr' => 'Courbes de niveau',
            ),
            368 => 
            array (
                'id' => '01hdkrfhgz253856fg47m7pdh0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Controlled Substance',
                'name_fr' => 'Substances contrôlées',
            ),
            369 => 
            array (
                'id' => '01hdkrfhh0qr3b433kz75g99yw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Convention on Trade of Endangered Species (WAPPRIITA)',
            'name_fr' => 'Convention sur le commerce international des espèces menacées d&#039;extinction (WAPPRIITA)',
            ),
            370 => 
            array (
                'id' => '01hdkrfhh0qr3b433kz75g99yx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Conventional Fuels',
                'name_fr' => 'Carburants classiques',
            ),
            371 => 
            array (
                'id' => '01hdkrfhh1p2pkj43tktbfv1m9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cooking',
                'name_fr' => 'Art culinaire',
            ),
            372 => 
            array (
                'id' => '01hdkrfhh1p2pkj43tktbfv1ma',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Copyrights',
                'name_fr' => 'Droits d&#039;auteur',
            ),
            373 => 
            array (
                'id' => '01hdkrfhh1p2pkj43tktbfv1mb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cordillera',
                'name_fr' => 'Cordillière',
            ),
            374 => 
            array (
                'id' => '01hdkrfhh2wkqtxhm90259b45w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Corn/maize',
                'name_fr' => 'Maïs',
            ),
            375 => 
            array (
                'id' => '01hdkrfhh2wkqtxhm90259b45x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Corrosion',
                'name_fr' => 'Corrosion',
            ),
            376 => 
            array (
                'id' => '01hdkrfhh3mxkzkfvgv5bwj9pe',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cosmetics',
                'name_fr' => 'Produits cosmétiques',
            ),
            377 => 
            array (
                'id' => '01hdkrfhh3mxkzkfvgv5bwj9pf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Coupled Ocean and Atmospheric Models',
                'name_fr' => 'Modèles couplés océan-atmosphère',
            ),
            378 => 
            array (
                'id' => '01hdkrfhh42x88b86r4jvn1k00',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'COVID-19',
                'name_fr' => 'COVID-19',
            ),
            379 => 
            array (
                'id' => '01hdkrfhh42x88b86r4jvn1k01',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cow',
                'name_fr' => 'La vache',
            ),
            380 => 
            array (
                'id' => '01hdkrfhh52jq6qc2s5gxq3f79',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'CPU',
                'name_fr' => 'Unité centrale',
            ),
            381 => 
            array (
                'id' => '01hdkrfhh52jq6qc2s5gxq3f7a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Critical habitat (species at risk)',
            'name_fr' => 'Habitat critique (espèces en péril)',
            ),
            382 => 
            array (
                'id' => '01hdkrfhh52jq6qc2s5gxq3f7b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Critical Infrastructure Vulnerabilities, Resiliency and Interdependencies',
                'name_fr' => 'Vulnérabilité, résilience et interdépendances des infrastructures essentielles',
            ),
            383 => 
            array (
                'id' => '01hdkrfhh6wd49anm55gv76fyb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Crop',
                'name_fr' => 'Cultures',
            ),
            384 => 
            array (
                'id' => '01hdkrfhh6wd49anm55gv76fyc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Crop production',
                'name_fr' => 'La production des cultures',
            ),
            385 => 
            array (
                'id' => '01hdkrfhh72mftkf48x6k7v3xc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Croplands and water management',
                'name_fr' => 'Gestion des terres cultivées et de l’eau',
            ),
            386 => 
            array (
                'id' => '01hdkrfhh72mftkf48x6k7v3xd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Crops',
                'name_fr' => 'Des cultures',
            ),
            387 => 
            array (
                'id' => '01hdkrfhh8et68cxzshmntb341',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Crude Oil',
                'name_fr' => 'Pétrole brute',
            ),
            388 => 
            array (
                'id' => '01hdkrfhh9qg908jdxexd7z5as',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cryospheric',
                'name_fr' => 'Cryosphère',
            ),
            389 => 
            array (
                'id' => '01hdkrfhh9qg908jdxexd7z5at',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cryptography',
                'name_fr' => 'Cryptographie',
            ),
            390 => 
            array (
                'id' => '01hdkrfhhazsxf6g4njdqqgv8e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Curator',
                'name_fr' => 'Conservateur',
            ),
            391 => 
            array (
                'id' => '01hdkrfhhazsxf6g4njdqqgv8f',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cutworm',
                'name_fr' => 'Du ver gris',
            ),
            392 => 
            array (
                'id' => '01hdkrfhhb7jpqj86f0mb4hkvj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cybersecurity',
                'name_fr' => 'Cybersécurité',
            ),
            393 => 
            array (
                'id' => '01hdkrfhhb7jpqj86f0mb4hkvk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cyst',
                'name_fr' => 'Kystes',
            ),
            394 => 
            array (
                'id' => '01hdkrfhhcwd7kjvqq1szwrzez',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Cytogenetics',
                'name_fr' => 'Cytogénétique',
            ),
            395 => 
            array (
                'id' => '01hdkrfhhcwd7kjvqq1szwrzf0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Dairy',
                'name_fr' => 'L&#039;exploitation laitière',
            ),
            396 => 
            array (
                'id' => '01hdkrfhhdt1s698r2ft8exb5d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Dairy technology',
                'name_fr' => 'Technologie laitière',
            ),
            397 => 
            array (
                'id' => '01hdkrfhhdt1s698r2ft8exb5e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Data acquisition',
                'name_fr' => 'Acquisition de données',
            ),
            398 => 
            array (
                'id' => '01hdkrfhhem8r164mgwpmxaqsg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Data analytics',
                'name_fr' => 'Analyse des données',
            ),
            399 => 
            array (
                'id' => '01hdkrfhhem8r164mgwpmxaqsh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Data and Text Mining',
                'name_fr' => 'Forage des données',
            ),
            400 => 
            array (
                'id' => '01hdkrfhhfvyx1zjfvbk92pph8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Data assimilation',
                'name_fr' => 'Assimilation des données',
            ),
            401 => 
            array (
                'id' => '01hdkrfhhfvyx1zjfvbk92pph9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Data Cleansing',
                'name_fr' => 'Nettoyage des données',
            ),
            402 => 
            array (
                'id' => '01hdkrfhhfvyx1zjfvbk92ppha',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Data Ingestion',
                'name_fr' => 'Ingestion de données',
            ),
            403 => 
            array (
                'id' => '01hdkrfhhgt1d1sz5k65h6f82g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Data Licensing and Distribution',
                'name_fr' => 'Attribution de licences de données et distribution',
            ),
            404 => 
            array (
                'id' => '01hdkrfhhgt1d1sz5k65h6f82h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Data Management',
                'name_fr' => 'Gestion des données',
            ),
            405 => 
            array (
                'id' => '01hdkrfhhhzntd329rcxtztsh6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Data Munging',
                'name_fr' => 'Exploitation des données',
            ),
            406 => 
            array (
                'id' => '01hdkrfhhhzntd329rcxtztsh7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Data processing',
                'name_fr' => 'Traitement des données',
            ),
            407 => 
            array (
                'id' => '01hdkrfhhjsf63k3b7q7nztayq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Data Quality',
                'name_fr' => 'Qualité des données',
            ),
            408 => 
            array (
                'id' => '01hdkrfhhjsf63k3b7q7nztayr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Data Quality Assurance and Control',
                'name_fr' => 'Assurance et contrôle de la qualité des données',
            ),
            409 => 
            array (
                'id' => '01hdkrfhhkmx79vpkze9n6w5m7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Data Science',
                'name_fr' => 'Science des données',
            ),
            410 => 
            array (
                'id' => '01hdkrfhhkmx79vpkze9n6w5m8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Data Visualization',
                'name_fr' => 'Visualisation des données',
            ),
            411 => 
            array (
                'id' => '01hdkrfhhkmx79vpkze9n6w5m9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Database',
                'name_fr' => 'Database',
            ),
            412 => 
            array (
                'id' => '01hdkrfhhmghbhyxp3pbbgtxsz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Davis Strait',
                'name_fr' => 'Détroit de Davis',
            ),
            413 => 
            array (
                'id' => '01hdkrfhhmghbhyxp3pbbgtxt0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Decision support systems',
                'name_fr' => 'Systèmes d&#039;aide à la décision',
            ),
            414 => 
            array (
                'id' => '01hdkrfhhnm8bm6jrcnfgk0et4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Decision Tree',
                'name_fr' => 'Arbre de décision',
            ),
            415 => 
            array (
                'id' => '01hdkrfhhnm8bm6jrcnfgk0et5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Decontamination',
                'name_fr' => 'Décontamination',
            ),
            416 => 
            array (
                'id' => '01hdkrfhhpc44sbyyf3hc3kgx9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Deep Learning',
                'name_fr' => 'Apprentissage profond',
            ),
            417 => 
            array (
                'id' => '01hdkrfhhpc44sbyyf3hc3kgxa',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Defence industry',
                'name_fr' => 'Industrie de la défense',
            ),
            418 => 
            array (
                'id' => '01hdkrfhhq37xw77812yjn7zn4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Demand-side Management',
                'name_fr' => 'Gestion de la demande',
            ),
            419 => 
            array (
                'id' => '01hdkrfhhq37xw77812yjn7zn5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Demersal Species',
                'name_fr' => 'Espèces démersales',
            ),
            420 => 
            array (
                'id' => '01hdkrfhhr16t700yd0mv828sn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Dendrochronology',
                'name_fr' => 'Dendrochronologie',
            ),
            421 => 
            array (
                'id' => '01hdkrfhhr16t700yd0mv828sp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Dendrology',
                'name_fr' => 'Dendrologie',
            ),
            422 => 
            array (
                'id' => '01hdkrfhhs1pzmjz2c82w33f7j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Deposition',
                'name_fr' => 'Dépôt',
            ),
            423 => 
            array (
                'id' => '01hdkrfhhs1pzmjz2c82w33f7k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Design Systems',
                'name_fr' => 'Systèmes de conception',
            ),
            424 => 
            array (
                'id' => '01hdkrfhhs1pzmjz2c82w33f7m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Developmental plant',
                'name_fr' => 'Phytophysiologie du développement',
            ),
            425 => 
            array (
                'id' => '01hdkrfhhtnkbj6h4nb301ngve',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Diagnostic method development',
                'name_fr' => 'Dévelopement de méthodes pour le diagnostic',
            ),
            426 => 
            array (
                'id' => '01hdkrfhhvkahskajvsbzwx81m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Diamonds',
                'name_fr' => 'Diamants',
            ),
            427 => 
            array (
                'id' => '01hdkrfhhvkahskajvsbzwx81n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Diesel Engines',
                'name_fr' => 'Moteurs diesel',
            ),
            428 => 
            array (
                'id' => '01hdkrfhhwx19gv6axyt79y253',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Diet and gut health',
                'name_fr' => 'Régimes alimentaires et santé intestinale',
            ),
            429 => 
            array (
                'id' => '01hdkrfhhwx19gv6axyt79y254',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Dietary fiber',
                'name_fr' => 'Fibres alimentaires',
            ),
            430 => 
            array (
                'id' => '01hdkrfhhxa9hhjd6y15gv6k1e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Digital Evaluation Model',
                'name_fr' => 'Modèle numérique d&#039;élévation',
            ),
            431 => 
            array (
                'id' => '01hdkrfhhxa9hhjd6y15gv6k1f',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Digital Health',
                'name_fr' => 'Santé numérique',
            ),
            432 => 
            array (
                'id' => '01hdkrfhhyxwsbkm4gatqc4ghv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Digital libraries',
                'name_fr' => 'Bibliothèque numérique',
            ),
            433 => 
            array (
                'id' => '01hdkrfhhyxwsbkm4gatqc4ghw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Digital manufacturing',
                'name_fr' => 'Fabrication numérique',
            ),
            434 => 
            array (
                'id' => '01hdkrfhhzw970xatbpfb105af',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Digital technology',
                'name_fr' => 'Technologie numérique',
            ),
            435 => 
            array (
                'id' => '01hdkrfhhzw970xatbpfb105ag',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Digitization',
                'name_fr' => 'Numérisation',
            ),
            436 => 
            array (
                'id' => '01hdkrfhj0wg54d0xf2t8deqw1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Direct contact steam generation (DCSG)',
            'name_fr' => 'Production de vapeur par contact direct (PVCD)',
            ),
            437 => 
            array (
                'id' => '01hdkrfhj0wg54d0xf2t8deqw2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Directed energy deposition',
                'name_fr' => 'Dépôt de matière sous énergie concentrée',
            ),
            438 => 
            array (
                'id' => '01hdkrfhj0wg54d0xf2t8deqw3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Disaster management',
                'name_fr' => 'Gestion des désastres',
            ),
            439 => 
            array (
                'id' => '01hdkrfhj1hgpr89aa2gda547d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Disaster mitigation',
                'name_fr' => 'Atténuation des dégâts',
            ),
            440 => 
            array (
                'id' => '01hdkrfhj1hgpr89aa2gda547e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Discovery',
                'name_fr' => 'Découverte',
            ),
            441 => 
            array (
                'id' => '01hdkrfhj257afr8xqpt2c1y8a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Disease impacts',
                'name_fr' => 'Incidence des maladies',
            ),
            442 => 
            array (
                'id' => '01hdkrfhj257afr8xqpt2c1y8b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Disease resistance',
                'name_fr' => 'Résistance aux maladies',
            ),
            443 => 
            array (
                'id' => '01hdkrfhj335wf10wne03vsb83',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Diseases',
                'name_fr' => 'Maladies',
            ),
            444 => 
            array (
                'id' => '01hdkrfhj335wf10wne03vsb84',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Dispensers',
                'name_fr' => 'Distributeur',
            ),
            445 => 
            array (
                'id' => '01hdkrfhj493efjm9569n2gt9w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Distributed computing',
                'name_fr' => 'Informatique distribuée',
            ),
            446 => 
            array (
                'id' => '01hdkrfhj493efjm9569n2gt9x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Distributed Energy',
                'name_fr' => 'Énergie décentralisée',
            ),
            447 => 
            array (
                'id' => '01hdkrfhj493efjm9569n2gt9y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Diversity and conservation',
                'name_fr' => 'Diversité et conservation',
            ),
            448 => 
            array (
                'id' => '01hdkrfhj5c7tz3p1879nn0jms',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Domestic substances',
                'name_fr' => 'Substances nationales',
            ),
            449 => 
            array (
                'id' => '01hdkrfhj5c7tz3p1879nn0jmt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Dose-Response Modeling',
                'name_fr' => 'Modélisation dose-réponse',
            ),
            450 => 
            array (
                'id' => '01hdkrfhj62e004ny51rx4hfbp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Dosimetry Services',
                'name_fr' => 'Services de dosimétrie',
            ),
            451 => 
            array (
                'id' => '01hdkrfhj62e004ny51rx4hfbq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Drought',
                'name_fr' => 'Sécheresse',
            ),
            452 => 
            array (
                'id' => '01hdkrfhj75mk6cghdhr8qykkt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Drought, cold and heat tolerance',
                'name_fr' => 'Tolérance à la sécheresse, au froid et à la chaleur',
            ),
            453 => 
            array (
                'id' => '01hdkrfhj75mk6cghdhr8qykkv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Drug residues',
                'name_fr' => 'Résidus de médicaments',
            ),
            454 => 
            array (
                'id' => '01hdkrfhj85jb3c2nd6nexmm4x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Dry bean',
                'name_fr' => 'Haricots secs',
            ),
            455 => 
            array (
                'id' => '01hdkrfhj85jb3c2nd6nexmm4y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Dynamic and structural computer modelling and analysis',
                'name_fr' => 'Modélisation et analyse dynamique et structurale sur ordinateur',
            ),
            456 => 
            array (
                'id' => '01hdkrfhj9w9gzcktc1mq0m9rw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Dynamic Global Positioning',
                'name_fr' => 'Positionnement global dynamique',
            ),
            457 => 
            array (
                'id' => '01hdkrfhj9w9gzcktc1mq0m9rx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'E. coli',
                'name_fr' => 'E. coli',
            ),
            458 => 
            array (
                'id' => '01hdkrfhj9w9gzcktc1mq0m9ry',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Earth Observation',
                'name_fr' => 'Observation de la terre',
            ),
            459 => 
            array (
                'id' => '01hdkrfhja5fe3whysnnc75c2b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Earth Sciences',
                'name_fr' => 'Sciences de la Terre',
            ),
            460 => 
            array (
                'id' => '01hdkrfhjbgrwm5vg9thc1xh4j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Earthquakes',
                'name_fr' => 'Tremblement de terre',
            ),
            461 => 
            array (
                'id' => '01hdkrfhjbgrwm5vg9thc1xh4k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Eastern Canada',
                'name_fr' => 'L&#039;Est du Canada',
            ),
            462 => 
            array (
                'id' => '01hdkrfhjc14m2jsygf0vwj03v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Eco-responsible Materials',
                'name_fr' => 'Matériaux écoresponsables',
            ),
            463 => 
            array (
                'id' => '01hdkrfhjc14m2jsygf0vwj03w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ecological conservation',
                'name_fr' => 'Protection écologique',
            ),
            464 => 
            array (
                'id' => '01hdkrfhjd2167943xewqgkk4r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ecological reforestation',
                'name_fr' => 'restauration biologique',
            ),
            465 => 
            array (
                'id' => '01hdkrfhjd2167943xewqgkk4s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ecological risk assessment',
                'name_fr' => 'Évaluation des risques écologiques',
            ),
            466 => 
            array (
                'id' => '01hdkrfhjej7t0vfqwqaefbx1a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ecology',
                'name_fr' => 'Écologie',
            ),
            467 => 
            array (
                'id' => '01hdkrfhjej7t0vfqwqaefbx1b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ecology and Ecosystems',
                'name_fr' => 'Écologie et écosystèmes',
            ),
            468 => 
            array (
                'id' => '01hdkrfhjf7gzebdgrxs1jkksm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Economic analysis',
                'name_fr' => 'Analyse économique',
            ),
            469 => 
            array (
                'id' => '01hdkrfhjf7gzebdgrxs1jkksn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Economic conditions',
                'name_fr' => 'Conditions économiques',
            ),
            470 => 
            array (
                'id' => '01hdkrfhjg1vxpptbhp5zh9yqg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Economic development',
                'name_fr' => 'Développement économique',
            ),
            471 => 
            array (
                'id' => '01hdkrfhjg1vxpptbhp5zh9yqh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Economic geology',
                'name_fr' => 'Géologie économique',
            ),
            472 => 
            array (
                'id' => '01hdkrfhjhxzbeda8xsa178asv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Economic policy',
                'name_fr' => 'Politique économique',
            ),
            473 => 
            array (
                'id' => '01hdkrfhjhxzbeda8xsa178asw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Economic research',
                'name_fr' => 'Recherche économique',
            ),
            474 => 
            array (
                'id' => '01hdkrfhjjdjgh8q7k8be867hg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Economic statistics',
                'name_fr' => 'Statistiques économiques',
            ),
            475 => 
            array (
                'id' => '01hdkrfhjjdjgh8q7k8be867hh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Economic trends',
                'name_fr' => 'Tendances économiques',
            ),
            476 => 
            array (
                'id' => '01hdkrfhjkyrmsn7dthzw7fsbs',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Economics',
                'name_fr' => 'Science économique',
            ),
            477 => 
            array (
                'id' => '01hdkrfhjkyrmsn7dthzw7fsbt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Economics and Industry',
                'name_fr' => 'Économie et industrie',
            ),
            478 => 
            array (
                'id' => '01hdkrfhjmm8zw52rpmjbrq3w9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ecopathology',
                'name_fr' => 'Écopathologie',
            ),
            479 => 
            array (
                'id' => '01hdkrfhjmm8zw52rpmjbrq3wa',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ecophysiology',
                'name_fr' => 'Écophysiologie',
            ),
            480 => 
            array (
                'id' => '01hdkrfhjmm8zw52rpmjbrq3wb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ecosystem',
                'name_fr' => 'Écosystème',
            ),
            481 => 
            array (
                'id' => '01hdkrfhjn3t9b76a4z7vvm12d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ecosystem effects of fishing',
                'name_fr' => 'Effets des écosystèmes sur la pêche',
            ),
            482 => 
            array (
                'id' => '01hdkrfhjp43ywzwqjm2mj8z0h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ecosystem health',
                'name_fr' => 'Santé des écosystèmes',
            ),
            483 => 
            array (
                'id' => '01hdkrfhjp43ywzwqjm2mj8z0j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ecosystem Management',
                'name_fr' => 'Gestion de l&#039;écosystème',
            ),
            484 => 
            array (
                'id' => '01hdkrfhjp43ywzwqjm2mj8z0k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ecosystem/Ecological modeling',
                'name_fr' => 'Modélisation des écosystèmes/écologique',
            ),
            485 => 
            array (
                'id' => '01hdkrfhjq1jv7mkgzgmpz0asy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ecosystems',
                'name_fr' => 'Écosystèmes',
            ),
            486 => 
            array (
                'id' => '01hdkrfhjq1jv7mkgzgmpz0asz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ecosystems &amp; Habitats',
                'name_fr' => 'Écosystèmes et habitats',
            ),
            487 => 
            array (
                'id' => '01hdkrfhjr26mefcactmhpftzx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ecotoxicology',
                'name_fr' => 'Écotoxicologie',
            ),
            488 => 
            array (
                'id' => '01hdkrfhjr26mefcactmhpftzy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Educational technology',
                'name_fr' => 'Technologie éducative',
            ),
            489 => 
            array (
                'id' => '01hdkrfhjs66yzsgkdtz86q13x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Effects',
                'name_fr' => 'Effets',
            ),
            490 => 
            array (
                'id' => '01hdkrfhjs66yzsgkdtz86q13y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Effects of Toxics',
                'name_fr' => 'Effets des substances toxiques',
            ),
            491 => 
            array (
                'id' => '01hdkrfhjt2rd8w5x6bhgtsjkj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Efficiency',
                'name_fr' => 'Efficacité',
            ),
            492 => 
            array (
                'id' => '01hdkrfhjt2rd8w5x6bhgtsjkk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Effluents',
                'name_fr' => 'Effluents',
            ),
            493 => 
            array (
                'id' => '01hdkrfhjvmkv94tgq2tkw2zrv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electric &amp; Hybrid Vehicles',
                'name_fr' => 'Véhicules électriques &amp; hybrides',
            ),
            494 => 
            array (
                'id' => '01hdkrfhjw3knymxnd78xg89ha',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electrical appliances',
                'name_fr' => 'Appareil électrique',
            ),
            495 => 
            array (
                'id' => '01hdkrfhjw3knymxnd78xg89hb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electrical equipment',
                'name_fr' => 'Équipement électrique',
            ),
            496 => 
            array (
                'id' => '01hdkrfhjxdd8bpbtrnqksbqjg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electrical power stations',
                'name_fr' => 'Centrale électrique',
            ),
            497 => 
            array (
                'id' => '01hdkrfhjxdd8bpbtrnqksbqjh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electricity',
                'name_fr' => 'Électricité',
            ),
            498 => 
            array (
                'id' => '01hdkrfhjyxy45qbp8zv7sdes0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electricity and Magnetism',
                'name_fr' => 'Électricité et magnétisme',
            ),
            499 => 
            array (
                'id' => '01hdkrfhjyxy45qbp8zv7sdes1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electro-technologies',
                'name_fr' => 'Électrotechnologies',
            ),
        ));
        \DB::table('expertises')->insert(array (
            0 => 
            array (
                'id' => '01hdkrfhjz3ys29r7fbx0fwnyf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electron beam welding',
                'name_fr' => 'Soudage par faisceau d’électrons',
            ),
            1 => 
            array (
                'id' => '01hdkrfhjz3ys29r7fbx0fwnyg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electronic',
                'name_fr' => 'Électronique',
            ),
            2 => 
            array (
                'id' => '01hdkrfhk0revp45yfr1jzp0d8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electronic data interchange',
                'name_fr' => 'Échange électronique d&#039;information',
            ),
            3 => 
            array (
                'id' => '01hdkrfhk0revp45yfr1jzp0d9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electronic Devices',
                'name_fr' => 'Dispositifs électroniques',
            ),
            4 => 
            array (
                'id' => '01hdkrfhk13fv1w0qnrk30axca',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electronic documents',
                'name_fr' => 'Document électronique',
            ),
            5 => 
            array (
                'id' => '01hdkrfhk13fv1w0qnrk30axcb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electronic equipment',
                'name_fr' => 'Équipement électronique',
            ),
            6 => 
            array (
                'id' => '01hdkrfhk2twwbwxxjy7w8qww4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electronic mail',
                'name_fr' => 'Courrier électronique',
            ),
            7 => 
            array (
                'id' => '01hdkrfhk2twwbwxxjy7w8qww5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electronic monitoring',
                'name_fr' => 'Surveillance electronique',
            ),
            8 => 
            array (
                'id' => '01hdkrfhk2twwbwxxjy7w8qww6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Electronics industry',
                'name_fr' => 'Industrie de l&#039;électronique',
            ),
            9 => 
            array (
                'id' => '01hdkrfhk33hn2zcxhwvbmjgt5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Emergency Management Systems and Interoperability',
                'name_fr' => 'Systèmes de gestion des urgences et interopérabilité',
            ),
            10 => 
            array (
                'id' => '01hdkrfhk33hn2zcxhwvbmjgt6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Emergency planning',
                'name_fr' => 'Planification d&#039;urgence',
            ),
            11 => 
            array (
                'id' => '01hdkrfhk4gseqha5baaatat80',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Emergency Response Service',
                'name_fr' => 'Services d’intervention d’urgence',
            ),
            12 => 
            array (
                'id' => '01hdkrfhk4gseqha5baaatat81',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Emerging chemicals',
                'name_fr' => 'Nouveaux produits chimiques',
            ),
            13 => 
            array (
                'id' => '01hdkrfhk5rb6prfz2z5m8cej5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Emerging green separation technologies',
                'name_fr' => 'Nouvelles technologies écologiques de séparation',
            ),
            14 => 
            array (
                'id' => '01hdkrfhk5rb6prfz2z5m8cej6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Emerging issues',
                'name_fr' => 'Nouveaux enjeux',
            ),
            15 => 
            array (
                'id' => '01hdkrfhk6jwdcrh4zw2d53g6w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Emerging Metrology',
                'name_fr' => 'Métrologie émergente',
            ),
            16 => 
            array (
                'id' => '01hdkrfhk6jwdcrh4zw2d53g6x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Emissions',
                'name_fr' => 'Émissions',
            ),
            17 => 
            array (
                'id' => '01hdkrfhk6jwdcrh4zw2d53g6y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Employee Assistance Programs',
                'name_fr' => 'Services d&#039;aide aux employés',
            ),
            18 => 
            array (
                'id' => '01hdkrfhk7wb090h00kxta7nkt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Encapsulation and powders',
                'name_fr' => 'Encapsulation et poudres',
            ),
            19 => 
            array (
                'id' => '01hdkrfhk7wb090h00kxta7nkv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Endocrine disruption',
                'name_fr' => 'Perturbation du système endocrinien',
            ),
            20 => 
            array (
                'id' => '01hdkrfhk8szegye0v39pqyb6v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Endocrinology',
                'name_fr' => 'Endocrinologie',
            ),
            21 => 
            array (
                'id' => '01hdkrfhk9rrcek2kxp31pzbtw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Energy',
                'name_fr' => 'Énergie',
            ),
            22 => 
            array (
                'id' => '01hdkrfhk9rrcek2kxp31pzbtx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Energy conservation',
                'name_fr' => 'Économies d’énergie',
            ),
            23 => 
            array (
                'id' => '01hdkrfhk9rrcek2kxp31pzbty',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Energy Conversion',
                'name_fr' => 'Conversion de l&#039;énergie',
            ),
            24 => 
            array (
                'id' => '01hdkrfhkam48ya81wmfzm5pew',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Energy efficiency',
                'name_fr' => 'Efficacité énergétique',
            ),
            25 => 
            array (
                'id' => '01hdkrfhkam48ya81wmfzm5pex',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Energy End Use',
                'name_fr' => 'Utilisation finale de l&#039;énergie',
            ),
            26 => 
            array (
                'id' => '01hdkrfhkbmq816ybmp5cdgxe0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'ENERGY STAR',
                'name_fr' => 'ENERGY STAR',
            ),
            27 => 
            array (
                'id' => '01hdkrfhkbmq816ybmp5cdgxe1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Energy Storage',
                'name_fr' => 'Stockage de l&#039;énergie',
            ),
            28 => 
            array (
                'id' => '01hdkrfhkc751yy481jt2djkhb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Energy technology',
                'name_fr' => 'Technologie énergétique',
            ),
            29 => 
            array (
                'id' => '01hdkrfhkdmtezec8d9x7ns0mb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Engineered materials',
                'name_fr' => 'Matériaux d&#039;ingénierie',
            ),
            30 => 
            array (
                'id' => '01hdkrfhkdmtezec8d9x7ns0mc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Engineering',
                'name_fr' => 'Ingénieur',
            ),
            31 => 
            array (
                'id' => '01hdkrfhkew9td1pwn0h1eesaz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Engineering and design',
                'name_fr' => 'Ingénierie et conception',
            ),
            32 => 
            array (
                'id' => '01hdkrfhkew9td1pwn0h1eesb0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Engines',
                'name_fr' => 'Moteur',
            ),
            33 => 
            array (
                'id' => '01hdkrfhkffygb935kef4ybjcr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Enhanced Oil Recovery',
                'name_fr' => 'Recuperation assistée du pétroles',
            ),
            34 => 
            array (
                'id' => '01hdkrfhkffygb935kef4ybjcs',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Enhanced Recovery',
                'name_fr' => 'Recuperation assistée',
            ),
            35 => 
            array (
                'id' => '01hdkrfhkffygb935kef4ybjct',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ensemble prediction',
                'name_fr' => 'Prévision d&#039;ensemble',
            ),
            36 => 
            array (
                'id' => '01hdkrfhkgv0y1bxa5abwjkdp8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Enteric microbiology and intestinal health',
                'name_fr' => 'Microbiologie entérique et santé intestinale',
            ),
            37 => 
            array (
                'id' => '01hdkrfhkgv0y1bxa5abwjkdp9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Entomology',
                'name_fr' => 'Entomologie',
            ),
            38 => 
            array (
                'id' => '01hdkrfhkhhjadv17bje98bqbq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Entomopathology',
                'name_fr' => 'Entomopathologie',
            ),
            39 => 
            array (
                'id' => '01hdkrfhkhhjadv17bje98bqbr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environment',
                'name_fr' => 'Environnement',
            ),
            40 => 
            array (
                'id' => '01hdkrfhkjy01ngczm5xjqqy4q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environment and Human Health',
                'name_fr' => 'Environnement et santé humaine',
            ),
            41 => 
            array (
                'id' => '01hdkrfhkjy01ngczm5xjqqy4r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental',
                'name_fr' => 'Environmentaux',
            ),
            42 => 
            array (
                'id' => '01hdkrfhkkrksxta0c423mn4ka',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental and Occupational Toxicology',
                'name_fr' => 'Toxicologie environnementale et professionnelle',
            ),
            43 => 
            array (
                'id' => '01hdkrfhkkrksxta0c423mn4kb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental biology',
                'name_fr' => 'Biologie environnementale',
            ),
            44 => 
            array (
                'id' => '01hdkrfhkmn7gy4k3ywdpzw80g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental Chemistry',
                'name_fr' => 'Chimie de l&#039;environnement',
            ),
            45 => 
            array (
                'id' => '01hdkrfhkmn7gy4k3ywdpzw80h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental contaminants',
                'name_fr' => 'Contaminants environnementaux',
            ),
            46 => 
            array (
                'id' => '01hdkrfhknchw0ctf7gta1w3v2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental Effects Monitoring Program',
                'name_fr' => 'Programme d&#039;étude de suivi des effets sur l&#039;environnement',
            ),
            47 => 
            array (
                'id' => '01hdkrfhknchw0ctf7gta1w3v3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental health',
                'name_fr' => 'Santé environnementale',
            ),
            48 => 
            array (
                'id' => '01hdkrfhkpmhb2yqfh0w2s10zm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental immunochemistry',
                'name_fr' => 'Immunochimie de l’environnement',
            ),
            49 => 
            array (
                'id' => '01hdkrfhkpmhb2yqfh0w2s10zn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental Impacts',
                'name_fr' => 'Conséquences environnementales',
            ),
            50 => 
            array (
                'id' => '01hdkrfhkqs95sv1zxkh59txhc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental management',
                'name_fr' => 'Gestion de l&#039;environnement',
            ),
            51 => 
            array (
                'id' => '01hdkrfhkqs95sv1zxkh59txhd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental metrology',
                'name_fr' => 'Métrologie environnementale',
            ),
            52 => 
            array (
                'id' => '01hdkrfhkqs95sv1zxkh59txhe',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental policy',
                'name_fr' => 'Politique environnementale',
            ),
            53 => 
            array (
                'id' => '01hdkrfhkrt4awgjapb7t9fwj4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental prediction',
                'name_fr' => 'Prévision environnementale',
            ),
            54 => 
            array (
                'id' => '01hdkrfhkrt4awgjapb7t9fwj5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental Radiation Protection',
                'name_fr' => 'Radioprotection environnementale',
            ),
            55 => 
            array (
                'id' => '01hdkrfhksr9azqvt7jf0srbna',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental science',
                'name_fr' => 'Sciences de l’environnement',
            ),
            56 => 
            array (
                'id' => '01hdkrfhksr9azqvt7jf0srbnb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Environmental technologies',
                'name_fr' => 'Technologies environnementales',
            ),
            57 => 
            array (
                'id' => '01hdkrfhkt5m05h4tavxp67pc9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Enzyme technologies',
                'name_fr' => 'Technologies enzymatiques',
            ),
            58 => 
            array (
                'id' => '01hdkrfhkt5m05h4tavxp67pca',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Enzymology',
                'name_fr' => 'Enzymologie',
            ),
            59 => 
            array (
                'id' => '01hdkrfhkv626e1qyakz578dzf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Epidemiology',
                'name_fr' => 'Épidémiologie',
            ),
            60 => 
            array (
                'id' => '01hdkrfhkv626e1qyakz578dzg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Epigenetics',
                'name_fr' => 'Épigénétique',
            ),
            61 => 
            array (
                'id' => '01hdkrfhkw6srm1kzk19yj9re6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Epitaxy',
                'name_fr' => 'Épitaxie',
            ),
            62 => 
            array (
                'id' => '01hdkrfhkw6srm1kzk19yj9re7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Equipment',
                'name_fr' => 'Équipement',
            ),
            63 => 
            array (
                'id' => '01hdkrfhkxrwyyd4gbpcyeafnr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Equipment industry',
                'name_fr' => 'Industrie de l&#039;équipement',
            ),
            64 => 
            array (
                'id' => '01hdkrfhkygw3pvfmv8smw96xy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Error Propagation',
                'name_fr' => 'Propagation d’erreur',
            ),
            65 => 
            array (
                'id' => '01hdkrfhkygw3pvfmv8smw96xz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Eutrophication',
                'name_fr' => 'Eutrophisation',
            ),
            66 => 
            array (
                'id' => '01hdkrfhkygw3pvfmv8smw96y0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Evaluation of New Products',
                'name_fr' => 'Évaluation des nouveaux produits',
            ),
            67 => 
            array (
                'id' => '01hdkrfhkz845v32xc9n91dq2h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Explosives',
                'name_fr' => 'Explosifs',
            ),
            68 => 
            array (
                'id' => '01hdkrfhkz845v32xc9n91dq2j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Exposure Science',
                'name_fr' => 'Science de l&#039;exposition',
            ),
            69 => 
            array (
                'id' => '01hdkrfhm0dysgnxqb4wzr28zn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Extraction techniques',
                'name_fr' => 'Techniques d&#039;extraction',
            ),
            70 => 
            array (
                'id' => '01hdkrfhm0dysgnxqb4wzr28zp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Extraction, Transportation and Spill Research',
                'name_fr' => 'Recherches sur l&#039;extraction, le transport et les déversements',
            ),
            71 => 
            array (
                'id' => '01hdkrfhm1wccrk85snp2t555w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Extrusion',
                'name_fr' => 'Extrusion',
            ),
            72 => 
            array (
                'id' => '01hdkrfhm1wccrk85snp2t555x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fatty acid metabolism',
                'name_fr' => 'Métabolisme des acides gras',
            ),
            73 => 
            array (
                'id' => '01hdkrfhm212335g5vtf8bdv7b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Federal science and technology',
                'name_fr' => 'Sciences et technologies fédérales',
            ),
            74 => 
            array (
                'id' => '01hdkrfhm212335g5vtf8bdv7c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fermentation',
                'name_fr' => 'Fermentation',
            ),
            75 => 
            array (
                'id' => '01hdkrfhm3v1rbj38hym2qqgc1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Field Data Collection',
                'name_fr' => 'Collecte de données sur le terrain',
            ),
            76 => 
            array (
                'id' => '01hdkrfhm3v1rbj38hym2qqgc2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Field Equipment',
                'name_fr' => 'Équipement de terrain',
            ),
            77 => 
            array (
                'id' => '01hdkrfhm40kvtjexbq7h5a281',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Financial Management',
                'name_fr' => 'Gestion financière',
            ),
            78 => 
            array (
                'id' => '01hdkrfhm40kvtjexbq7h5a282',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Finfish',
                'name_fr' => 'Poissons',
            ),
            79 => 
            array (
                'id' => '01hdkrfhm40kvtjexbq7h5a283',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fire',
                'name_fr' => 'Incendies',
            ),
            80 => 
            array (
                'id' => '01hdkrfhm58h55nnpp3m627rb1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fire safety',
                'name_fr' => 'Sécurité-incendie',
            ),
            81 => 
            array (
                'id' => '01hdkrfhm58h55nnpp3m627rb2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fireworks',
                'name_fr' => 'Feux d’artifice',
            ),
            82 => 
            array (
                'id' => '01hdkrfhm6ypnbspmet2acgwam',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'First Nations and Inuit Community Programs',
                'name_fr' => 'Programmes communautaires pour les Premières nations et les Inuits',
            ),
            83 => 
            array (
                'id' => '01hdkrfhm6ypnbspmet2acgwan',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'First Nations and Inuit Health Programming and Services',
                'name_fr' => 'Programmes liés à la santé des Premières nations et des Inuits',
            ),
            84 => 
            array (
                'id' => '01hdkrfhm7xznhyfdxvwxyrqp7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'First Nations and Inuit Health Protection and Public Health',
                'name_fr' => 'Premières nations et les Inuits - santé publique et protection de la Santé',
            ),
            85 => 
            array (
                'id' => '01hdkrfhm7xznhyfdxvwxyrqp8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'First Nations and Inuit Primary Care',
                'name_fr' => 'Premières nations et les Inuits - soins de santé primaires',
            ),
            86 => 
            array (
                'id' => '01hdkrfhm86rt54hw27krxnrm6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'First Responders',
                'name_fr' => 'Premiers intervenants',
            ),
            87 => 
            array (
                'id' => '01hdkrfhm86rt54hw27krxnrm7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fish',
                'name_fr' => 'Poisson',
            ),
            88 => 
            array (
                'id' => '01hdkrfhm9628h9dgp4h9hh9wx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fish population science',
                'name_fr' => 'Science de la population ichtyologique',
            ),
            89 => 
            array (
                'id' => '01hdkrfhm9628h9dgp4h9hh9wy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fish Stock Assessment/Population Modeling',
                'name_fr' => 'Évaluation des stocks de poissons/Modélisation des populations',
            ),
            90 => 
            array (
                'id' => '01hdkrfhm9628h9dgp4h9hh9wz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fisheries',
                'name_fr' => 'Pêches',
            ),
            91 => 
            array (
                'id' => '01hdkrfhmam01w7g7qam91qgh2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fisheries Act',
                'name_fr' => 'Loi sur les pêches',
            ),
            92 => 
            array (
                'id' => '01hdkrfhmam01w7g7qam91qgh3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Flax',
                'name_fr' => 'Lin',
            ),
            93 => 
            array (
                'id' => '01hdkrfhmb6bw39qkw82z9n5sz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fleet management and heavy-duty vehicles',
                'name_fr' => 'Gestion de parc de véhicules et véhicules lourds',
            ),
            94 => 
            array (
                'id' => '01hdkrfhmb6bw39qkw82z9n5t0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fleet Optimization',
                'name_fr' => 'Optimisation du parc automobile',
            ),
            95 => 
            array (
                'id' => '01hdkrfhmcwvtrfmgwry4a9xwp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Floods',
                'name_fr' => 'Inondation',
            ),
            96 => 
            array (
                'id' => '01hdkrfhmcwvtrfmgwry4a9xwq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fluorides',
                'name_fr' => 'Fluorure',
            ),
            97 => 
            array (
                'id' => '01hdkrfhmd0fx3m57zjrq9rc21',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fly Wheels',
                'name_fr' => 'Volants d’inertie',
            ),
            98 => 
            array (
                'id' => '01hdkrfhmd0fx3m57zjrq9rc22',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Food',
                'name_fr' => 'Alimentaire',
            ),
            99 => 
            array (
                'id' => '01hdkrfhmem5mxhmj765stb8kp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Food and Nutrition',
                'name_fr' => 'Aliments et nutrition',
            ),
            100 => 
            array (
                'id' => '01hdkrfhmem5mxhmj765stb8kq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Food biochemistry',
                'name_fr' => 'Biochimie alimentaire',
            ),
            101 => 
            array (
                'id' => '01hdkrfhmfjmedc4j6pdaq34fh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Food Borne Chemical Contaminants',
                'name_fr' => 'Contaminants chimiques d&#039;origine alimentaire',
            ),
            102 => 
            array (
                'id' => '01hdkrfhmgfqxef7w981y8njg1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Food Borne Pathogens',
                'name_fr' => 'Pathogènes d&#039;origine alimentaire',
            ),
            103 => 
            array (
                'id' => '01hdkrfhmgfqxef7w981y8njg2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Food chemical safety',
                'name_fr' => 'Sécurité chimique des aliments',
            ),
            104 => 
            array (
                'id' => '01hdkrfhmh0snt966f9cs849mw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Food microbiology',
                'name_fr' => 'Microbiologie des aliments',
            ),
            105 => 
            array (
                'id' => '01hdkrfhmh0snt966f9cs849mx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Food process',
                'name_fr' => 'Procédés alimentaires',
            ),
            106 => 
            array (
                'id' => '01hdkrfhmh0snt966f9cs849my',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Food Quality',
                'name_fr' => 'Qualité des aliments',
            ),
            107 => 
            array (
                'id' => '01hdkrfhmjxdwdqrgzyzhg5fyy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Food Safety',
                'name_fr' => 'Salubrité alimentaire',
            ),
            108 => 
            array (
                'id' => '01hdkrfhmjxdwdqrgzyzhg5fyz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Food Science',
                'name_fr' => 'Science des aliments',
            ),
            109 => 
            array (
                'id' => '01hdkrfhmkqazq1v7pzbne2nwm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Food web transfer',
                'name_fr' => 'Transfert trophique',
            ),
            110 => 
            array (
                'id' => '01hdkrfhmkqazq1v7pzbne2nwn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Food Webs',
                'name_fr' => 'Réseaux trophiques',
            ),
            111 => 
            array (
                'id' => '01hdkrfhmm0az71g2yksj2jp7j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Footprint Reduction',
                'name_fr' => 'Réduction de l&#039;empreinte',
            ),
            112 => 
            array (
                'id' => '01hdkrfhmm0az71g2yksj2jp7k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forage',
                'name_fr' => 'Plantes fourragères',
            ),
            113 => 
            array (
                'id' => '01hdkrfhmn78kf0cdcmr54df1g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forage diseases',
                'name_fr' => 'Maladies des plantes fourragères',
            ),
            114 => 
            array (
                'id' => '01hdkrfhmn78kf0cdcmr54df1h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forage grasses',
                'name_fr' => 'Des herbes fourragères',
            ),
            115 => 
            array (
                'id' => '01hdkrfhmn78kf0cdcmr54df1j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forages',
                'name_fr' => 'Les fourrages',
            ),
            116 => 
            array (
                'id' => '01hdkrfhmp1wyas5t04hc55091',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forecasting',
                'name_fr' => 'Prévision',
            ),
            117 => 
            array (
                'id' => '01hdkrfhmp1wyas5t04hc55092',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forensics',
                'name_fr' => 'Aspect judiciaire',
            ),
            118 => 
            array (
                'id' => '01hdkrfhmqzq3cxzgz9rvrgn6y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forensics pollution tracking',
                'name_fr' => 'Suivi judiciaire de la pollution',
            ),
            119 => 
            array (
                'id' => '01hdkrfhmqzq3cxzgz9rvrgn6z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest Assessment',
                'name_fr' => 'Évalutation des ressources forestières',
            ),
            120 => 
            array (
                'id' => '01hdkrfhmr8y35p21ftkjbmb95',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest Based Communities',
                'name_fr' => 'Communautés forestières',
            ),
            121 => 
            array (
                'id' => '01hdkrfhmr8y35p21ftkjbmb96',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest Carbon',
                'name_fr' => 'Carbone forestier',
            ),
            122 => 
            array (
                'id' => '01hdkrfhms1s9ztvnzdn2k703q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest dynamics',
                'name_fr' => 'Dynamique forestière',
            ),
            123 => 
            array (
                'id' => '01hdkrfhms1s9ztvnzdn2k703r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest ecological classification systems',
                'name_fr' => 'Forest ecological classification systems',
            ),
            124 => 
            array (
                'id' => '01hdkrfhmt0vd0jrym0hbtd62t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest ecology',
                'name_fr' => 'Écologie des forêts',
            ),
            125 => 
            array (
                'id' => '01hdkrfhmt0vd0jrym0hbtd62v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest Ecosystem Integrity',
                'name_fr' => 'Intégrité des écosytèmes forestiers',
            ),
            126 => 
            array (
                'id' => '01hdkrfhmt0vd0jrym0hbtd62w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest fire',
                'name_fr' => 'Incendie de forêt',
            ),
            127 => 
            array (
                'id' => '01hdkrfhmvd08jvfn84qn01rv1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest Fires',
                'name_fr' => 'Feux de forêt',
            ),
            128 => 
            array (
                'id' => '01hdkrfhmvd08jvfn84qn01rv2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest genetics',
                'name_fr' => 'Génétique forestière',
            ),
            129 => 
            array (
                'id' => '01hdkrfhmww10bkxy2m7a5a5fm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest Industry',
                'name_fr' => 'Industrie forestière',
            ),
            130 => 
            array (
                'id' => '01hdkrfhmww10bkxy2m7a5a5fn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest invasive alien species',
                'name_fr' => 'Espèces exotiques envahissantes des forêts',
            ),
            131 => 
            array (
                'id' => '01hdkrfhmxatjh974hjd0cz5ky',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest Management',
                'name_fr' => 'Aménagement forestier',
            ),
            132 => 
            array (
                'id' => '01hdkrfhmxatjh974hjd0cz5kz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest mensuration',
                'name_fr' => 'Dendrométrie',
            ),
            133 => 
            array (
                'id' => '01hdkrfhmy8ct209tthyg80s65',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest modeling',
                'name_fr' => 'Modélisation de forêts',
            ),
            134 => 
            array (
                'id' => '01hdkrfhmzkn8am4afsvn86v08',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest Products',
                'name_fr' => 'Produits forestiers',
            ),
            135 => 
            array (
                'id' => '01hdkrfhmzkn8am4afsvn86v09',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forest Tenure',
                'name_fr' => 'Tenure forestière',
            ),
            136 => 
            array (
                'id' => '01hdkrfhn0n5mxg3gfgf7wa7ar',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forestry',
                'name_fr' => 'Foresterie',
            ),
            137 => 
            array (
                'id' => '01hdkrfhn0n5mxg3gfgf7wa7as',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forests',
                'name_fr' => 'Forêts',
            ),
            138 => 
            array (
                'id' => '01hdkrfhn1pjh3fck944sk5hm5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forming',
                'name_fr' => 'Formage',
            ),
            139 => 
            array (
                'id' => '01hdkrfhn1pjh3fck944sk5hm6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Forming and Molding Processes',
                'name_fr' => 'Processus de formage et de moulage',
            ),
            140 => 
            array (
                'id' => '01hdkrfhn2nv6jms4b9e8jbgt5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fortification',
                'name_fr' => 'Fortification/Enrichissement',
            ),
            141 => 
            array (
                'id' => '01hdkrfhn2nv6jms4b9e8jbgt6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fossil Fuels',
                'name_fr' => 'Combustibles fossiles',
            ),
            142 => 
            array (
                'id' => '01hdkrfhn2nv6jms4b9e8jbgt7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fraser River-Georgia Basin',
                'name_fr' => 'Fleuve Fraser - bassin de Géorgie',
            ),
            143 => 
            array (
                'id' => '01hdkrfhn3a4k5rwh7nten7191',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Freshwater',
                'name_fr' => 'Eau douce',
            ),
            144 => 
            array (
                'id' => '01hdkrfhn3a4k5rwh7nten7192',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Freshwater Lakes',
                'name_fr' => 'Lacs d&#039;eau douce',
            ),
            145 => 
            array (
                'id' => '01hdkrfhn46p7jxb4g64hgkf92',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Friction stir welding',
                'name_fr' => 'Soudage par friction-malaxage',
            ),
            146 => 
            array (
                'id' => '01hdkrfhn46p7jxb4g64hgkf93',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Froth',
                'name_fr' => 'Mousse',
            ),
            147 => 
            array (
                'id' => '01hdkrfhn57g59jms78x3jjweh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fruit',
                'name_fr' => 'Fruits',
            ),
            148 => 
            array (
                'id' => '01hdkrfhn57g59jms78x3jjwej',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fuel Cells',
                'name_fr' => 'Piles à combustible',
            ),
            149 => 
            array (
                'id' => '01hdkrfhn6f5b7bjpyg4w9yna2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fuel consumption',
                'name_fr' => 'Consommation de carburant',
            ),
            150 => 
            array (
                'id' => '01hdkrfhn6f5b7bjpyg4w9yna3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fuelling Infrastructure',
                'name_fr' => 'Infrastructure de ravitaillement',
            ),
            151 => 
            array (
                'id' => '01hdkrfhn6f5b7bjpyg4w9yna4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Functional',
                'name_fr' => 'Fonctionnelle',
            ),
            152 => 
            array (
                'id' => '01hdkrfhn7p0gy8pkz10yve55t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Functional ingredients',
                'name_fr' => 'Ingrédients fonctionnels',
            ),
            153 => 
            array (
                'id' => '01hdkrfhn7p0gy8pkz10yve55v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fungal',
                'name_fr' => 'Champignons',
            ),
            154 => 
            array (
                'id' => '01hdkrfhn8pvsw0kz5s9s5wj9j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Fusarium head blight',
                'name_fr' => 'Fusariose de l’épi',
            ),
            155 => 
            array (
                'id' => '01hdkrfhn8pvsw0kz5s9s5wj9k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Future Fuels',
                'name_fr' => 'Carburants de l&#039;avenir',
            ),
            156 => 
            array (
                'id' => '01hdkrfhn9wj0zphk77fptfcaa',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Garden Soil',
                'name_fr' => 'Terre de jardin',
            ),
            157 => 
            array (
                'id' => '01hdkrfhn9wj0zphk77fptfcab',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Gas Hydrates',
                'name_fr' => 'Les hydrates de gaz',
            ),
            158 => 
            array (
                'id' => '01hdkrfhnaveme4ycwxyrbyv5j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Gas industry',
                'name_fr' => 'Industrie gazière',
            ),
            159 => 
            array (
                'id' => '01hdkrfhnaveme4ycwxyrbyv5k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Genetic diversity',
                'name_fr' => 'Diversité génétique',
            ),
            160 => 
            array (
                'id' => '01hdkrfhnbey688tr97nek1x29',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Genetic Engineering',
                'name_fr' => 'Génie génétique',
            ),
            161 => 
            array (
                'id' => '01hdkrfhnbey688tr97nek1x2a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Genetic Toxicology',
                'name_fr' => 'Toxicologie génétique',
            ),
            162 => 
            array (
                'id' => '01hdkrfhnc4vygy2gqzw2ws5nj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Genetically Modified Foods',
                'name_fr' => 'Aliment génétiquement modifié',
            ),
            163 => 
            array (
                'id' => '01hdkrfhnc4vygy2gqzw2ws5nk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Genetics',
                'name_fr' => 'Génétique',
            ),
            164 => 
            array (
                'id' => '01hdkrfhnc4vygy2gqzw2ws5nm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Genomics',
                'name_fr' => 'Génomique',
            ),
            165 => 
            array (
                'id' => '01hdkrfhnd8tvgsyxc0evpf938',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Genomics techniques',
                'name_fr' => 'Techniques génomiques',
            ),
            166 => 
            array (
                'id' => '01hdkrfhnd8tvgsyxc0evpf939',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geochemistry',
                'name_fr' => 'Géochimie',
            ),
            167 => 
            array (
                'id' => '01hdkrfhnepe7d5v7s8eawaxvb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geochemistry - Inorganic',
                'name_fr' => 'Géochimie - inorganique',
            ),
            168 => 
            array (
                'id' => '01hdkrfhnfghxtkm5qg55j0rxy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geochemistry - Organic',
                'name_fr' => 'Géochimie - organique',
            ),
            169 => 
            array (
                'id' => '01hdkrfhnfghxtkm5qg55j0rxz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geochronology',
                'name_fr' => 'Géochronologie',
            ),
            170 => 
            array (
                'id' => '01hdkrfhngdjwfbp5k469y63ke',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geodesy',
                'name_fr' => 'Géodésie',
            ),
            171 => 
            array (
                'id' => '01hdkrfhngdjwfbp5k469y63kf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geodetic Reference Systems',
                'name_fr' => 'Systèmes de réference géodésiques',
            ),
            172 => 
            array (
                'id' => '01hdkrfhnhyqtmwcy8nw6w026n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geographic data',
                'name_fr' => 'Données géographiques',
            ),
            173 => 
            array (
                'id' => '01hdkrfhnhyqtmwcy8nw6w026p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Geographic Information System (GIS)',
            'name_fr' => 'Système d&#039;information géographique (SIG)',
            ),
            174 => 
            array (
                'id' => '01hdkrfhnjtschts5r8gg7s3yb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geographic information systems',
                'name_fr' => 'Système d&#039;information géographique',
            ),
            175 => 
            array (
                'id' => '01hdkrfhnjtschts5r8gg7s3yc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geographical Names',
                'name_fr' => 'Toponymes',
            ),
            176 => 
            array (
                'id' => '01hdkrfhnkefqdj3ptpksemc6q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geography',
                'name_fr' => 'Géographie',
            ),
            177 => 
            array (
                'id' => '01hdkrfhnkefqdj3ptpksemc6r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geological',
                'name_fr' => 'Géologique',
            ),
            178 => 
            array (
                'id' => '01hdkrfhnmqhrbsc3vy39z1074',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geological mapping',
                'name_fr' => 'Cartographie géologique',
            ),
            179 => 
            array (
                'id' => '01hdkrfhnmqhrbsc3vy39z1075',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geology',
                'name_fr' => 'Géologie',
            ),
            180 => 
            array (
                'id' => '01hdkrfhnmqhrbsc3vy39z1076',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geology - Bedrock',
                'name_fr' => 'Géologie du substratum',
            ),
            181 => 
            array (
                'id' => '01hdkrfhnnw0qd4qygq2nvspdv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geology - Marine',
                'name_fr' => 'Géologie - marine',
            ),
            182 => 
            array (
                'id' => '01hdkrfhnnw0qd4qygq2nvspdw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geology - Structural',
                'name_fr' => 'Géologie - structurale',
            ),
            183 => 
            array (
                'id' => '01hdkrfhnppcn113edqcw5h3th',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geology - Surficial',
                'name_fr' => 'Géologie de surface',
            ),
            184 => 
            array (
                'id' => '01hdkrfhnppcn113edqcw5h3tj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geomatic',
                'name_fr' => 'Géomatique',
            ),
            185 => 
            array (
                'id' => '01hdkrfhnq6k4c03mgvbm81k62',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geomorphology',
                'name_fr' => 'Géomorphologie',
            ),
            186 => 
            array (
                'id' => '01hdkrfhnq6k4c03mgvbm81k63',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geophysics',
                'name_fr' => 'Géophysique',
            ),
            187 => 
            array (
                'id' => '01hdkrfhnrbg721gaknk1a29w1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Georgia Basin',
                'name_fr' => 'Bassin de Georgia',
            ),
            188 => 
            array (
                'id' => '01hdkrfhnrbg721gaknk1a29w2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geoscience',
                'name_fr' => 'Géoscience',
            ),
            189 => 
            array (
                'id' => '01hdkrfhnsjs5bh2tw4kg1xxpa',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geoscience - Marine',
                'name_fr' => 'Géoscience - marine',
            ),
            190 => 
            array (
                'id' => '01hdkrfhnsjs5bh2tw4kg1xxpb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geospatial Analysis',
                'name_fr' => 'Analyse géospatiale',
            ),
            191 => 
            array (
                'id' => '01hdkrfhnsjs5bh2tw4kg1xxpc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Geospatial Data Management',
                'name_fr' => 'Gestion de données géospatiales',
            ),
            192 => 
            array (
                'id' => '01hdkrfhntge7zqhjxpcr8pmte',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'GIS - Geographical Info System',
                'name_fr' => 'SIG - Système d&#039;info géographique',
            ),
            193 => 
            array (
                'id' => '01hdkrfhntge7zqhjxpcr8pmtf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'GIS Technician',
                'name_fr' => 'Technicien du SIG',
            ),
            194 => 
            array (
                'id' => '01hdkrfhnveqvz0a9j5vcqggjb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Glaciers',
                'name_fr' => 'Glaciers',
            ),
            195 => 
            array (
                'id' => '01hdkrfhnveqvz0a9j5vcqggjc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Global Navigation Satellite Systems (GNSS)',
            'name_fr' => 'Systèmes globaux de radiolocalisation par satellite (GNSS)',
            ),
            196 => 
            array (
                'id' => '01hdkrfhnwx8zxhtf82eeeqaeq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Governance and Infrastructure Support to First Nations and Inuit Health System',
                'name_fr' => 'Gouvernance et soutien à l&#039;infrastructure pour le système de Santé des Premières nations et des Inuits',
            ),
            197 => 
            array (
                'id' => '01hdkrfhnwx8zxhtf82eeeqaer',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Government and Management Support',
                'name_fr' => 'Appui à la gouvernance et à la gestion',
            ),
            198 => 
            array (
                'id' => '01hdkrfhnxbzj1dppmqfnfhp57',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'GPS',
                'name_fr' => 'GPS',
            ),
            199 => 
            array (
                'id' => '01hdkrfhnxbzj1dppmqfnfhp58',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'GPS radio-occultation',
                'name_fr' => 'Radio-occultation GPS',
            ),
            200 => 
            array (
                'id' => '01hdkrfhnyscadd3zhmh4w2ryp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'GPU',
            'name_fr' => 'Unité de traitement graphique (UTG)',
            ),
            201 => 
            array (
                'id' => '01hdkrfhnyscadd3zhmh4w2ryq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Grain quality',
                'name_fr' => 'Qualité des grains',
            ),
            202 => 
            array (
                'id' => '01hdkrfhnzdt42r1cw2gyfdx65',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Grain technology',
                'name_fr' => 'Technologie céréalière',
            ),
            203 => 
            array (
                'id' => '01hdkrfhnzdt42r1cw2gyfdx66',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Grand Banks of Newfoundland',
                'name_fr' => 'Grands Bancs de Terre-Neuve',
            ),
            204 => 
            array (
                'id' => '01hdkrfhp0ya5q46k5pgc2rdck',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Grapes, plant virus vectors',
                'name_fr' => 'Raisins, vecteurs des phytovirus',
            ),
            205 => 
            array (
                'id' => '01hdkrfhp1fe87y6hvfz1azmd0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Grassland birds',
                'name_fr' => 'Oiseaux des prairies',
            ),
            206 => 
            array (
                'id' => '01hdkrfhp1fe87y6hvfz1azmd1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Grasslands',
                'name_fr' => 'Surfaces en herbe',
            ),
            207 => 
            array (
                'id' => '01hdkrfhp2e57rme4z9dzcwxm2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Grazing',
                'name_fr' => 'Pâturage',
            ),
            208 => 
            array (
                'id' => '01hdkrfhp2e57rme4z9dzcwxm3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Grazing management',
                'name_fr' => 'Gestion des pâturages',
            ),
            209 => 
            array (
                'id' => '01hdkrfhp2e57rme4z9dzcwxm4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Great Lakes',
                'name_fr' => 'Grands Lacs',
            ),
            210 => 
            array (
                'id' => '01hdkrfhp3j60cxw4wsc4we51w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Great Lakes Water Quality Agreement',
                'name_fr' => 'Accord relatif à la qualité de l&#039;eau dans les Grands Lacs',
            ),
            211 => 
            array (
                'id' => '01hdkrfhp3j60cxw4wsc4we51x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Great Lakes-St. Lawrence',
                'name_fr' => 'Grands Lacs - St-Laurent',
            ),
            212 => 
            array (
                'id' => '01hdkrfhp4vnp6kjhm3zf6sk18',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Green roof technology',
                'name_fr' => 'Technologie de toits verts',
            ),
            213 => 
            array (
                'id' => '01hdkrfhp4vnp6kjhm3zf6sk19',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Green technologies',
                'name_fr' => 'Technologies vertes',
            ),
            214 => 
            array (
                'id' => '01hdkrfhp5k7ttx3pkt0dz0dpg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Greenhouse',
                'name_fr' => 'L’environnement serricole',
            ),
            215 => 
            array (
                'id' => '01hdkrfhp5k7ttx3pkt0dz0dph',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Greenhouse and vegetable',
                'name_fr' => 'Serriculture et des légumes',
            ),
            216 => 
            array (
                'id' => '01hdkrfhp65ctwetwzar7d67gs',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Greenhouse crop',
                'name_fr' => 'L&#039;environnement serricole',
            ),
            217 => 
            array (
                'id' => '01hdkrfhp65ctwetwzar7d67gt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Greenhouse Gas (GHG) Reduction',
            'name_fr' => 'Réduction des gaz à effet de serre (GES)',
            ),
            218 => 
            array (
                'id' => '01hdkrfhp7t51tft6aebwx02cp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Greenhouse gases',
                'name_fr' => 'Gaz à effet de serre',
            ),
            219 => 
            array (
                'id' => '01hdkrfhp7t51tft6aebwx02cq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Grid Integration',
                'name_fr' => 'Intégration au réseau',
            ),
            220 => 
            array (
                'id' => '01hdkrfhp8x5b94vv8v80ynbr4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ground beetle',
                'name_fr' => 'Des carabes',
            ),
            221 => 
            array (
                'id' => '01hdkrfhp8x5b94vv8v80ynbr5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ground Control',
                'name_fr' => 'Contrôle du sol',
            ),
            222 => 
            array (
                'id' => '01hdkrfhp9n5r4zzvn470nhpnf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Groundwater',
                'name_fr' => 'Eaux souterraines',
            ),
            223 => 
            array (
                'id' => '01hdkrfhp9n5r4zzvn470nhpng',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Groundwater science',
                'name_fr' => 'Science des eaux souterraines',
            ),
            224 => 
            array (
                'id' => '01hdkrfhp9n5r4zzvn470nhpnh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Gulf of Maine',
                'name_fr' => 'Golfe du Maine',
            ),
            225 => 
            array (
                'id' => '01hdkrfhpakm1tznzkv31a0btt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Gulf of St. Lawrence',
                'name_fr' => 'Golfe du St-Laurent',
            ),
            226 => 
            array (
                'id' => '01hdkrfhpakm1tznzkv31a0btv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Gulfwatch',
                'name_fr' => 'Surveillance de golfe',
            ),
            227 => 
            array (
                'id' => '01hdkrfhpbkd38d5anntm9g29h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Habitat',
                'name_fr' => 'Habitat',
            ),
            228 => 
            array (
                'id' => '01hdkrfhpbkd38d5anntm9g29j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Habitat replenishment',
                'name_fr' => 'Reconstitution des habitats',
            ),
            229 => 
            array (
                'id' => '01hdkrfhpcgb504rxg8ec48t1g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Habitat selection',
                'name_fr' => 'Sélection de l&#039;habitat',
            ),
            230 => 
            array (
                'id' => '01hdkrfhpcgb504rxg8ec48t1h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Haida Gwaii',
                'name_fr' => 'Haida Gwaii',
            ),
            231 => 
            array (
                'id' => '01hdkrfhpdeebv526px60w1nam',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Haptics',
                'name_fr' => 'Haptiques',
            ),
            232 => 
            array (
                'id' => '01hdkrfhpdeebv526px60w1nan',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hardware',
                'name_fr' => 'Matériel',
            ),
            233 => 
            array (
                'id' => '01hdkrfhpdeebv526px60w1nap',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Harvesting',
                'name_fr' => 'Récolte',
            ),
            234 => 
            array (
                'id' => '01hdkrfhpehht1pe977d0j32y9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hazard Identification',
                'name_fr' => 'Détermination des dangers',
            ),
            235 => 
            array (
                'id' => '01hdkrfhpf4n63hckq9yna0wgw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Health',
                'name_fr' => 'Santé',
            ),
            236 => 
            array (
                'id' => '01hdkrfhpf4n63hckq9yna0wgx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Health and Wellness',
                'name_fr' => 'Santé et bien-être animal',
            ),
            237 => 
            array (
                'id' => '01hdkrfhpga43cr82gsaapy53z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Health Behaviour',
                'name_fr' => 'Comportements de santé',
            ),
            238 => 
            array (
                'id' => '01hdkrfhpga43cr82gsaapy540',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Health Benefits',
                'name_fr' => 'Effets positifs sur la santé',
            ),
            239 => 
            array (
                'id' => '01hdkrfhphxjz81yterraqa76f',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Health Information',
                'name_fr' => 'Information sur la santé',
            ),
            240 => 
            array (
                'id' => '01hdkrfhphxjz81yterraqa76g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Health physics',
                'name_fr' => 'Radioprotection',
            ),
            241 => 
            array (
                'id' => '01hdkrfhpjqcszncbd718d07ps',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Health Products',
                'name_fr' => 'Produits de santé',
            ),
            242 => 
            array (
                'id' => '01hdkrfhpjqcszncbd718d07pt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Health promotion',
                'name_fr' => 'Promotion de la santé',
            ),
            243 => 
            array (
                'id' => '01hdkrfhpkd3x6wbrztz0xt145',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Health Sciences',
                'name_fr' => 'Sciences de la santé',
            ),
            244 => 
            array (
                'id' => '01hdkrfhpkd3x6wbrztz0xt146',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Health System Renewal',
                'name_fr' => 'Renouvellement du système de santé',
            ),
            245 => 
            array (
                'id' => '01hdkrfhpmfx8krj9924w3014t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Heat Transfer',
                'name_fr' => 'Transfert de chaleur',
            ),
            246 => 
            array (
                'id' => '01hdkrfhpmfx8krj9924w3014v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Heating, Ventilation and Air Conditioning (HVAC)',
            'name_fr' => 'Chauffage, ventilation et climatisation (CVC)',
            ),
            247 => 
            array (
                'id' => '01hdkrfhpn6878tvetyn5cenkm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Heavy metals',
                'name_fr' => 'Métaux lourds',
            ),
            248 => 
            array (
                'id' => '01hdkrfhpn6878tvetyn5cenkn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Heavy Vehicle Engineering and testing',
                'name_fr' => 'Ingénierie et essais de véhicules lourds',
            ),
            249 => 
            array (
                'id' => '01hdkrfhppqwnx77d0g60gm90n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Helminth',
                'name_fr' => 'Helminthes',
            ),
            250 => 
            array (
                'id' => '01hdkrfhppqwnx77d0g60gm90p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hemp',
                'name_fr' => 'Du chanvre',
            ),
            251 => 
            array (
                'id' => '01hdkrfhpqdy1vkasbg0zyjs4g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Herbicide-resistant plants',
                'name_fr' => 'Plantes résistant aux herbicides',
            ),
            252 => 
            array (
                'id' => '01hdkrfhpqdy1vkasbg0zyjs4h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Herbicides',
                'name_fr' => 'Herbicides',
            ),
            253 => 
            array (
                'id' => '01hdkrfhpqdy1vkasbg0zyjs4j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Herpetofauna',
                'name_fr' => 'Herpétofaune',
            ),
            254 => 
            array (
                'id' => '01hdkrfhprr502z7bgj0z91b8k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'High performance rail',
                'name_fr' => 'Transport ferroviaire haute performance',
            ),
            255 => 
            array (
                'id' => '01hdkrfhprr502z7bgj0z91b8m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'HiPrOx Power Systems',
                'name_fr' => 'Réseau énergétique HiPrOx',
            ),
            256 => 
            array (
                'id' => '01hdkrfhpsj10bg87h7vqb2g7w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Horizontal gene transfer',
                'name_fr' => 'Transfert horizontal de gènes',
            ),
            257 => 
            array (
                'id' => '01hdkrfhpsj10bg87h7vqb2g7x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Horizontal Reference Systems',
                'name_fr' => 'Systèmes de référence horizontaux',
            ),
            258 => 
            array (
                'id' => '01hdkrfhpt4c0bcg58am7jft6a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hormone Profiling',
                'name_fr' => 'Profilage hormonal',
            ),
            259 => 
            array (
                'id' => '01hdkrfhpt4c0bcg58am7jft6b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Horticulture',
                'name_fr' => 'Horticulture',
            ),
            260 => 
            array (
                'id' => '01hdkrfhpv6fg0j4bkk11kcg0t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Host plant resistance',
                'name_fr' => 'Résistance des plantes hôtes',
            ),
            261 => 
            array (
                'id' => '01hdkrfhpv6fg0j4bkk11kcg0v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'House Dust',
                'name_fr' => 'Poussière domestique',
            ),
            262 => 
            array (
                'id' => '01hdkrfhpwrd9evz3n54a0acvt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Housing research',
                'name_fr' => 'Recherche sur l’habitation',
            ),
            263 => 
            array (
                'id' => '01hdkrfhpwrd9evz3n54a0acvv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hudson Bay',
                'name_fr' => 'Baie d&#039;Hudson',
            ),
            264 => 
            array (
                'id' => '01hdkrfhpxwh0373z95hq9370c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hudson Strait',
                'name_fr' => 'Détroit d&#039;Hudson',
            ),
            265 => 
            array (
                'id' => '01hdkrfhpy0fhtfkrk1s58kmg0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hudson-James Bay',
                'name_fr' => 'Baie d&#039;Hudson - Baie James',
            ),
            266 => 
            array (
                'id' => '01hdkrfhpy0fhtfkrk1s58kmg1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Human',
                'name_fr' => 'Humaine',
            ),
            267 => 
            array (
                'id' => '01hdkrfhpzy17aqt46a5p3264e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Human Exposure Research',
                'name_fr' => 'Recherche sur l&#039;exposition humaine',
            ),
            268 => 
            array (
                'id' => '01hdkrfhq0xtjpjk7d7d1h7xyr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Human Health Risk Assessment',
                'name_fr' => 'Évaluation des risques pour la santé humaine',
            ),
            269 => 
            array (
                'id' => '01hdkrfhq0xtjpjk7d7d1h7xys',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Human impacts',
                'name_fr' => 'Incidence de l&#039;homme',
            ),
            270 => 
            array (
                'id' => '01hdkrfhq1htz2x6w2xcha5hwc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Human resources',
                'name_fr' => 'Ressources humaines',
            ),
            271 => 
            array (
                'id' => '01hdkrfhq2k3awdhfh6tb0e3zk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Human Resources Management',
                'name_fr' => 'Gestion des ressources humaines',
            ),
            272 => 
            array (
                'id' => '01hdkrfhq2k3awdhfh6tb0e3zm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Human-computer interaction',
                'name_fr' => 'Interaction personne-machine',
            ),
            273 => 
            array (
                'id' => '01hdkrfhq2k3awdhfh6tb0e3zn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Human-machine interactivity',
                'name_fr' => 'Interactivité homme-machine',
            ),
            274 => 
            array (
                'id' => '01hdkrfhq3jwfp3dfw6sxwe92a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hurricanes',
                'name_fr' => 'Ourogan',
            ),
            275 => 
            array (
                'id' => '01hdkrfhq3jwfp3dfw6sxwe92b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hydroacoustic',
                'name_fr' => 'Hydroacoustique',
            ),
            276 => 
            array (
                'id' => '01hdkrfhq3jwfp3dfw6sxwe92c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hydrobiology',
                'name_fr' => 'Hydrobiologie',
            ),
            277 => 
            array (
                'id' => '01hdkrfhq4gdnen50yb5gs9rk8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hydrocarbons',
                'name_fr' => 'Hydrocarbures',
            ),
            278 => 
            array (
                'id' => '01hdkrfhq4gdnen50yb5gs9rk9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hydroelectric plants',
                'name_fr' => 'Centrale hydroélectrique',
            ),
            279 => 
            array (
                'id' => '01hdkrfhq530jdn1fqcbh68x02',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hydrogen',
                'name_fr' => 'Hydrogène',
            ),
            280 => 
            array (
                'id' => '01hdkrfhq530jdn1fqcbh68x03',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hydrogeology',
                'name_fr' => 'Hydrogéologie',
            ),
            281 => 
            array (
                'id' => '01hdkrfhq530jdn1fqcbh68x04',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hydrography',
                'name_fr' => 'Hydrographie',
            ),
            282 => 
            array (
                'id' => '01hdkrfhq6d97s6tf1sxva40cy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hydrography and Seabed Mapping',
                'name_fr' => 'Hydrographie et cartographie du fond marin',
            ),
            283 => 
            array (
                'id' => '01hdkrfhq6d97s6tf1sxva40cz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hydrological',
                'name_fr' => 'Ressources hydrologiques',
            ),
            284 => 
            array (
                'id' => '01hdkrfhq78qjm8ste4v3ndz2x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hydrology',
                'name_fr' => 'Hydrologie',
            ),
            285 => 
            array (
                'id' => '01hdkrfhq78qjm8ste4v3ndz2y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Hydrostratigraphy',
                'name_fr' => 'Hydrostratigraphie',
            ),
            286 => 
            array (
                'id' => '01hdkrfhq8wg0vbz81vmnspa08',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ice',
                'name_fr' => 'Glace',
            ),
            287 => 
            array (
                'id' => '01hdkrfhq8wg0vbz81vmnspa09',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ice jams',
                'name_fr' => 'Embâcles',
            ),
            288 => 
            array (
                'id' => '01hdkrfhq92d9xy978269p60kf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ice mechanics',
                'name_fr' => 'Mécanique des glaces',
            ),
            289 => 
            array (
                'id' => '01hdkrfhq92d9xy978269p60kg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Iceberg',
                'name_fr' => 'Iceberg',
            ),
            290 => 
            array (
                'id' => '01hdkrfhqaevtz6nz7gch5z1ws',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Ichneumonid (parasitic) wasp',
                'name_fr' => 'Des ichneumonidés parasitoïdes',
            ),
            291 => 
            array (
                'id' => '01hdkrfhqaevtz6nz7gch5z1wt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Icing / deicing',
                'name_fr' => 'Givrage / dégivrage',
            ),
            292 => 
            array (
                'id' => '01hdkrfhqbwj0vm250fxnn1rvn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Illegal Logging',
                'name_fr' => 'Exploitation illégale',
            ),
            293 => 
            array (
                'id' => '01hdkrfhqbwj0vm250fxnn1rvp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Image Analytics',
                'name_fr' => 'Analyse de l&#039;image',
            ),
            294 => 
            array (
                'id' => '01hdkrfhqc8nfs4h353ag6f38b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Immunology',
                'name_fr' => 'Immunologie',
            ),
            295 => 
            array (
                'id' => '01hdkrfhqc8nfs4h353ag6f38c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Immunotoxicology',
                'name_fr' => 'Immunotoxicologie',
            ),
            296 => 
            array (
                'id' => '01hdkrfhqc8nfs4h353ag6f38d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Impacts of urbanization',
                'name_fr' => 'Incidence de l&#039;urbanisation',
            ),
            297 => 
            array (
                'id' => '01hdkrfhqdybgceaxvmfc9ap4t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Implants',
                'name_fr' => 'Implants',
            ),
            298 => 
            array (
                'id' => '01hdkrfhqdybgceaxvmfc9ap4v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'In-vitro digestion',
                'name_fr' => 'Digestion In Vitro',
            ),
            299 => 
            array (
                'id' => '01hdkrfhqeadw786bym6dnvjgr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Indigenous collaborations',
                'name_fr' => 'Collaborations autochtones',
            ),
            300 => 
            array (
                'id' => '01hdkrfhqeadw786bym6dnvjgs',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Indoor Air',
                'name_fr' => 'Air intérieur',
            ),
            301 => 
            array (
                'id' => '01hdkrfhqf5bjddb3j3qtv6w0b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Indoor air quality',
                'name_fr' => 'Qualité de l&#039;air intérieur',
            ),
            302 => 
            array (
                'id' => '01hdkrfhqf5bjddb3j3qtv6w0c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Industrial',
                'name_fr' => 'Industrielle',
            ),
            303 => 
            array (
                'id' => '01hdkrfhqgjqacm6t428zy5gce',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Industrial Biotechnology',
                'name_fr' => 'Biotechnologies industrielles',
            ),
            304 => 
            array (
                'id' => '01hdkrfhqgjqacm6t428zy5gcf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Industrial Designs',
                'name_fr' => 'Dessins industriels',
            ),
            305 => 
            array (
                'id' => '01hdkrfhqhzmqgnfddencp08sv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Industrial impacts',
                'name_fr' => 'Incidence de l&#039;industrie',
            ),
            306 => 
            array (
                'id' => '01hdkrfhqhzmqgnfddencp08sw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Industrial Research',
                'name_fr' => 'Recherche industrielle',
            ),
            307 => 
            array (
                'id' => '01hdkrfhqjwkx09we5g20x7p1j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Industrial technology',
                'name_fr' => 'Technologie industrielle',
            ),
            308 => 
            array (
                'id' => '01hdkrfhqkz04yedbsj205sd1g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Infections',
                'name_fr' => 'Infections',
            ),
            309 => 
            array (
                'id' => '01hdkrfhqkz04yedbsj205sd1h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Infectious diseases',
                'name_fr' => 'Maladie infectieuse',
            ),
            310 => 
            array (
                'id' => '01hdkrfhqmv7rc25hmj2hvsgwf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Information Management',
                'name_fr' => 'Gestion de l&#039;information',
            ),
            311 => 
            array (
                'id' => '01hdkrfhqmv7rc25hmj2hvsgwg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Information network',
                'name_fr' => 'Réseau d&#039;information',
            ),
            312 => 
            array (
                'id' => '01hdkrfhqmv7rc25hmj2hvsgwh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Information systems',
                'name_fr' => 'Système d&#039;information',
            ),
            313 => 
            array (
                'id' => '01hdkrfhqn2533vd88gf3ffvs2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Information Technology',
                'name_fr' => 'Technologie de l&#039;information',
            ),
            314 => 
            array (
                'id' => '01hdkrfhqn2533vd88gf3ffvs3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Infrastructure',
                'name_fr' => 'Infrastructure',
            ),
            315 => 
            array (
                'id' => '01hdkrfhqp2cqcmztt4c9dhf24',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Inhalation Exposures',
                'name_fr' => 'Exposition par inhalation',
            ),
            316 => 
            array (
                'id' => '01hdkrfhqp2cqcmztt4c9dhf25',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Inland waters',
                'name_fr' => 'Eaux intérieures',
            ),
            317 => 
            array (
                'id' => '01hdkrfhqqk5n2jeqhzhswww2e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Inorganic Chemistry',
                'name_fr' => 'Chimie inorganique',
            ),
            318 => 
            array (
                'id' => '01hdkrfhqqk5n2jeqhzhswww2f',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Inorganic contaminants',
                'name_fr' => 'Contaminants inorganiques',
            ),
            319 => 
            array (
                'id' => '01hdkrfhqrhjfeewvtjxrrb5pt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Insect',
                'name_fr' => 'Des insectes',
            ),
            320 => 
            array (
                'id' => '01hdkrfhqrhjfeewvtjxrrb5pv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Insect ecology',
                'name_fr' => 'Écologie des insectes',
            ),
            321 => 
            array (
                'id' => '01hdkrfhqsz8aspjf9zfmfaj06',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Insect molecular',
                'name_fr' => 'Moléculaire des insectes',
            ),
            322 => 
            array (
                'id' => '01hdkrfhqsz8aspjf9zfmfaj07',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Insect pathology',
                'name_fr' => 'Pathologie des insectes',
            ),
            323 => 
            array (
                'id' => '01hdkrfhqtcwrr42w384qwwqpk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Insect pest management',
                'name_fr' => 'Lutte contre les insectes nuisibles',
            ),
            324 => 
            array (
                'id' => '01hdkrfhqtcwrr42w384qwwqpm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Insect pests',
                'name_fr' => 'Les insectes nuisibles',
            ),
            325 => 
            array (
                'id' => '01hdkrfhqv8hvc084d4bpqfp4e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Insect-plant interactions',
                'name_fr' => 'Interactions plantes-insectes',
            ),
            326 => 
            array (
                'id' => '01hdkrfhqv8hvc084d4bpqfp4f',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Insecticide toxicology',
                'name_fr' => 'Toxicologie des insecticides',
            ),
            327 => 
            array (
                'id' => '01hdkrfhqv8hvc084d4bpqfp4g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Insects',
                'name_fr' => 'Insectes',
            ),
            328 => 
            array (
                'id' => '01hdkrfhqwmhwsjz8m3hqxgcv2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Insects - Baculovirus',
                'name_fr' => 'Insectes - Baculovirus',
            ),
            329 => 
            array (
                'id' => '01hdkrfhqwmhwsjz8m3hqxgcv3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Insects - Integrated pest management',
                'name_fr' => 'Insectes -  Lutte antiparasitaire intégrée',
            ),
            330 => 
            array (
                'id' => '01hdkrfhqxc1pb5rcmfhr9kc6r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Insects - Plant-insect interactions',
                'name_fr' => 'Insectes - Interactions plantes-insectes',
            ),
            331 => 
            array (
                'id' => '01hdkrfhqxc1pb5rcmfhr9kc6s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Instrumentation',
                'name_fr' => 'Instrumentation',
            ),
            332 => 
            array (
                'id' => '01hdkrfhqyb9sx3z5tqmz10b4k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Insulation',
                'name_fr' => 'Isolation thermique',
            ),
            333 => 
            array (
                'id' => '01hdkrfhqyb9sx3z5tqmz10b4m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Integrated biology (zoology)',
            'name_fr' => 'Biologie intégrée (zoologie)',
            ),
            334 => 
            array (
                'id' => '01hdkrfhqyb9sx3z5tqmz10b4n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Integrated circuit layout designs',
                'name_fr' => 'Schémas de configuration de circuits intégrés',
            ),
            335 => 
            array (
                'id' => '01hdkrfhqzktkcqk6tkvtzt2gy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Integrated management',
                'name_fr' => 'Gestion intégrée',
            ),
            336 => 
            array (
                'id' => '01hdkrfhqzktkcqk6tkvtzt2gz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Integrated Oceans Management Science',
                'name_fr' => 'Science de la gestion intégrée des océans',
            ),
            337 => 
            array (
                'id' => '01hdkrfhr0v7x66vcn54mfs3xe',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Integrated pest management',
                'name_fr' => 'Lutte antiparasitaire intégrée',
            ),
            338 => 
            array (
                'id' => '01hdkrfhr0v7x66vcn54mfs3xf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Integrated pest management – small fruits',
                'name_fr' => 'Lutte antiparasitaire intégrée – petits fruits',
            ),
            339 => 
            array (
                'id' => '01hdkrfhr1v7v635mk0k1fj538',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Integration',
                'name_fr' => 'Intégration',
            ),
            340 => 
            array (
                'id' => '01hdkrfhr1v7v635mk0k1fj539',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Integration Management',
                'name_fr' => 'Gestion de l&#039;intétration',
            ),
            341 => 
            array (
                'id' => '01hdkrfhr2gwn4p647a4jm3h1k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Intellectual Property',
                'name_fr' => 'Propriété intellectuelle',
            ),
            342 => 
            array (
                'id' => '01hdkrfhr2gwn4p647a4jm3h1m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Intelligent systems',
                'name_fr' => 'Système intelligent',
            ),
            343 => 
            array (
                'id' => '01hdkrfhr33a79qk08dggj70z4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Intelligent Transportation Systems',
                'name_fr' => 'Systèmes de transport intelligents',
            ),
            344 => 
            array (
                'id' => '01hdkrfhr4m9dnh01d4ce9tb6y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Interactive',
                'name_fr' => 'Interactive',
            ),
            345 => 
            array (
                'id' => '01hdkrfhr4m9dnh01d4ce9tb6z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Interactive AR/VR',
                'name_fr' => 'RV/RA Interactive',
            ),
            346 => 
            array (
                'id' => '01hdkrfhr4m9dnh01d4ce9tb70',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Interactive Simulation',
                'name_fr' => 'Simulation interactive',
            ),
            347 => 
            array (
                'id' => '01hdkrfhr52q2dzxppkchfmt3r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Interior Plains',
                'name_fr' => 'Plaines intérieures',
            ),
            348 => 
            array (
                'id' => '01hdkrfhr52q2dzxppkchfmt3s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Interior Platform',
                'name_fr' => 'Quai intérieure',
            ),
            349 => 
            array (
                'id' => '01hdkrfhr6s8bhk9jy4xmwarep',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Internal Services',
                'name_fr' => 'Services internes',
            ),
            350 => 
            array (
                'id' => '01hdkrfhr6s8bhk9jy4xmwareq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'International guidelines',
                'name_fr' => 'Lignes directrices internationales',
            ),
            351 => 
            array (
                'id' => '01hdkrfhr728vaahn56gkm1tcb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'International Health Affairs',
                'name_fr' => 'Affaires internationales de santé',
            ),
            352 => 
            array (
                'id' => '01hdkrfhr728vaahn56gkm1tcc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'International telecommunications',
                'name_fr' => 'Télécommunications internationales',
            ),
            353 => 
            array (
                'id' => '01hdkrfhr8szv3rxjdnbzyx0b7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Internationally Protected Persons Health',
                'name_fr' => 'Santé des personnes jouissant d&#039;une protection internationale',
            ),
            354 => 
            array (
                'id' => '01hdkrfhr8szv3rxjdnbzyx0b8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Internet',
                'name_fr' => 'Internet',
            ),
            355 => 
            array (
                'id' => '01hdkrfhr9jeng5ry4qfv90m3t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Internet of things',
                'name_fr' => 'Internet des objets',
            ),
            356 => 
            array (
                'id' => '01hdkrfhr9jeng5ry4qfv90m3v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Intestinal',
                'name_fr' => 'Intestinale',
            ),
            357 => 
            array (
                'id' => '01hdkrfhraekzpnw15jq5n8z42',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Intranets',
                'name_fr' => 'Intranet',
            ),
            358 => 
            array (
                'id' => '01hdkrfhraekzpnw15jq5n8z43',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Invasive insect pests',
                'name_fr' => 'Insectes nuisibles invasifs',
            ),
            359 => 
            array (
                'id' => '01hdkrfhrby6drw5etsqw8sdcg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Invasive Species',
                'name_fr' => 'Espèces envahissantes',
            ),
            360 => 
            array (
                'id' => '01hdkrfhrby6drw5etsqw8sdch',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Inventions',
                'name_fr' => 'Invention',
            ),
            361 => 
            array (
                'id' => '01hdkrfhrby6drw5etsqw8sdcj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Invertebrate Stock Assessment',
                'name_fr' => 'Évaluation des stocks d&#039;invertébrés',
            ),
            362 => 
            array (
                'id' => '01hdkrfhrcv8gj0vgvsesf3fd1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ionization and Radiation',
                'name_fr' => 'Ionisation et radiation',
            ),
            363 => 
            array (
                'id' => '01hdkrfhrcv8gj0vgvsesf3fd2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Irrigation',
                'name_fr' => 'Irrigation',
            ),
            364 => 
            array (
                'id' => '01hdkrfhrdt7238kn4nva59vq4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Irrigation agronomy',
                'name_fr' => 'Agronomie de l’irrigation',
            ),
            365 => 
            array (
                'id' => '01hdkrfhrdt7238kn4nva59vq5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Irrigation and drainage',
                'name_fr' => 'L’irrigation et du drainage',
            ),
            366 => 
            array (
                'id' => '01hdkrfhrewc1dv18rrh347yfa',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Island',
                'name_fr' => 'Île',
            ),
            367 => 
            array (
                'id' => '01hdkrfhrewc1dv18rrh347yfb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Kimberley Process',
                'name_fr' => 'Processus de Kimberley',
            ),
            368 => 
            array (
                'id' => '01hdkrfhrfz294az3wy0kvj3e1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Knowledge and technology transfer',
                'name_fr' => 'Transfert des connaissances et des technologies',
            ),
            369 => 
            array (
                'id' => '01hdkrfhrfz294az3wy0kvj3e2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Knowledge brokering',
                'name_fr' => 'Transmission du savoir',
            ),
            370 => 
            array (
                'id' => '01hdkrfhrfz294az3wy0kvj3e3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Knowledge management',
                'name_fr' => 'Gestion du savoir',
            ),
            371 => 
            array (
                'id' => '01hdkrfhrg7knkvxmagbyt101x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Knowledge Transfer',
                'name_fr' => 'Transfert des connaissances',
            ),
            372 => 
            array (
                'id' => '01hdkrfhrg7knkvxmagbyt101y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Knowledge translation',
                'name_fr' => 'Application des connaissances',
            ),
            373 => 
            array (
                'id' => '01hdkrfhrhhqnb748kwywhjg3g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Labelling',
                'name_fr' => 'Étiquetage',
            ),
            374 => 
            array (
                'id' => '01hdkrfhrhhqnb748kwywhjg3h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Laboratories',
                'name_fr' => 'Laboratoire',
            ),
            375 => 
            array (
                'id' => '01hdkrfhrj789wdbjt51961gxh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Laboratory measurements',
                'name_fr' => 'Mesures en laboratoire',
            ),
            376 => 
            array (
                'id' => '01hdkrfhrj789wdbjt51961gxj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Labrador Sea',
                'name_fr' => 'Mer du Labrador',
            ),
            377 => 
            array (
                'id' => '01hdkrfhrkz7k7v8s3c1fjmwnz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Labrador Shelf',
                'name_fr' => 'Plateau du Labrador',
            ),
            378 => 
            array (
                'id' => '01hdkrfhrkz7k7v8s3c1fjmwp0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lactation',
                'name_fr' => 'La lactation',
            ),
            379 => 
            array (
                'id' => '01hdkrfhrms51y3xed99jgcdeb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lactic acid bacteria',
                'name_fr' => 'Bactéries lactiques',
            ),
            380 => 
            array (
                'id' => '01hdkrfhrms51y3xed99jgcdec',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lake',
                'name_fr' => 'Lac',
            ),
            381 => 
            array (
                'id' => '01hdkrfhrnpt5t9sne72ybpnda',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lake Ice',
                'name_fr' => 'Glace de lac',
            ),
            382 => 
            array (
                'id' => '01hdkrfhrnpt5t9sne72ybpndb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lake Winnipeg',
                'name_fr' => 'Lac Winnipeg',
            ),
            383 => 
            array (
                'id' => '01hdkrfhrp3zy1b2f7em7vy310',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lakes',
                'name_fr' => 'Lacs',
            ),
            384 => 
            array (
                'id' => '01hdkrfhrp3zy1b2f7em7vy311',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Land Administration',
                'name_fr' => 'Administration des terres',
            ),
            385 => 
            array (
                'id' => '01hdkrfhrqdqxgcg5nh1pfp6bm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Land Cover',
                'name_fr' => 'Couverture terrestre',
            ),
            386 => 
            array (
                'id' => '01hdkrfhrqdqxgcg5nh1pfp6bn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Land management',
                'name_fr' => 'Gestion des terres',
            ),
            387 => 
            array (
                'id' => '01hdkrfhrrr7tjhwpk9vbdb0qv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Land Reclamation',
                'name_fr' => 'Remise en état des terres',
            ),
            388 => 
            array (
                'id' => '01hdkrfhrrr7tjhwpk9vbdb0qw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Land resource specialist',
                'name_fr' => 'Spécialiste des ressources terrestres',
            ),
            389 => 
            array (
                'id' => '01hdkrfhrrr7tjhwpk9vbdb0qx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Land Surface/Atmosphere Interactions',
                'name_fr' => 'Interactions entre la surface des terres et l’atmosphère',
            ),
            390 => 
            array (
                'id' => '01hdkrfhrs0g4v7n66xn2nhw03',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Land use',
                'name_fr' => 'Utilisation des terres',
            ),
            391 => 
            array (
                'id' => '01hdkrfhrs0g4v7n66xn2nhw04',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Land-use planning and management',
                'name_fr' => 'Aménagement et gestion des terres',
            ),
            392 => 
            array (
                'id' => '01hdkrfhrtp0m3g2sek3d9fw3t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Landbirds',
                'name_fr' => 'Oiseaux terrestres',
            ),
            393 => 
            array (
                'id' => '01hdkrfhrtp0m3g2sek3d9fw3v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lands - Aboriginal Lands',
                'name_fr' => 'Terres - terres autochtones',
            ),
            394 => 
            array (
                'id' => '01hdkrfhrv1xp6e49hqh5h44nf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lands - National Parks',
                'name_fr' => 'Terres - parcs nationaux',
            ),
            395 => 
            array (
                'id' => '01hdkrfhrv1xp6e49hqh5h44ng',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lands - Offshore',
                'name_fr' => 'Terres - extracôtiers',
            ),
            396 => 
            array (
                'id' => '01hdkrfhrw4rxmzrs53e3dmkkc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lands - Protected Areas',
                'name_fr' => 'Terres - aires protégées',
            ),
            397 => 
            array (
                'id' => '01hdkrfhrw4rxmzrs53e3dmkkd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lands - The North',
                'name_fr' => 'Terres - le nord',
            ),
            398 => 
            array (
                'id' => '01hdkrfhrxrb74wba7h78e6rxc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lands Survey',
                'name_fr' => 'Arpentage des terres',
            ),
            399 => 
            array (
                'id' => '01hdkrfhrxrb74wba7h78e6rxd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Landscape',
                'name_fr' => 'Paysage',
            ),
            400 => 
            array (
                'id' => '01hdkrfhrxrb74wba7h78e6rxe',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Landscape and watershed management',
                'name_fr' => 'Gestion des paysages et des bassins versants',
            ),
            401 => 
            array (
                'id' => '01hdkrfhryde7v6r1syca21zyr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Landslides',
                'name_fr' => 'Glissements de terrain',
            ),
            402 => 
            array (
                'id' => '01hdkrfhryde7v6r1syca21zys',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Laser additive manufacturing',
                'name_fr' => 'Fabrication additive par laser',
            ),
            403 => 
            array (
                'id' => '01hdkrfhrzymsc4m2cxkbyjs3q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Laser technologies',
                'name_fr' => 'Technologies laser',
            ),
            404 => 
            array (
                'id' => '01hdkrfhrzymsc4m2cxkbyjs3r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Laser technology',
                'name_fr' => 'Technologie laser',
            ),
            405 => 
            array (
                'id' => '01hdkrfhs0nbhhvhde22fnvqza',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Laser welding',
                'name_fr' => 'Soudage laser',
            ),
            406 => 
            array (
                'id' => '01hdkrfhs0nbhhvhde22fnvqzb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Leaf beetle',
                'name_fr' => 'Du chrysomèle',
            ),
            407 => 
            array (
                'id' => '01hdkrfhs1p42c3vgaxb6tpsg2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Leaf rust',
                'name_fr' => 'Rouille des feuilles',
            ),
            408 => 
            array (
                'id' => '01hdkrfhs1p42c3vgaxb6tpsg3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Leaf spots of cereals',
                'name_fr' => 'Taches foliaires des céréales',
            ),
            409 => 
            array (
                'id' => '01hdkrfhs2738xm4fmt1xv1j7k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Leafhopper',
                'name_fr' => 'La cicadelle',
            ),
            410 => 
            array (
                'id' => '01hdkrfhs2738xm4fmt1xv1j7m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Legal',
                'name_fr' => 'Juridique',
            ),
            411 => 
            array (
                'id' => '01hdkrfhs31fapvpcqf9jptwd4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Legal Boundaries',
                'name_fr' => 'Limites juridiques',
            ),
            412 => 
            array (
                'id' => '01hdkrfhs31fapvpcqf9jptwd5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Legal Boundaries - Offshore',
                'name_fr' => 'Limites juridiques - littoral',
            ),
            413 => 
            array (
                'id' => '01hdkrfhs4g90m8fa29jtv6qj3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Legal Boundaries - Terrestrial',
                'name_fr' => 'Limites juridiques - terrestres',
            ),
            414 => 
            array (
                'id' => '01hdkrfhs5zfhby0nxmfs79awt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Legal testimony',
                'name_fr' => 'Témoignage devant les tribunaux',
            ),
            415 => 
            array (
                'id' => '01hdkrfhs5zfhby0nxmfs79awv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Legislation',
                'name_fr' => 'Législation',
            ),
            416 => 
            array (
                'id' => '01hdkrfhs6xnbzwza4yt0snvn5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Length',
                'name_fr' => 'Longueurs',
            ),
            417 => 
            array (
                'id' => '01hdkrfhs6xnbzwza4yt0snvn6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lentic Systems',
                'name_fr' => 'Systèmes lénitiques',
            ),
            418 => 
            array (
                'id' => '01hdkrfhs7by830kxvkrb8b1bp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lentils',
                'name_fr' => 'Lentilles',
            ),
            419 => 
            array (
                'id' => '01hdkrfhs7by830kxvkrb8b1bq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Licensing',
                'name_fr' => 'Licences d&#039;exploitation',
            ),
            420 => 
            array (
                'id' => '01hdkrfhs8h06pphws7ta3gb45',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lightweighting',
                'name_fr' => 'Allègement des véhicules',
            ),
            421 => 
            array (
                'id' => '01hdkrfhs8h06pphws7ta3gb46',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Limnology',
                'name_fr' => 'Limnologie',
            ),
            422 => 
            array (
                'id' => '01hdkrfhs8h06pphws7ta3gb47',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Limnology/ freshwater ecology',
                'name_fr' => 'Limnologie/écologie de l’eau potable',
            ),
            423 => 
            array (
                'id' => '01hdkrfhs9rftxygkm77mzepj9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Limnology/Freshwater',
                'name_fr' => 'Limnologie/eau douce',
            ),
            424 => 
            array (
                'id' => '01hdkrfhs9rftxygkm77mzepja',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lipids and fatty acids',
                'name_fr' => 'Lipides et acides gras',
            ),
            425 => 
            array (
                'id' => '01hdkrfhsaegh7qgsvyw7jxn9r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Liquified Natural Gas',
                'name_fr' => 'Le gaz naturel liquéfié',
            ),
            426 => 
            array (
                'id' => '01hdkrfhsaegh7qgsvyw7jxn9s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Livestock',
                'name_fr' => 'Animaux d&#039;élevage',
            ),
            427 => 
            array (
                'id' => '01hdkrfhsbqbmxyv5dam411zdq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Livestock diseases',
                'name_fr' => 'Maladies du bétail',
            ),
            428 => 
            array (
                'id' => '01hdkrfhsbqbmxyv5dam411zdr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Livestock management',
                'name_fr' => 'Gestion du bétail',
            ),
            429 => 
            array (
                'id' => '01hdkrfhsc2fkpw78d8jnwh3t7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Livestock phenomics',
                'name_fr' => 'Phénomique du bétail',
            ),
            430 => 
            array (
                'id' => '01hdkrfhsc2fkpw78d8jnwh3t8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lotic Systems',
                'name_fr' => 'Systèmes lotiques',
            ),
            431 => 
            array (
                'id' => '01hdkrfhsdh4m9x96se3c2tkwt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Lumber',
                'name_fr' => 'Bois d&#039;oeuvre',
            ),
            432 => 
            array (
                'id' => '01hdkrfhsdh4m9x96se3c2tkwv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Machine Learning',
                'name_fr' => 'Apprentissage automatique',
            ),
            433 => 
            array (
                'id' => '01hdkrfhsep7k4gq54q8dq0p1d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Machine readable data',
                'name_fr' => 'Données lisibles par machine',
            ),
            434 => 
            array (
                'id' => '01hdkrfhsep7k4gq54q8dq0p1e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Machinery',
                'name_fr' => 'Machinerie',
            ),
            435 => 
            array (
                'id' => '01hdkrfhsep7k4gq54q8dq0p1f',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mackenzie Delta',
                'name_fr' => 'Delta du Mackenzie',
            ),
            436 => 
            array (
                'id' => '01hdkrfhsfcba00da01rzzgq05',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mackenzie Mountains',
                'name_fr' => 'Montagnes du Mackenzie',
            ),
            437 => 
            array (
                'id' => '01hdkrfhsfcba00da01rzzgq06',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mackenzie River',
                'name_fr' => 'Fleuve Mackenzie',
            ),
            438 => 
            array (
                'id' => '01hdkrfhsgmkwtt2rc0ekps5xt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mackenzie Valley',
                'name_fr' => 'Vallée du Mackenzie',
            ),
            439 => 
            array (
                'id' => '01hdkrfhsgmkwtt2rc0ekps5xv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Macroinvertebrates',
                'name_fr' => 'Macro-invertébrés',
            ),
            440 => 
            array (
                'id' => '01hdkrfhsh2nkyt9htf1zv7w2w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Macronutrients',
                'name_fr' => 'Macronutriments',
            ),
            441 => 
            array (
                'id' => '01hdkrfhsh2nkyt9htf1zv7w2x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Management',
                'name_fr' => 'Gestion',
            ),
            442 => 
            array (
                'id' => '01hdkrfhsj2r49t7kmfswjbbyn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Management agronomy',
                'name_fr' => 'Agronomie de la gestion',
            ),
            443 => 
            array (
                'id' => '01hdkrfhsj2r49t7kmfswjbbyp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Management and Oversight',
                'name_fr' => 'Gestion et surveillance',
            ),
            444 => 
            array (
                'id' => '01hdkrfhsknpe5w4adz2y717c4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Management information systems',
                'name_fr' => 'Système d&#039;information de gestion',
            ),
            445 => 
            array (
                'id' => '01hdkrfhsm65vb0rrd8yzqh8m4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Management of mineral fertilizers',
                'name_fr' => 'Gestion des engrais minéraux',
            ),
            446 => 
            array (
                'id' => '01hdkrfhsm65vb0rrd8yzqh8m5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Manufacturing industry',
                'name_fr' => 'Industrie de la fabrication',
            ),
            447 => 
            array (
                'id' => '01hdkrfhsnhq7vxdh1wvxgy34z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Manure and slurry management',
                'name_fr' => 'Gestion des fumiers et lisiers',
            ),
            448 => 
            array (
                'id' => '01hdkrfhsnhq7vxdh1wvxgy350',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Marine',
                'name_fr' => 'Milieu marin',
            ),
            449 => 
            array (
                'id' => '01hdkrfhsp6graf62zh3a9ztk1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Marine biotoxins',
                'name_fr' => 'Biotoxines marines',
            ),
            450 => 
            array (
                'id' => '01hdkrfhsp6graf62zh3a9ztk2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Marine ecosystem',
                'name_fr' => 'Écosystème marin',
            ),
            451 => 
            array (
                'id' => '01hdkrfhsqqm2kxxwgxjrvwz99',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Marine Environments',
                'name_fr' => 'Milieux marins',
            ),
            452 => 
            array (
                'id' => '01hdkrfhsqqm2kxxwgxjrvwz9a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Marine geohazards',
                'name_fr' => 'Géorisques marins',
            ),
            453 => 
            array (
                'id' => '01hdkrfhsr9kdb46xb8xdy5n7w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Marine mammal',
                'name_fr' => 'Mammifères marins',
            ),
            454 => 
            array (
                'id' => '01hdkrfhsr9kdb46xb8xdy5n7x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Marine Mammals',
                'name_fr' => 'Mammifières marins',
            ),
            455 => 
            array (
                'id' => '01hdkrfhssya0xsk0s9d704x5x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Marine navigation',
                'name_fr' => 'Navigation maritime',
            ),
            456 => 
            array (
                'id' => '01hdkrfhssya0xsk0s9d704x5y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Marine renewable energy',
                'name_fr' => 'Énergie marine renouvelable',
            ),
            457 => 
            array (
                'id' => '01hdkrfhstwspc9zhrr2pm83g5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Marine Resource Development',
                'name_fr' => 'Mise en valeur des ressources marines',
            ),
            458 => 
            array (
                'id' => '01hdkrfhstwspc9zhrr2pm83g6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Marine toxins',
                'name_fr' => 'Toxines marines',
            ),
            459 => 
            array (
                'id' => '01hdkrfhsvp7rapa7g12hbb97b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Maritimes',
                'name_fr' => 'Maritimes',
            ),
            460 => 
            array (
                'id' => '01hdkrfhsvp7rapa7g12hbb97c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Marker assisted',
                'name_fr' => 'Assistée par les marqueurs',
            ),
            461 => 
            array (
                'id' => '01hdkrfhsw5rdn1zq7aq9787rg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mass and related quantities',
                'name_fr' => 'Masses et grandeurs apparentées',
            ),
            462 => 
            array (
                'id' => '01hdkrfhsw5rdn1zq7aq9787rh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Material processing',
                'name_fr' => 'Transformation des matériaux',
            ),
            463 => 
            array (
                'id' => '01hdkrfhsw5rdn1zq7aq9787rj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Materials',
                'name_fr' => 'Matériaux',
            ),
            464 => 
            array (
                'id' => '01hdkrfhsx3h5kt7zhecpb3mh7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Materials - High Energy',
                'name_fr' => 'Matériaux à haute énergie',
            ),
            465 => 
            array (
                'id' => '01hdkrfhsx3h5kt7zhecpb3mh8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Materials - Light Weight',
                'name_fr' => 'Matériaux légers',
            ),
            466 => 
            array (
                'id' => '01hdkrfhsyt9kcza7hd2eh454c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Materials Evaluation',
                'name_fr' => 'Évaluation des matériaux',
            ),
            467 => 
            array (
                'id' => '01hdkrfhsyt9kcza7hd2eh454d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Materials for Energy Technologies',
                'name_fr' => 'Matériaux pour technologies énergétiques',
            ),
            468 => 
            array (
                'id' => '01hdkrfhsz5dbqzvnj717czqh8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Materials Processing',
                'name_fr' => 'Traitement des matériaux',
            ),
            469 => 
            array (
                'id' => '01hdkrfhsz5dbqzvnj717czqh9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Materials technology',
                'name_fr' => 'Technologie des matériaux',
            ),
            470 => 
            array (
                'id' => '01hdkrfht01s09rf5panengqm8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Materials testing',
                'name_fr' => 'Essai des matériaux',
            ),
            471 => 
            array (
                'id' => '01hdkrfht01s09rf5panengqm9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Maternal health',
                'name_fr' => 'Santé maternelle',
            ),
            472 => 
            array (
                'id' => '01hdkrfht102eyxgjs3tg6y44m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mathematics',
                'name_fr' => 'Mathématiques',
            ),
            473 => 
            array (
                'id' => '01hdkrfht102eyxgjs3tg6y44n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mathematics and Statistics',
                'name_fr' => 'Statistique mathématique',
            ),
            474 => 
            array (
                'id' => '01hdkrfht2fn5fapecw0n9kjaf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Measurement Science',
                'name_fr' => 'Métrologie',
            ),
            475 => 
            array (
                'id' => '01hdkrfht2fn5fapecw0n9kjag',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Measurement Technologies &amp; Methodologies',
                'name_fr' => 'Technologies &amp; méthodologies de mesure',
            ),
            476 => 
            array (
                'id' => '01hdkrfht2fn5fapecw0n9kjah',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Meat',
                'name_fr' => 'La viande',
            ),
            477 => 
            array (
                'id' => '01hdkrfht3y5dmk9bzwbgkc5p3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Meat quality',
                'name_fr' => 'Qualité de la viande',
            ),
            478 => 
            array (
                'id' => '01hdkrfht3y5dmk9bzwbgkc5p4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Meat quality - applied bioinstrumentation',
                'name_fr' => 'Qualité de la viande – bioinstrumentation appliquée',
            ),
            479 => 
            array (
                'id' => '01hdkrfht45qw8jn69td699xy5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Meat quality and nutrition',
                'name_fr' => 'Qualité de la viande et nutrition',
            ),
            480 => 
            array (
                'id' => '01hdkrfht54rcvehxsqs0am8sw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Meat quality and processing',
                'name_fr' => 'Qualité et transformation des viandes',
            ),
            481 => 
            array (
                'id' => '01hdkrfht54rcvehxsqs0am8sx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Meat science',
                'name_fr' => 'Science de la viande',
            ),
            482 => 
            array (
                'id' => '01hdkrfht63d1edm0nasyj0r5z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mechanical assembling',
                'name_fr' => 'Assemblage mécanique',
            ),
            483 => 
            array (
                'id' => '01hdkrfht63d1edm0nasyj0r60',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mechanical Engineering',
                'name_fr' => 'Génie mécanique',
            ),
            484 => 
            array (
                'id' => '01hdkrfht77ffnbdj9rykajjst',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mechanisms of Action',
                'name_fr' => 'Mécanismes d&#039;action',
            ),
            485 => 
            array (
                'id' => '01hdkrfht77ffnbdj9rykajjsv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Medical devices',
                'name_fr' => 'Instruments médicaux',
            ),
            486 => 
            array (
                'id' => '01hdkrfht8n6taqwc35t5z0xfc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Medical physics',
                'name_fr' => 'Physique médicale',
            ),
            487 => 
            array (
                'id' => '01hdkrfht8n6taqwc35t5z0xfd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Medical products industry',
                'name_fr' => 'Industrie des produits médicaux',
            ),
            488 => 
            array (
                'id' => '01hdkrfht8n6taqwc35t5z0xfe',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Medical technology',
                'name_fr' => 'Technologies médicales',
            ),
            489 => 
            array (
                'id' => '01hdkrfht9x2c8qg9hnq5ymb89',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Medicine',
                'name_fr' => 'Médecine',
            ),
            490 => 
            array (
                'id' => '01hdkrfht9x2c8qg9hnq5ymb8a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Membrane separation and extraction processes',
                'name_fr' => 'Procédés de séparation des membranes et d&#039;extraction',
            ),
            491 => 
            array (
                'id' => '01hdkrfhtaj1khjgy9kfjwvcxp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mensuration',
                'name_fr' => 'Mesurage',
            ),
            492 => 
            array (
                'id' => '01hdkrfhtaj1khjgy9kfjwvcxq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mercury',
                'name_fr' => 'Mercure',
            ),
            493 => 
            array (
                'id' => '01hdkrfhtb9ytxwtcjghxfpxnh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Metabolic',
                'name_fr' => 'Métabolique',
            ),
            494 => 
            array (
                'id' => '01hdkrfhtb9ytxwtcjghxfpxnj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Metadata Management',
                'name_fr' => 'Gestion des métadonnées',
            ),
            495 => 
            array (
                'id' => '01hdkrfhtc105d325jgakqz5cx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Metal Active Gas welding',
            'name_fr' => 'Soudage à l’arc sous protection de gaz inerte avec fil-électrode fusible (ou soudage MIG)',
            ),
            496 => 
            array (
                'id' => '01hdkrfhtc105d325jgakqz5cy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Metal Bioaccessibility',
                'name_fr' => 'Biodisponibilité des métaux',
            ),
            497 => 
            array (
                'id' => '01hdkrfhtdeyy9myknejap1k7t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Metal Inert Gas welding',
            'name_fr' => 'Soudage à l’arc en atmosphère active (ou soudage MAG)',
            ),
            498 => 
            array (
                'id' => '01hdkrfhtdeyy9myknejap1k7v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Metal levels',
                'name_fr' => 'Teneurs en métaux',
            ),
            499 => 
            array (
                'id' => '01hdkrfhteavcmd2v36cben32r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Metallurgy',
                'name_fr' => 'Métallurgie',
            ),
        ));
        \DB::table('expertises')->insert(array (
            0 => 
            array (
                'id' => '01hdkrfhteavcmd2v36cben32s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Metals',
                'name_fr' => 'Métaux',
            ),
            1 => 
            array (
                'id' => '01hdkrfhteavcmd2v36cben32t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Meteorites',
                'name_fr' => 'Météorites',
            ),
            2 => 
            array (
                'id' => '01hdkrfhtf1rr5ydb26cavhrkm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Methods development',
                'name_fr' => 'Mise au point de méthodes',
            ),
            3 => 
            array (
                'id' => '01hdkrfhtf1rr5ydb26cavhrkn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Micro-moth',
                'name_fr' => 'Des microlépidoptères',
            ),
            4 => 
            array (
                'id' => '01hdkrfhtg7tmhz3c6jgg9gr8n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Micro-organism collection',
                'name_fr' => 'Collection de micro-organismes',
            ),
            5 => 
            array (
                'id' => '01hdkrfhtg7tmhz3c6jgg9gr8p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Micro-organisms',
                'name_fr' => 'Microorganismes',
            ),
            6 => 
            array (
                'id' => '01hdkrfhth335224gjsv6t2vdf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Microbial',
                'name_fr' => 'Microbiens',
            ),
            7 => 
            array (
                'id' => '01hdkrfhth335224gjsv6t2vdg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Microbial fermentation',
                'name_fr' => 'Fermentation microbienne',
            ),
            8 => 
            array (
                'id' => '01hdkrfhtjve8pb9rtrf11ghw8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Microbiology',
                'name_fr' => 'Microbiologie',
            ),
            9 => 
            array (
                'id' => '01hdkrfhtjve8pb9rtrf11ghw9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Microelectronics',
                'name_fr' => 'Microélectronique',
            ),
            10 => 
            array (
                'id' => '01hdkrfhtkvfkfy4mcf67qb59y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Microfabrication',
                'name_fr' => 'Microfabrication',
            ),
            11 => 
            array (
                'id' => '01hdkrfhtkvfkfy4mcf67qb59z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Microinvertebrates',
                'name_fr' => 'Micro-invertébrés',
            ),
            12 => 
            array (
                'id' => '01hdkrfhtm4b5p975yky73ggxt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Micrometeorology',
                'name_fr' => 'Micrométéorologie',
            ),
            13 => 
            array (
                'id' => '01hdkrfhtm4b5p975yky73ggxv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Micronutrients',
                'name_fr' => 'Micronutriments',
            ),
            14 => 
            array (
                'id' => '01hdkrfhtnj3j4r5gm55agcbd1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Microphysics',
                'name_fr' => 'Microphysique',
            ),
            15 => 
            array (
                'id' => '01hdkrfhtnj3j4r5gm55agcbd2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Microscopy',
                'name_fr' => 'Microscopie',
            ),
            16 => 
            array (
                'id' => '01hdkrfhtp1645awp8vfsk9ehf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mid-Atlantic Ocean',
                'name_fr' => 'Océan mi-Atlantique',
            ),
            17 => 
            array (
                'id' => '01hdkrfhtp1645awp8vfsk9ehg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Migration',
                'name_fr' => 'Migration',
            ),
            18 => 
            array (
                'id' => '01hdkrfhtqhmsjpkfm1apcba45',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Migratory birds',
                'name_fr' => 'Oiseaux migrateurs',
            ),
            19 => 
            array (
                'id' => '01hdkrfhtqhmsjpkfm1apcba46',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Migratory Birds Convention Act',
                'name_fr' => 'Loi sur la Convention concernant les oiseaux migrateurs',
            ),
            20 => 
            array (
                'id' => '01hdkrfhtrg9xzsfb8hxpsxnng',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Military engineering',
                'name_fr' => 'Génie militaire',
            ),
            21 => 
            array (
                'id' => '01hdkrfhtrg9xzsfb8hxpsxnnh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Military technology',
                'name_fr' => 'Technologie militaire',
            ),
            22 => 
            array (
                'id' => '01hdkrfhtsjxb1n045q624ahqm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mine Integrity',
                'name_fr' => 'L&#039;intégrité des mines',
            ),
            23 => 
            array (
                'id' => '01hdkrfhtsjxb1n045q624ahqn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mine Ventilation',
                'name_fr' => 'Ventilation des mines',
            ),
            24 => 
            array (
                'id' => '01hdkrfhtt1h37x3wfk32n4e7k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mineral Analysis',
                'name_fr' => 'L&#039;analyse minérale',
            ),
            25 => 
            array (
                'id' => '01hdkrfhtt1h37x3wfk32n4e7m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mineral Commodities',
                'name_fr' => 'Produits minéraux',
            ),
            26 => 
            array (
                'id' => '01hdkrfhtvb0nqx56p35b8npq0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mineral Exploration',
                'name_fr' => 'Exploration minière',
            ),
            27 => 
            array (
                'id' => '01hdkrfhtvb0nqx56p35b8npq1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mineral Processing',
                'name_fr' => 'Traitement des minéraux',
            ),
            28 => 
            array (
                'id' => '01hdkrfhtwffwqnzbcf9y0x56x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mineral Resource Potential',
                'name_fr' => 'Potentiel de ressources minérales',
            ),
            29 => 
            array (
                'id' => '01hdkrfhtwffwqnzbcf9y0x56y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mineral Trade',
                'name_fr' => 'Commerce des minerais',
            ),
            30 => 
            array (
                'id' => '01hdkrfhtwffwqnzbcf9y0x56z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mineralogy',
                'name_fr' => 'Minéralogie',
            ),
            31 => 
            array (
                'id' => '01hdkrfhtx424m2qnzvjy0fe41',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mineralogy and Rock Properties',
                'name_fr' => 'Minéralogie et propriétés de la roche',
            ),
            32 => 
            array (
                'id' => '01hdkrfhtx424m2qnzvjy0fe42',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Minerals',
                'name_fr' => 'Minéraux',
            ),
            33 => 
            array (
                'id' => '01hdkrfhtycm0p6xex7d0n28dd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Minerals - Industrial',
                'name_fr' => 'Minéraux - industriel',
            ),
            34 => 
            array (
                'id' => '01hdkrfhtycm0p6xex7d0n28de',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Minerals and Metals',
                'name_fr' => 'Minéraux et métaux',
            ),
            35 => 
            array (
                'id' => '01hdkrfhtza9ys7wv787w5a22a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mining',
                'name_fr' => 'Exploitation de mines',
            ),
            36 => 
            array (
                'id' => '01hdkrfhtza9ys7wv787w5a22b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mining &amp; Milling Energy Management',
                'name_fr' => 'Exploitation minière et gestion des usines de traitement',
            ),
            37 => 
            array (
                'id' => '01hdkrfhv03agmcm44ht75tmak',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mining - Deep',
                'name_fr' => 'Exploitation minière - grande profondeur',
            ),
            38 => 
            array (
                'id' => '01hdkrfhv03agmcm44ht75tmam',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mining - Green',
                'name_fr' => 'Exploitation minière - vert',
            ),
            39 => 
            array (
                'id' => '01hdkrfhv1dqhye422r8j6c3ns',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mining - Metals',
                'name_fr' => 'Exploitation minière - métaux',
            ),
            40 => 
            array (
                'id' => '01hdkrfhv1dqhye422r8j6c3nt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mining - Reclamation',
                'name_fr' => 'Extraction -- Remise en état rentable',
            ),
            41 => 
            array (
                'id' => '01hdkrfhv1dqhye422r8j6c3nv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mining Environment',
                'name_fr' => 'L&#039;environnement minier',
            ),
            42 => 
            array (
                'id' => '01hdkrfhv2p3qcdzfyd491d60g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mining Extraction',
                'name_fr' => 'Extraction minière',
            ),
            43 => 
            array (
                'id' => '01hdkrfhv2p3qcdzfyd491d60h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mining industry',
                'name_fr' => 'Industrie minière',
            ),
            44 => 
            array (
                'id' => '01hdkrfhv3p59bmxvcefwva8h6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mining Methods',
                'name_fr' => 'Méthodes minières',
            ),
            45 => 
            array (
                'id' => '01hdkrfhv3p59bmxvcefwva8h7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mining technology',
                'name_fr' => 'Technologie minière',
            ),
            46 => 
            array (
                'id' => '01hdkrfhv4h91fvzwe5514j7rr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Minor use pesticide',
                'name_fr' => 'Des pesticides à usage limité',
            ),
            47 => 
            array (
                'id' => '01hdkrfhv4h91fvzwe5514j7rs',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Minor use pesticides',
                'name_fr' => 'Pesticides à usage limité',
            ),
            48 => 
            array (
                'id' => '01hdkrfhv5g89e4jrz1nzy5bzt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mites',
                'name_fr' => 'Mites',
            ),
            49 => 
            array (
                'id' => '01hdkrfhv5g89e4jrz1nzy5bzv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mixed-wood Forests',
                'name_fr' => 'Forêts mixtes',
            ),
            50 => 
            array (
                'id' => '01hdkrfhv6xb6rpw9mrfp9rhde',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Model Forests',
                'name_fr' => 'Forêts modèles',
            ),
            51 => 
            array (
                'id' => '01hdkrfhv7dhnzq7acx4extwdp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Modeling',
                'name_fr' => 'Modélisation',
            ),
            52 => 
            array (
                'id' => '01hdkrfhv7dhnzq7acx4extwdq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Modelling and process simulation',
                'name_fr' => 'Modélisation et simulation de procédés',
            ),
            53 => 
            array (
                'id' => '01hdkrfhv80g4pr3w7d54x671x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Modelling Software',
                'name_fr' => 'Logiciel de modélisation',
            ),
            54 => 
            array (
                'id' => '01hdkrfhv80g4pr3w7d54x671y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Molecular',
                'name_fr' => 'Moléculaire',
            ),
            55 => 
            array (
                'id' => '01hdkrfhv80g4pr3w7d54x671z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Molecular bacteriology',
                'name_fr' => 'Bactériologie moléculaire',
            ),
            56 => 
            array (
                'id' => '01hdkrfhv900jcynnp0ag7hf3z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Molecular biology',
                'name_fr' => 'Biologie moléculaire',
            ),
            57 => 
            array (
                'id' => '01hdkrfhv900jcynnp0ag7hf40',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Molecular genetics',
                'name_fr' => 'Génétique moléculaire',
            ),
            58 => 
            array (
                'id' => '01hdkrfhvadw4g7qg5vqk5gz4g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Molecular plant',
                'name_fr' => 'Phytopathologie moléculaire',
            ),
            59 => 
            array (
                'id' => '01hdkrfhvadw4g7qg5vqk5gz4h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Molecular spectroscopy',
                'name_fr' => 'Spectroscopie moléculaire',
            ),
            60 => 
            array (
                'id' => '01hdkrfhvbq6aq97hafypvpswv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Molecular taxonomic characterization',
                'name_fr' => 'Caractérisation taxonomique moléculaire',
            ),
            61 => 
            array (
                'id' => '01hdkrfhvbq6aq97hafypvpsww',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Molecular weed science',
                'name_fr' => 'Malherbologie moléculaire',
            ),
            62 => 
            array (
                'id' => '01hdkrfhvczt11pyr1ys7c12d3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Monitoring',
                'name_fr' => 'Surveillance',
            ),
            63 => 
            array (
                'id' => '01hdkrfhvczt11pyr1ys7c12d4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Monitoring and Process Control',
                'name_fr' => 'Surveillance et contrôle de procédé',
            ),
            64 => 
            array (
                'id' => '01hdkrfhvdvdkn3644p2b9pd2m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Monte-Carlo Methods',
                'name_fr' => 'Méthodes Monte-Carlo',
            ),
            65 => 
            array (
                'id' => '01hdkrfhvdvdkn3644p2b9pd2n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mushroom',
                'name_fr' => 'Des champignons',
            ),
            66 => 
            array (
                'id' => '01hdkrfhve6af1jzrad9h7n0ms',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mussels',
                'name_fr' => 'Moules',
            ),
            67 => 
            array (
                'id' => '01hdkrfhve6af1jzrad9h7n0mt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mustard',
                'name_fr' => 'Graine de moutarde',
            ),
            68 => 
            array (
                'id' => '01hdkrfhvf8rg164sb1q9q2w1h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mutagenesis and Carcinogenesis',
                'name_fr' => 'Mutagenèse et carcinogenèse',
            ),
            69 => 
            array (
                'id' => '01hdkrfhvf8rg164sb1q9q2w1j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mycology',
                'name_fr' => 'Mycologie',
            ),
            70 => 
            array (
                'id' => '01hdkrfhvg68v6xfynqz3s8tsc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mycotoxigenic fungi',
                'name_fr' => 'Des champignons mycotoxigènes',
            ),
            71 => 
            array (
                'id' => '01hdkrfhvg68v6xfynqz3s8tsd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mycotoxin',
                'name_fr' => 'Mycotoxines',
            ),
            72 => 
            array (
                'id' => '01hdkrfhvg68v6xfynqz3s8tse',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Mycotoxins and allergens',
                'name_fr' => 'Mycotoxines et allergènes',
            ),
            73 => 
            array (
                'id' => '01hdkrfhvh1f1gs3kb4f5qmg6k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nano metrology',
                'name_fr' => 'Nanométrologie',
            ),
            74 => 
            array (
                'id' => '01hdkrfhvh1f1gs3kb4f5qmg6m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nanomaterials',
                'name_fr' => 'Nanomatériaux',
            ),
            75 => 
            array (
                'id' => '01hdkrfhvj43zwkwkyt0785jkb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nanotechnology',
                'name_fr' => 'Nanotechnologie',
            ),
            76 => 
            array (
                'id' => '01hdkrfhvj43zwkwkyt0785jkc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nanotechnology and nanomaterials',
                'name_fr' => 'Nanotechnologie et nanomatériaux',
            ),
            77 => 
            array (
                'id' => '01hdkrfhvkrf61w1fb2528hf0v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'National plant virus collection',
                'name_fr' => 'Collection nationale de phytovirus',
            ),
            78 => 
            array (
                'id' => '01hdkrfhvkrf61w1fb2528hf0w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Native Forest Pest',
                'name_fr' => 'Ravageurs forestiers indigènes',
            ),
            79 => 
            array (
                'id' => '01hdkrfhvmpt5mtnay6rd79s8v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Native species',
                'name_fr' => 'Espèces indigènes',
            ),
            80 => 
            array (
                'id' => '01hdkrfhvmpt5mtnay6rd79s8w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Natural enemies',
                'name_fr' => 'Ennemis naturels',
            ),
            81 => 
            array (
                'id' => '01hdkrfhvnnw534798xhgs9869',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Natural Gas',
                'name_fr' => 'Gaz naturel',
            ),
            82 => 
            array (
                'id' => '01hdkrfhvnnw534798xhgs986a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Natural Gas Liquids',
                'name_fr' => 'Liquides de gaz naturel',
            ),
            83 => 
            array (
                'id' => '01hdkrfhvnnw534798xhgs986b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Natural Hazards',
                'name_fr' => 'Risques naturels',
            ),
            84 => 
            array (
                'id' => '01hdkrfhvp3x0f6n1qp9a4ftwv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Natural Health Products',
                'name_fr' => 'Produits de santé naturels',
            ),
            85 => 
            array (
                'id' => '01hdkrfhvp3x0f6n1qp9a4ftww',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Natural language processing',
                'name_fr' => 'Traitement du langage naturel',
            ),
            86 => 
            array (
                'id' => '01hdkrfhvqjgajx6xv4r9mbfqe',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Natural products',
                'name_fr' => 'Produits naturels',
            ),
            87 => 
            array (
                'id' => '01hdkrfhvrdt4m2zraym9x3mpb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Natural products chemistry',
                'name_fr' => 'Chimie des produits naturels',
            ),
            88 => 
            array (
                'id' => '01hdkrfhvrdt4m2zraym9x3mpc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Natural resource management',
                'name_fr' => 'Gestion des ressources naturelles',
            ),
            89 => 
            array (
                'id' => '01hdkrfhvsmkgs3pmre3m9vrs1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nature &amp; Wildlife',
                'name_fr' => 'Nature et faune',
            ),
            90 => 
            array (
                'id' => '01hdkrfhvsmkgs3pmre3m9vrs2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Navigation',
                'name_fr' => 'Navigation',
            ),
            91 => 
            array (
                'id' => '01hdkrfhvtex3x5r69xdb4hd48',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Navigation systems',
                'name_fr' => 'Système d&#039;aide à la navigation',
            ),
            92 => 
            array (
                'id' => '01hdkrfhvtex3x5r69xdb4hd49',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'NE Newfoundland Shelf',
                'name_fr' => 'Plateau nord-est de Terre-Neuve',
            ),
            93 => 
            array (
                'id' => '01hdkrfhvvgxpb5w6m570v8985',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nematode',
                'name_fr' => 'Des nématodes',
            ),
            94 => 
            array (
                'id' => '01hdkrfhvvgxpb5w6m570v8986',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nematology',
                'name_fr' => 'Nématologie',
            ),
            95 => 
            array (
                'id' => '01hdkrfhvwqrzp22v7zcxjbff4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Neural Network',
                'name_fr' => 'Réseau neuronal',
            ),
            96 => 
            array (
                'id' => '01hdkrfhvwqrzp22v7zcxjbff5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Neurosciences',
                'name_fr' => 'Neurosciences',
            ),
            97 => 
            array (
                'id' => '01hdkrfhvxzkhndg4ggj74x23m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Neurotoxicology',
                'name_fr' => 'Neurotoxicologie',
            ),
            98 => 
            array (
                'id' => '01hdkrfhvxzkhndg4ggj74x23n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'New crop agronomy and adaptation',
                'name_fr' => 'Agronomie et adaptation des nouvelles cultures',
            ),
            99 => 
            array (
                'id' => '01hdkrfhvya8jfjzcm6madd9pe',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Nitrogen biofertilizers (bacteria)',
            'name_fr' => 'Biofertilisants azotés (bactéries)',
            ),
            100 => 
            array (
                'id' => '01hdkrfhvya8jfjzcm6madd9pf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nitrogen cycling',
                'name_fr' => 'Cycle de l’azote',
            ),
            101 => 
            array (
                'id' => '01hdkrfhvya8jfjzcm6madd9pg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'NMR Spectroscopy',
                'name_fr' => 'Spectroscopie de Résonance Magnétique Nucléaire',
            ),
            102 => 
            array (
                'id' => '01hdkrfhvz1xdmvrwetys9wy5e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Non-Aqueous Processes',
                'name_fr' => 'Processus non aqueuse',
            ),
            103 => 
            array (
                'id' => '01hdkrfhvz1xdmvrwetys9wy5f',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Non-destructive Testing (NDT)',
            'name_fr' => 'Essais non destructifs (END)',
            ),
            104 => 
            array (
                'id' => '01hdkrfhw0x4t215t47rzearn0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Non-Insured Health Benefits (supplementary) for First Nations and Inuit',
            'name_fr' => 'Services de santé non assurés (prestations supplémentaires) pour les Premières nations et les Inuits',
            ),
            105 => 
            array (
                'id' => '01hdkrfhw0x4t215t47rzearn1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Non-metals',
                'name_fr' => 'Substances non métalliques',
            ),
            106 => 
            array (
                'id' => '01hdkrfhw1jnpwxs89pwh09knk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Non-point Sources',
                'name_fr' => 'Sources non ponctuelles',
            ),
            107 => 
            array (
                'id' => '01hdkrfhw1jnpwxs89pwh09knm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'North America',
                'name_fr' => 'Amérique du Nord',
            ),
            108 => 
            array (
                'id' => '01hdkrfhw2wp46gh8szvgphea5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'North Atlantic Ocean',
                'name_fr' => 'Océan Atlantique nord',
            ),
            109 => 
            array (
                'id' => '01hdkrfhw2wp46gh8szvgphea6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Northern Canada',
                'name_fr' => 'Nord canadien',
            ),
            110 => 
            array (
                'id' => '01hdkrfhw3sp22skwhw68qq8ar',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Northern Resources',
                'name_fr' => 'Ressources du Nord',
            ),
            111 => 
            array (
                'id' => '01hdkrfhw3sp22skwhw68qq8as',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Northern rivers',
                'name_fr' => 'Rivières du Nord',
            ),
            112 => 
            array (
                'id' => '01hdkrfhw42xhwtab80s1aykcz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'NoSQL',
                'name_fr' => 'NoSQL',
            ),
            113 => 
            array (
                'id' => '01hdkrfhw42xhwtab80s1aykd0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Novel bacterial species',
                'name_fr' => 'Nouvelles espèces de bactéries',
            ),
            114 => 
            array (
                'id' => '01hdkrfhw5wfq1f4ec9kgy0fk9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Novel Foods',
                'name_fr' => 'Aliments nouveaux',
            ),
            115 => 
            array (
                'id' => '01hdkrfhw5wfq1f4ec9kgy0fka',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Novel processing technologies',
                'name_fr' => 'Nouvelles technologies de transformation',
            ),
            116 => 
            array (
                'id' => '01hdkrfhw6j8pxee8jfkt1apra',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Novel-trait plants',
                'name_fr' => 'Plantes porteuses de nouvelles caractéristiques',
            ),
            117 => 
            array (
                'id' => '01hdkrfhw6j8pxee8jfkt1aprb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nowcasting',
                'name_fr' => 'Prévision immédiate',
            ),
            118 => 
            array (
                'id' => '01hdkrfhw6j8pxee8jfkt1aprc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nuclear Energy',
                'name_fr' => 'Énergie nucléaire',
            ),
            119 => 
            array (
                'id' => '01hdkrfhw7wydbtzzbc6fy63mz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nuclear magnetic resonance',
                'name_fr' => 'Résonance magnétique nucléaire',
            ),
            120 => 
            array (
                'id' => '01hdkrfhw8kwps8tc71mrv5262',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nuclear physics',
                'name_fr' => 'Physique nucléaire',
            ),
            121 => 
            array (
                'id' => '01hdkrfhw8kwps8tc71mrv5263',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nuclear technology',
                'name_fr' => 'Technologie nucléaire',
            ),
            122 => 
            array (
                'id' => '01hdkrfhw9wa5dr01e0vsx5kan',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Numeric modelling',
                'name_fr' => 'Modélisation numérique',
            ),
            123 => 
            array (
                'id' => '01hdkrfhw9wa5dr01e0vsx5kap',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nutrient cycling',
                'name_fr' => 'Cycle des éléments nutritifs',
            ),
            124 => 
            array (
                'id' => '01hdkrfhwaav0kwfag1cr4395n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nutrient management',
                'name_fr' => 'Gestion des éléments nutritifs',
            ),
            125 => 
            array (
                'id' => '01hdkrfhwaav0kwfag1cr4395p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nutrients',
                'name_fr' => 'Éléments nutritifs',
            ),
            126 => 
            array (
                'id' => '01hdkrfhwbwnnyt72dnz73qnz3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nutrition',
                'name_fr' => 'Nutrition',
            ),
            127 => 
            array (
                'id' => '01hdkrfhwbwnnyt72dnz73qnz4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Nutritional value of ruminant feed',
                'name_fr' => 'Valeur nutritive des aliments pour ruminants',
            ),
            128 => 
            array (
                'id' => '01hdkrfhwc1vtq7evq3b7hk46c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Oat',
                'name_fr' => 'L&#039;avoine',
            ),
            129 => 
            array (
                'id' => '01hdkrfhwc1vtq7evq3b7hk46d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Observational astronomy',
                'name_fr' => 'Astronomie d&#039;observation',
            ),
            130 => 
            array (
                'id' => '01hdkrfhwdwv6rt4yy5tzhf9dx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ocean',
                'name_fr' => 'Océan',
            ),
            131 => 
            array (
                'id' => '01hdkrfhwdwv6rt4yy5tzhf9dy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ocean climate',
                'name_fr' => 'Climat des océans',
            ),
            132 => 
            array (
                'id' => '01hdkrfhwdwv6rt4yy5tzhf9dz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ocean colour',
                'name_fr' => 'Couleur de l&#039;océan',
            ),
            133 => 
            array (
                'id' => '01hdkrfhwe62277zf0mjvkd74m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ocean Data Collection Science',
                'name_fr' => 'Science de la collecte de données océanographiques',
            ),
            134 => 
            array (
                'id' => '01hdkrfhwe62277zf0mjvkd74n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ocean Engineering',
                'name_fr' => 'Génie océanique',
            ),
            135 => 
            array (
                'id' => '01hdkrfhwf9awfassyvtj5tr27',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ocean Engineering and Innovation',
                'name_fr' => 'Génie océanique et innovation',
            ),
            136 => 
            array (
                'id' => '01hdkrfhwf9awfassyvtj5tr28',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ocean Modeling',
                'name_fr' => 'Modélisation océanique',
            ),
            137 => 
            array (
                'id' => '01hdkrfhwgabf4hc5a4qp9ce8k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ocean Monitoring',
                'name_fr' => 'Surveillance des océans',
            ),
            138 => 
            array (
                'id' => '01hdkrfhwgabf4hc5a4qp9ce8m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Oceanography',
                'name_fr' => 'Océanographie',
            ),
            139 => 
            array (
                'id' => '01hdkrfhwh3n7gvzn92f032xk0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Oceans act',
                'name_fr' => 'Législation marine',
            ),
            140 => 
            array (
                'id' => '01hdkrfhwh3n7gvzn92f032xk1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Office automation',
                'name_fr' => 'Bureautique',
            ),
            141 => 
            array (
                'id' => '01hdkrfhwjwgtppqq5bnd5w9e3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Official Language Minority Community Development',
                'name_fr' => 'Développement des communautés de langue officielle en situation minoritaire',
            ),
            142 => 
            array (
                'id' => '01hdkrfhwjwgtppqq5bnd5w9e4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Oil Sand Processing',
                'name_fr' => 'Exploitation des sables bitumineux',
            ),
            143 => 
            array (
                'id' => '01hdkrfhwk49ndjcvas6z9dc2r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Oil Sands',
                'name_fr' => 'Sables bitumineux',
            ),
            144 => 
            array (
                'id' => '01hdkrfhwk49ndjcvas6z9dc2s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Oil spills',
                'name_fr' => 'Déversements de pétrole',
            ),
            145 => 
            array (
                'id' => '01hdkrfhwk49ndjcvas6z9dc2t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Oil spills and petroleum',
                'name_fr' => 'Déversements de pétrole et d&#039;hydrocarbures',
            ),
            146 => 
            array (
                'id' => '01hdkrfhwmnxy2etg0hjx114kw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Oilseed',
                'name_fr' => 'Oléagineux',
            ),
            147 => 
            array (
                'id' => '01hdkrfhwmnxy2etg0hjx114kx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Online analytics',
                'name_fr' => 'Analytique en direct',
            ),
            148 => 
            array (
                'id' => '01hdkrfhwnj5c0y1a04az9s49k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Optical fibre lasers and sensors',
                'name_fr' => 'Capteurs et lasers à fibre optique',
            ),
            149 => 
            array (
                'id' => '01hdkrfhwnj5c0y1a04az9s49m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Optimization',
                'name_fr' => 'Optimisation',
            ),
            150 => 
            array (
                'id' => '01hdkrfhwp49jjgsewetyjq3ke',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Oral exposures',
                'name_fr' => 'Exposition par voie orale',
            ),
            151 => 
            array (
                'id' => '01hdkrfhwp49jjgsewetyjq3kf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ore Deposits',
                'name_fr' => 'Gisements de minerai',
            ),
            152 => 
            array (
                'id' => '01hdkrfhwqpw3hc9w31qpb7k6n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ores Processing',
                'name_fr' => 'Traitement des minerais',
            ),
            153 => 
            array (
                'id' => '01hdkrfhwqpw3hc9w31qpb7k6p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Organic compounds',
                'name_fr' => 'Composés organiques',
            ),
            154 => 
            array (
                'id' => '01hdkrfhwr7y4hrngq5b8jad9e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Organic semiconductors',
                'name_fr' => 'Semiconducteurs organiques',
            ),
            155 => 
            array (
                'id' => '01hdkrfhwr7y4hrngq5b8jad9f',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Organizational sciences',
                'name_fr' => 'Sciences organisationnelles',
            ),
            156 => 
            array (
                'id' => '01hdkrfhws6fpct1qr5d8r1znv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Organotins',
                'name_fr' => 'Organoétains',
            ),
            157 => 
            array (
                'id' => '01hdkrfhws6fpct1qr5d8r1znw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Oxidative stress',
                'name_fr' => 'Stress oxydatif',
            ),
            158 => 
            array (
                'id' => '01hdkrfhwtve8pwca06eb905q6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ozone',
                'name_fr' => 'Ozone',
            ),
            159 => 
            array (
                'id' => '01hdkrfhwtve8pwca06eb905q7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pacific Margin',
                'name_fr' => 'Marge Pacifique',
            ),
            160 => 
            array (
                'id' => '01hdkrfhwv83km3faqhpv6wp78',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pacific Ocean',
                'name_fr' => 'Océan Pacifique',
            ),
            161 => 
            array (
                'id' => '01hdkrfhwv83km3faqhpv6wp79',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pacific Ocean - North',
                'name_fr' => 'Océan Pacifique - nord',
            ),
            162 => 
            array (
                'id' => '01hdkrfhwwm3dhhx0evcx55e6c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Packaging',
                'name_fr' => 'Emballage',
            ),
            163 => 
            array (
                'id' => '01hdkrfhwwm3dhhx0evcx55e6d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Palaeontology',
                'name_fr' => 'Paléontologie',
            ),
            164 => 
            array (
                'id' => '01hdkrfhwxmhwsc0avchbdgank',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Paleontology - Invertebrate',
                'name_fr' => 'Paléontologie des invertébrés',
            ),
            165 => 
            array (
                'id' => '01hdkrfhwxmhwsc0avchbdganm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Paleontology - Macro',
                'name_fr' => 'Macro paléontologie',
            ),
            166 => 
            array (
                'id' => '01hdkrfhwywt8hxtx0sy7709xs',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Paleontology - Micro',
                'name_fr' => 'Micro paléontologie',
            ),
            167 => 
            array (
                'id' => '01hdkrfhwztz42krey36w5592t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Paleontology - Vertebrate',
                'name_fr' => 'Paléontologie des vertébrés',
            ),
            168 => 
            array (
                'id' => '01hdkrfhwztz42krey36w5592v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pandemic',
                'name_fr' => 'Pandémie',
            ),
            169 => 
            array (
                'id' => '01hdkrfhx03q2a4gq6wtk6cz8y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Paper',
                'name_fr' => 'Papier',
            ),
            170 => 
            array (
                'id' => '01hdkrfhx03q2a4gq6wtk6cz8z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Paper mills',
                'name_fr' => 'Usines de papier',
            ),
            171 => 
            array (
                'id' => '01hdkrfhx1pk4g8gz3aqac3k9x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Paramedics',
                'name_fr' => 'Paramédics',
            ),
            172 => 
            array (
                'id' => '01hdkrfhx1pk4g8gz3aqac3k9y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Parameterization of gravity waves',
                'name_fr' => 'Paramétrage des ondes de gravité',
            ),
            173 => 
            array (
                'id' => '01hdkrfhx25y5qa81f7yg5xc3w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Parasite',
                'name_fr' => 'Parasites',
            ),
            174 => 
            array (
                'id' => '01hdkrfhx25y5qa81f7yg5xc3x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Parasitology',
                'name_fr' => 'Parasitologie',
            ),
            175 => 
            array (
                'id' => '01hdkrfhx25y5qa81f7yg5xc3y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Particle physics',
                'name_fr' => 'Physique des particules',
            ),
            176 => 
            array (
                'id' => '01hdkrfhx3nk5dr47sn9xax2za',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Particulate matter',
                'name_fr' => 'Particules',
            ),
            177 => 
            array (
                'id' => '01hdkrfhx3nk5dr47sn9xax2zb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Passenger Conveyances',
                'name_fr' => 'Transporteurs communs',
            ),
            178 => 
            array (
                'id' => '01hdkrfhx4mgxsbnvrve92ftgg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Passive microwave imager radiances',
                'name_fr' => 'Données de radiance fournies par imageur hyperfréquences passif',
            ),
            179 => 
            array (
                'id' => '01hdkrfhx4mgxsbnvrve92ftgh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pasture systems',
                'name_fr' => 'Systèmes de pâturage',
            ),
            180 => 
            array (
                'id' => '01hdkrfhx5vn7hr87x6fxwvgmv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Patents',
                'name_fr' => 'Brevets',
            ),
            181 => 
            array (
                'id' => '01hdkrfhx5vn7hr87x6fxwvgmw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pathogenes',
                'name_fr' => 'Pathogènes',
            ),
            182 => 
            array (
                'id' => '01hdkrfhx6cq3jj9n2ysgvyjcm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pathogens',
                'name_fr' => 'Agents pathogènes',
            ),
            183 => 
            array (
                'id' => '01hdkrfhx6cq3jj9n2ysgvyjcn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pathology',
                'name_fr' => 'Pathologie',
            ),
            184 => 
            array (
                'id' => '01hdkrfhx6cq3jj9n2ysgvyjcp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Peace Athabasca Delta',
                'name_fr' => 'Delta Paix-Athabasca',
            ),
            185 => 
            array (
                'id' => '01hdkrfhx7xwht8x0v5mbt9d5c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Peas',
                'name_fr' => 'Pois',
            ),
            186 => 
            array (
                'id' => '01hdkrfhx7xwht8x0v5mbt9d5d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Peatland',
                'name_fr' => 'Tourbière',
            ),
            187 => 
            array (
                'id' => '01hdkrfhx8mhvjp31gf00kjpec',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pelagic',
                'name_fr' => 'Pélagique',
            ),
            188 => 
            array (
                'id' => '01hdkrfhx9jg668jz7tghwkc2y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pelagic Species',
                'name_fr' => 'Espèces pélagiques',
            ),
            189 => 
            array (
                'id' => '01hdkrfhx9jg668jz7tghwkc2z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Perennial cereals biomass',
                'name_fr' => 'Céréales pérennes pour améliorer la biomasse',
            ),
            190 => 
            array (
                'id' => '01hdkrfhxakxwsnr5yefvq1be4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Perimeter sensing',
                'name_fr' => 'Surveillance périmétrique',
            ),
            191 => 
            array (
                'id' => '01hdkrfhxakxwsnr5yefvq1be5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Permafrost',
                'name_fr' => 'Pergélisol',
            ),
            192 => 
            array (
                'id' => '01hdkrfhxbww8pwxd3cdva4vn2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Persistent organic pollutants (POPs)',
            'name_fr' => 'Polluants organiques persistants (POP)',
            ),
            193 => 
            array (
                'id' => '01hdkrfhxcgth6mr719gqdt9xy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Personal digital assistant',
                'name_fr' => 'Assistant numérique personnel',
            ),
            194 => 
            array (
                'id' => '01hdkrfhxcgth6mr719gqdt9xz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pest management',
                'name_fr' => 'Lutte antiparasitaire',
            ),
            195 => 
            array (
                'id' => '01hdkrfhxda48d53qjmryccyw2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pesticide Regulation',
                'name_fr' => 'Réglementation des pesticides',
            ),
            196 => 
            array (
                'id' => '01hdkrfhxda48d53qjmryccyw3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pesticide residues',
                'name_fr' => 'Résidus de pesticides',
            ),
            197 => 
            array (
                'id' => '01hdkrfhxda48d53qjmryccyw4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pesticide Risk Reduction',
                'name_fr' => 'Réduction des risques liés aux pesticides',
            ),
            198 => 
            array (
                'id' => '01hdkrfhxend6gn9bgqa6ng6y9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pesticides',
                'name_fr' => 'Pesticides',
            ),
            199 => 
            array (
                'id' => '01hdkrfhxftmbfarxhxzw0vdjv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pesticides and herbicides',
                'name_fr' => 'Pesticides et herbicides',
            ),
            200 => 
            array (
                'id' => '01hdkrfhxftmbfarxhxzw0vdjw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Petrochemicals',
                'name_fr' => 'Produits pétrochimiques',
            ),
            201 => 
            array (
                'id' => '01hdkrfhxftmbfarxhxzw0vdjx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Petrography',
                'name_fr' => 'Pétrographie',
            ),
            202 => 
            array (
                'id' => '01hdkrfhxg7mh0mf5xy82m3dwa',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Petroleum industry',
                'name_fr' => 'Industrie pétrolière',
            ),
            203 => 
            array (
                'id' => '01hdkrfhxg7mh0mf5xy82m3dwb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Petroleum Products',
                'name_fr' => 'Produits pétroliers',
            ),
            204 => 
            array (
                'id' => '01hdkrfhxjr87bnd5cr59z72p5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Petroleum Systems',
                'name_fr' => 'Systèmes pétroliers',
            ),
            205 => 
            array (
                'id' => '01hdkrfhxksfv5znkc5c4009m4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Petrology',
                'name_fr' => 'Pétrologie',
            ),
            206 => 
            array (
                'id' => '01hdkrfhxksfv5znkc5c4009m5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pharmaceutical Human Drugs',
                'name_fr' => 'Produits pharmaceutiques destinés à l&#039;usage humain',
            ),
            207 => 
            array (
                'id' => '01hdkrfhxmek624wa05vt88m4m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pharmaceuticals',
                'name_fr' => 'Produits pharmaceutiques',
            ),
            208 => 
            array (
                'id' => '01hdkrfhxmek624wa05vt88m4n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pharmaceuticals and personal care products',
                'name_fr' => 'Produits pharmaceutiques et produits de soins personnels',
            ),
            209 => 
            array (
                'id' => '01hdkrfhxnps2gd3x3z6cw80js',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pharmacokinetics/Dynamics',
                'name_fr' => 'Pharmacocinétique et pharmacodynamie',
            ),
            210 => 
            array (
                'id' => '01hdkrfhxnps2gd3x3z6cw80jt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pharmacology',
                'name_fr' => 'Pharmacologie',
            ),
            211 => 
            array (
                'id' => '01hdkrfhxpp1qh7wh0jfhd078m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Phenology',
                'name_fr' => 'Phénologie',
            ),
            212 => 
            array (
                'id' => '01hdkrfhxqnyqayh9ywjj1n1x1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Photochemical smog',
                'name_fr' => 'Smog photochimique',
            ),
            213 => 
            array (
                'id' => '01hdkrfhxqnyqayh9ywjj1n1x2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Photochemistry',
                'name_fr' => 'Photochimie',
            ),
            214 => 
            array (
                'id' => '01hdkrfhxr1z02yw58ptyb61gw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Photometry and Radiometry',
                'name_fr' => 'Photométrie et radiométrie',
            ),
            215 => 
            array (
                'id' => '01hdkrfhxr1z02yw58ptyb61gx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Photonic Devices',
                'name_fr' => 'Dispositifs photoniques',
            ),
            216 => 
            array (
                'id' => '01hdkrfhxsgch5g2h8yamq7qma',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Photonics',
                'name_fr' => 'Photonique',
            ),
            217 => 
            array (
                'id' => '01hdkrfhxsgch5g2h8yamq7qmb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Physical',
                'name_fr' => 'Physique',
            ),
            218 => 
            array (
                'id' => '01hdkrfhxtvssezc94q357dhmx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Physical Modelling',
                'name_fr' => 'Modélisation physique',
            ),
            219 => 
            array (
                'id' => '01hdkrfhxvpzh07vby152tgpb2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Physical oceanography',
                'name_fr' => 'Océanographie physique',
            ),
            220 => 
            array (
                'id' => '01hdkrfhxwxwhwt4v6z4j2c340',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Physical sciences',
                'name_fr' => 'Sciences physiques',
            ),
            221 => 
            array (
                'id' => '01hdkrfhxwxwhwt4v6z4j2c341',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Physicochemical processes',
                'name_fr' => 'Procédés physicochimiques',
            ),
            222 => 
            array (
                'id' => '01hdkrfhxx49n3ex3f7ewg2nc4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Physiology',
                'name_fr' => 'Physiologie',
            ),
            223 => 
            array (
                'id' => '01hdkrfhxx49n3ex3f7ewg2nc5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Physiology &amp; Biochemistry',
                'name_fr' => 'Physiologie et biochimie',
            ),
            224 => 
            array (
                'id' => '01hdkrfhxy10x0nm0zmh8qvqka',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Phytochemical',
                'name_fr' => 'Agents phytochimiques',
            ),
            225 => 
            array (
                'id' => '01hdkrfhxy10x0nm0zmh8qvqkb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Phytopathogens',
                'name_fr' => 'Phytopathogène',
            ),
            226 => 
            array (
                'id' => '01hdkrfhxzrekaraebq18c713j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Phytopathology',
                'name_fr' => 'Phytopathologie',
            ),
            227 => 
            array (
                'id' => '01hdkrfhxzrekaraebq18c713k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Phytoplankton',
                'name_fr' => 'Phytoplancton',
            ),
            228 => 
            array (
                'id' => '01hdkrfhy04kvtynte0tz1dwg6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Phytoplasm',
                'name_fr' => 'Phytoplasmes',
            ),
            229 => 
            array (
                'id' => '01hdkrfhy04kvtynte0tz1dwg7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Phytosterols',
                'name_fr' => 'Phytostérols',
            ),
            230 => 
            array (
                'id' => '01hdkrfhy1hhpt0dw8prrbmw6w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Pinnipeds (seals, walruses)',
            'name_fr' => 'Pinnipèdes (phoques, morses)',
            ),
            231 => 
            array (
                'id' => '01hdkrfhy1hhpt0dw8prrbmw6x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pipelines',
                'name_fr' => 'Oléoducs',
            ),
            232 => 
            array (
                'id' => '01hdkrfhy2ebek8k6dga2tej5c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Placers',
                'name_fr' => 'Gisement alluvial',
            ),
            233 => 
            array (
                'id' => '01hdkrfhy2ebek8k6dga2tej5d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plant',
                'name_fr' => 'Végétale',
            ),
            234 => 
            array (
                'id' => '01hdkrfhy3qvkexmthhwk0gd4f',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plant bacterial taxonomy',
                'name_fr' => 'Taxonomie bactérienne des plantes',
            ),
            235 => 
            array (
                'id' => '01hdkrfhy3qvkexmthhwk0gd4g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plant cell technologies',
                'name_fr' => 'Technologies à base de cellules végétales',
            ),
            236 => 
            array (
                'id' => '01hdkrfhy3qvkexmthhwk0gd4h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plant disease management',
                'name_fr' => 'Lutte contre les maladies végétales',
            ),
            237 => 
            array (
                'id' => '01hdkrfhy4wdp8xykwkbx3tbh1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plant diseases',
                'name_fr' => 'Ravageur des plantes',
            ),
            238 => 
            array (
                'id' => '01hdkrfhy4wdp8xykwkbx3tbh2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plant germplasm development – apple, sweet cherry',
                'name_fr' => 'Élaboration de matériel phytogénétique – pomme, cerise douce',
            ),
            239 => 
            array (
                'id' => '01hdkrfhy5pb5pwqq2y0rkcfds',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plant Hardiness',
                'name_fr' => 'Rusticité des plantes',
            ),
            240 => 
            array (
                'id' => '01hdkrfhy5pb5pwqq2y0rkcfdt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plant molecular',
                'name_fr' => 'Moléculaire des plantes',
            ),
            241 => 
            array (
                'id' => '01hdkrfhy6bqtxeknap9yq9y3m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plant pathogen interactions',
                'name_fr' => 'Interactions des pathogènes végétaux',
            ),
            242 => 
            array (
                'id' => '01hdkrfhy6bqtxeknap9yq9y3n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plant pests',
                'name_fr' => 'Plantes envahissantes',
            ),
            243 => 
            array (
                'id' => '01hdkrfhy7m4q8nw1csg3z9j34',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plant production',
                'name_fr' => 'Production végétale',
            ),
            244 => 
            array (
                'id' => '01hdkrfhy7m4q8nw1csg3z9j35',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plant reproduction',
                'name_fr' => 'Reproduction des plantes',
            ),
            245 => 
            array (
                'id' => '01hdkrfhy8gxntfq92jzv2g16p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plant stress',
                'name_fr' => 'Stress chez les végétaux',
            ),
            246 => 
            array (
                'id' => '01hdkrfhy8gxntfq92jzv2g16q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plant viruses',
                'name_fr' => 'Phytovirus',
            ),
            247 => 
            array (
                'id' => '01hdkrfhy9tmfhe6xgf0f7z2t7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plant-feeding mite',
                'name_fr' => 'Des acariens phytovores',
            ),
            248 => 
            array (
                'id' => '01hdkrfhy9tmfhe6xgf0f7z2t8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Planthopper',
                'name_fr' => 'Du fulgore',
            ),
            249 => 
            array (
                'id' => '01hdkrfhyadm60eyvaa1r2jej7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plants',
                'name_fr' => 'Des végétaux',
            ),
            250 => 
            array (
                'id' => '01hdkrfhyadm60eyvaa1r2jej8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Plutonic',
                'name_fr' => 'Plutonique',
            ),
            251 => 
            array (
                'id' => '01hdkrfhybtcjzw8mr4c5tf3y6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Polar bears',
                'name_fr' => 'Ours polaires',
            ),
            252 => 
            array (
                'id' => '01hdkrfhybtcjzw8mr4c5tf3y7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Polar Continental Shelf',
                'name_fr' => 'Plateau continental polaire',
            ),
            253 => 
            array (
                'id' => '01hdkrfhycmx27cyh3pqpt4055',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Police and Law Enforcement',
                'name_fr' => 'Police et application de la loi',
            ),
            254 => 
            array (
                'id' => '01hdkrfhycmx27cyh3pqpt4056',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Policy',
                'name_fr' => 'Politique',
            ),
            255 => 
            array (
                'id' => '01hdkrfhyd2a5jnw7xfasy584c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Policy Analysis',
                'name_fr' => 'Analyse des politiques',
            ),
            256 => 
            array (
                'id' => '01hdkrfhyd2a5jnw7xfasy584d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Policy Development',
                'name_fr' => 'Élaboration de politiques',
            ),
            257 => 
            array (
                'id' => '01hdkrfhyep8zscktf0wp5xnk0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pollinating and parasitoid fly',
                'name_fr' => 'Des mouches pollinisatrices et parasitoïdes',
            ),
            258 => 
            array (
                'id' => '01hdkrfhyep8zscktf0wp5xnk1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pollinator',
                'name_fr' => 'Pollinisateurs',
            ),
            259 => 
            array (
                'id' => '01hdkrfhyf0ft8fz5nym70dc0k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pollutants',
                'name_fr' => 'Polluants',
            ),
            260 => 
            array (
                'id' => '01hdkrfhyf0ft8fz5nym70dc0m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pollution &amp; Waste',
                'name_fr' => 'Pollution et déchets',
            ),
            261 => 
            array (
                'id' => '01hdkrfhygx98g9zrvpvgdh0xp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pollution prevention',
                'name_fr' => 'Prévention de la pollution',
            ),
            262 => 
            array (
                'id' => '01hdkrfhygx98g9zrvpvgdh0xq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Polycyclic aromatic hydrocarbons (PAHs)',
            'name_fr' => 'Hydrocarbures aromatiques polycycliques (HAP)',
            ),
            263 => 
            array (
                'id' => '01hdkrfhygx98g9zrvpvgdh0xr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Polymer Composites',
                'name_fr' => 'Composites à base de polymères',
            ),
            264 => 
            array (
                'id' => '01hdkrfhyhv6c7z0hwez2tapsk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Polymers',
                'name_fr' => 'Polymères',
            ),
            265 => 
            array (
                'id' => '01hdkrfhyhv6c7z0hwez2tapsm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Polysaccharides',
                'name_fr' => 'Polysaccharides',
            ),
            266 => 
            array (
                'id' => '01hdkrfhyjbbaeccs3cg633hp9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Population Dynamics',
                'name_fr' => 'Dynamique des populations',
            ),
            267 => 
            array (
                'id' => '01hdkrfhyjbbaeccs3cg633hpa',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Population ecology',
                'name_fr' => 'Écologie des populations',
            ),
            268 => 
            array (
                'id' => '01hdkrfhyk2kzmrhv8bhkfk93j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Population genetics',
                'name_fr' => 'Génétique des populations',
            ),
            269 => 
            array (
                'id' => '01hdkrfhyk2kzmrhv8bhkfk93k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Population monitoring techniques',
                'name_fr' => 'Techniques de surveillance des populations',
            ),
            270 => 
            array (
                'id' => '01hdkrfhymr7nxvh1weer3dwnf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Population tracking and analysis',
                'name_fr' => 'Suivi et analyse de la population',
            ),
            271 => 
            array (
                'id' => '01hdkrfhymr7nxvh1weer3dwng',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pork',
                'name_fr' => 'Le cheptel porc',
            ),
            272 => 
            array (
                'id' => '01hdkrfhynrkyk1nw5ed7wk623',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pork behaviour and welfare',
                'name_fr' => 'Comportement et bien-être du porc',
            ),
            273 => 
            array (
                'id' => '01hdkrfhynrkyk1nw5ed7wk624',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pork carcass quality',
                'name_fr' => 'Qualité de la carcasse de porc',
            ),
            274 => 
            array (
                'id' => '01hdkrfhynrkyk1nw5ed7wk625',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pork lactation',
                'name_fr' => 'La lactation du porc',
            ),
            275 => 
            array (
                'id' => '01hdkrfhypmt4rt8ax679mrjy6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Post Combustion Capture',
                'name_fr' => 'Captage postcombustion',
            ),
            276 => 
            array (
                'id' => '01hdkrfhypmt4rt8ax679mrjy7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Post-harvest',
                'name_fr' => 'Postrécolte',
            ),
            277 => 
            array (
                'id' => '01hdkrfhyq1ctpgvteta2ej59t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Potato',
                'name_fr' => 'Pommes de terre',
            ),
            278 => 
            array (
                'id' => '01hdkrfhyq1ctpgvteta2ej59v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Potato plant',
                'name_fr' => 'Phytopathologie de la pomme de terre',
            ),
            279 => 
            array (
                'id' => '01hdkrfhyr2z3vezezamczjagq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Poultry',
                'name_fr' => 'le cheptel volaille',
            ),
            280 => 
            array (
                'id' => '01hdkrfhyr2z3vezezamczjagr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Powder Metallurgy',
                'name_fr' => 'Métallurgie des poudres',
            ),
            281 => 
            array (
                'id' => '01hdkrfhyskdg4snczd963w193',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Power bed fusion',
                'name_fr' => 'Fusion sur lit de poudre',
            ),
            282 => 
            array (
                'id' => '01hdkrfhyskdg4snczd963w194',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Power Cycles',
                'name_fr' => 'Impulsion motrice',
            ),
            283 => 
            array (
                'id' => '01hdkrfhyty3sxv60p5bs15b3n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Prairie Provinces',
                'name_fr' => 'Provinces des Prairies',
            ),
            284 => 
            array (
                'id' => '01hdkrfhyty3sxv60p5bs15b3p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Precambrian Geology',
                'name_fr' => 'Géologie Précambrien',
            ),
            285 => 
            array (
                'id' => '01hdkrfhyvcv3f09wgn1y82qgh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Precious Metals',
                'name_fr' => 'Métaux précieux',
            ),
            286 => 
            array (
                'id' => '01hdkrfhyw7ey9f9t6ewxzvb98',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Precipitation',
                'name_fr' => 'Précipitation',
            ),
            287 => 
            array (
                'id' => '01hdkrfhyw7ey9f9t6ewxzvb99',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Precipitation Processes and Measurement',
                'name_fr' => 'Mesures et processus liés aux précipitations',
            ),
            288 => 
            array (
                'id' => '01hdkrfhyw7ey9f9t6ewxzvb9a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Precision farming',
                'name_fr' => 'Agriculture de précision',
            ),
            289 => 
            array (
                'id' => '01hdkrfhyxskjayrx84hr09g7a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Predacious fly',
                'name_fr' => 'Des mouches prédatrices',
            ),
            290 => 
            array (
                'id' => '01hdkrfhyxskjayrx84hr09g7b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Prediction',
                'name_fr' => 'Prédiction',
            ),
            291 => 
            array (
                'id' => '01hdkrfhyy797c3jx34avn2qzb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Primary industry',
                'name_fr' => 'Industrie primaire',
            ),
            292 => 
            array (
                'id' => '01hdkrfhyy797c3jx34avn2qzc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Printable electronics',
                'name_fr' => 'Électronique imprimable',
            ),
            293 => 
            array (
                'id' => '01hdkrfhyzn3faqj88k9k3fw0y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Printing technologies',
                'name_fr' => 'Technologies d&#039;impression',
            ),
            294 => 
            array (
                'id' => '01hdkrfhyzn3faqj88k9k3fw0z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Prion',
                'name_fr' => 'Prions',
            ),
            295 => 
            array (
                'id' => '01hdkrfhz0rey09z55nncr1eqs',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Probiotics',
                'name_fr' => 'Probiotiques',
            ),
            296 => 
            array (
                'id' => '01hdkrfhz0rey09z55nncr1eqt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Processes Connectivity',
                'name_fr' => 'Connectivité des procédés',
            ),
            297 => 
            array (
                'id' => '01hdkrfhz1ghgw0257j5941kts',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Processing',
                'name_fr' => 'Transformation',
            ),
            298 => 
            array (
                'id' => '01hdkrfhz1ghgw0257j5941ktt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Production',
                'name_fr' => 'Production culturale',
            ),
            299 => 
            array (
                'id' => '01hdkrfhz2eh2z6anxp5gt4nzj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Production pathology',
                'name_fr' => 'Pathologie de la production',
            ),
            300 => 
            array (
                'id' => '01hdkrfhz2eh2z6anxp5gt4nzk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Profiles',
                'name_fr' => 'Profils',
            ),
            301 => 
            array (
                'id' => '01hdkrfhz2eh2z6anxp5gt4nzm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Program Management',
                'name_fr' => 'Gestion de programme',
            ),
            302 => 
            array (
                'id' => '01hdkrfhz34dn3cz57cy5rb92j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Project management',
                'name_fr' => 'Gestion de projet',
            ),
            303 => 
            array (
                'id' => '01hdkrfhz34dn3cz57cy5rb92k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Propulsion systems',
                'name_fr' => 'Systèmes de propulsion',
            ),
            304 => 
            array (
                'id' => '01hdkrfhz44fank7w8mjzkaw9h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Protected Areas',
                'name_fr' => 'Zones protégées',
            ),
            305 => 
            array (
                'id' => '01hdkrfhz44fank7w8mjzkaw9j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Protein',
                'name_fr' => 'Des protéines',
            ),
            306 => 
            array (
                'id' => '01hdkrfhz54mmy08ybv5v9972r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Protein Engineering',
                'name_fr' => 'Ingénierie des protéines',
            ),
            307 => 
            array (
                'id' => '01hdkrfhz54mmy08ybv5v9972s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Protein metabolism',
                'name_fr' => 'Métabolisme protéique',
            ),
            308 => 
            array (
                'id' => '01hdkrfhz6fbgm9k6cm5bnbyjm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Proteomics',
                'name_fr' => 'Protéomique',
            ),
            309 => 
            array (
                'id' => '01hdkrfhz6fbgm9k6cm5bnbyjn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Prototyping',
                'name_fr' => 'Prototypage',
            ),
            310 => 
            array (
                'id' => '01hdkrfhz6fbgm9k6cm5bnbyjp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Protozoa',
                'name_fr' => 'Protozoaires',
            ),
            311 => 
            array (
                'id' => '01hdkrfhz7yx9rxs1d9st56y9j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Psychology',
                'name_fr' => 'Psychologie',
            ),
            312 => 
            array (
                'id' => '01hdkrfhz806zv6sndmfcr3mnc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Psychosocial and Community Resilience',
                'name_fr' => 'Résilience psychosociale et communautaire',
            ),
            313 => 
            array (
                'id' => '01hdkrfhz806zv6sndmfcr3mnd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Public Health',
                'name_fr' => 'Santé publique',
            ),
            314 => 
            array (
                'id' => '01hdkrfhz9b6x40kmrf4jkwbad',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Public Service Health',
                'name_fr' => 'Santé des fonctionnaires fédéraux',
            ),
            315 => 
            array (
                'id' => '01hdkrfhz9b6x40kmrf4jkwbae',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Publishing and Editing',
                'name_fr' => 'Publication et édition',
            ),
            316 => 
            array (
                'id' => '01hdkrfhz9b6x40kmrf4jkwbaf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pulp',
                'name_fr' => 'Pulpe',
            ),
            317 => 
            array (
                'id' => '01hdkrfhzafzxmzc2gfc8k108m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pulp and paper',
                'name_fr' => 'Pâtes et papier',
            ),
            318 => 
            array (
                'id' => '01hdkrfhzafzxmzc2gfc8k108n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pulp and paper industry',
                'name_fr' => 'Industrie des pâtes et papiers',
            ),
            319 => 
            array (
                'id' => '01hdkrfhzbc2kfcge7n2zqrnkh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pulse',
                'name_fr' => 'Légumineuses',
            ),
            320 => 
            array (
                'id' => '01hdkrfhzcbe1kde44ra4rtbad',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Pulse diseases',
                'name_fr' => 'Maladies des légumineuses',
            ),
            321 => 
            array (
                'id' => '01hdkrfhzcbe1kde44ra4rtbae',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Quality assurance',
                'name_fr' => 'Assurance de la qualité',
            ),
            322 => 
            array (
                'id' => '01hdkrfhzd604azvdsbfx5vnje',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Quantum',
                'name_fr' => 'Quantique',
            ),
            323 => 
            array (
                'id' => '01hdkrfhzd604azvdsbfx5vnjf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Quantum communication components and networks',
                'name_fr' => 'Composants et réseaux pour la communication quantique',
            ),
            324 => 
            array (
                'id' => '01hdkrfhze2kqt2cydwks610cr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Quantum computing components and design',
                'name_fr' => 'Conception et composants de l&#039;informatique quantique',
            ),
            325 => 
            array (
                'id' => '01hdkrfhze2kqt2cydwks610cs',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Quantum PNT',
            'name_fr' => 'Positionnement, navigation et synchronisation (PNS) quantiques',
            ),
            326 => 
            array (
                'id' => '01hdkrfhzf77t1qgp39tw1n4k2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Quantum sensor technologies',
                'name_fr' => 'Technologies de capteurs quantiques',
            ),
            327 => 
            array (
                'id' => '01hdkrfhzf77t1qgp39tw1n4k3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Queen Charlotte Basin',
                'name_fr' => 'Bassin de la Reine Charlotte',
            ),
            328 => 
            array (
                'id' => '01hdkrfhzgqaeq0ranwk8tpz03',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Radar',
                'name_fr' => 'Radar',
            ),
            329 => 
            array (
                'id' => '01hdkrfhzgqaeq0ranwk8tpz04',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Radar &amp; Monitoring',
                'name_fr' => 'Radar et surveillance',
            ),
            330 => 
            array (
                'id' => '01hdkrfhzh2805p1w7q7xbay3j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'RADARSAT',
                'name_fr' => 'RADARSAT',
            ),
            331 => 
            array (
                'id' => '01hdkrfhzh2805p1w7q7xbay3k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Radiation',
                'name_fr' => 'Radiation',
            ),
            332 => 
            array (
                'id' => '01hdkrfhzjxz5j064gys0kd9hk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Radiation detection',
                'name_fr' => 'Détection du rayonnement',
            ),
            333 => 
            array (
                'id' => '01hdkrfhzjxz5j064gys0kd9hm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Radiation Emitting Devices',
                'name_fr' => 'Dispositifs émettant des radiations',
            ),
            334 => 
            array (
                'id' => '01hdkrfhzjxz5j064gys0kd9hn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Radiative transfer',
                'name_fr' => 'Transfert radiatif',
            ),
            335 => 
            array (
                'id' => '01hdkrfhzk1bm2ensc0rxew87v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Radio',
                'name_fr' => 'Radio',
            ),
            336 => 
            array (
                'id' => '01hdkrfhzk1bm2ensc0rxew87w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Radioactive Waste - High level',
                'name_fr' => 'Déchets hautement radioactifs',
            ),
            337 => 
            array (
                'id' => '01hdkrfhzm2epjjbgbrjy5dkfp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Radioactive Waste - Low level',
                'name_fr' => 'Déchets à radioactité faible',
            ),
            338 => 
            array (
                'id' => '01hdkrfhzm2epjjbgbrjy5dkfq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Radioactivity',
                'name_fr' => 'Radioactivité',
            ),
            339 => 
            array (
                'id' => '01hdkrfhzn8vdg3xjpqn8sz10y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Radiological-nuclear',
                'name_fr' => 'Radio-nucléaire',
            ),
            340 => 
            array (
                'id' => '01hdkrfhzpah866r6s0pmzbnhv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Radionavigation',
                'name_fr' => 'Radionavigation',
            ),
            341 => 
            array (
                'id' => '01hdkrfhzpah866r6s0pmzbnhw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rail',
                'name_fr' => 'Rail',
            ),
            342 => 
            array (
                'id' => '01hdkrfhzqvmhfsgadj1q0wnq8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rail vehicle engineering',
                'name_fr' => 'Génie des véhicules ferroviaires',
            ),
            343 => 
            array (
                'id' => '01hdkrfhzqvmhfsgadj1q0wnq9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rain &amp; Stormwater',
                'name_fr' => 'Pluie et eaux de ruissellement',
            ),
            344 => 
            array (
                'id' => '01hdkrfhzqvmhfsgadj1q0wnqa',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rainforest',
                'name_fr' => 'Forêt tropicale',
            ),
            345 => 
            array (
                'id' => '01hdkrfhzrw5e3p157t1v46md4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Range plant',
                'name_fr' => 'Plantes de parcours',
            ),
            346 => 
            array (
                'id' => '01hdkrfhzrw5e3p157t1v46md5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Raptors',
                'name_fr' => 'Rapaces',
            ),
            347 => 
            array (
                'id' => '01hdkrfhzsjzxnfs1awm5b79e6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rare Earth Metals',
                'name_fr' => 'Métaux des terres rares',
            ),
            348 => 
            array (
                'id' => '01hdkrfhzsjzxnfs1awm5b79e7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Re-evaluation of Older Products',
                'name_fr' => 'Réévaluation des produits plus anciens',
            ),
            349 => 
            array (
                'id' => '01hdkrfhztbsb53aee9vs0kxwh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Real Property',
                'name_fr' => 'Biens immobiliers',
            ),
            350 => 
            array (
                'id' => '01hdkrfhztbsb53aee9vs0kxwj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Real-time Simulation',
                'name_fr' => 'Simulation en temps réel',
            ),
            351 => 
            array (
                'id' => '01hdkrfhzvsb2rvn5gsfxfgrgs',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Recombination',
                'name_fr' => 'La recombinaison',
            ),
            352 => 
            array (
                'id' => '01hdkrfhzvsb2rvn5gsfxfgrgt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Recycling',
                'name_fr' => 'Recyclage',
            ),
            353 => 
            array (
                'id' => '01hdkrfhzwdmbf1cdnm5ja1j5j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Red River',
                'name_fr' => 'Rivière Rouge',
            ),
            354 => 
            array (
                'id' => '01hdkrfhzxexs98wg5xvmbvp9k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Refineries',
                'name_fr' => 'Raffineries',
            ),
            355 => 
            array (
                'id' => '01hdkrfhzxexs98wg5xvmbvp9m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Refining',
                'name_fr' => 'Raffinage',
            ),
            356 => 
            array (
                'id' => '01hdkrfhzyh7s63f9b2kdkwdsq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Regional Mapping',
                'name_fr' => 'Cartographie régionale',
            ),
            357 => 
            array (
                'id' => '01hdkrfhzyh7s63f9b2kdkwdsr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Regulation &amp; Enforcement',
                'name_fr' => 'Réglementation et application de la loi',
            ),
            358 => 
            array (
                'id' => '01hdkrfhzz52z8wm3kt7gcznr5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Regulatory Toxicology',
                'name_fr' => 'Toxicologie réglementaire',
            ),
            359 => 
            array (
                'id' => '01hdkrfhzz52z8wm3kt7gcznr6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rehabilitation',
                'name_fr' => 'Réhabilitation',
            ),
            360 => 
            array (
                'id' => '01hdkrfhzz52z8wm3kt7gcznr7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Remediation',
                'name_fr' => 'Restauration',
            ),
            361 => 
            array (
                'id' => '01hdkrfj001p9sm5x14bsp3mt7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Remote (off-grid) energy systems',
                'name_fr' => 'Filière énergétique éloignées/hors réseau',
            ),
            362 => 
            array (
                'id' => '01hdkrfj001p9sm5x14bsp3mt8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Remote Data Management',
                'name_fr' => 'Gestion des données à distance',
            ),
            363 => 
            array (
                'id' => '01hdkrfj01gqg3jbn529rr9wa7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Remote Predictive Mapping',
                'name_fr' => 'Télécartographie predictive',
            ),
            364 => 
            array (
                'id' => '01hdkrfj01gqg3jbn529rr9wa8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Remote sensing',
                'name_fr' => 'Télédétection',
            ),
            365 => 
            array (
                'id' => '01hdkrfj028v82fhhv9yv5bsr9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Remote Sensing - Hyperspectral',
                'name_fr' => 'Télédétection - hyperspectrale',
            ),
            366 => 
            array (
                'id' => '01hdkrfj028v82fhhv9yv5bsra',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Remote Sensing - Optical',
                'name_fr' => 'Télédétection - optique',
            ),
            367 => 
            array (
                'id' => '01hdkrfj03ed3m2zjg9kztjkg6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Remote Sensing - Synthetic Aperture Radar (SAR)',
                'name_fr' => 'Télédétection - Radar à synthèse d&#039;ouverture',
            ),
            368 => 
            array (
                'id' => '01hdkrfj03ed3m2zjg9kztjkg7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Renewable Energy',
                'name_fr' => 'Énergie renouvelable',
            ),
            369 => 
            array (
                'id' => '01hdkrfj04n0xw2mk9x7fj1fqz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Renewable Fuels',
                'name_fr' => 'Carburant renouvable',
            ),
            370 => 
            array (
                'id' => '01hdkrfj04n0xw2mk9x7fj1fr0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Renewables - Geothermal Energy',
                'name_fr' => 'Énergies renouvelables - Énergie géothermique',
            ),
            371 => 
            array (
                'id' => '01hdkrfj059kp27gwswnc8b6a9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Renewables - Hydroelectricity',
                'name_fr' => 'Énergies renouvelables - Hydroélectricité',
            ),
            372 => 
            array (
                'id' => '01hdkrfj059kp27gwswnc8b6aa',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Renewables - Marine Energy',
                'name_fr' => 'Énergies renouvelables – Marine',
            ),
            373 => 
            array (
                'id' => '01hdkrfj059kp27gwswnc8b6ab',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Renewables - Solar Energy',
                'name_fr' => 'Énergies renouvelables – Solaire',
            ),
            374 => 
            array (
                'id' => '01hdkrfj06wbrvtdg49tbabyyp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Renewables - Waste',
                'name_fr' => 'Énergies renouvelables – Déchets',
            ),
            375 => 
            array (
                'id' => '01hdkrfj06wbrvtdg49tbabyyq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Reproductive and Developmental Toxicology',
                'name_fr' => 'Toxicologie de la reproduction et du développement',
            ),
            376 => 
            array (
                'id' => '01hdkrfj0745f9hry78mxc90e7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Reproductive Technology',
                'name_fr' => 'Technologie de la reproduction',
            ),
            377 => 
            array (
                'id' => '01hdkrfj0745f9hry78mxc90e8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Reproductive Toxicology',
                'name_fr' => 'Toxicologie de la reproduction',
            ),
            378 => 
            array (
                'id' => '01hdkrfj0848xav1rwy9gpb0bx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Reprography',
                'name_fr' => 'Reprographie',
            ),
            379 => 
            array (
                'id' => '01hdkrfj0848xav1rwy9gpb0by',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Reptiles',
                'name_fr' => 'Reptiles',
            ),
            380 => 
            array (
                'id' => '01hdkrfj09ev4tqxa4tpqn7x8v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Reservoir',
                'name_fr' => 'Réservoir',
            ),
            381 => 
            array (
                'id' => '01hdkrfj09ev4tqxa4tpqn7x8w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Residential Contaminants',
                'name_fr' => 'Contaminants dans les résidences',
            ),
            382 => 
            array (
                'id' => '01hdkrfj0ahc8xt7wvf3rz9caa',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Residue',
                'name_fr' => 'Résidus',
            ),
            383 => 
            array (
                'id' => '01hdkrfj0ahc8xt7wvf3rz9cab',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Resource extraction impacts',
                'name_fr' => 'Incidence de l&#039;extraction des ressources',
            ),
            384 => 
            array (
                'id' => '01hdkrfj0bq4ftxrm0r3c02z2z',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Resource management',
                'name_fr' => 'Gestion des ressources',
            ),
            385 => 
            array (
                'id' => '01hdkrfj0bq4ftxrm0r3c02z30',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Resource Management Services',
                'name_fr' => 'Services de gestion des ressources',
            ),
            386 => 
            array (
                'id' => '01hdkrfj0cshbmebd2xqk0986b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Resources',
                'name_fr' => 'Ressources',
            ),
            387 => 
            array (
                'id' => '01hdkrfj0cshbmebd2xqk0986c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rhizobia',
                'name_fr' => 'Des rhizobiums',
            ),
            388 => 
            array (
                'id' => '01hdkrfj0cshbmebd2xqk0986d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rickettsia',
                'name_fr' => 'Rickettsies',
            ),
            389 => 
            array (
                'id' => '01hdkrfj0dzpt5cb992y4m7vyq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Riparian',
                'name_fr' => 'Rivulaire',
            ),
            390 => 
            array (
                'id' => '01hdkrfj0eh0eq0wym6h6312ey',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Riparian (shoreline) habitats',
                'name_fr' => 'Habitats riverains',
            ),
            391 => 
            array (
                'id' => '01hdkrfj0eh0eq0wym6h6312ez',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Risk analysis',
                'name_fr' => 'Analyse du risque',
            ),
            392 => 
            array (
                'id' => '01hdkrfj0f0wzxx4r5qsk6r2zg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Risk Assessment',
                'name_fr' => 'Évaluation des risques',
            ),
            393 => 
            array (
                'id' => '01hdkrfj0f0wzxx4r5qsk6r2zh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Risk Assessment Methodology',
                'name_fr' => 'Méthode d&#039;évaluation des risques',
            ),
            394 => 
            array (
                'id' => '01hdkrfj0gntpebaj46kfzw3x4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Risk management',
                'name_fr' => 'Gestion des risques',
            ),
            395 => 
            array (
                'id' => '01hdkrfj0gntpebaj46kfzw3x5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'River',
                'name_fr' => 'Rivière',
            ),
            396 => 
            array (
                'id' => '01hdkrfj0hdj15f6yc8a63qe7p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'River Engineering',
                'name_fr' => 'Génie fluvial',
            ),
            397 => 
            array (
                'id' => '01hdkrfj0hdj15f6yc8a63qe7q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'River Ice',
                'name_fr' => 'Glace de rivière',
            ),
            398 => 
            array (
                'id' => '01hdkrfj0hdj15f6yc8a63qe7r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Road',
                'name_fr' => 'Routier',
            ),
            399 => 
            array (
                'id' => '01hdkrfj0j198fxvggdh4xjnyh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Robotics',
                'name_fr' => 'Robotique',
            ),
            400 => 
            array (
                'id' => '01hdkrfj0j198fxvggdh4xjnyj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rock Breakage',
                'name_fr' => 'L&#039;abattage de roche',
            ),
            401 => 
            array (
                'id' => '01hdkrfj0kzazb7hc1ggy4w3tt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rocks - Igneous',
                'name_fr' => 'Roches - ignées',
            ),
            402 => 
            array (
                'id' => '01hdkrfj0kzazb7hc1ggy4w3tv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rocks - Metamorphic',
                'name_fr' => 'Roches - métamorphiques',
            ),
            403 => 
            array (
                'id' => '01hdkrfj0my76gj851nnwm0eqe',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rocks - Sedimentary',
                'name_fr' => 'Roches - sédimentaires',
            ),
            404 => 
            array (
                'id' => '01hdkrfj0my76gj851nnwm0eqf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rocky Mountains',
                'name_fr' => 'Les Rocheuses',
            ),
            405 => 
            array (
                'id' => '01hdkrfj0nbnc6n06e1skm0vtp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rolling',
                'name_fr' => 'Laminage',
            ),
            406 => 
            array (
                'id' => '01hdkrfj0nbnc6n06e1skm0vtq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rumen',
                'name_fr' => 'Rumen',
            ),
            407 => 
            array (
                'id' => '01hdkrfj0pbt3jss8524fh8nze',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rumen metagenomics',
                'name_fr' => 'Métagénomique du rumen',
            ),
            408 => 
            array (
                'id' => '01hdkrfj0pbt3jss8524fh8nzf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ruminant',
                'name_fr' => 'Ruminants',
            ),
            409 => 
            array (
                'id' => '01hdkrfj0q5w6qpbkm922dt19q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Rust and smut fungi',
                'name_fr' => 'Des champignons de la rouille et du charbon des plantes',
            ),
            410 => 
            array (
                'id' => '01hdkrfj0q5w6qpbkm922dt19r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Safety',
                'name_fr' => 'Sécurité',
            ),
            411 => 
            array (
                'id' => '01hdkrfj0rrrchegfacc9rqt1n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Safety and Security',
                'name_fr' => 'Sûreté et sécurité',
            ),
            412 => 
            array (
                'id' => '01hdkrfj0rrrchegfacc9rqt1p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Saint Lawrence River',
                'name_fr' => 'Fleuve Saint-Laurent',
            ),
            413 => 
            array (
                'id' => '01hdkrfj0rrrchegfacc9rqt1q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Saltwater intrusions',
                'name_fr' => 'Invasions d&#039;eau salée',
            ),
            414 => 
            array (
                'id' => '01hdkrfj0sxvj6p5pgdf1y1fhg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sampling Technologies',
                'name_fr' => 'Technologies d&#039;échantillonnage',
            ),
            415 => 
            array (
                'id' => '01hdkrfj0sxvj6p5pgdf1y1fhh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Saskatchewan River',
                'name_fr' => 'Rivière Saskatchewan',
            ),
            416 => 
            array (
                'id' => '01hdkrfj0tws5fn8cv2eqabqk1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Satellite Data',
                'name_fr' => 'Données satellitaires',
            ),
            417 => 
            array (
                'id' => '01hdkrfj0tws5fn8cv2eqabqk2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Satellite Imagery',
                'name_fr' => 'Imagerie satellitaire',
            ),
            418 => 
            array (
                'id' => '01hdkrfj0vatnf3vx7yr7nqdvr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Satellite remote sensing',
                'name_fr' => 'Télédétection par satellite',
            ),
            419 => 
            array (
                'id' => '01hdkrfj0vatnf3vx7yr7nqdvs',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Satellite systems',
                'name_fr' => 'Systèmes par satellite',
            ),
            420 => 
            array (
                'id' => '01hdkrfj0w94zzn4emafxvb46e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Satellite technology',
                'name_fr' => 'Technologie des satellites',
            ),
            421 => 
            array (
                'id' => '01hdkrfj0w94zzn4emafxvb46f',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Satellite telemetry',
                'name_fr' => 'Télémesure satellitaire',
            ),
            422 => 
            array (
                'id' => '01hdkrfj0x581yan7s1y5y6trt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Satellites',
                'name_fr' => 'Satellite',
            ),
            423 => 
            array (
                'id' => '01hdkrfj0x581yan7s1y5y6trv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sawmill',
                'name_fr' => 'Scierie',
            ),
            424 => 
            array (
                'id' => '01hdkrfj0ygm6zd9n549c6vz85',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Science &amp; Technology Communication',
                'name_fr' => 'Communications en science et techn.',
            ),
            425 => 
            array (
                'id' => '01hdkrfj0ygm6zd9n549c6vz86',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Science Assessment',
                'name_fr' => 'Évaluation scientifique',
            ),
            426 => 
            array (
                'id' => '01hdkrfj0zb4dedwwxaey3vzht',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Science management',
                'name_fr' => 'Gestion des sciences',
            ),
            427 => 
            array (
                'id' => '01hdkrfj0zb4dedwwxaey3vzhv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Science Policy',
                'name_fr' => 'Politique scientifique',
            ),
            428 => 
            array (
                'id' => '01hdkrfj1055f7fscj4q9338zy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Science-policy integration',
                'name_fr' => 'Intégration des politiques scientifiques',
            ),
            429 => 
            array (
                'id' => '01hdkrfj1055f7fscj4q9338zz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sciences',
                'name_fr' => 'Sciences',
            ),
            430 => 
            array (
                'id' => '01hdkrfj11kcm07361jnvazjvt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Scientific communications',
                'name_fr' => 'Communications scientifique',
            ),
            431 => 
            array (
                'id' => '01hdkrfj11kcm07361jnvazjvv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Scientific equipment',
                'name_fr' => 'Équipement scientifique',
            ),
            432 => 
            array (
                'id' => '01hdkrfj12qmk0w8rpw4ew6cjm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Scientific information',
                'name_fr' => 'Information scientifique',
            ),
            433 => 
            array (
                'id' => '01hdkrfj12qmk0w8rpw4ew6cjn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Scientific research',
                'name_fr' => 'Recherche scientifique',
            ),
            434 => 
            array (
                'id' => '01hdkrfj13k61kywb0e1mmhpsf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Scotian Shelf',
                'name_fr' => 'Plateau écossais',
            ),
            435 => 
            array (
                'id' => '01hdkrfj13k61kywb0e1mmhpsg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sea Ice',
                'name_fr' => 'Glace océanique',
            ),
            436 => 
            array (
                'id' => '01hdkrfj144gz4dn55are096e3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sea ice modelling',
                'name_fr' => 'Modélisation de la glace de mer',
            ),
            437 => 
            array (
                'id' => '01hdkrfj144gz4dn55are096e4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Seabirds',
                'name_fr' => 'Oiseaux marins',
            ),
            438 => 
            array (
                'id' => '01hdkrfj15mbncz17dw2vqm54c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Secondary metabolism',
                'name_fr' => 'Métabolisme secondaire',
            ),
            439 => 
            array (
                'id' => '01hdkrfj15mbncz17dw2vqm54d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Security Materials Tech',
                'name_fr' => 'Technologies des matériaux de sécurité',
            ),
            440 => 
            array (
                'id' => '01hdkrfj15mbncz17dw2vqm54e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sediment',
                'name_fr' => 'Sédiments',
            ),
            441 => 
            array (
                'id' => '01hdkrfj162qz77zsgr7gdm8ne',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sedimentology',
                'name_fr' => 'Sédimentologie',
            ),
            442 => 
            array (
                'id' => '01hdkrfj162qz77zsgr7gdm8nf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Seeds',
                'name_fr' => 'Semences',
            ),
            443 => 
            array (
                'id' => '01hdkrfj17n3gxd1by2epms0aw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Seismology',
                'name_fr' => 'Séismologie',
            ),
            444 => 
            array (
                'id' => '01hdkrfj17n3gxd1by2epms0ax',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Semiconductors',
                'name_fr' => 'Semiconducteurs',
            ),
            445 => 
            array (
                'id' => '01hdkrfj189sm9ymbrvbsppz8r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sensors',
                'name_fr' => 'Capteurs',
            ),
            446 => 
            array (
                'id' => '01hdkrfj189sm9ymbrvbsppz8s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sensory evaluation',
                'name_fr' => 'Analyse sensorielle',
            ),
            447 => 
            array (
                'id' => '01hdkrfj199wnbxyfgvy4ay9qt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sentiment Analysis',
                'name_fr' => 'Analyse de sentiments',
            ),
            448 => 
            array (
                'id' => '01hdkrfj199wnbxyfgvy4ay9qv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sequestration',
                'name_fr' => 'Phase emprisonnée',
            ),
            449 => 
            array (
                'id' => '01hdkrfj1azgnrax6pa7cw12jx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Severe Weather',
                'name_fr' => 'Temps violent',
            ),
            450 => 
            array (
                'id' => '01hdkrfj1azgnrax6pa7cw12jy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Shale',
                'name_fr' => 'Schiste',
            ),
            451 => 
            array (
                'id' => '01hdkrfj1azgnrax6pa7cw12jz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Shale gas',
                'name_fr' => 'Gaz de schiste',
            ),
            452 => 
            array (
                'id' => '01hdkrfj1b4ecprjz8rxvyqn49',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Shellfish/Invertebrates',
                'name_fr' => 'Mollusques/Crustacés/Invertébrés',
            ),
            453 => 
            array (
                'id' => '01hdkrfj1b4ecprjz8rxvyqn4a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Shorebirds',
                'name_fr' => 'Oiseaux de rivage',
            ),
            454 => 
            array (
                'id' => '01hdkrfj1chbq61a6fxyy5hjrp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Silviculture',
                'name_fr' => 'Sylviculture',
            ),
            455 => 
            array (
                'id' => '01hdkrfj1chbq61a6fxyy5hjrq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Simulation',
                'name_fr' => 'Simulation',
            ),
            456 => 
            array (
                'id' => '01hdkrfj1d3sw30xemst95eewy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Simulation &amp; Numerical Modelling',
                'name_fr' => 'Simulation et modélisation numérique',
            ),
            457 => 
            array (
                'id' => '01hdkrfj1d3sw30xemst95eewz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Simulation and Digital Health',
                'name_fr' => 'Simulation et Santé numérique',
            ),
            458 => 
            array (
                'id' => '01hdkrfj1e81w63eewgqd9sfbs',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Simulation and Predictive AI',
                'name_fr' => 'Simulation et IA prédictive',
            ),
            459 => 
            array (
                'id' => '01hdkrfj1f5mfyemz8fc7g7hvm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Simulations',
                'name_fr' => 'Simulations',
            ),
            460 => 
            array (
                'id' => '01hdkrfj1f5mfyemz8fc7g7hvn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Site remediation',
                'name_fr' => 'Assainissement des lieux',
            ),
            461 => 
            array (
                'id' => '01hdkrfj1g0rfwx04y7jq4mjgw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Small fruit entomology',
                'name_fr' => 'Entomologie des petits fruits',
            ),
            462 => 
            array (
                'id' => '01hdkrfj1g0rfwx04y7jq4mjgx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Smart Grid',
                'name_fr' => 'Réseau électrique intelligent',
            ),
            463 => 
            array (
                'id' => '01hdkrfj1hnqkr626sh87dg0mz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Smart Materials',
                'name_fr' => 'Matériaux intelligents',
            ),
            464 => 
            array (
                'id' => '01hdkrfj1hnqkr626sh87dg0n0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Smart Parts',
                'name_fr' => 'Pièces intelligentes',
            ),
            465 => 
            array (
                'id' => '01hdkrfj1j1agxpzqcnz8vfyp7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Smart Tooling',
                'name_fr' => 'Outillage intelligent',
            ),
            466 => 
            array (
                'id' => '01hdkrfj1j1agxpzqcnz8vfyp8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Smut, ergot and crown rust of oats',
                'name_fr' => 'Charbon, ergot et rouille de la couronne de l’avoine',
            ),
            467 => 
            array (
                'id' => '01hdkrfj1k8v68m0f9e8z14zqc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Snow',
                'name_fr' => 'Neige',
            ),
            468 => 
            array (
                'id' => '01hdkrfj1k8v68m0f9e8z14zqd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Snow Processes and Measurement',
                'name_fr' => 'Mesures et processus liés à la neige',
            ),
            469 => 
            array (
                'id' => '01hdkrfj1md3xyhj3rpwrtfkjq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Snowmelt',
                'name_fr' => 'Fonte des neiges',
            ),
            470 => 
            array (
                'id' => '01hdkrfj1md3xyhj3rpwrtfkjr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Snowpacks',
                'name_fr' => 'Stock nival',
            ),
            471 => 
            array (
                'id' => '01hdkrfj1md3xyhj3rpwrtfkjs',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Snowstorms',
                'name_fr' => 'Tempêtes de neige',
            ),
            472 => 
            array (
                'id' => '01hdkrfj1n5sn16588mcprxa0x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Social Sciences',
                'name_fr' => 'Sciences sociales',
            ),
            473 => 
            array (
                'id' => '01hdkrfj1n5sn16588mcprxa0y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sociology',
                'name_fr' => 'Sociologie',
            ),
            474 => 
            array (
                'id' => '01hdkrfj1pdvq3wybaz08b9vjb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Software',
                'name_fr' => 'Logiciel',
            ),
            475 => 
            array (
                'id' => '01hdkrfj1pdvq3wybaz08b9vjc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Software development',
                'name_fr' => 'Développement de logiciels',
            ),
            476 => 
            array (
                'id' => '01hdkrfj1q3eaahb74bfkb118p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil',
                'name_fr' => 'Sols',
            ),
            477 => 
            array (
                'id' => '01hdkrfj1q3eaahb74bfkb118q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil and crop nitrogen cycling',
                'name_fr' => 'Cycle de l’azote dans les sols et dans les cultures',
            ),
            478 => 
            array (
                'id' => '01hdkrfj1r7nhvqcejhbaycp5e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil and environmental science',
                'name_fr' => 'Science des sols et de l’environnement',
            ),
            479 => 
            array (
                'id' => '01hdkrfj1r7nhvqcejhbaycp5f',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil and geospatial science',
                'name_fr' => 'Sols et science géospatiale',
            ),
            480 => 
            array (
                'id' => '01hdkrfj1sx3g27dxwmph0xggv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil conservation',
                'name_fr' => 'Conservation des sols',
            ),
            481 => 
            array (
                'id' => '01hdkrfj1sx3g27dxwmph0xggw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil fertility',
                'name_fr' => 'Fertilité des sols',
            ),
            482 => 
            array (
                'id' => '01hdkrfj1t4rm5927x7dj3nfk2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil fertilization',
                'name_fr' => 'Fertilisation des sols',
            ),
            483 => 
            array (
                'id' => '01hdkrfj1t4rm5927x7dj3nfk3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil management',
                'name_fr' => 'Gestion des sols',
            ),
            484 => 
            array (
                'id' => '01hdkrfj1v044rzgdvfr87rbrn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil mite',
                'name_fr' => 'Des acariens des sols',
            ),
            485 => 
            array (
                'id' => '01hdkrfj1v044rzgdvfr87rbrp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil nutrients',
                'name_fr' => 'Éléments nutritifs du sols',
            ),
            486 => 
            array (
                'id' => '01hdkrfj1whar4p1ajgrvf3qss',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil pedology',
                'name_fr' => 'Pédologie des sols',
            ),
            487 => 
            array (
                'id' => '01hdkrfj1whar4p1ajgrvf3qst',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil physical quality',
                'name_fr' => 'Qualité physique des sols',
            ),
            488 => 
            array (
                'id' => '01hdkrfj1xvnbs6g9knfq65m2b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil physics',
                'name_fr' => 'Physique des sols',
            ),
            489 => 
            array (
                'id' => '01hdkrfj1xvnbs6g9knfq65m2c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil science',
                'name_fr' => 'Sciences des sols',
            ),
            490 => 
            array (
                'id' => '01hdkrfj1xvnbs6g9knfq65m2d',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil-plant-atmosphere interactions',
                'name_fr' => 'Interactions sol-plante-atmosphère',
            ),
            491 => 
            array (
                'id' => '01hdkrfj1ybwqksk8dhgjktr9x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soil-water-crop',
                'name_fr' => 'Sol-eau-culture',
            ),
            492 => 
            array (
                'id' => '01hdkrfj1zrq7kngt3b2j1m18k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Solar heating',
                'name_fr' => 'Chauffage solaire',
            ),
            493 => 
            array (
                'id' => '01hdkrfj203av7c525hhfsrt5b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Solar-terrestrial science',
                'name_fr' => 'Sciences Soleil-Terre',
            ),
            494 => 
            array (
                'id' => '01hdkrfj203av7c525hhfsrt5c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Solvent',
                'name_fr' => 'Solvant',
            ),
            495 => 
            array (
                'id' => '01hdkrfj21d2f777cpvx9bwcp4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Solvents',
                'name_fr' => 'Solvants',
            ),
            496 => 
            array (
                'id' => '01hdkrfj21d2f777cpvx9bwcp5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Songbirds',
                'name_fr' => 'Oiseaux chanteurs',
            ),
            497 => 
            array (
                'id' => '01hdkrfj21d2f777cpvx9bwcp6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Source Rocks',
                'name_fr' => 'Roches mères',
            ),
            498 => 
            array (
                'id' => '01hdkrfj22aee6x4jt9fekt1yp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'South Atlantic Ocean',
                'name_fr' => 'Océan Atlantique sud',
            ),
            499 => 
            array (
                'id' => '01hdkrfj22aee6x4jt9fekt1yq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soyabean',
                'name_fr' => 'Soya',
            ),
        ));
        \DB::table('expertises')->insert(array (
            0 => 
            array (
                'id' => '01hdkrfj23qbabr99qfe116vk5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Soybean quality',
                'name_fr' => 'Qualité des graines de soya',
            ),
            1 => 
            array (
                'id' => '01hdkrfj23qbabr99qfe116vk6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Space exploration',
                'name_fr' => 'Exploration spatiale',
            ),
            2 => 
            array (
                'id' => '01hdkrfj247rztc5htfew8ttn8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Space Physics',
                'name_fr' => 'Physique de l&#039;espace',
            ),
            3 => 
            array (
                'id' => '01hdkrfj247rztc5htfew8ttn9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Space sciences',
                'name_fr' => 'Sciences de l&#039;espace',
            ),
            4 => 
            array (
                'id' => '01hdkrfj25pchcf7gcs5wcraz7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Space station',
                'name_fr' => 'Station orbitale',
            ),
            5 => 
            array (
                'id' => '01hdkrfj25pchcf7gcs5wcraz8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Space technology',
                'name_fr' => 'Technologie spatiale',
            ),
            6 => 
            array (
                'id' => '01hdkrfj26p9zzpmqe0837cqfy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Space Weather',
                'name_fr' => 'Météo spatiale',
            ),
            7 => 
            array (
                'id' => '01hdkrfj26p9zzpmqe0837cqfz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Spatial analysis',
                'name_fr' => 'Analyse spatiale',
            ),
            8 => 
            array (
                'id' => '01hdkrfj26p9zzpmqe0837cqg0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Spatial Data Standards',
                'name_fr' => 'Normalisation des données spatiales',
            ),
            9 => 
            array (
                'id' => '01hdkrfj278fb2xmv2zjektf3k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Spatial Standards',
                'name_fr' => 'Normes spatiales',
            ),
            10 => 
            array (
                'id' => '01hdkrfj278fb2xmv2zjektf3m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Speciality coating',
                'name_fr' => 'Revêtements spécialisés',
            ),
            11 => 
            array (
                'id' => '01hdkrfj286h8ajpva1cn1hrs0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Species at risk',
                'name_fr' => 'Espèces en péril',
            ),
            12 => 
            array (
                'id' => '01hdkrfj286h8ajpva1cn1hrs1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Species at Risk Act',
                'name_fr' => 'Loi sur les espèces en péril',
            ),
            13 => 
            array (
                'id' => '01hdkrfj296k1eqjq7w2nhemca',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Spring wheat',
                'name_fr' => 'Blé de printemps',
            ),
            14 => 
            array (
                'id' => '01hdkrfj296k1eqjq7w2nhemcb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'SQL',
                'name_fr' => 'SQL',
            ),
            15 => 
            array (
                'id' => '01hdkrfj2avs9cbgxxs425gafg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Stable isotope ecology',
                'name_fr' => 'Milieu d&#039;isotope stable',
            ),
            16 => 
            array (
                'id' => '01hdkrfj2bkheddb8gx5f6d140',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Stable isotope fingerprinting',
                'name_fr' => 'Cartographie des isotopes stables',
            ),
            17 => 
            array (
                'id' => '01hdkrfj2bkheddb8gx5f6d141',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Stable Isotopes',
                'name_fr' => 'Isotopes stable',
            ),
            18 => 
            array (
                'id' => '01hdkrfj2bkheddb8gx5f6d142',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Stand-off CBRNE measurement',
                'name_fr' => 'Détection d&#039;agents CBRNE à distance',
            ),
            19 => 
            array (
                'id' => '01hdkrfj2c1psfcdssqh2a45h8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Standards',
                'name_fr' => 'Normes',
            ),
            20 => 
            array (
                'id' => '01hdkrfj2c1psfcdssqh2a45h9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Standards/Method Development',
                'name_fr' => 'Élaboration de normes et de méthodes',
            ),
            21 => 
            array (
                'id' => '01hdkrfj2d6evvsenwfymcwx6h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Starch-based functional food bioproducts',
                'name_fr' => 'Bioproduits des alimentaires fonctionnels à base d’amidon',
            ),
            22 => 
            array (
                'id' => '01hdkrfj2d6evvsenwfymcwx6j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Starch-based functional food industrial uses',
                'name_fr' => 'Applications industrielles des alimentaires fonctionnels à base d’amidon',
            ),
            23 => 
            array (
                'id' => '01hdkrfj2exyw53qs4f2vydzw8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Starch-based functional food ingredients',
                'name_fr' => 'Ingrédients alimentaires fonctionnels à base d’amidon',
            ),
            24 => 
            array (
                'id' => '01hdkrfj2exyw53qs4f2vydzw9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Starters',
                'name_fr' => 'Ferments',
            ),
            25 => 
            array (
                'id' => '01hdkrfj2fv2w53razm8xpb8n3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Static',
                'name_fr' => 'Statique',
            ),
            26 => 
            array (
                'id' => '01hdkrfj2gm7zkg6ea0b6rrv4a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Static Global Positioning',
                'name_fr' => 'Positionnement global statique',
            ),
            27 => 
            array (
                'id' => '01hdkrfj2gm7zkg6ea0b6rrv4b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Statistical Learning',
                'name_fr' => 'Apprentissage statistique',
            ),
            28 => 
            array (
                'id' => '01hdkrfj2hx77kncdmk6mdwa5r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Statistics',
                'name_fr' => 'Statisques',
            ),
            29 => 
            array (
                'id' => '01hdkrfj2hx77kncdmk6mdwa5s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Statistics and Economics',
                'name_fr' => 'Statistiques et économie',
            ),
            30 => 
            array (
                'id' => '01hdkrfj2jm7grd39xkx9z2a2p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Steam assisted gravity drainage (SAGD)',
            'name_fr' => 'Drainage par gravité au moyen de vapeur (DGMV)',
            ),
            31 => 
            array (
                'id' => '01hdkrfj2jm7grd39xkx9z2a2q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Steam Generation',
                'name_fr' => 'Production de vapeur',
            ),
            32 => 
            array (
                'id' => '01hdkrfj2jm7grd39xkx9z2a2r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Stem cells',
                'name_fr' => 'Cellule souche',
            ),
            33 => 
            array (
                'id' => '01hdkrfj2kx87xn61txhpd6p0b',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Stored product',
                'name_fr' => 'Produits entreposés',
            ),
            34 => 
            array (
                'id' => '01hdkrfj2kx87xn61txhpd6p0c',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Stormwater management',
                'name_fr' => 'Gestion des eaux de ruissellement',
            ),
            35 => 
            array (
                'id' => '01hdkrfj2m7br54wytzcwq5dpt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Stratigraphy',
                'name_fr' => 'Stratigraphie',
            ),
            36 => 
            array (
                'id' => '01hdkrfj2m7br54wytzcwq5dpv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Stream',
                'name_fr' => 'Ruisseau',
            ),
            37 => 
            array (
                'id' => '01hdkrfj2njvpxajsjp0q8j2tg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Street Dust',
                'name_fr' => 'Poussière de rue',
            ),
            38 => 
            array (
                'id' => '01hdkrfj2njvpxajsjp0q8j2th',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Stress',
                'name_fr' => 'Stress',
            ),
            39 => 
            array (
                'id' => '01hdkrfj2p6147m3qm11krwkdx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Structural Engineering',
                'name_fr' => 'Ingénierie structurale',
            ),
            40 => 
            array (
                'id' => '01hdkrfj2p6147m3qm11krwkdy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Structural Geology',
                'name_fr' => 'Géologie structural',
            ),
            41 => 
            array (
                'id' => '01hdkrfj2qxnbbkm9dez5a2dpa',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Structural Integrity',
                'name_fr' => 'Intégrité structurale',
            ),
            42 => 
            array (
                'id' => '01hdkrfj2qxnbbkm9dez5a2dpb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Sub-organismal level (cellular, molecular)',
            'name_fr' => 'Niveau des sous-organismes (cellulaire, moléculaire)',
            ),
            43 => 
            array (
                'id' => '01hdkrfj2rhrg10eaf8xggbxty',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Substance Use and Abuse',
                'name_fr' => 'Consommation et abus de substances',
            ),
            44 => 
            array (
                'id' => '01hdkrfj2rhrg10eaf8xggbxtz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Subsurface water inputs',
                'name_fr' => 'Alimentation en eau souterraine',
            ),
            45 => 
            array (
                'id' => '01hdkrfj2rhrg10eaf8xggbxv0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Supervised Learning',
                'name_fr' => 'Apprentissage supervisé',
            ),
            46 => 
            array (
                'id' => '01hdkrfj2seym707zxbtb16ape',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Surface analysis',
                'name_fr' => 'Analyse de surface',
            ),
            47 => 
            array (
                'id' => '01hdkrfj2seym707zxbtb16apf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Surficial Deposits',
                'name_fr' => 'Dépôts de surface',
            ),
            48 => 
            array (
                'id' => '01hdkrfj2tvj2kvf3m7fabeq96',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Surveying',
                'name_fr' => 'Arpentage',
            ),
            49 => 
            array (
                'id' => '01hdkrfj2tvj2kvf3m7fabeq97',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sustainability',
                'name_fr' => 'Durabilité',
            ),
            50 => 
            array (
                'id' => '01hdkrfj2v9g9md5by5c72pp5g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sustainable agriculture',
                'name_fr' => 'Agriculture durable',
            ),
            51 => 
            array (
                'id' => '01hdkrfj2v9g9md5by5c72pp5h',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sustainable development',
                'name_fr' => 'Développement durable',
            ),
            52 => 
            array (
                'id' => '01hdkrfj2w0bph5rggpgnwyrmd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sustainable Environmental Health',
                'name_fr' => 'Hygiène de l&#039;environnement durable',
            ),
            53 => 
            array (
                'id' => '01hdkrfj2w0bph5rggpgnwyrme',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sustainable production systems: HOLOS',
                'name_fr' => 'Systèmes de production durables : HOLOS',
            ),
            54 => 
            array (
                'id' => '01hdkrfj2w0bph5rggpgnwyrmf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Sustainable systems agronomy',
                'name_fr' => 'Agronomie des systèmes durables',
            ),
            55 => 
            array (
                'id' => '01hdkrfj2xsa7gy4p61tp7y84t',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Symbionts',
                'name_fr' => 'Symbionte',
            ),
            56 => 
            array (
                'id' => '01hdkrfj2xsa7gy4p61tp7y84v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Symbiotic nitrogen-fixing bacteria',
                'name_fr' => 'Bactéries symbiotiques fixatrices d’azote ',
            ),
            57 => 
            array (
                'id' => '01hdkrfj2yfff9504rb73j7wbx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Systematics',
                'name_fr' => 'Systématique',
            ),
            58 => 
            array (
                'id' => '01hdkrfj2yfff9504rb73j7wby',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Systems integration',
                'name_fr' => 'Intégration des systèmes',
            ),
            59 => 
            array (
                'id' => '01hdkrfj2z8gh0faefja9axxj6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
            'name_en' => 'Tachinid (parasitic) fly',
            'name_fr' => 'Des tachinaires (parasitoïdes)',
            ),
            60 => 
            array (
                'id' => '01hdkrfj2z8gh0faefja9axxj7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Taiga',
                'name_fr' => 'Taïga',
            ),
            61 => 
            array (
                'id' => '01hdkrfj30rf8h53y55rrypk9v',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Tailing',
                'name_fr' => 'Résidus miniers',
            ),
            62 => 
            array (
                'id' => '01hdkrfj31bnqv5k8d5jddafgd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Taste and odour',
                'name_fr' => 'Goût et odeur',
            ),
            63 => 
            array (
                'id' => '01hdkrfj31bnqv5k8d5jddafge',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Technoeconomic analysis',
                'name_fr' => 'Analyse technoéconomique',
            ),
            64 => 
            array (
                'id' => '01hdkrfj32s7b8jva9jppspxbs',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Technological innovation',
                'name_fr' => 'Innovation technologique',
            ),
            65 => 
            array (
                'id' => '01hdkrfj32s7b8jva9jppspxbt',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Technologies',
                'name_fr' => 'Technologies',
            ),
            66 => 
            array (
                'id' => '01hdkrfj32s7b8jva9jppspxbv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Technology',
                'name_fr' => 'Technologie',
            ),
            67 => 
            array (
                'id' => '01hdkrfj332gg3ta23w3dt3ga6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Technology policy',
                'name_fr' => 'Politique technologique',
            ),
            68 => 
            array (
                'id' => '01hdkrfj332gg3ta23w3dt3ga7',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Technology transfer',
                'name_fr' => 'Transfert technologique',
            ),
            69 => 
            array (
                'id' => '01hdkrfj34cmre33xvjckbz3p4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Tectonics',
                'name_fr' => 'Tectoniques',
            ),
            70 => 
            array (
                'id' => '01hdkrfj34cmre33xvjckbz3p5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Telecommunications',
                'name_fr' => 'Télécommunications',
            ),
            71 => 
            array (
                'id' => '01hdkrfj3504561222r6vpwjnr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Telecommunications networks',
                'name_fr' => 'Réseau de télécommunications',
            ),
            72 => 
            array (
                'id' => '01hdkrfj3504561222r6vpwjns',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Telecommunications policy',
                'name_fr' => 'Politique en matière de télécommunications',
            ),
            73 => 
            array (
                'id' => '01hdkrfj3601c4vkbbncwjvyse',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Teleconferencing',
                'name_fr' => 'Téléconférence',
            ),
            74 => 
            array (
                'id' => '01hdkrfj3601c4vkbbncwjvysf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Teleconnection',
                'name_fr' => 'Téléconnexion',
            ),
            75 => 
            array (
                'id' => '01hdkrfj37cnpp1g9c460z4hby',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Telehealth',
                'name_fr' => 'Télésanté',
            ),
            76 => 
            array (
                'id' => '01hdkrfj37cnpp1g9c460z4hbz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Telemedicine',
                'name_fr' => 'Télémédecine',
            ),
            77 => 
            array (
                'id' => '01hdkrfj385kq4rpxz71haqqbj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Telephones',
                'name_fr' => 'Téléphone',
            ),
            78 => 
            array (
                'id' => '01hdkrfj385kq4rpxz71haqqbk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Television',
                'name_fr' => 'Télévision',
            ),
            79 => 
            array (
                'id' => '01hdkrfj39809n1pj0t6x239zv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Terrestrial molluscs',
                'name_fr' => 'Mollusques terrestres',
            ),
            80 => 
            array (
                'id' => '01hdkrfj39809n1pj0t6x239zw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Testing',
                'name_fr' => 'Essai',
            ),
            81 => 
            array (
                'id' => '01hdkrfj3ap12jq70wae4tfkvd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Text Analytics',
                'name_fr' => 'Analytique du texte',
            ),
            82 => 
            array (
                'id' => '01hdkrfj3ap12jq70wae4tfkve',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Text Collection',
                'name_fr' => 'Collection de texte',
            ),
            83 => 
            array (
                'id' => '01hdkrfj3ap12jq70wae4tfkvf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Text Processing',
                'name_fr' => 'Traitement de texte',
            ),
            84 => 
            array (
                'id' => '01hdkrfj3b45pdsvxab0pcqk0f',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Therapeutics',
                'name_fr' => 'Thérapeutique',
            ),
            85 => 
            array (
                'id' => '01hdkrfj3b45pdsvxab0pcqk0g',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Thermal Energy Storage',
                'name_fr' => 'Stockage de l&#039;énergie thermique',
            ),
            86 => 
            array (
                'id' => '01hdkrfj3cbwajn69fzp5k0vva',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Thermal Maturity',
                'name_fr' => 'Maturité thermique',
            ),
            87 => 
            array (
                'id' => '01hdkrfj3cbwajn69fzp5k0vvb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Thermal Spray',
                'name_fr' => 'Projection thermique',
            ),
            88 => 
            array (
                'id' => '01hdkrfj3dxhnq22jgcxjpvvmg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Thermometry',
                'name_fr' => 'Thermométrie',
            ),
            89 => 
            array (
                'id' => '01hdkrfj3dxhnq22jgcxjpvvmh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Tides and Water Levels',
                'name_fr' => 'Marées et niveaux d&#039;eau',
            ),
            90 => 
            array (
                'id' => '01hdkrfj3enf4ttgkcer4v5477',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Tides, Water Levels, Storm Surge and Erosion Studies',
                'name_fr' => 'Études sur les marées, les niveaux d’eau, les ondes de tempête et l&#039;érosion',
            ),
            91 => 
            array (
                'id' => '01hdkrfj3enf4ttgkcer4v5478',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Time and Frequency',
                'name_fr' => 'Temps et fréquences',
            ),
            92 => 
            array (
                'id' => '01hdkrfj3f8943kv86f905je60',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Tobacco',
                'name_fr' => 'Tabac',
            ),
            93 => 
            array (
                'id' => '01hdkrfj3f8943kv86f905je61',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Tools',
                'name_fr' => 'Outillage',
            ),
            94 => 
            array (
                'id' => '01hdkrfj3g5w4m8atbsstbd7x3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Top predator species',
                'name_fr' => 'Espèces de prédateurs de niveau trophique supérieur',
            ),
            95 => 
            array (
                'id' => '01hdkrfj3g5w4m8atbsstbd7x4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Topographic',
                'name_fr' => 'Topographique',
            ),
            96 => 
            array (
                'id' => '01hdkrfj3hd4fzgfr9hehgypjv',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Topography',
                'name_fr' => 'Topographie',
            ),
            97 => 
            array (
                'id' => '01hdkrfj3hd4fzgfr9hehgypjw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Toponymy',
                'name_fr' => 'Toponymie',
            ),
            98 => 
            array (
                'id' => '01hdkrfj3jk1em6ccsck9250cb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Toxicity',
                'name_fr' => 'Toxicité',
            ),
            99 => 
            array (
                'id' => '01hdkrfj3jk1em6ccsck9250cc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Toxicogenomics',
                'name_fr' => 'Toxicogénomique',
            ),
            100 => 
            array (
                'id' => '01hdkrfj3k8emwvfspd1mck95m',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Toxicology',
                'name_fr' => 'Toxicologie',
            ),
            101 => 
            array (
                'id' => '01hdkrfj3k8emwvfspd1mck95n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Toxins',
                'name_fr' => 'Toxines',
            ),
            102 => 
            array (
                'id' => '01hdkrfj3m5afctyykpmfchsag',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Trace-metal dynamics',
                'name_fr' => 'Dynamique des éléments traces métalliques',
            ),
            103 => 
            array (
                'id' => '01hdkrfj3m5afctyykpmfchsah',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Track maintenance planning',
                'name_fr' => 'Planification de l&#039;entretien des voies ferrées',
            ),
            104 => 
            array (
                'id' => '01hdkrfj3n3edt13zvs1p37t8n',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Training',
                'name_fr' => 'Formation',
            ),
            105 => 
            array (
                'id' => '01hdkrfj3n3edt13zvs1p37t8p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Training Software',
                'name_fr' => 'Logiciels de formation',
            ),
            106 => 
            array (
                'id' => '01hdkrfj3p9z0ynv96vtmnnnz5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Transborder data flow',
                'name_fr' => 'Flux transfrontière de données',
            ),
            107 => 
            array (
                'id' => '01hdkrfj3p9z0ynv96vtmnnnz6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Translation',
                'name_fr' => 'Traduction',
            ),
            108 => 
            array (
                'id' => '01hdkrfj3qz3f0186knsxta2p0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Transparency',
                'name_fr' => 'Transparence',
            ),
            109 => 
            array (
                'id' => '01hdkrfj3qz3f0186knsxta2p1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Transportation',
                'name_fr' => 'Transport',
            ),
            110 => 
            array (
                'id' => '01hdkrfj3rbwyzjkn91g5j3cw0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Travel and Other Administrative Services',
                'name_fr' => 'Voyage et autres services administratifs',
            ),
            111 => 
            array (
                'id' => '01hdkrfj3rbwyzjkn91g5j3cw1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Treatment',
                'name_fr' => 'Traitement',
            ),
            112 => 
            array (
                'id' => '01hdkrfj3rbwyzjkn91g5j3cw2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Tree Breeding',
                'name_fr' => 'Amélioration des arbres',
            ),
            113 => 
            array (
                'id' => '01hdkrfj3s0pnv9gz603m4aerb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Tree development',
                'name_fr' => 'Développement des arbres',
            ),
            114 => 
            array (
                'id' => '01hdkrfj3s0pnv9gz603m4aerc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Tree fruit evaluation',
                'name_fr' => 'Évaluation des fruits de verger',
            ),
            115 => 
            array (
                'id' => '01hdkrfj3t1pkz17bnqgv03r9x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Trees',
                'name_fr' => 'Arbres',
            ),
            116 => 
            array (
                'id' => '01hdkrfj3t1pkz17bnqgv03r9y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Trends and variability',
                'name_fr' => 'Tendances et variabilité',
            ),
            117 => 
            array (
                'id' => '01hdkrfj3vvstwfekbqnm1a42r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Triage',
                'name_fr' => 'Triage',
            ),
            118 => 
            array (
                'id' => '01hdkrfj3vvstwfekbqnm1a42s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Triage Software',
                'name_fr' => 'Logiciels de triage',
            ),
            119 => 
            array (
                'id' => '01hdkrfj3w7q7wq11gz772tbhy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Triticale',
                'name_fr' => 'Triticale',
            ),
            120 => 
            array (
                'id' => '01hdkrfj3w7q7wq11gz772tbhz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Tsunamis',
                'name_fr' => 'Tsunami',
            ),
            121 => 
            array (
                'id' => '01hdkrfj3xjqwdr5bhyr3tf4fm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Tundra',
                'name_fr' => 'Toundra',
            ),
            122 => 
            array (
                'id' => '01hdkrfj3xjqwdr5bhyr3tf4fn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Ultra-fast laser technology',
                'name_fr' => 'Technologie laser ultrarapide',
            ),
            123 => 
            array (
                'id' => '01hdkrfj3xjqwdr5bhyr3tf4fp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Unconventional Energy',
                'name_fr' => 'Énergie non-conventionnelle',
            ),
            124 => 
            array (
                'id' => '01hdkrfj3yey9vt6cwy49hcwcx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Unsupervised Learning',
                'name_fr' => 'Apprentissage non supervisé',
            ),
            125 => 
            array (
                'id' => '01hdkrfj3yey9vt6cwy49hcwcy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Upgrading',
                'name_fr' => 'Valorisation',
            ),
            126 => 
            array (
                'id' => '01hdkrfj3ztgwzp8mmfe59p829',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Uranium',
                'name_fr' => 'Uranium',
            ),
            127 => 
            array (
                'id' => '01hdkrfj3ztgwzp8mmfe59p82a',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Urban',
                'name_fr' => 'Urbaine',
            ),
            128 => 
            array (
                'id' => '01hdkrfj4006h146fp3vhxh368',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Urban ecosystems',
                'name_fr' => 'Écosystèmes urbains',
            ),
            129 => 
            array (
                'id' => '01hdkrfj4006h146fp3vhxh369',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Urban Environments',
                'name_fr' => 'Milieu urbain',
            ),
            130 => 
            array (
                'id' => '01hdkrfj41srhz4ez7myzf32yk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Urban Forestry',
                'name_fr' => 'Foresterie urbaine',
            ),
            131 => 
            array (
                'id' => '01hdkrfj41srhz4ez7myzf32ym',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Urban Meteorology',
                'name_fr' => 'Météorologie urbaine',
            ),
            132 => 
            array (
                'id' => '01hdkrfj422zvr94g571va34ab',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'UV radiation',
                'name_fr' => 'Rayons UV',
            ),
            133 => 
            array (
                'id' => '01hdkrfj422zvr94g571va34ac',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Vaccine',
                'name_fr' => 'Vaccins',
            ),
            134 => 
            array (
                'id' => '01hdkrfj43jc597s78xe34e53x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Value added processing',
                'name_fr' => 'Transformation à valeur ajoutée',
            ),
            135 => 
            array (
                'id' => '01hdkrfj43jc597s78xe34e53y',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Vancouver Island Shelf',
                'name_fr' => 'Plateau de l&#039;île de Vancouver',
            ),
            136 => 
            array (
                'id' => '01hdkrfj444m5yhvewdzaf00cw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Vascular plants',
                'name_fr' => 'Plantes vasculaires',
            ),
            137 => 
            array (
                'id' => '01hdkrfj444m5yhvewdzaf00cx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Vegetable cropping systems',
                'name_fr' => 'Systèmes de culture de légumes',
            ),
            138 => 
            array (
                'id' => '01hdkrfj45y9dt7kyt3kabgch2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Vegetation',
                'name_fr' => 'Végétation',
            ),
            139 => 
            array (
                'id' => '01hdkrfj45y9dt7kyt3kabgch3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Vegetation Management',
                'name_fr' => 'Aménagement de la végétation',
            ),
            140 => 
            array (
                'id' => '01hdkrfj46tqcwk5yh66n8cp9j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Vehicle accident and incident resolution',
                'name_fr' => 'Résolution d&#039;accidents et d&#039;incidents de véhicule',
            ),
            141 => 
            array (
                'id' => '01hdkrfj46tqcwk5yh66n8cp9k',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Vehicle characterization',
                'name_fr' => 'Caractérisation de véhicules',
            ),
            142 => 
            array (
                'id' => '01hdkrfj47ytqv2jvxfp6p60cx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Vermiform',
                'name_fr' => 'Vermiformes',
            ),
            143 => 
            array (
                'id' => '01hdkrfj47ytqv2jvxfp6p60cy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Vertical Reference Systems',
                'name_fr' => 'Systèmes de référence verticaux',
            ),
            144 => 
            array (
                'id' => '01hdkrfj48rdqteqsr5tpy6gjn',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Veterinary diagnostics',
                'name_fr' => 'Diagnostic vétérinaire',
            ),
            145 => 
            array (
                'id' => '01hdkrfj48rdqteqsr5tpy6gjp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Veterinary Drugs',
                'name_fr' => 'Médicaments à usage vétérinaire',
            ),
            146 => 
            array (
                'id' => '01hdkrfj49drvjhftn109qbz47',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Virology',
                'name_fr' => 'Virologie',
            ),
            147 => 
            array (
                'id' => '01hdkrfj49drvjhftn109qbz48',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Virtual Assistants',
                'name_fr' => 'Assistants virtuels',
            ),
            148 => 
            array (
                'id' => '01hdkrfj4a0450m2z0972g0wk8',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Virus',
                'name_fr' => 'Virus',
            ),
            149 => 
            array (
                'id' => '01hdkrfj4a0450m2z0972g0wk9',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Virus-like organisms',
                'name_fr' => 'Organismes apparentés à des virus',
            ),
            150 => 
            array (
                'id' => '01hdkrfj4a0450m2z0972g0wka',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Vitamin metabolism in cows',
                'name_fr' => 'Métabolisme des vitamines chez la vache',
            ),
            151 => 
            array (
                'id' => '01hdkrfj4b2v8d5qwp71d7g6zr',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Vitamin metabolism in swine',
                'name_fr' => 'Métabolisme des vitamines chez le porc',
            ),
            152 => 
            array (
                'id' => '01hdkrfj4b2v8d5qwp71d7g6zs',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Viticulture',
                'name_fr' => 'Viticulture',
            ),
            153 => 
            array (
                'id' => '01hdkrfj4cqr493evt64880a2r',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Volatile Organic Compounds',
                'name_fr' => 'Composés organiques peu volatils',
            ),
            154 => 
            array (
                'id' => '01hdkrfj4cqr493evt64880a2s',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Volcanoes',
                'name_fr' => 'Volcan',
            ),
            155 => 
            array (
                'id' => '01hdkrfj4d6s4ykej3ytec5p64',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Volcanology',
                'name_fr' => 'Volcanologie',
            ),
            156 => 
            array (
                'id' => '01hdkrfj4d6s4ykej3ytec5p65',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Vulnerability thresholds',
                'name_fr' => 'Seuils de vulnérabilité',
            ),
            157 => 
            array (
                'id' => '01hdkrfj4eqp85hzy1fcpr00a5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Waste heat capture and utilization',
                'name_fr' => 'Capture et utilisation de la chaleur résiduelle',
            ),
            158 => 
            array (
                'id' => '01hdkrfj4eqp85hzy1fcpr00a6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Waste Management',
                'name_fr' => 'Gestion des déchets',
            ),
            159 => 
            array (
                'id' => '01hdkrfj4f3zabcswa567dh1qk',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Waste-to-Resource',
                'name_fr' => 'Déchets à ressources',
            ),
            160 => 
            array (
                'id' => '01hdkrfj4f3zabcswa567dh1qm',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Wastewater',
                'name_fr' => 'Eaux usées',
            ),
            161 => 
            array (
                'id' => '01hdkrfj4g7cnc4834b37qz7xw',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Water',
                'name_fr' => 'Eau',
            ),
            162 => 
            array (
                'id' => '01hdkrfj4g7cnc4834b37qz7xx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Water and nutrient management',
                'name_fr' => 'Gestion de l’eau et des éléments nutritifs',
            ),
            163 => 
            array (
                'id' => '01hdkrfj4h7rqxxw61nnj0kfa5',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Water management',
                'name_fr' => 'Gestion de l&#039;eau',
            ),
            164 => 
            array (
                'id' => '01hdkrfj4h7rqxxw61nnj0kfa6',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Water quality',
                'name_fr' => 'Qualité de l&#039;eau',
            ),
            165 => 
            array (
                'id' => '01hdkrfj4j0jexzqrfcsvd3jge',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Water quantity',
                'name_fr' => 'Quantité d&#039;eau',
            ),
            166 => 
            array (
                'id' => '01hdkrfj4j0jexzqrfcsvd3jgf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Water resource',
                'name_fr' => 'Ressources hydriques',
            ),
            167 => 
            array (
                'id' => '01hdkrfj4k7msb93j3kmx6xs5j',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Water resource management',
                'name_fr' => 'Gestion des ressources en eau',
            ),
            168 => 
            array (
                'id' => '01hdkrfj4mhdkjbs9r211bzsq2',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Water Treatment',
                'name_fr' => 'Traitement de l&#039;eau',
            ),
            169 => 
            array (
                'id' => '01hdkrfj4mhdkjbs9r211bzsq3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Waterbirds',
                'name_fr' => 'Oiseaux aquatiques',
            ),
            170 => 
            array (
                'id' => '01hdkrfj4mhdkjbs9r211bzsq4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Waterfowl',
                'name_fr' => 'Sauvagine',
            ),
            171 => 
            array (
                'id' => '01hdkrfj4n9ekmppy52gpc558w',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Watersheds',
                'name_fr' => 'Bassins hydrologiques',
            ),
            172 => 
            array (
                'id' => '01hdkrfj4n9ekmppy52gpc558x',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Weather &amp; Meteorology',
                'name_fr' => 'Temps et météorologie',
            ),
            173 => 
            array (
                'id' => '01hdkrfj4pynt0gnjhc4dnqh73',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Weather Modification Information Act',
                'name_fr' => 'Loi sur les renseignements en matière de modification du temps',
            ),
            174 => 
            array (
                'id' => '01hdkrfj4pynt0gnjhc4dnqh74',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Web Applications',
                'name_fr' => 'Applications Web',
            ),
            175 => 
            array (
                'id' => '01hdkrfj4qtz5h4yw1y1ef8bgz',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Web Sites',
                'name_fr' => 'Site Web',
            ),
            176 => 
            array (
                'id' => '01hdkrfj4qtz5h4yw1y1ef8bh0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Weed',
                'name_fr' => 'Mauvaises herbes',
            ),
            177 => 
            array (
                'id' => '01hdkrfj4rhy3nmmww358xbnd0',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Weed biology',
                'name_fr' => 'Biologie des mauvaises herbes',
            ),
            178 => 
            array (
                'id' => '01hdkrfj4rhy3nmmww358xbnd1',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Weed ecology',
                'name_fr' => 'Écologie des mauvaises herbes',
            ),
            179 => 
            array (
                'id' => '01hdkrfj4s4mjrremr3t5ff1fa',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Weed science',
                'name_fr' => 'Malherbologie',
            ),
            180 => 
            array (
                'id' => '01hdkrfj4s4mjrremr3t5ff1fb',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Weevil/pest beetle',
                'name_fr' => 'Des charançons/coléoptères indésirables',
            ),
            181 => 
            array (
                'id' => '01hdkrfj4s4mjrremr3t5ff1fc',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Welding',
                'name_fr' => 'Soudage',
            ),
            182 => 
            array (
                'id' => '01hdkrfj4tp06e7e5a6165b5se',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Western Canada',
                'name_fr' => 'L&#039;Ouest canadien',
            ),
            183 => 
            array (
                'id' => '01hdkrfj4tp06e7e5a6165b5sf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Western Queen Charlotte Margin',
                'name_fr' => 'Marge ouest de la Reine Charlotte',
            ),
            184 => 
            array (
                'id' => '01hdkrfj4vm5r0scwxs8xnc3bd',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Wetland ecology',
                'name_fr' => 'Écologie des zones humides',
            ),
            185 => 
            array (
                'id' => '01hdkrfj4vm5r0scwxs8xnc3be',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Wetlands',
                'name_fr' => 'Zones humides',
            ),
            186 => 
            array (
                'id' => '01hdkrfj4wssvv3kjydc7405ar',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Wheat',
                'name_fr' => 'Blé',
            ),
            187 => 
            array (
                'id' => '01hdkrfj4wssvv3kjydc7405as',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Wheat molecular',
                'name_fr' => 'Moléculaire du blé',
            ),
            188 => 
            array (
                'id' => '01hdkrfj4xk6pha7wn9vms2gfp',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Wheel/rail interactions',
                'name_fr' => 'Interactions roue-rail',
            ),
            189 => 
            array (
                'id' => '01hdkrfj4xk6pha7wn9vms2gfq',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Wildlife',
                'name_fr' => 'Faune',
            ),
            190 => 
            array (
                'id' => '01hdkrfj4y2h3k1p18cbvqe8v3',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Wildlife monitoring',
                'name_fr' => 'Surveillance de la faune',
            ),
            191 => 
            array (
                'id' => '01hdkrfj4y2h3k1p18cbvqe8v4',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Wildlife Populations',
                'name_fr' => 'Population faunique',
            ),
            192 => 
            array (
                'id' => '01hdkrfj4z1abzqy27wy1y7v4p',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Wind and wave analysis',
                'name_fr' => 'Analyse des vents et des vagues',
            ),
            193 => 
            array (
                'id' => '01hdkrfj4z1abzqy27wy1y7v4q',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Windsor-Quebec Corridor',
                'name_fr' => 'Corridor-Québec-Windsor',
            ),
            194 => 
            array (
                'id' => '01hdkrfj50m7c32xq5z6h5wras',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Wine',
                'name_fr' => 'Vin',
            ),
            195 => 
            array (
                'id' => '01hdkrfj50m7c32xq5z6h5wrat',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Winter wheat',
                'name_fr' => 'Blé d&#039;hiver',
            ),
            196 => 
            array (
                'id' => '01hdkrfj50m7c32xq5z6h5wrav',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Women&#039;s Health',
                'name_fr' => 'Santé des femmes',
            ),
            197 => 
            array (
                'id' => '01hdkrfj51nmc749jqva54njqx',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Wood Fibre',
                'name_fr' => 'Fibre de bois',
            ),
            198 => 
            array (
                'id' => '01hdkrfj51nmc749jqva54njqy',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Wood Preservation',
                'name_fr' => 'Préservation du bois',
            ),
            199 => 
            array (
                'id' => '01hdkrfj52qcacennv8s2x4p57',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Wood Supply',
                'name_fr' => 'Approvisionnement en bois',
            ),
            200 => 
            array (
                'id' => '01hdkrfj53w4zeh9xcmrk7nrkh',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Wood-based Panels',
                'name_fr' => 'Panneaux en fibre de bois',
            ),
            201 => 
            array (
                'id' => '01hdkrfj53w4zeh9xcmrk7nrkj',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Workplace Hazardous Materials Information System',
                'name_fr' => 'Système d&#039;information sur les matières dangereuses utilisées au travail',
            ),
            202 => 
            array (
                'id' => '01hdkrfj544se2jzv6zg9k5g1e',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Workplace Health',
                'name_fr' => 'Santé en milieu de travail',
            ),
            203 => 
            array (
                'id' => '01hdkrfj544se2jzv6zg9k5g1f',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Xenotransplantation',
                'name_fr' => 'Xénotransplantation',
            ),
            204 => 
            array (
                'id' => '01hdkrfj55ksjdj6999ykh7shf',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Yeast',
                'name_fr' => 'Levures',
            ),
            205 => 
            array (
                'id' => '01hdkrfj55ksjdj6999ykh7shg',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Zoonosis',
                'name_fr' => 'Zoonoses',
            ),
            206 => 
            array (
                'id' => '01hdkrfj56tgnbzv47s87twq35',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Zooplankton',
                'name_fr' => 'Zooplancton',
            ),
            207 => 
            array (
                'id' => '01hdkrfj56tgnbzv47s87twq36',
                'created_at' => '2023-10-25 15:50:33',
                'updated_at' => '2023-10-25 15:50:33',
                'name_en' => 'Zoosporic fungi',
                'name_fr' => 'Des champignons zoosporiques',
            ),
        ));
        
        
    }
}