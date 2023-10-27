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
                'created_at' => '2023-10-27 16:05:35',
                'id' => '01hdry4h2mg015dt4a0dz39dgh',
                'name_en' => '3D Geological Modeling',
                'name_fr' => 'Modélisation géologique 3D',
                'updated_at' => '2023-10-27 16:05:35',
            ),
            1 => 
            array (
                'created_at' => '2023-10-27 16:05:35',
                'id' => '01hdry4h32npcy6xzsxg7jg24c',
                'name_en' => 'Abiotic Stresses in Crop Plants',
                'name_fr' => 'Stress abiotiques chez les plantes cultivées',
                'updated_at' => '2023-10-27 16:05:35',
            ),
            2 => 
            array (
                'created_at' => '2023-10-27 16:05:35',
                'id' => '01hdry4h3grvfehqkvdfpynhg5',
                'name_en' => 'Aboriginal forestry',
                'name_fr' => 'Foresterie autochtone',
                'updated_at' => '2023-10-27 16:05:35',
            ),
            3 => 
            array (
                'created_at' => '2023-10-27 16:05:35',
                'id' => '01hdry4h3xyw3wm3p19jsa62e4',
                'name_en' => 'Access',
                'name_fr' => 'Accès',
                'updated_at' => '2023-10-27 16:05:35',
            ),
            4 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4h4h3sc98p6r1beazv5z',
                'name_en' => 'Acid rain',
                'name_fr' => 'Pluies acides',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            5 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4h56bd1pjcmzyqgtq6wf',
                'name_en' => 'Acidification and acid rain',
                'name_fr' => 'Acidification et pluies acides',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            6 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4h5p9d535ybncvrd74hp',
                'name_en' => 'Acoustics, Ultrasound and Vibration',
                'name_fr' => 'Acoustique, ultrasons et vibrations',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            7 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4h68db4krcexrqfr42tr',
                'name_en' => 'Acquiring',
                'name_fr' => 'Acquisition',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            8 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4h6m3m42cf3wvfgs6p45',
                'name_en' => 'Acquisitions',
                'name_fr' => 'Acquisitions',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            9 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4h728mmf7hq939407kqr',
                'name_en' => 'Adaptation',
                'name_fr' => 'Adaptation',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            10 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4h7jd9mj5m3qechdmgcm',
                'name_en' => 'Adaptation & Impacts',
                'name_fr' => 'Adaptation et incidence',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            11 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4h80ccg30y3b9re6a2nb',
                'name_en' => 'Additive Manufacturing',
                'name_fr' => 'Fabrication additive',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            12 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4h8jrd24hpqzs2m2rbvt',
                'name_en' => 'Additives',
                'name_fr' => 'Additifs',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            13 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4h915n5prknbemraehrn',
                'name_en' => 'Adhesive bonding',
                'name_fr' => 'Collage',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            14 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4h9f56ht0v3j7sr2q1ts',
                'name_en' => 'Advanced / Alternative Fuels',
                'name_fr' => 'Carburants de replacement / de pointe',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            15 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4h9zqv6d9dypxb302621',
                'name_en' => 'Advanced imaging',
                'name_fr' => 'Imagerie de pointe',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            16 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hawbn4s9gw1rp98yp38',
                'name_en' => 'Advanced Manufacturing',
                'name_fr' => 'Fabrication de pointe',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            17 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hbn2sf9s5bfrphey9c6',
                'name_en' => 'Advanced photonics sensing',
                'name_fr' => 'Détection photonique avancée',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            18 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hc5w5wgcjnbrxa2179c',
                'name_en' => 'Aerial insectivore birds',
                'name_fr' => 'Oiseaux insectivores aériens',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            19 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hcwhwnhac58mxdt5er4',
                'name_en' => 'Aerial Photography',
                'name_fr' => 'Photographie aérienne',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            20 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hdnsgnv53rvjagjg4sj',
                'name_en' => 'Aerodynamics',
                'name_fr' => 'Aérodynamique',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            21 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4he4zh254jbac4c6fgmc',
                'name_en' => 'Aeronautical Engineering',
                'name_fr' => 'Génie aéronautique',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            22 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hem3am2f5qw4wpfv0ab',
                'name_en' => 'Aerosol-cloud interactions',
                'name_fr' => 'Interactions entre les nuages et les aérosols',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            23 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hf4mdmmjazmwfknwj7r',
                'name_en' => 'Aerosols',
                'name_fr' => 'Aérosols',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            24 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hfkpfgzqw0pjdpv996d',
                'name_en' => 'Aerospace industry',
                'name_fr' => 'Industrie de l\'aérospatiale',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            25 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hgd781kh96dedj1tv4g',
                'name_en' => 'Agri-ecosystem',
                'name_fr' => 'Agroécosystèmes',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            26 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hgyqv59wac64gjh778b',
                'name_en' => 'Agri-Food',
                'name_fr' => 'Secteur agroalimentaire',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            27 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hhej517scxf25hf4da3',
                'name_en' => 'Agri-food industry',
                'name_fr' => 'Industrie agro-alimentaire',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            28 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hhyp27ck5jsnt48hfdy',
                'name_en' => 'Agricultural',
                'name_fr' => 'Agricole',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            29 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hjdyt5a3fw4nnvqttvs',
                'name_en' => 'Agricultural economics',
                'name_fr' => 'Agroéconomie',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            30 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hjwrc088yehcn3zhgrv',
                'name_en' => 'Agricultural impacts',
                'name_fr' => 'Incidence de l\'agriculture',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            31 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hkdcrp3gq65fcpqr8f4',
                'name_en' => 'Agricultural pests',
                'name_fr' => 'Organismes nuisibles de l’agriculture',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            32 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hkx8m6rjkc3a7barghv',
                'name_en' => 'Agricultural technology',
                'name_fr' => 'Technologie agricole',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            33 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hmdq5qvcyvbn8h24331',
                'name_en' => 'Agriculturally-significant photosynthetic bacteria',
                'name_fr' => 'Bactéries photosynthétiques d’importance agricole',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            34 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hmvrr32gvbqckgkawan',
                'name_en' => 'Agriculture',
                'name_fr' => 'Agriculture',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            35 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hn9dj98jwvz3900ystj',
                'name_en' => 'Agriculture-environment interactions',
                'name_fr' => 'Interactions entre l’agriculture et l’environnement',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            36 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hnqnr6vbmy3v82qdy4z',
                'name_en' => 'Agro-Ecosystem productivity and health',
                'name_fr' => 'Productivité des écosystèmes agricoles et santé',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            37 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hpdxfnekwb9kjarkk05',
                'name_en' => 'Agro-micrometeorology',
                'name_fr' => 'Agromicrométéorologie',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            38 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hpzddcfbpk55g9f4r6w',
                'name_en' => 'Agroclimatology',
                'name_fr' => 'Agroclimatologie',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            39 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hqhx298h7nftkjv70ww',
                'name_en' => 'Agroforestry',
                'name_fr' => 'Agroforesterie',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            40 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hr1q3cdcrea0xpt2kgm',
                'name_en' => 'Agrometeorology',
                'name_fr' => 'Agrométéorologie',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            41 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hrjqv88bnxgy78pdmxd',
                'name_en' => 'Agronomy',
                'name_fr' => 'Agronomie',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            42 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hs3zaqmm31w7mmbmc2y',
                'name_en' => 'Air',
                'name_fr' => 'Air',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            43 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hsrryfj3g2mmn0a5aep',
                'name_en' => 'Air Emissions',
                'name_fr' => 'Émissions atmosphériques',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            44 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4htd4vdmbxmfb3xry2jh',
                'name_en' => 'Air Pollution & Quality',
                'name_fr' => 'Pollution atmosphérique et qualité de l\'air',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            45 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4htwavg7de3k49vthxwe',
                'name_en' => 'Air Quality',
                'name_fr' => 'Qualité de l\'air',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            46 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hvbxfqgdevqz1d59p3k',
                'name_en' => 'Airborne measurements',
                'name_fr' => 'Mesures aériennes',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            47 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hvwwmtpqww1mp7kpbb3',
                'name_en' => 'Aircraft',
                'name_fr' => 'Aviation',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            48 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hwcd26k0nthws1zahrc',
                'name_en' => 'Alcohol',
                'name_fr' => 'Alcool',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            49 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hxba75ek560xt1jt6vm',
                'name_en' => 'Alfalfa',
                'name_fr' => 'La luzerne',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            50 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hxvsyw4cbd6fbfw28yc',
                'name_en' => 'Algae',
                'name_fr' => 'Algues',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            51 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hydmqbb595rkjmzbzve',
                'name_en' => 'Algal biotechnologies',
                'name_fr' => 'Biotechnologies algales',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            52 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hytbyqmyy9gesh7c7rs',
                'name_en' => 'Algal blooms',
                'name_fr' => 'Prolifération d\'algues',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            53 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hzawpn642yag0hepce9',
                'name_en' => 'Algal cultivation',
                'name_fr' => 'Culture des algues',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            54 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4hzv3zahe8r590jdfr6n',
                'name_en' => 'Algal ecology',
                'name_fr' => 'Écologie des algues',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            55 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4j0amkacv80y14gpcf5m',
                'name_en' => 'Algal genomics',
                'name_fr' => 'Génomique des algues',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            56 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4j0w5d0czp4yafc8smkb',
                'name_en' => 'Algal Technologies',
                'name_fr' => 'Technologies algales',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            57 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4j1cm90qzggstfwn5gka',
                'name_en' => 'Alien invasive species',
                'name_fr' => 'Espèces exotiques envahissantes',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            58 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4j1whp3e14hd5bcdbnv8',
                'name_en' => 'Alien Species',
                'name_fr' => 'Espèces exotiques',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            59 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4j2eh97msx75baen1733',
                'name_en' => 'Allergens',
                'name_fr' => 'Allergènes',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            60 => 
            array (
                'created_at' => '2023-10-27 16:05:36',
                'id' => '01hdry4j2sgg9hebymb7faqw3h',
                'name_en' => 'Alpine birds',
                'name_fr' => 'Oiseaux alpins',
                'updated_at' => '2023-10-27 16:05:36',
            ),
            61 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4j393htytzqspfdr1e8b',
                'name_en' => 'Alpine habitats',
                'name_fr' => 'Habitats alpins',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            62 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4j3qjbtrg1r89qjavmt9',
                'name_en' => 'Alternative crops and diversification',
                'name_fr' => 'Cultures de remplacement et diversification',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            63 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4j47pxmfwr5syjx2gczz',
                'name_en' => 'Alternative sources',
                'name_fr' => 'Sources de remplacement',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            64 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4j4r3d9f53w8b48d15ez',
                'name_en' => 'Aluminium',
                'name_fr' => 'Aluminium',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            65 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4j58e0dsbm3s8dfb7yg7',
                'name_en' => 'Amphibians',
                'name_fr' => 'Amphibiens',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            66 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4j5thf0tg6a88x0khyka',
                'name_en' => 'Analytical chemistry',
                'name_fr' => 'Chimie analytique',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            67 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4j6aqjcv7vdshs2q4qga',
                'name_en' => 'Analytical Method Development',
                'name_fr' => 'Élaboration de méthodes analytiques',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            68 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4j6sw5aatgz7t27stzgq',
                'name_en' => 'Analyzing',
                'name_fr' => 'Analyse',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            69 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4j7b39hsemrzwfw9tq55',
                'name_en' => 'Animal',
                'name_fr' => 'Animal',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            70 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4j846eg6j98ne07yc2dr',
                'name_en' => 'Animal cryobiology',
                'name_fr' => 'Cryobiologie animale',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            71 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4j8p5kz376yz9frprqtd',
                'name_en' => 'Animal diseases',
                'name_fr' => 'Maladies animales',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            72 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4j9a9nvwktvkpyhvgmcb',
                'name_en' => 'Animal metabolism',
                'name_fr' => 'Métabolisme animal',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            73 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jacxw20mrgf0ahtnaze',
                'name_en' => 'Animal Microbiology',
                'name_fr' => 'Microbiologie animale',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            74 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jay9bcqwrzcab9494r1',
                'name_en' => 'Animal Parasitology',
                'name_fr' => 'Parasitologie animale',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            75 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jbc2c305b87yrev8dp1',
                'name_en' => 'Animal welfare',
                'name_fr' => 'Bien-être des animaux',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            76 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jbvcd8cw297vmg6r52c',
                'name_en' => 'Anomaly Detection',
                'name_fr' => 'Détection d\'anomalies',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            77 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jcagmb2tcwj1kycv6v6',
                'name_en' => 'Anti-microbial resistance of microbes in food production',
                'name_fr' => 'Résistance antimicrobienne des microbes dans la production alimentaire',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            78 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jctq4tq7tcd8h0j273e',
                'name_en' => 'Anti-nutrients',
                'name_fr' => 'Anti-nutriments',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            79 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jdb96gafaemm00ptrpc',
                'name_en' => 'Antioxidant',
                'name_fr' => 'Agents antioxydants',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            80 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jdybfm86phx9bxbz251',
                'name_en' => 'Antioxidant chemistry',
                'name_fr' => 'Chimie des agents antioxydants',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            81 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jed36mgfm70ej0yhapm',
                'name_en' => 'Apiculture',
                'name_fr' => 'Apiculture',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            82 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jey14n0nng18jhxr0a0',
                'name_en' => 'Appalachians',
                'name_fr' => 'Appalaches',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            83 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jfft19xxms61z5e0b1h',
                'name_en' => 'Applied',
                'name_fr' => 'Appliquée',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            84 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jg0jvz4m75cwg1heg6n',
                'name_en' => 'Applied Psychology',
                'name_fr' => 'Psychologie appliquée',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            85 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jgjrdtnz3r78d228dyr',
                'name_en' => 'Aquaculture',
                'name_fr' => 'Aquaculture',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            86 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jh6xz9xg8f543rgjhy5',
                'name_en' => 'Aquatic Animal Health',
                'name_fr' => 'Santé de la faune aquatique',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            87 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jhp0mvj32bt2ct84t13',
                'name_en' => 'Aquatic Animal Health incl. Veterinary',
                'name_fr' => 'Santé des animaux aquatiques, y compris vétérinaire',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            88 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jj6mgb3437zcendpp84',
                'name_en' => 'Aquatic Ecosystems',
                'name_fr' => 'Écosystèmes aquatiques',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            89 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jjm40sgrvdp6jstna1h',
                'name_en' => 'Aquatic ecosystems science',
                'name_fr' => 'Science des écosystèmes aquatiques',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            90 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jk3dx2rsdwmay24b34a',
                'name_en' => 'Aquatic habitat/ aquatic environmental science',
                'name_fr' => 'Habitat aquatique/ Science de l’environnement aquatique',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            91 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jkk3gy0133mwb7kwq47',
                'name_en' => 'Aquatic invasive species',
                'name_fr' => 'Espèces aquatiques envahissantes',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            92 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jm4eqzfdy2df7hrx2f8',
                'name_en' => 'Aquatic organisms',
                'name_fr' => 'Organismes aquatiques',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            93 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jmm8ngebh8c4ky66hpd',
                'name_en' => 'Aquatic plant ecology',
                'name_fr' => 'Écologie des plantes aquatiques',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            94 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jn4ens5k6fzfwx9cnjr',
                'name_en' => 'Aquatic Science',
                'name_fr' => 'Sciences aquatiques',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            95 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jnmx33v6r0pckfkjy3m',
                'name_en' => 'Aquifer',
                'name_fr' => 'Aquifère',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            96 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jp9ez6p09mmt0cf4q98',
                'name_en' => 'Aquifers Assessment and Evaluation',
                'name_fr' => 'Charactérisation & évaluation des aquifères',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            97 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jpr2bgzzrgzpt57x20z',
                'name_en' => 'Arbuscular mycorrhizal fungi',
                'name_fr' => 'Des champignons mycorhiziens arbusculaires',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            98 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jq9n48hrj48bthnn5d3',
                'name_en' => 'Arctic',
                'name_fr' => 'Arctique',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            99 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jqs7rqxpv31r8ptqzf5',
                'name_en' => 'Arctic & Northern',
                'name_fr' => 'Arctique et Nord',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            100 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jre5j441ry7zpx6qagj',
                'name_en' => 'Arctic animal ecology',
                'name_fr' => 'Écologie animale de l\'Arctique',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            101 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jrwmqdhaeg2r7m5y5ww',
                'name_en' => 'Arctic environment',
                'name_fr' => 'Milieu arctique',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            102 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jsbew3t3j8bm4nnhqh4',
                'name_en' => 'Arctic Ocean',
                'name_fr' => 'Océan Arctique',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            103 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jstpskt7dgwnhd6dbz8',
                'name_en' => 'Armour',
                'name_fr' => 'Blindage',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            104 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jt7n1yadpz502ax9hmj',
                'name_en' => 'Arthropod',
                'name_fr' => 'Arthropodes',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            105 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jtnfab8d59b3xzrpeke',
                'name_en' => 'Artificial insemination',
                'name_fr' => 'Insémination artificielle',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            106 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jv4546s4cyqmk9p74k5',
                'name_en' => 'Artificial Intelligence',
                'name_fr' => 'Intelligence artificielle',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            107 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jvktxm67dnda8fb9f97',
                'name_en' => 'Assembling',
                'name_fr' => 'Assemblage',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            108 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jw6ggppp80jd2kzf6bb',
                'name_en' => 'Assessment',
                'name_fr' => 'Évaluation',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            109 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jwrr4qvfbvze1b2p3mm',
                'name_en' => 'Assessment and Quality System',
                'name_fr' => 'Système d\'évaluation et Système qualité',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            110 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jx8craf88szfv9y5fxv',
                'name_en' => 'Asset Management Services',
                'name_fr' => 'Services de gestion des biens',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            111 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jxn0tpj6dwpgzbq4qe7',
                'name_en' => 'Assisted Human Reproduction',
                'name_fr' => 'Procréation assistée au Canada',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            112 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jy25k31g43b0mkjh70t',
                'name_en' => 'Assistive Technologies',
                'name_fr' => 'Technologie d\'aide',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            113 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jykdqx27g43w56zm0tm',
                'name_en' => 'Astronaut training',
                'name_fr' => 'Entraînement des astronautes',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            114 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jz3ah7r66behr30kpgy',
                'name_en' => 'Astronomy',
                'name_fr' => 'Astronomie',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            115 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4jznavxften5md1cnkz8',
                'name_en' => 'Astrophysics',
                'name_fr' => 'Astrophysique',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            116 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4k05v03htgszvmhq14tf',
                'name_en' => 'Atlantic Canada',
                'name_fr' => 'Canada Atlantique',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            117 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4k0t75ae3ktzd6kagkpx',
                'name_en' => 'Atlantic Ocean',
                'name_fr' => 'Océan Atlantique',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            118 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4k193g28qjrfkahj81we',
                'name_en' => 'Atmospheric chemistry',
                'name_fr' => 'Chimie atmosphérique',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            119 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4k1qwzsjp8pfsabmb870',
                'name_en' => 'Atmospheric circulation',
                'name_fr' => 'Circulation atmosphérique',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            120 => 
            array (
                'created_at' => '2023-10-27 16:05:37',
                'id' => '01hdry4k28yyn1n4sphxvffgn4',
                'name_en' => 'Atmospheric environment',
                'name_fr' => 'Environnement atmosphérique',
                'updated_at' => '2023-10-27 16:05:37',
            ),
            121 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4k2qwe11th8c0x503rrh',
                'name_en' => 'Atmospheric Modelling',
                'name_fr' => 'Modélisation de l\'atmosphère',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            122 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4k3833vt27cyg8me8ttn',
                'name_en' => 'Atmospheric models',
                'name_fr' => 'Modèles atmosphériques',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            123 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4k3qgw4wpjqr0p8qs3dn',
                'name_en' => 'Atmospheric science',
                'name_fr' => 'Science de l’atmosphère',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            124 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4k49qa5m29qm8dv6bwam',
                'name_en' => 'Atmospheric transport',
                'name_fr' => 'Transport atmosphérique',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            125 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4k4tfv6ptn7szrmxextv',
                'name_en' => 'Atomics, molecular and optical physics',
                'name_fr' => 'Physique atomique, moléculaire et optique',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            126 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4k5adn3h5ary01p3yx8j',
                'name_en' => 'Attributes',
                'name_fr' => 'Caractéristiques',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            127 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4k5v461473tr912qm83y',
                'name_en' => 'Audiovisual equipment',
                'name_fr' => 'Équipement audiovisuel',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            128 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4k6brcc2ardxr261v8ym',
                'name_en' => 'Automated Decision Making Process',
                'name_fr' => 'Prise de décision automatisée',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            129 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4k6tv0xpw1fm0ggpcbgv',
                'name_en' => 'Automation',
                'name_fr' => 'Automatisation',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            130 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4k7dbkaj7zf47ykp9cjq',
                'name_en' => 'Automobile industry',
                'name_fr' => 'Industrie de l\'automobile',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            131 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4k7xrwq6dnxmapewer2g',
                'name_en' => 'Automotive',
                'name_fr' => 'Automobile',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            132 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4k8e8jjy3a05q489nzq5',
                'name_en' => 'Automotive Engineering',
                'name_fr' => 'Ingénierie automobile',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            133 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4k96nj3sda8xsa02jarg',
                'name_en' => 'Autonomous cars',
                'name_fr' => 'Véhicules autonomes',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            134 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4k9wsztzad7gqv2yn6bj',
            'name_en' => 'Avian (bird) nutrition',
                'name_fr' => 'Nutrition des oiseaux',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            135 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kandwqqf7ejz6he5jjk',
                'name_en' => 'Bacteria',
                'name_fr' => 'Bactéries',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            136 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kb6yv559kgb8g0trb14',
                'name_en' => 'Bacterial ecology',
                'name_fr' => 'Écologie bactérienne',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            137 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kbrtk64ad0cnzep9vfz',
                'name_en' => 'Bacterial genetics',
                'name_fr' => 'Génétique bactérienne',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            138 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kcazek3qgm17a9we3sd',
                'name_en' => 'Bacterial genomics',
                'name_fr' => 'Génomique bactérienne',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            139 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kcvzbpkmsvwkhw81d4y',
                'name_en' => 'Bacterial pathogens',
                'name_fr' => 'Agents pathogènes d\'origine bactérienne',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            140 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kdc56nzsqnjf1b4kvz2',
                'name_en' => 'Bacterial phylogenetics and systematics',
                'name_fr' => 'Systématique et phylogénétique bactériennes',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            141 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kdxpmkaar28ar0p9v1b',
                'name_en' => 'Bacterial population ecology and genetics',
                'name_fr' => 'Écologie et génétique des populations bactériennes',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            142 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4ken4qhzagqzxd57wxrx',
                'name_en' => 'Bacteriology',
                'name_fr' => 'Bactériologie',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            143 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kf5t0j8mzj9xg9pmjk5',
                'name_en' => 'Bacteriophages',
            'name_fr' => 'Bactériophages (virus bactériens) ',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            144 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kfnfwf15p5ayekdba8n',
                'name_en' => 'Baffin Bay',
                'name_fr' => 'Baie de Baffin',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            145 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kg7kx4a9annjbwgxj4h',
                'name_en' => 'Baffin Shelf',
                'name_fr' => 'Plate-forme de Baffin',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            146 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kgs2jn9kcv5pktymdzz',
                'name_en' => 'Barents and Kara Seas',
                'name_fr' => 'Mers de Barents et Kara',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            147 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4khawpk9mxr0mgs66ppa',
                'name_en' => 'Barley',
                'name_fr' => 'L\'orge',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            148 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4khtxjdz1dt7ta3qd7jq',
                'name_en' => 'Base Metals',
                'name_fr' => 'Métaux de base',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            149 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kjb5f8jf0jz894eqezm',
                'name_en' => 'Basin Analysis',
                'name_fr' => 'Analyse de bassin',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            150 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kjw9e4sygtakmsa3ftw',
                'name_en' => 'Basins - Offshore or Frontier',
                'name_fr' => 'Bassins extracôtiers ou limitrophes',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            151 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kkb0k6tw0yxpazaxard',
                'name_en' => 'Batteries',
                'name_fr' => 'Batteries',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            152 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kkxejdjgkx10yfjkwf4',
                'name_en' => 'Bay of Fundy',
                'name_fr' => 'Baie de Fundy',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            153 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kmatsttmqsakmm2pk2p',
                'name_en' => 'Bayesian Statistics',
                'name_fr' => 'Statistiques bayésiennes',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            154 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kmvbb9hg887wvbpa2td',
                'name_en' => 'BC Fjords/Coastal Waterways',
                'name_fr' => 'Les eaux côtières/Fjord CB',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            155 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4knchgw0qt0w1q18aned',
                'name_en' => 'Bean',
                'name_fr' => 'Haricots',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            156 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kntkb0b6p7baq2rag41',
                'name_en' => 'Beaufort Sea',
                'name_fr' => 'Mer de Beaufort',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            157 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kpfvhqqwpz7qdq7cg91',
                'name_en' => 'Bee',
                'name_fr' => 'Des abeilles',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            158 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kqax03hmf7rn4zngted',
                'name_en' => 'Beef',
                'name_fr' => 'Bœuf',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            159 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kqsjvg985sbymqjdmqa',
                'name_en' => 'Beneficial bacteria',
                'name_fr' => 'Bactéries bénéfiques',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            160 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kr9rkrcmjepfa6sz1ps',
            'name_en' => 'Beneficial Bioinoculants (Bacteria)',
            'name_fr' => 'Bio-inoculants bénéfiques (bactéries)',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            161 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4krvxq22w9q1sdm1medh',
                'name_en' => 'Beneficial management practices',
                'name_fr' => 'Pratiques de gestion bénéfique',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            162 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4ks978hb5h59b1gw9rv6',
                'name_en' => 'Benthic',
                'name_fr' => 'Benthique',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            163 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4ksr76hkfzq7qp2cm623',
                'name_en' => 'Benthic macroinvertebrates',
                'name_fr' => 'Macro-invertébrés benthiques',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            164 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kt6tp36q17pfdcysy8b',
                'name_en' => 'Benthic Species',
                'name_fr' => 'Espéces benthiques',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            165 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4ktq2kp3xhe6k25nnn1s',
                'name_en' => 'Big Data',
                'name_fr' => 'Mégadonnées',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            166 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kv9r93w6cqsxr1pse46',
                'name_en' => 'Bio-based products',
                'name_fr' => 'Produits biologiques',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            167 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kvvkzrgxx734d4qhzb3',
                'name_en' => 'Bioaccumulation/biomagnification',
                'name_fr' => 'Bioaccumulation/bioamplification',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            168 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kwbw5w9wzqthdh0j431',
                'name_en' => 'Bioactive characterization',
                'name_fr' => 'Caractérisation des substances bioactives',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            169 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kx08ahb2j3gj4x9s9sg',
                'name_en' => 'Bioactives',
                'name_fr' => 'Composés bioactifs',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            170 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kxkcj3dche7p7krg32f',
                'name_en' => 'Bioassay',
                'name_fr' => 'Épreuve biologique',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            171 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kyaw6w4pxvgfpatne1a',
                'name_en' => 'Bioassays, biomarkers',
                'name_fr' => 'Essais biologiques, biomarqueurs',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            172 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kytytv0ad3jak3rzyhj',
                'name_en' => 'Biochemistry',
                'name_fr' => 'Biochimie',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            173 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kzcve9ahndjwe8725dr',
                'name_en' => 'Bioclimatic',
                'name_fr' => 'Bioclimatique',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            174 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4kzw5kc46qtbrb3vpc1b',
                'name_en' => 'Bioclimatology',
                'name_fr' => 'Bioclimatologie',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            175 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4m0d7hrm3rajv6phyjyv',
                'name_en' => 'Biocontrol',
                'name_fr' => 'Lutte biologique',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            176 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4m0ymzhhs42v2hb1rhs3',
                'name_en' => 'Biodiversity',
                'name_fr' => 'Biodiversité',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            177 => 
            array (
                'created_at' => '2023-10-27 16:05:38',
                'id' => '01hdry4m1eakcmvvc1vx56sbv1',
                'name_en' => 'Biodiversity science',
                'name_fr' => 'Science de la biodiversité',
                'updated_at' => '2023-10-27 16:05:38',
            ),
            178 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4m1ybanmgp8tcbv8fq7q',
                'name_en' => 'Bioeconomy',
                'name_fr' => 'Bioéconomie',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            179 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4m2a9av7bftb1zenmjq4',
                'name_en' => 'Bioenergy',
                'name_fr' => 'Bioénergie',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            180 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4m30vymmdzy5sw91fjnf',
                'name_en' => 'Biofuel',
                'name_fr' => 'Biocombustible',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            181 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4m3k83fr3dne84dnh88b',
                'name_en' => 'Biofuels',
                'name_fr' => 'Biocarburants',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            182 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4m43fv530m00sak46g6q',
                'name_en' => 'Biogeochemistry',
                'name_fr' => 'Biogéochimie',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            183 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4m4jkxr93rymgw75kmdf',
                'name_en' => 'Bioindicator species',
                'name_fr' => 'Espèces indicatrices',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            184 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4m549pncfbg6vqzyebh9',
                'name_en' => 'Biological control of agricultural pests',
                'name_fr' => 'Lutte biologique aux organismes nuisibles de l’agriculture',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            185 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4m5n282tkszmnatgrjjy',
                'name_en' => 'Biological nitrogen fixation',
                'name_fr' => 'Fixation biologique de l’azote',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            186 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4m65n8907hqa0q5g2enx',
                'name_en' => 'Biological oceanography',
                'name_fr' => 'Océanographie biologique',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            187 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4m6qwd469r58zz5vz0jd',
                'name_en' => 'Biological testing',
                'name_fr' => 'Essai biologique',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            188 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4m7ancz7k8jt2w2qwjs3',
                'name_en' => 'Biological/genomics',
                'name_fr' => 'Biologie/génomique',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            189 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4m80cyqxpnq3799ya36q',
                'name_en' => 'Biologics and Radiopharmaceuticals',
                'name_fr' => 'Produits biologiques et radiopharmaceutiques',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            190 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4m8v956jfa0n99d7p5y1',
                'name_en' => 'Biology',
                'name_fr' => 'Biologie',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            191 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4m9hd61r1dt2p6qzc4z8',
                'name_en' => 'Biomass',
                'name_fr' => 'Biomasse',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            192 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4ma08cgwkb1s27ap7f9g',
                'name_en' => 'Biomass characterization',
                'name_fr' => 'Caractérisation de la biomasse',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            193 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mafx46ry7cfw18ka1kv',
                'name_en' => 'Biomass co-firing',
                'name_fr' => 'Cocuisson de la biomass',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            194 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mb06csgda7p6p98cyb6',
                'name_en' => 'Biomaterials',
                'name_fr' => 'Biomatériaux',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            195 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mbsnx7py7m1b4fdr17e',
                'name_en' => 'Biomathematics',
                'name_fr' => 'Biomathématiques',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            196 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mc61vn5xq1ejhexe930',
                'name_en' => 'Biomechanical Models',
                'name_fr' => 'Biomechanical Models',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            197 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mcm9j8gd3zfehbw0zv0',
                'name_en' => 'Biomedical Data Science',
                'name_fr' => 'Science des données biomédicales',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            198 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4md46tj15k93nxt3ayh8',
                'name_en' => 'Biomedical Experiments',
                'name_fr' => 'Expérimentation biomédicale',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            199 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mdp7b2y2mhsgaa247ch',
                'name_en' => 'Biomedical Mechatronics',
                'name_fr' => 'Mécatronique biomédicale',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            200 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4me7t9wbekw3y71kgj40',
                'name_en' => 'Biomedical Software',
                'name_fr' => 'Logiciels biomédicaux',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            201 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4merbpwcfgdwhgw3tbva',
                'name_en' => 'Biometrics for National Security',
                'name_fr' => 'Biométrie pour la sécurité nationale',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            202 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mfgrd5yhwz42wba3b95',
                'name_en' => 'Biometry',
                'name_fr' => 'Biométrie',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            203 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mg1bskj11nh94e1t1s6',
                'name_en' => 'Biomonitoring',
                'name_fr' => 'Biosurveillance',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            204 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mgj8m5na7dv8sswg9cj',
                'name_en' => 'Biopesticides',
                'name_fr' => 'Biopesticides',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            205 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mh3cncyp2mgahy56f97',
                'name_en' => 'Biopolymers',
                'name_fr' => 'Biopolymères',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            206 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mhn3zxhfnwy0p5xw0sx',
                'name_en' => 'Bioprocessing',
                'name_fr' => 'Biotransformation',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            207 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mj5ztgyjg7hk3091p8h',
                'name_en' => 'Bioproducts',
                'name_fr' => 'Bioproduits',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            208 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mjm39yra3zqj42cpb9p',
                'name_en' => 'Biorefinery',
                'name_fr' => 'Bioraffinerie',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            209 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mk4qaaf0pbpmqvr6pnk',
                'name_en' => 'Bioresources',
                'name_fr' => 'Les bioressources',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            210 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mknff3jeejewrp7enc2',
                'name_en' => 'Biosimilars',
                'name_fr' => 'Produits biosimilaires',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            211 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mm3tm667cmqwf73g1zv',
                'name_en' => 'Biostatistics',
                'name_fr' => 'Biostatistiques',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            212 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mmgxwj26hz5zrzz3ydx',
                'name_en' => 'Biosystems',
                'name_fr' => 'Biosystèmes',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            213 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mn00erzmbjym4mdn460',
                'name_en' => 'Biotechnology',
                'name_fr' => 'Biotechnologie',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            214 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mnecqv0v2jkzbger03h',
                'name_en' => 'Biotechnology/genomics',
                'name_fr' => 'Biotechnologie/génomique',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            215 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mnyb65dpger7t86z3jv',
                'name_en' => 'Biotherapeutics',
                'name_fr' => 'Produits biothérapeutiques',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            216 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mpezcc6sxvj9kw81347',
                'name_en' => 'Biotic Stresses in Crop Plants',
                'name_fr' => 'Stress biotiques chez les plantes cultivées',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            217 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mpybxe5dx6hra7qpx9t',
                'name_en' => 'Bird populations',
                'name_fr' => 'Population d\'oiseaux',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            218 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mqgdvmpkqfq2g00xp46',
                'name_en' => 'Birds',
                'name_fr' => 'Oiseaux',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            219 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mr131bkk23wnp9q6j1k',
                'name_en' => 'Bitumen',
                'name_fr' => 'Bitume',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            220 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mrjebs57h1qpqxhpgxa',
                'name_en' => 'Blast Effects',
                'name_fr' => 'Effets des explosifs',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            221 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4ms17k4vvpav8ve1j4et',
                'name_en' => 'Blockchain',
                'name_fr' => 'Chaîne de blocs',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            222 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mshr1aqmvrzkgrgx0k9',
                'name_en' => 'Blue-green algae',
                'name_fr' => 'Algues bleues',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            223 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mt10n9p0yr15eka5kb7',
                'name_en' => 'Border and Transportation Security',
                'name_fr' => 'Sécurité des frontières et du transport',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            224 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mthqcvhyp7ty296h1sm',
                'name_en' => 'Boreal forest',
                'name_fr' => 'Forêt boréale',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            225 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mv0kp2dygw6s6t81dn0',
                'name_en' => 'Botany',
                'name_fr' => 'Botanique',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            226 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mvmjh059735eazg6t75',
            'name_en' => 'Braconid (parasitic) wasp',
            'name_fr' => 'Des braconidés (parasitoïdes)',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            227 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mw65kreywcmfvjm8xxn',
                'name_en' => 'Brain-computer interface',
                'name_fr' => 'Interface cervaux-machine',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            228 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mwr4fb61eg461swcxhk',
                'name_en' => 'Brassicaceae',
                'name_fr' => 'Des brassicacées',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            229 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mx8q6vd1j2hcxmyd0c7',
                'name_en' => 'Brassicaceae biosafety',
                'name_fr' => 'Biosécurité des brassicacées',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            230 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mxs89gc99d9ye55p14k',
                'name_en' => 'Breeding',
                'name_fr' => 'Sélection',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            231 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mydhn3r2370nrj4e5en',
                'name_en' => 'Broadcasting',
                'name_fr' => 'Radiodiffusion',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            232 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mz32r5jcqx30a3cjgac',
                'name_en' => 'Broadcasting industry',
                'name_fr' => 'Industrie de la radiodiffusion',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            233 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4mzrb76cycp20szadjgf',
            'name_en' => 'Brominated flame retardants (BFRs)',
            'name_fr' => 'Ignifugeants bromés (IB)',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            234 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4n06wn3gb59mc5mf57gk',
                'name_en' => 'Building codes, regulations and standards',
                'name_fr' => 'Codes, réglementation et normes du bâtiment',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            235 => 
            array (
                'created_at' => '2023-10-27 16:05:39',
                'id' => '01hdry4n0qthg2qhrh4p0skrjb',
                'name_en' => 'Building materials',
                'name_fr' => 'Matériaux de construction',
                'updated_at' => '2023-10-27 16:05:39',
            ),
            236 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n17kfc4ns8nhcfra3ww',
                'name_en' => 'Building occupant safety and security',
                'name_fr' => 'Sécurité des occupants des bâtiments',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            237 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n1t4c2gd50ja7ggnexz',
                'name_en' => 'Business understanding',
                'name_fr' => 'Compréhension du monde des affaires',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            238 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n28hn5vy9gm0775emkv',
                'name_en' => 'Cable television',
                'name_fr' => 'Câblodistribution',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            239 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n2tmbtqzg9g27jhf9yp',
                'name_en' => 'Calibration',
                'name_fr' => 'Étalonnage',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            240 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n39m6wbycdr5n39c6z5',
                'name_en' => 'Calibration/Validation',
                'name_fr' => 'Calibration/Validation',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            241 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n3saf2by52ychc8jkfd',
                'name_en' => 'Canada Health Act Administration',
                'name_fr' => 'Administration de le Loi canadienne sur la santé',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            242 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n47vrfd6semq6mzemtk',
                'name_en' => 'Canada Wildlife Act',
                'name_fr' => 'Loi sur les espèces sauvages au Canada',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            243 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n4mc150ddx63vm16ktx',
                'name_en' => 'Canada\'s Extended Continental Shelf',
                'name_fr' => 'Plateau continental étendu du Canada',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            244 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n55109c3xvby8pt1yya',
                'name_en' => 'Canada-wide Strategy for the Management of Municipal Wastewater Effluent',
                'name_fr' => 'Stratégie pancanadienne sur la gestion des effluents d\'eaux usées municipales',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            245 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n5tf3ntq0vsn70bg0vy',
                'name_en' => 'Canadian crop and crop relative',
                'name_fr' => 'Des cultures canadiennes et apparentées',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            246 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n6ccnmdswmf5ms01zxh',
                'name_en' => 'Canadian Environmental Assessment Act',
                'name_fr' => 'Loi canadienne sur l\'évaluation environnementale',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            247 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n6tdw4yyxq43rnvygd4',
            'name_en' => 'Canadian Environmental Protection Act (1999)',
            'name_fr' => 'Loi canadienne sur la protection de l\'environnement (1999)',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            248 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n7h4acjh7h5a6r1d4jg',
                'name_en' => 'Canadian Health System',
                'name_fr' => 'Système de santé au Canada',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            249 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n89rd800z16w2j70wg6',
                'name_en' => 'Cancer',
                'name_fr' => 'Cancer',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            250 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n92f3tcse0w86kewted',
                'name_en' => 'Cancer Risk Assessment',
                'name_fr' => 'Évaluation des risques de cancer',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            251 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4n9hh6x71mrdryp4xmws',
                'name_en' => 'Canola',
                'name_fr' => 'Canola',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            252 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4na29m6wjv2g8rth3x81',
                'name_en' => 'Carbohydrate',
                'name_fr' => 'Des glucides',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            253 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nak3a901a792ej53fm3',
                'name_en' => 'Carbon budget',
                'name_fr' => 'Bilan du carbone',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            254 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nb4cj20w9mkyb8wpgjj',
                'name_en' => 'Carbon capture, utilization & storage',
                'name_fr' => 'Stockage, utilisation & séquestration du carbonne',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            255 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nbnsfz1m093sf5bxddv',
                'name_en' => 'Carbon sequestering',
                'name_fr' => 'Séquestration de carbone',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            256 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nc7p0m4b4350sm8e62k',
                'name_en' => 'Carcass grading',
                'name_fr' => 'Classification des carcasses',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            257 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4ncvbrsdkmxtwk3yza8j',
                'name_en' => 'Caribou',
                'name_fr' => 'Caribou',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            258 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4ndcm6423qwg7cwc09av',
                'name_en' => 'Cartography',
                'name_fr' => 'Cartographie',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            259 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4ne1117dr5pcbhctq95g',
                'name_en' => 'Casting',
                'name_fr' => 'Moulage',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            260 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4neskeeverd9cdhcgc41',
                'name_en' => 'Catalyst',
                'name_fr' => 'Catalysateur',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            261 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nfaa0cm8fz5rfdj2vaw',
                'name_en' => 'Cavity-nesting species',
                'name_fr' => 'Oiseaux cavernicoles',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            262 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nfsc7tcb4p314bxe7s9',
                'name_en' => 'Cellular interactions',
                'name_fr' => 'Interactions cellulaires',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            263 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4ng8b0xxr4v56b3rxmz0',
                'name_en' => 'Cellular telephones',
                'name_fr' => 'Téléphone cellulaire',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            264 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4ngtp8gktcft4208071r',
                'name_en' => 'Central Canada',
                'name_fr' => 'Canada central',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            265 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nhb0fycvtbe45j92qea',
                'name_en' => 'Cereal',
                'name_fr' => 'Céréales',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            266 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nhvpa53w2aje4snzamm',
                'name_en' => 'Cereal Chemistry',
                'name_fr' => 'Chimie céréalière',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            267 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4njbgstnc0j38zjpmze3',
                'name_en' => 'Cereal molecular',
                'name_fr' => 'Moléculaire des céréales',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            268 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4njv19bs3x10g7qgbqe8',
                'name_en' => 'Cereal stem rust',
                'name_fr' => 'Rouille de la tige des céréales',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            269 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nk8pqjvbfb7pjfhfcet',
                'name_en' => 'Cereals',
                'name_fr' => 'Les céréales',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            270 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nkpxe3x02zjk91wtt9b',
                'name_en' => 'Cereals and pulses',
                'name_fr' => 'Céréales et grain',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            271 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nm61sqp2m7fp4r6tem7',
                'name_en' => 'Cereals quality',
                'name_fr' => 'Qualité des céréales',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            272 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nmkgec1e76xb2tr4cch',
                'name_en' => 'Characterization',
                'name_fr' => 'Caractérisation',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            273 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nn3s2fmcv7gjtk577gv',
                'name_en' => 'Chargers',
                'name_fr' => 'Chargeurs de batteries',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            274 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nnh45q3g6y8ejn84p9j',
                'name_en' => 'Cheese technology',
                'name_fr' => 'Technologie fromagère',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            275 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4np007tmn7wp8d1g5k0h',
                'name_en' => 'Chemical',
                'name_fr' => 'Chimie',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            276 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4npf78pfbk95dqqbzytz',
                'name_en' => 'Chemical analysis',
                'name_fr' => 'Analyses chimiques',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            277 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nq0pxk3e6z541wwd1hf',
                'name_en' => 'Chemical and biological analysis',
                'name_fr' => 'Analyse chimique et biologique',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            278 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nqbbw6cg590sdprwj02',
                'name_en' => 'Chemical ecology',
                'name_fr' => 'Écologie chimique',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            279 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nqv3ag1976kbacxbanx',
                'name_en' => 'Chemical industry',
                'name_fr' => 'Industrie chimique',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            280 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nrby46e3ra62z5t686a',
                'name_en' => 'Chemical Management',
                'name_fr' => 'Gestion des produits chimiques',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            281 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nrv83q5v60yqze4berj',
                'name_en' => 'Chemical Oceanography',
                'name_fr' => 'Océanographie chimique',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            282 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nsc88tsmz4251txz19r',
                'name_en' => 'Chemicals Management Plan',
                'name_fr' => 'Plan de gestion des produits chimiques',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            283 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nsz8frjhfxtn7maha5k',
                'name_en' => 'Cheminformatics',
                'name_fr' => 'Chimio-informatique',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            284 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4ntgj3ryaprxg4a19mvp',
                'name_en' => 'Chemistry - inorganic',
                'name_fr' => 'Chimie - inorganique',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            285 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nv3rcvajvhcntybf32w',
                'name_en' => 'Chemistry and Biology',
                'name_fr' => 'Chimie et biologie',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            286 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nvh8scxwdeg2mn7m4bp',
                'name_en' => 'Child health',
                'name_fr' => 'Santé infantile',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            287 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nvzmfve6g5rmwwf22yj',
                'name_en' => 'Chronic diseases',
                'name_fr' => 'Maladie chronique',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            288 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nwqyewr23zygcyd3bcm',
                'name_en' => 'Churchill River',
                'name_fr' => 'Rivière Churchill',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            289 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nx94a8wjk4zbq6r36ze',
                'name_en' => 'Circulation',
                'name_fr' => 'Circulation',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            290 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nxsmj1bxyvaw6g8st4z',
                'name_en' => 'Circumpolar Region',
                'name_fr' => 'Région circumpolaire',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            291 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4ny63wf137nfq7z1k7gj',
                'name_en' => 'Civil engineering',
                'name_fr' => 'Génie civil',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            292 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nza92tyvqykxfh2renn',
                'name_en' => 'Classical Statistics',
                'name_fr' => 'Statistiques classiques',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            293 => 
            array (
                'created_at' => '2023-10-27 16:05:40',
                'id' => '01hdry4nzsvzfy70ktjqmjx5h6',
                'name_en' => 'Classification',
                'name_fr' => 'Classification',
                'updated_at' => '2023-10-27 16:05:40',
            ),
            294 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p0bhbyq3vrr125488ky',
                'name_en' => 'Clean Air Regulatory Agenda',
                'name_fr' => 'Programme de réglementation de la qualité de l\'air',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            295 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p0wcdj569pgkn04y4qv',
                'name_en' => 'Clean Coal',
                'name_fr' => 'Charbon propre',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            296 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p1cfvtxcff23qg452dd',
                'name_en' => 'Clean Electricity',
                'name_fr' => 'Électricité propre',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            297 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p1xnm6yf14y3hwe8jke',
                'name_en' => 'Cleanup and decontamination',
                'name_fr' => 'Nettoyage et décontamination',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            298 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p2bqpg4jzfp6g9jkgm1',
                'name_en' => 'Climate',
                'name_fr' => 'Climat',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            299 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p2z8jzcb7c54py1g1t7',
                'name_en' => 'Climate Change',
                'name_fr' => 'Changement climatique',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            300 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p3fjn6zc4qntdb97xzf',
                'name_en' => 'Climate Change Adaptation',
                'name_fr' => 'Adaptation aux changements climatiques',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            301 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p3xw130mq2ykwxte7em',
                'name_en' => 'Climate Change and Processes',
                'name_fr' => 'Changements et processus climatiques',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            302 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p4he10mmkpk21jzxnbn',
                'name_en' => 'Climate change impacts',
                'name_fr' => 'Incidence des changements climatiques',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            303 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p51nttdzez5te7cd8fk',
                'name_en' => 'Climate change mitigation',
                'name_fr' => 'Atténuation du changement climatique',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            304 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p5khwq3mj0v45hfa3gs',
                'name_en' => 'Climate Change Risk Analysis',
                'name_fr' => 'Analyse des risques associés aux changements climatiques',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            305 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p62k6m2b2bkbksm2sgm',
                'name_en' => 'Climate Impact',
                'name_fr' => 'Impacts du changement climatique',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            306 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p6wwvfgp1m36pw1yrbg',
                'name_en' => 'Climate Modeling',
                'name_fr' => 'Modélisation climatique',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            307 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p7g9cv8e91ft6cervv4',
                'name_en' => 'Climate resilience and sustainability',
                'name_fr' => 'Résilience climatique et durabilité',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            308 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p7x4w5k93tqdcsfqp30',
                'name_en' => 'Climatic controls',
                'name_fr' => 'Modifications climatiques',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            309 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p8e3g7ydcyhrmanz3wt',
                'name_en' => 'Clinical decision support systems ',
                'name_fr' => 'Systèmes de soutien à la prise de décision clinique',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            310 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p8tzennp7n28bc11574',
                'name_en' => 'Cloning',
                'name_fr' => 'Clonage',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            311 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p99038cgw3em0mfptzw',
                'name_en' => 'Cloud modelling',
                'name_fr' => 'Modélisation des nuages',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            312 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4p9rnevbx5snvnq4w1dr',
                'name_en' => 'Cloud physics',
                'name_fr' => 'Physique des nuages',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            313 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pa6paf4pq39bd8xvap1',
                'name_en' => 'Cloud processes',
                'name_fr' => 'Processus liés aux nuages',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            314 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4panxymajgsh5byq2s1b',
                'name_en' => 'CloudSat',
                'name_fr' => 'CloudSat',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            315 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pb65zpfm0xfventaxve',
                'name_en' => 'Clustering',
                'name_fr' => 'Agrégation',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            316 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pcfcygd1ts7y3a4b8t6',
                'name_en' => 'Co-generation',
                'name_fr' => 'Cogénération',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            317 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pcyc2vpt219vbg3gky6',
                'name_en' => 'Coal and Coal Products',
                'name_fr' => 'Charbon et produits du charbon',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            318 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pdf7watvkgxkmq93c9r',
                'name_en' => 'Coal Bed Methane',
                'name_fr' => 'Méthane de houille',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            319 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pe02tgscwd9jvjk94vh',
                'name_en' => 'Coastal - Arctic Canada',
                'name_fr' => 'Maritime - Canada arctique',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            320 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4peg1h16tdd5ag43qetn',
                'name_en' => 'Coastal - Beaufort Sea',
                'name_fr' => 'Maritime - Mer de Beaufort',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            321 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pevyjq8vhnbg36a0t0g',
                'name_en' => 'Coastal - Eastern Canada',
                'name_fr' => 'Maritime - l\'est du Canada',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            322 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pfg944cs1xkwm8xebr1',
                'name_en' => 'Coastal - Western Canada',
                'name_fr' => 'Maritime - l\'ouest du Canada',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            323 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pg3jvpm8rvka1kbzc9g',
                'name_en' => 'Coastal Engineering',
                'name_fr' => 'Génie côtier',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            324 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pgpvz3z2fvwmwj5eb2w',
                'name_en' => 'Coastal habitats',
                'name_fr' => 'Habitats côtiers',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            325 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4ph70fyrfnhazn3yy8ng',
                'name_en' => 'Coatings',
                'name_fr' => 'Revêtements',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            326 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4phre4g93ddatd9a1kha',
                'name_en' => 'Cold and drought tolerance',
                'name_fr' => 'Tolérance au froid et à la sécheresse',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            327 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pj97qjbwc1wd1gkwtvg',
                'name_en' => 'Cold Region Engineering',
                'name_fr' => 'Ingénierie en régions froides',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            328 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pjsn4mk30gfk15wnavk',
                'name_en' => 'Cold spray additive manufacturing',
                'name_fr' => 'Fabrication additive par projection à froid',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            329 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pkbgtqqj05wkx5tn3np',
                'name_en' => 'Combined Heat and Power',
                'name_fr' => 'Congénération',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            330 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pkxb10rq9c3j2gvy39x',
                'name_en' => 'Combined sewer overflow',
                'name_fr' => 'Déversoirs d\'orage',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            331 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pmcx3r00sqz9rk6eehf',
                'name_en' => 'Communication Protocol',
                'name_fr' => 'Protocole de communication',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            332 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pmsqk12pwc43knja221',
                'name_en' => 'Communications',
                'name_fr' => 'Communications',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            333 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pn9qjy3mez3v82rn0cr',
                'name_en' => 'Communications equipment',
                'name_fr' => 'Équipement de communications',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            334 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pnrrybe11npnshfy9mk',
                'name_en' => 'Communications strategy',
                'name_fr' => 'Stratégie de communications',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            335 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pp9p4eys6n7k83nqkkg',
                'name_en' => 'Community radio',
                'name_fr' => 'Radio communautaire',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            336 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4ppnmswmjsna56t01a77',
                'name_en' => 'Comparative genomics',
                'name_fr' => 'Génomique comparative',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            337 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pq6v4kp57mv3rar5x3f',
                'name_en' => 'Compliance and Enforcement',
                'name_fr' => 'Conformité et exécution de la loi',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            338 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pqwhxcwg6trt27dd19f',
                'name_en' => 'Composites',
                'name_fr' => 'Matériaux composites',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            339 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pr9qa2btvngaf7s9x8m',
                'name_en' => 'Compound detection',
                'name_fr' => 'Détection de composés',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            340 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4prtfqv78bhg48jf4hrt',
                'name_en' => 'Computational linguistics',
                'name_fr' => 'Linguistique informatique',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            341 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pse36ger9e9cvkb19ap',
                'name_en' => 'Computer analysis and modelling',
                'name_fr' => 'Analyse et modélisation par ordinateur',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            342 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pswb5tzbhb204hc7dkq',
                'name_en' => 'Computer networks',
                'name_fr' => 'Réseau informatique',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            343 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4ptam3rgjj70vsh74013',
                'name_en' => 'Computer programming',
                'name_fr' => 'Programmation d\'ordinateur',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            344 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pts1hy93p9h8a04hv7w',
                'name_en' => 'Computer science',
                'name_fr' => 'Informatique',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            345 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pv6hq0k3rq9fwb1tya5',
                'name_en' => 'Computer security',
                'name_fr' => 'Sécurité informatique',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            346 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pvn0fpqp7v2kxh5w0by',
                'name_en' => 'Computer services',
                'name_fr' => 'Services informatiques',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            347 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pw5mdc83dcd1mqn2z2j',
                'name_en' => 'Computer systems',
                'name_fr' => 'Système informatique',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            348 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pwmady781xpxs7vqbmf',
                'name_en' => 'Computer Vision',
                'name_fr' => 'Vision par ordinateur',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            349 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4px4jan658m3ytp19dkw',
                'name_en' => 'Computer-aided design',
                'name_fr' => 'Conception assistée par ordinateur',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            350 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pxn1j0sty57f4vw3xh8',
                'name_en' => 'Computers',
                'name_fr' => 'Ordinateurs',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            351 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4py7zrdw46wqjrsq14ft',
                'name_en' => 'Computing Power',
                'name_fr' => 'Capacité de traitement',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            352 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pyp10tkmg04crk6rzjw',
                'name_en' => 'Concrete',
                'name_fr' => 'Béton',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            353 => 
            array (
                'created_at' => '2023-10-27 16:05:41',
                'id' => '01hdry4pz4n0sqqyxtgp21d0fs',
                'name_en' => 'Condensing Heat Exchanger',
                'name_fr' => 'Échangeur de chaleur-évaporateur',
                'updated_at' => '2023-10-27 16:05:41',
            ),
            354 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4pzmfgyk1h85cmqgrb31',
                'name_en' => 'Condiment mustard',
                'name_fr' => 'Moutarde condimentaire',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            355 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q01kcqftj2sypn6jvt5',
                'name_en' => 'Connectivity',
                'name_fr' => 'Connectivité',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            356 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q0jpcje62za2bd2x89n',
                'name_en' => 'Conservation biology',
                'name_fr' => 'Biologie de la conservation',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            357 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q18rbpnqmj2qesfhapk',
                'name_en' => 'Conservation of forest biodiversity',
                'name_fr' => 'Conservation de la biodiversité des forêts',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            358 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q1qbnx2hwg43t3mrmsw',
                'name_en' => 'Construction',
                'name_fr' => 'Construction',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            359 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q29j9zpzgmzj75r0m17',
                'name_en' => 'Consumer Product Safety',
                'name_fr' => 'Sécurité des produits de consommation',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            360 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q2tce052zcxffvx2ba0',
                'name_en' => 'Consumer Products',
                'name_fr' => 'Produits de consommation',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            361 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q3bfhxvmsb9q84x33am',
                'name_en' => 'Consumer Protection',
                'name_fr' => 'Protection du consommateur',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            362 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q3vmqm5sc3fhp9vs6p0',
                'name_en' => 'Consumer sciences',
                'name_fr' => 'Des sciences de la consommation',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            363 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q4cts7e54cqkvk4a5h1',
                'name_en' => 'Contaminants',
                'name_fr' => 'Contaminants',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            364 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q4rt96atjkbbdkdgnr3',
                'name_en' => 'Contaminated Sites',
                'name_fr' => 'Sites contaminés',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            365 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q5ew9amfaa4qarzdy1t',
                'name_en' => 'Contours',
                'name_fr' => 'Courbes de niveau',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            366 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q66mrj9983ncb0rjg5n',
                'name_en' => 'Controlled Substance',
                'name_fr' => 'Substances contrôlées',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            367 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q71rjf4cr3mxvrj1n1j',
            'name_en' => 'Convention on Trade of Endangered Species (WAPPRIITA)',
            'name_fr' => 'Convention sur le commerce international des espèces menacées d\'extinction (WAPPRIITA)',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            368 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q7gmagf9v8p7w2whtww',
                'name_en' => 'Conventional Fuels',
                'name_fr' => 'Carburants classiques',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            369 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q80mx9kg6fs02wt2x05',
                'name_en' => 'Cooking',
                'name_fr' => 'Art culinaire',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            370 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q8ctj8h84dyrdrseavx',
                'name_en' => 'Copyrights',
                'name_fr' => 'Droits d\'auteur',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            371 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q8tvbkm64s4zfm3addd',
                'name_en' => 'Cordillera',
                'name_fr' => 'Cordillière',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            372 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q9bk2w7pj2y4nss6m1c',
                'name_en' => 'Corn/maize',
                'name_fr' => 'Maïs',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            373 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4q9w6kdjygs44aqnzew0',
                'name_en' => 'Corrosion',
                'name_fr' => 'Corrosion',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            374 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qad30r11m11fq8gxq7s',
                'name_en' => 'Cosmetics',
                'name_fr' => 'Produits cosmétiques',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            375 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qavqw5frz7nzpah300h',
                'name_en' => 'Coupled Ocean and Atmospheric Models',
                'name_fr' => 'Modèles couplés océan-atmosphère',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            376 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qbbzx8f0p1mrsjsqvzs',
                'name_en' => 'COVID-19',
                'name_fr' => 'COVID-19',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            377 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qc0zb390t11wg4bzd5z',
                'name_en' => 'Cow',
                'name_fr' => 'La vache',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            378 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qchw2r02rxrjg3wbst4',
                'name_en' => 'CPU',
                'name_fr' => 'Unité centrale',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            379 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qd72b7cbax5d0d8gb4x',
            'name_en' => 'Critical habitat (species at risk)',
            'name_fr' => 'Habitat critique (espèces en péril)',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            380 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qdr2e1ysx7rstkm1kmj',
                'name_en' => 'Critical Infrastructure Vulnerabilities, Resiliency and Interdependencies',
                'name_fr' => 'Vulnérabilité, résilience et interdépendances des infrastructures essentielles',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            381 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qeaz4qahqxgdtqd8p4p',
                'name_en' => 'Crop',
                'name_fr' => 'Des cultures',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            382 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qet0chhszx37zrnqk4x',
                'name_en' => 'Crop production',
                'name_fr' => 'La production des cultures',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            383 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qfaz8xk08p7wt0he6c4',
                'name_en' => 'Croplands and water management',
                'name_fr' => 'Gestion des terres cultivées et de l’eau',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            384 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qfvyzmrgk7njn6tph7e',
                'name_en' => 'Crude Oil',
                'name_fr' => 'Pétrole brute',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            385 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qg9m7b5szcxvy4tpd57',
                'name_en' => 'Cryospheric',
                'name_fr' => 'Cryosphère',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            386 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qgwvxkfqfs4h3r4dza6',
                'name_en' => 'Cryptography',
                'name_fr' => 'Cryptographie',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            387 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qh69mcs6cydmy08kkt3',
                'name_en' => 'Curator',
                'name_fr' => 'Conservateur',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            388 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qhn73qy3srvhxnzfna6',
                'name_en' => 'Cutworm',
                'name_fr' => 'Du ver gris',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            389 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qj6kgywez47870390yy',
                'name_en' => 'Cybersecurity',
                'name_fr' => 'Cybersécurité',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            390 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qjqz497k9r5bsh4j05n',
                'name_en' => 'Cyst',
                'name_fr' => 'Kystes',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            391 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qk67d7x8mz13xk63349',
                'name_en' => 'Cytogenetics',
                'name_fr' => 'Cytogénétique',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            392 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qkn5np28hb1eebmtsj5',
                'name_en' => 'Dairy',
                'name_fr' => 'L\'exploitation laitière',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            393 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qm33hgfbzjgkfynrde5',
                'name_en' => 'Dairy technology',
                'name_fr' => 'Technologie laitière',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            394 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qmh1kyz3dyqwvjnsdc8',
                'name_en' => 'Data acquisition',
                'name_fr' => 'Acquisition de données',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            395 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qmzamq6mtn8kcyxmpg9',
                'name_en' => 'Data analytics',
                'name_fr' => 'Analyse des données',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            396 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qnga9pc2kcvhg9vwv3r',
                'name_en' => 'Data and Text Mining',
                'name_fr' => 'Forage des données',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            397 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qnw8269rxdb3ahbm45s',
                'name_en' => 'Data assimilation',
                'name_fr' => 'Assimilation des données',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            398 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qpc8zdfezz9e5d550zd',
                'name_en' => 'Data Cleansing',
                'name_fr' => 'Nettoyage des données',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            399 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qpwpm0d4af8ad1pf0fy',
                'name_en' => 'Data Ingestion',
                'name_fr' => 'Ingestion de données',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            400 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qqcp3gc260qtcfmhmrf',
                'name_en' => 'Data Licensing and Distribution',
                'name_fr' => 'Attribution de licences de données et distribution',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            401 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qqvwb5rek18a4ng3bdd',
                'name_en' => 'Data Management',
                'name_fr' => 'Gestion des données',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            402 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qrc77gb1pabpeaep6ad',
                'name_en' => 'Data Munging',
                'name_fr' => 'Exploitation des données',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            403 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qs04knvf6s1y0yxxckr',
                'name_en' => 'Data processing',
                'name_fr' => 'Traitement des données',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            404 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qsgmg9z142rewdbhy95',
                'name_en' => 'Data Quality',
                'name_fr' => 'Qualité des données',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            405 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qsy7g6cjgcj177q929k',
                'name_en' => 'Data Quality Assurance and Control',
                'name_fr' => 'Assurance et contrôle de la qualité des données',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            406 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qtg174egh2y8wj790h2',
                'name_en' => 'Data Science',
                'name_fr' => 'Science des données',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            407 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qtyfmprdzr7s9rcebg5',
                'name_en' => 'Data Visualization',
                'name_fr' => 'Visualisation des données',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            408 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qvfqsqwgt3cn8p00tcw',
                'name_en' => 'Database',
                'name_fr' => 'Database',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            409 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qw27ygk1rcj62v2t4ak',
                'name_en' => 'Davis Strait',
                'name_fr' => 'Détroit de Davis',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            410 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qwhbk2w0v1md9ye5qcv',
                'name_en' => 'Decision support systems',
                'name_fr' => 'Systèmes d\'aide à la décision',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            411 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qx0svvsz1w4barf5s68',
                'name_en' => 'Decision Tree',
                'name_fr' => 'Arbre de décision',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            412 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qxht7sh21vym58hjyt4',
                'name_en' => 'Decontamination',
                'name_fr' => 'Décontamination',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            413 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qy2kgdqc051b52wf4f2',
                'name_en' => 'Deep Learning',
                'name_fr' => 'Apprentissage profond',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            414 => 
            array (
                'created_at' => '2023-10-27 16:05:42',
                'id' => '01hdry4qyks0j0a7hwnz4xy7wt',
                'name_en' => 'Defence industry',
                'name_fr' => 'Industrie de la défense',
                'updated_at' => '2023-10-27 16:05:42',
            ),
            415 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4qz4x85894xbm8ekvggd',
                'name_en' => 'Demand-side Management',
                'name_fr' => 'Gestion de la demande',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            416 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4qzpcq777f0snn3wtsx3',
                'name_en' => 'Demersal Species',
                'name_fr' => 'Espèces démersales',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            417 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r098qw94fabykypmhdd',
                'name_en' => 'Dendrochronology',
                'name_fr' => 'Dendrochronologie',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            418 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r0vhcz2hvw11kfsb61a',
                'name_en' => 'Dendrology',
                'name_fr' => 'Dendrologie',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            419 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r1hhdkyezvmm5kt979v',
                'name_en' => 'Deposition',
                'name_fr' => 'Dépôt',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            420 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r22m5fj772txmapd8xr',
                'name_en' => 'Design Systems',
                'name_fr' => 'Systèmes de conception',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            421 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r2m9da98xet7yvzeebr',
                'name_en' => 'Developmental plant',
                'name_fr' => 'Phytophysiologie du développement',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            422 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r34efmddenv1hrbqy6s',
                'name_en' => 'Diagnostic method development',
                'name_fr' => 'Dévelopement de méthodes pour le diagnostic',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            423 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r3nhp7k9ybxbv6w334k',
                'name_en' => 'Diamonds',
                'name_fr' => 'Diamants',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            424 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r45y0t8ghe7grkfbtvm',
                'name_en' => 'Diesel Engines',
                'name_fr' => 'Moteurs diesel',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            425 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r4nkefkz6xy1jyqm25m',
                'name_en' => 'Diet and gut health',
                'name_fr' => 'Régimes alimentaires et santé intestinale',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            426 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r5cmdj34t1p3fp0n7x1',
                'name_en' => 'Dietary fiber',
                'name_fr' => 'Fibres alimentaires',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            427 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r5ye1vk4cw1ndkgvjbf',
                'name_en' => 'Digital Evaluation Model',
                'name_fr' => 'Modèle numérique d\'élévation',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            428 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r6d1t0a2zva4apkx9vn',
                'name_en' => 'Digital Health',
                'name_fr' => 'Santé numérique',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            429 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r6xqk6vdc0peznkgz5m',
                'name_en' => 'Digital libraries',
                'name_fr' => 'Bibliothèque numérique',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            430 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r7bavrgymnzs6kpwsg0',
                'name_en' => 'Digital manufacturing',
                'name_fr' => 'Fabrication numérique',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            431 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r7vdp1y4vjhba3fma0h',
                'name_en' => 'Digital technology',
                'name_fr' => 'Technologie numérique',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            432 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r8an0e8fy7wdh0wd0hv',
                'name_en' => 'Digitization',
                'name_fr' => 'Numérisation',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            433 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r8w4577wz7s80g8anh4',
            'name_en' => 'Direct contact steam generation (DCSG)',
            'name_fr' => 'Production de vapeur par contact direct (PVCD)',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            434 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r9cqvcs2b1r2r8e0w30',
                'name_en' => 'Directed energy deposition',
                'name_fr' => 'Dépôt de matière sous énergie concentrée',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            435 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4r9whz9drvx4yh5jv9cm',
                'name_en' => 'Disaster management',
                'name_fr' => 'Gestion des désastres',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            436 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4raasge1nt3rz19xmsgd',
                'name_en' => 'Disaster mitigation',
                'name_fr' => 'Atténuation des dégâts',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            437 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rat7q5kbrcc21bgxy5d',
                'name_en' => 'Discovery',
                'name_fr' => 'Découverte',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            438 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rbdn5r8hhbb40t7gr68',
                'name_en' => 'Disease impacts',
                'name_fr' => 'Incidence des maladies',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            439 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rbxkwcd7df1jtyhe0gv',
                'name_en' => 'Disease resistance',
                'name_fr' => 'Résistance aux maladies',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            440 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rcfj59vav5crsd0hetq',
                'name_en' => 'Diseases',
                'name_fr' => 'Maladies',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            441 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rd0ek13rv0tetyyjmye',
                'name_en' => 'Dispensers',
                'name_fr' => 'Distributeur',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            442 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rdb9ghkqwx5chear2v2',
                'name_en' => 'Distributed computing',
                'name_fr' => 'Informatique distribuée',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            443 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rdw2vhcy70akzzx3cv6',
                'name_en' => 'Distributed Energy',
                'name_fr' => 'Énergie décentralisée',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            444 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rebxwe12w27y5nna0pz',
                'name_en' => 'Diversity and conservation',
                'name_fr' => 'Diversité et conservation',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            445 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rew72vtmqqb02bbv4ed',
                'name_en' => 'Domestic substances',
                'name_fr' => 'Substances nationales',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            446 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rfc0w83faaftp0aktch',
                'name_en' => 'Dose-Response Modeling',
                'name_fr' => 'Modélisation dose-réponse',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            447 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rfyvt3ry7p8m9wkcyw0',
                'name_en' => 'Dosimetry Services',
                'name_fr' => 'Services de dosimétrie',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            448 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rgd4sgtnmgr3zv2r7ca',
                'name_en' => 'Drought',
                'name_fr' => 'Sécheresse',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            449 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rgztbx1dshk6f1971d5',
                'name_en' => 'Drought, cold and heat tolerance',
                'name_fr' => 'Tolérance à la sécheresse, au froid et à la chaleur',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            450 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rhfzpcqsqj1wrqcbmdr',
                'name_en' => 'Drug residues',
                'name_fr' => 'Résidus de médicaments',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            451 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rhxfcrme7xqp8ahdrr0',
                'name_en' => 'Dry bean',
                'name_fr' => 'Haricots secs',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            452 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rjbd5eyf4ww5mjjz6db',
                'name_en' => 'Dynamic and structural computer modelling and analysis',
                'name_fr' => 'Modélisation et analyse dynamique et structurale sur ordinateur',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            453 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rjxv7dmmhqcrx7b3eda',
                'name_en' => 'Dynamic Global Positioning',
                'name_fr' => 'Positionnement global dynamique',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            454 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rkedhnsaqta97dsfxfs',
                'name_en' => 'E. coli',
                'name_fr' => 'E. coli',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            455 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rkvdqyc2f00rfapbjfm',
                'name_en' => 'Earth Observation',
                'name_fr' => 'Observation de la terre',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            456 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rmayebf6qyz7hck7mq6',
                'name_en' => 'Earth Sciences',
                'name_fr' => 'Sciences de la Terre',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            457 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rmt3dkpxcjrjghhftg6',
                'name_en' => 'Earthquakes',
                'name_fr' => 'Tremblement de terre',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            458 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rnavz8mtsmzmjmvb3d1',
                'name_en' => 'Eastern Canada',
                'name_fr' => 'L\'Est du Canada',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            459 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rntb43t48fv4p2c1gtg',
                'name_en' => 'Eco-responsible Materials',
                'name_fr' => 'Matériaux écoresponsables',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            460 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rpebaaxbrv4hcery7jd',
                'name_en' => 'Ecological conservation',
                'name_fr' => 'Protection écologique',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            461 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rpydaqckc9n1ma27n9n',
                'name_en' => 'Ecological reforestation',
                'name_fr' => 'restauration biologique',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            462 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rqej4tzs5rzkpd1sef1',
                'name_en' => 'Ecological risk assessment',
                'name_fr' => 'Évaluation des risques écologiques',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            463 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rqyq0mf6y4x4ge6hfec',
                'name_en' => 'Ecology',
                'name_fr' => 'Écologie',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            464 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rrfadwhtxvfgdtg1xwk',
                'name_en' => 'Ecology and Ecosystems',
                'name_fr' => 'Écologie et écosystèmes',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            465 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rrttysen6tcwcj0f61e',
                'name_en' => 'Economic analysis',
                'name_fr' => 'Analyse économique',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            466 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rs8bayw16xv4jjcb0xb',
                'name_en' => 'Economic conditions',
                'name_fr' => 'Conditions économiques',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            467 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rsxm8tgzd89av1e3yc1',
                'name_en' => 'Economic development',
                'name_fr' => 'Développement économique',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            468 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rtchvbt3npcbqh3c3qw',
                'name_en' => 'Economic geology',
                'name_fr' => 'Géologie économique',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            469 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rtvskyee7cefvmsh3ym',
                'name_en' => 'Economic policy',
                'name_fr' => 'Politique économique',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            470 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rvb6j0m664a3htv7jg0',
                'name_en' => 'Economic research',
                'name_fr' => 'Recherche économique',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            471 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rvsdd8scs979mxjs8a7',
                'name_en' => 'Economic statistics',
                'name_fr' => 'Statistiques économiques',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            472 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rw83nz1w4exsbnerwfd',
                'name_en' => 'Economic trends',
                'name_fr' => 'Tendances économiques',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            473 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rwrwje2d4mxp7ys1a0f',
                'name_en' => 'Economics',
                'name_fr' => 'Science économique',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            474 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rxe0pqgvsebhptdnskp',
                'name_en' => 'Economics and Industry',
                'name_fr' => 'Économie et industrie',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            475 => 
            array (
                'created_at' => '2023-10-27 16:05:43',
                'id' => '01hdry4rxy0m7hkca7mrnf3chz',
                'name_en' => 'Ecopathology',
                'name_fr' => 'Écopathologie',
                'updated_at' => '2023-10-27 16:05:43',
            ),
            476 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4ryfxhwdx19mbhbkz1az',
                'name_en' => 'Ecophysiology',
                'name_fr' => 'Écophysiologie',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            477 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4ryz2s6xac9wptgd9hg2',
                'name_en' => 'Ecosystem',
                'name_fr' => 'Écosystème',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            478 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4rzh9n1b5znmv7kw7jgt',
                'name_en' => 'Ecosystem effects of fishing',
                'name_fr' => 'Effets des écosystèmes sur la pêche',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            479 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s03savbsjdc0erjn7dg',
                'name_en' => 'Ecosystem health',
                'name_fr' => 'Santé des écosystèmes',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            480 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s0pkzk4yhycf0kev1er',
                'name_en' => 'Ecosystem Management',
                'name_fr' => 'Gestion de l\'écosystème',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            481 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s1cmadv18me6s6br4c7',
                'name_en' => 'Ecosystem/Ecological modeling',
                'name_fr' => 'Modélisation des écosystèmes/écologique',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            482 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s1wmfjqtmrq1s79dyxt',
                'name_en' => 'Ecosystems',
                'name_fr' => 'Écosystèmes',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            483 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s2cqabz1vssz0wztm0t',
                'name_en' => 'Ecosystems & Habitats',
                'name_fr' => 'Écosystèmes et habitats',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            484 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s2x1jqd9bksngcqys2h',
                'name_en' => 'Ecotoxicology',
                'name_fr' => 'Écotoxicologie',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            485 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s3fpgpd5qm72d558yzr',
                'name_en' => 'Educational technology',
                'name_fr' => 'Technologie éducative',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            486 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s44hp1fxd191t99yhme',
                'name_en' => 'Effects',
                'name_fr' => 'Effets',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            487 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s4zpv47hwsf1jyw9y15',
                'name_en' => 'Effects of Toxics',
                'name_fr' => 'Effets des substances toxiques',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            488 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s5htw78y10kmqm4jgk4',
                'name_en' => 'Efficiency',
                'name_fr' => 'Efficacité',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            489 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s625hk8p3n3jy46b9yt',
                'name_en' => 'Effluents',
                'name_fr' => 'Effluents',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            490 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s6qj4dww8dg9ydktta0',
                'name_en' => 'Electric & Hybrid Vehicles',
                'name_fr' => 'Véhicules électriques & hybrides',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            491 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s77ceg404ny243cp9ez',
                'name_en' => 'Electrical appliances',
                'name_fr' => 'Appareil électrique',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            492 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s7mtmx7yzgzzhvqdt4x',
                'name_en' => 'Electrical equipment',
                'name_fr' => 'Équipement électrique',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            493 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s84ardds1sta01z36r8',
                'name_en' => 'Electrical power stations',
                'name_fr' => 'Centrale électrique',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            494 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s8mbgmjdjd2y3se1hdd',
                'name_en' => 'Electricity',
                'name_fr' => 'Électricité',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            495 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s965bc8qp3rjkjx14kt',
                'name_en' => 'Electricity and Magnetism',
                'name_fr' => 'Électricité et magnétisme',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            496 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4s9nqc28zyv7k1r4518c',
                'name_en' => 'Electro-technologies',
                'name_fr' => 'Électrotechnologies',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            497 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sa4ff4eetzahes4vmfq',
                'name_en' => 'Electron beam welding',
                'name_fr' => 'Soudage par faisceau d’électrons',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            498 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4samy4hn6bvnz5jz0f0h',
                'name_en' => 'Electronic',
                'name_fr' => 'Électronique',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            499 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sb38cgqkcmpyrfm71cz',
                'name_en' => 'Electronic data interchange',
                'name_fr' => 'Échange électronique d\'information',
                'updated_at' => '2023-10-27 16:05:44',
            ),
        ));
        \DB::table('expertises')->insert(array (
            0 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sbhpk89wezn3kvvyxgk',
                'name_en' => 'Electronic Devices',
                'name_fr' => 'Dispositifs électroniques',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            1 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sc0vhmebc80cg1ykc1e',
                'name_en' => 'Electronic documents',
                'name_fr' => 'Document électronique',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            2 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4scnabq3y5w0ndjrprkb',
                'name_en' => 'Electronic equipment',
                'name_fr' => 'Équipement électronique',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            3 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sdant8eynx7mkj1e75c',
                'name_en' => 'Electronic mail',
                'name_fr' => 'Courrier électronique',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            4 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sdt8p4qbcg5wz05fnxb',
                'name_en' => 'Electronic monitoring',
                'name_fr' => 'Surveillance electronique',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            5 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4seb2dgqzw3sjmzkxx6t',
                'name_en' => 'Electronics industry',
                'name_fr' => 'Industrie de l\'électronique',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            6 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4setkbx88zjxjak4bfac',
                'name_en' => 'Emergency Management Systems and Interoperability',
                'name_fr' => 'Systèmes de gestion des urgences et interopérabilité',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            7 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sfccyc0zz4ykh11ngy2',
                'name_en' => 'Emergency planning',
                'name_fr' => 'Planification d\'urgence',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            8 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sfq8r0cd5pnzdd75xsp',
                'name_en' => 'Emergency Response Service',
                'name_fr' => 'Services d’intervention d’urgence',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            9 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sg8vvtxz8txf9pee40c',
                'name_en' => 'Emerging chemicals',
                'name_fr' => 'Nouveaux produits chimiques',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            10 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sgxyaeva1mqhqzg4sw4',
                'name_en' => 'Emerging green separation technologies',
                'name_fr' => 'Nouvelles technologies écologiques de séparation',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            11 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4shd7v11ndchm0jbkfbx',
                'name_en' => 'Emerging issues',
                'name_fr' => 'Nouveaux enjeux',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            12 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4shtj1s4gct5ffy69vbx',
                'name_en' => 'Emerging Metrology',
                'name_fr' => 'Métrologie émergente',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            13 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sj85q59sacjqdey60f9',
                'name_en' => 'Emissions',
                'name_fr' => 'Émissions',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            14 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sjv0135dee0z1d7eg3j',
                'name_en' => 'Employee Assistance Programs',
                'name_fr' => 'Services d\'aide aux employés',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            15 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4skm0vn0htrt6kth8s1h',
                'name_en' => 'Encapsulation and powders',
                'name_fr' => 'Encapsulation et poudres',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            16 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sm4mnra6ad1nsnqdtzm',
                'name_en' => 'Endocrine disruption',
                'name_fr' => 'Perturbation du système endocrinien',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            17 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4smjnftjqwygc8744d9z',
                'name_en' => 'Endocrinology',
                'name_fr' => 'Endocrinologie',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            18 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4smz4wy0fnezc918q7yb',
                'name_en' => 'Energy',
                'name_fr' => 'Énergie',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            19 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sne3htetgktzeh0t3xg',
                'name_en' => 'Energy conservation',
                'name_fr' => 'Économies d’énergie',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            20 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4snz093bsr6256q396nb',
                'name_en' => 'Energy Conversion',
                'name_fr' => 'Conversion de l\'énergie',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            21 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4spg0v1m12c9gmfeshfc',
                'name_en' => 'Energy Efficiency',
                'name_fr' => 'Éfficacité énergétique',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            22 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sq7zpe1md86eb4zjwc1',
                'name_en' => 'Energy End Use',
                'name_fr' => 'Utilisation finale de l\'énergie',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            23 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sqq3haeh0vx2txrdwcj',
                'name_en' => 'ENERGY STAR',
                'name_fr' => 'ENERGY STAR',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            24 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4sr7d1absjas86bt3nnf',
                'name_en' => 'Energy Storage',
                'name_fr' => 'Stockage de l\'énergie',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            25 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4srtmb36cgtdn3h841gc',
                'name_en' => 'Energy technology',
                'name_fr' => 'Technologie énergétique',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            26 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4ssc8pw8jbjztwcw7mwv',
                'name_en' => 'Engineered materials',
                'name_fr' => 'Matériaux d\'ingénierie',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            27 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4ssvr7drye6b3s9w36gg',
                'name_en' => 'Engineering',
                'name_fr' => 'Ingénierie',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            28 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4stbq4xedyyph4yt0g3s',
                'name_en' => 'Engineering and design',
                'name_fr' => 'Ingénierie et conception',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            29 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4stv1j80fr4wtb65v8ng',
                'name_en' => 'Engines',
                'name_fr' => 'Moteur',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            30 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4svcew610y5z69yn830x',
                'name_en' => 'Enhanced Oil Recovery',
                'name_fr' => 'Recuperation assistée du pétroles',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            31 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4svxgrrnwzkn4q60t436',
                'name_en' => 'Enhanced Recovery',
                'name_fr' => 'Recuperation assistée',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            32 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4swe2yjfkz2mdmb3w3bh',
                'name_en' => 'Ensemble prediction',
                'name_fr' => 'Prévision d\'ensemble',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            33 => 
            array (
                'created_at' => '2023-10-27 16:05:44',
                'id' => '01hdry4swytd91dydq5c61m0kd',
                'name_en' => 'Enteric microbiology and intestinal health',
                'name_fr' => 'Microbiologie entérique et santé intestinale',
                'updated_at' => '2023-10-27 16:05:44',
            ),
            34 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4sxh6ezpc398eh6ec6zy',
                'name_en' => 'Entomology',
                'name_fr' => 'Éntomologie',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            35 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4sy4jy5d9pecmn81t6h5',
                'name_en' => 'Entomopathology',
                'name_fr' => 'Entomopathologie',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            36 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4sypd9053fy37pwwhgz7',
                'name_en' => 'Environment',
                'name_fr' => 'Environnement',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            37 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4sze309dcdgbq5s4x3k6',
                'name_en' => 'Environment and Human Health',
                'name_fr' => 'Environnement et santé humaine',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            38 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t00yg1kzhhp4xbchgyh',
                'name_en' => 'Environmental',
                'name_fr' => 'Environnementale',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            39 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t0kdwfy0zzt88hxz24s',
                'name_en' => 'Environmental and Occupational Toxicology',
                'name_fr' => 'Toxicologie environnementale et professionnelle',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            40 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t137fz0xf5s71yx9315',
                'name_en' => 'Environmental biology',
                'name_fr' => 'Biologie environnementale',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            41 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t1hrzcq2g249sketxwd',
                'name_en' => 'Environmental Chemistry',
                'name_fr' => 'Chimie de l\'environnement',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            42 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t1z38x2g3s0857p6zkr',
                'name_en' => 'Environmental contaminants',
                'name_fr' => 'Contaminants environnementaux',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            43 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t2ewwkvyzcwb5wqwndd',
                'name_en' => 'Environmental Effects Monitoring Program',
                'name_fr' => 'Programme d\'étude de suivi des effets sur l\'environnement',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            44 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t2xxcptw4v1wfb6tg9r',
                'name_en' => 'Environmental health',
                'name_fr' => 'Santé environnementale',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            45 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t41k10t74k8h62a93yb',
                'name_en' => 'Environmental immunochemistry',
                'name_fr' => 'Immunochimie de l’environnement',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            46 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t4wxyc966aqyn00qypv',
                'name_en' => 'Environmental Impacts',
                'name_fr' => 'Conséquences environnementales',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            47 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t5fkf0z9r1avhvjh4s4',
                'name_en' => 'Environmental management',
                'name_fr' => 'Gestion de l\'environnement',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            48 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t5z88mvh2f1821z53bj',
                'name_en' => 'Environmental metrology',
                'name_fr' => 'Métrologie environnementale',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            49 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t6ejbcdmmwxf62y6wve',
                'name_en' => 'Environmental Policy',
                'name_fr' => 'Politique environnementale',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            50 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t6xsz2ef16kpd7y7ysc',
                'name_en' => 'Environmental prediction',
                'name_fr' => 'Prévision environnementale',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            51 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t7gz2c6p7pxze61zwcs',
                'name_en' => 'Environmental Radiation Protection',
                'name_fr' => 'Radioprotection environnementale',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            52 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t84qmmr0rrnfd9zhqpe',
                'name_en' => 'Environmental science',
                'name_fr' => 'Sciences de l’environnement',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            53 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t8knsh169j0esnmaq2n',
                'name_en' => 'Environmental technologies',
                'name_fr' => 'Technologies environnementales',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            54 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t93kbtwgbfdgy2veqhv',
                'name_en' => 'Enzyme technologies',
                'name_fr' => 'Technologies enzymatiques',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            55 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4t9kf30r3rx0k86fkehq',
                'name_en' => 'Enzymology',
                'name_fr' => 'Enzymologie',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            56 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4ta5fz32kk3y332pej5t',
                'name_en' => 'Epidemiology',
                'name_fr' => 'Épidémiologie',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            57 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tanrzh3xtjc0y6qtns6',
                'name_en' => 'Epigenetics',
                'name_fr' => 'Épigénétique',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            58 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tb6b1xhmfa2mxk03f59',
                'name_en' => 'Epitaxy',
                'name_fr' => 'Épitaxie',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            59 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tbrdmvc10p20nt0rkwm',
                'name_en' => 'Equipment',
                'name_fr' => 'Équipement',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            60 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tc7xrhtay7zbzd18skp',
                'name_en' => 'Equipment industry',
                'name_fr' => 'Industrie de l\'équipement',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            61 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tcpwrfmtgz54cdq73tb',
                'name_en' => 'Error Propagation',
                'name_fr' => 'Propagation d’erreur',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            62 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4td71m21r414fq72w6zt',
                'name_en' => 'Eutrophication',
                'name_fr' => 'Eutrophisation',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            63 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tdpfcermhhhhg3mk647',
                'name_en' => 'Evaluation of New Products',
                'name_fr' => 'Évaluation des nouveaux produits',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            64 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4te905jp10crfjtnn8vj',
                'name_en' => 'Explosives',
                'name_fr' => 'Explosifs',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            65 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tev73wk7qj8rc4dcjbj',
                'name_en' => 'Exposure Science',
                'name_fr' => 'Science de l\'exposition',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            66 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tfa6wcm0gwbn6j66f6m',
                'name_en' => 'Extraction techniques',
                'name_fr' => 'Techniques d\'extraction',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            67 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tfvq3adg473h3qrbt8h',
                'name_en' => 'Extraction, Transportation and Spill Research',
                'name_fr' => 'Recherches sur l\'extraction, le transport et les déversements',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            68 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tgbf24py7vn4yh8t4hy',
                'name_en' => 'Extrusion',
                'name_fr' => 'Extrusion',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            69 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tgx1sxegndb8rzcx756',
                'name_en' => 'Fatty acid metabolism',
                'name_fr' => 'Métabolisme des acides gras',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            70 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4thef830px98c9jy9ttv',
                'name_en' => 'Federal science and technology',
                'name_fr' => 'Sciences et technologies fédérales',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            71 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tj2bqwf5frq9pd85dt5',
                'name_en' => 'Fermentation',
                'name_fr' => 'Fermentation',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            72 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tjjzvm11zdbkw4d02s9',
                'name_en' => 'Field Data Collection',
                'name_fr' => 'Collecte de données sur le terrain',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            73 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tk1780me9w66scnac7g',
                'name_en' => 'Field Equipment',
                'name_fr' => 'Équipement de terrain',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            74 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tke34zrjmgvdwqaetcd',
                'name_en' => 'Financial Management',
                'name_fr' => 'Gestion financière',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            75 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tkx86ay8acebavnt6e1',
                'name_en' => 'Finfish',
                'name_fr' => 'Poissons',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            76 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tmc765f6jtwcb5g4evj',
                'name_en' => 'Fire',
                'name_fr' => 'Incendies',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            77 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tmx99sapk212dwjzecz',
                'name_en' => 'Fire safety',
                'name_fr' => 'Sécurité-incendie',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            78 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tnezbfrrpmbc1ahzjvk',
                'name_en' => 'Fireworks',
                'name_fr' => 'Feux d’artifice',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            79 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tny7b1f0nmf30g1hh9q',
                'name_en' => 'First Nations and Inuit Community Programs',
                'name_fr' => 'Programmes communautaires pour les Premières nations et les Inuits',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            80 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tpfc8fqf2r5mvede75c',
                'name_en' => 'First Nations and Inuit Health Programming and Services',
                'name_fr' => 'Programmes liés à la santé des Premières nations et des Inuits',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            81 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tq0zg1f7j3r9mdwcnch',
                'name_en' => 'First Nations and Inuit Health Protection and Public Health',
                'name_fr' => 'Premières nations et les Inuits - santé publique et protection de la Santé',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            82 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tqjz5ajkmjqqsfpyqwh',
                'name_en' => 'First Nations and Inuit Primary Care',
                'name_fr' => 'Premières nations et les Inuits - soins de santé primaires',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            83 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tr39zf54tn0jvt6hzjy',
                'name_en' => 'First Responders',
                'name_fr' => 'Premiers intervenants',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            84 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4trjg4d6xtpfgrt2bxcv',
                'name_en' => 'Fish',
                'name_fr' => 'Poisson',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            85 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4ts2x0g5jc8dy0fejyyt',
                'name_en' => 'Fish population science',
                'name_fr' => 'Science de la population ichtyologique',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            86 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tsmhq1rqdgsjmfmn3va',
                'name_en' => 'Fish Stock Assessment/Population Modeling',
                'name_fr' => 'Évaluation des stocks de poissons/Modélisation des populations',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            87 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tt6t99bw8esvd1xjsmy',
                'name_en' => 'Fisheries',
                'name_fr' => 'Pêches',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            88 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4ttsdbhbxvjmjv2qvh9w',
                'name_en' => 'Fisheries Act',
                'name_fr' => 'Loi sur les pêches',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            89 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tv9kvxssmrvv74rb6w1',
                'name_en' => 'Flax',
                'name_fr' => 'Lin',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            90 => 
            array (
                'created_at' => '2023-10-27 16:05:45',
                'id' => '01hdry4tvzm1a1824yb3swq2dy',
                'name_en' => 'Fleet management and heavy-duty vehicles',
                'name_fr' => 'Gestion de parc de véhicules et véhicules lourds',
                'updated_at' => '2023-10-27 16:05:45',
            ),
            91 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4twhaxpynzmm94maw1ze',
                'name_en' => 'Fleet Optimization',
                'name_fr' => 'Optimisation du parc automobile',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            92 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4tx5fb908nhmzbxvzz81',
                'name_en' => 'Floods',
                'name_fr' => 'Inondation',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            93 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4txhaaffp5drza9m9tkn',
                'name_en' => 'Fluorides',
                'name_fr' => 'Fluorure',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            94 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4txzaa7s93zh4tdrjnpt',
                'name_en' => 'Fly Wheels',
                'name_fr' => 'Volants d’inertie',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            95 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4tygdfp0r8n6pe3dp30t',
                'name_en' => 'Food',
                'name_fr' => 'Alimentaire',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            96 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4tyzsmqk8s3j05tscn7e',
                'name_en' => 'Food and Nutrition',
                'name_fr' => 'Aliments et nutrition',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            97 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4tzhhwvysr1rdax6m3ss',
                'name_en' => 'Food biochemistry',
                'name_fr' => 'Biochimie alimentaire',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            98 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v07g41xzq516ypqq2x7',
                'name_en' => 'Food Borne Chemical Contaminants',
                'name_fr' => 'Contaminants chimiques d\'origine alimentaire',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            99 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v0vc0b7t0hkv6pknz60',
                'name_en' => 'Food Borne Pathogens',
                'name_fr' => 'Pathogènes d\'origine alimentaire',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            100 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v1cazjghe431bh8adzr',
                'name_en' => 'Food chemical safety',
                'name_fr' => 'Sécurité chimique des aliments',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            101 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v1stabpjmp1sn7hys9j',
                'name_en' => 'Food microbiology',
                'name_fr' => 'Microbiologie des aliments',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            102 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v27aj271eqqqy287hva',
                'name_en' => 'Food process',
                'name_fr' => 'Procédés alimentaires',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            103 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v2zbxrt0p5errymfqhq',
                'name_en' => 'Food quality',
                'name_fr' => 'Qualité des aliments',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            104 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v3z1062svdzvqa1jwgz',
                'name_en' => 'Food Safety',
                'name_fr' => 'Salubrité alimentaire',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            105 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v4k3m9jw81hwbyjqk7v',
                'name_en' => 'Food Science',
                'name_fr' => 'Science des aliments',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            106 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v563hh8k872a3xqtn67',
                'name_en' => 'Food web transfer',
                'name_fr' => 'Transfert trophique',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            107 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v5vwwcax9rcycvs1b16',
                'name_en' => 'Food Webs',
                'name_fr' => 'Réseaux trophiques',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            108 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v6bnyf4qpd141kfbsdb',
                'name_en' => 'Footprint Reduction',
                'name_fr' => 'Réduction de l\'empreinte',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            109 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v6xebbbztp7stwwv2v3',
                'name_en' => 'Forage',
                'name_fr' => 'Des plantes fourragères',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            110 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v7eqvx5dvvw09m8qj3k',
                'name_en' => 'Forage diseases',
                'name_fr' => 'Maladies des plantes fourragères',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            111 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v8avnf9zk9w03fq1hqf',
                'name_en' => 'Forage grasses',
                'name_fr' => 'Des herbes fourragères',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            112 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v8v4dwk69zdzfwrgpws',
                'name_en' => 'Forage plant',
                'name_fr' => 'Plantes fourragères',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            113 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v9cmenghg21s2q9qe5e',
                'name_en' => 'Forages',
                'name_fr' => 'Les fourrages',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            114 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4v9xqcwm9vr6gyjdhsya',
                'name_en' => 'Forecasting',
                'name_fr' => 'Prévision',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            115 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vaejtnc8dhped0xk5tx',
                'name_en' => 'Forensics',
                'name_fr' => 'Médecine légale',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            116 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vb0py8qzz7vzx2362cj',
                'name_en' => 'Forensics pollution tracking',
                'name_fr' => 'Suivi judiciaire de la pollution',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            117 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vbfjcs5bv108b5369hd',
                'name_en' => 'Forest Assessment',
                'name_fr' => 'Évalutation des ressources forestières',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            118 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vbz5knsj1exmpnkjjan',
                'name_en' => 'Forest Based Communities',
                'name_fr' => 'Communautés forestières',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            119 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vcgd71fz2wdkv3jcgb4',
                'name_en' => 'Forest Carbon',
                'name_fr' => 'Carbone forestier',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            120 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vcz5r7sdbcfr6x85efs',
                'name_en' => 'Forest dynamics',
                'name_fr' => 'Dynamique forestière',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            121 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vddw9fxr6sa385g2e74',
                'name_en' => 'Forest ecological classification systems',
                'name_fr' => 'Forest ecological classification systems',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            122 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vdycsjyen4xn3013da8',
                'name_en' => 'Forest ecology',
                'name_fr' => 'Écologie des forêts',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            123 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vee7fdr2c44k8tj5xy5',
                'name_en' => 'Forest Ecosystem Integrity',
                'name_fr' => 'Intégrité des écosytèmes forestiers',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            124 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vew5z8g53f0therxh31',
                'name_en' => 'Forest fire',
                'name_fr' => 'Incendie de forêt',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            125 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vfd7eynjmbmn6mtae7t',
                'name_en' => 'Forest Fires',
                'name_fr' => 'Feux de forêt',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            126 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vfynvn8babsx1rz4w02',
                'name_en' => 'Forest genetics',
                'name_fr' => 'Génétique forestière',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            127 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vg9hfsgx831998cmf6f',
                'name_en' => 'Forest Industry',
                'name_fr' => 'Industrie forestière',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            128 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vgrgjnzs2pkr7f6ndgm',
                'name_en' => 'Forest invasive alien species',
                'name_fr' => 'Espèces exotiques envahissantes des forêts',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            129 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vh7bpxb11mm6qw7h3a7',
                'name_en' => 'Forest management',
                'name_fr' => 'Aménagement forestier',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            130 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vhqtyrz2d8knrkb3bxa',
                'name_en' => 'Forest mensuration',
                'name_fr' => 'Dendrométrie',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            131 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vj7nsrfgsjfzhzbsr5f',
                'name_en' => 'Forest modeling',
                'name_fr' => 'Modélisation de forêts',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            132 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vjn6cfgn5batkahkan9',
                'name_en' => 'Forest Products',
                'name_fr' => 'Produits forestiers',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            133 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vk6adwdkakrha37gpms',
                'name_en' => 'Forest Tenure',
                'name_fr' => 'Tenure forestière',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            134 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vkn4pfw0stv8j48jw69',
                'name_en' => 'Forestry',
                'name_fr' => 'Foresterie',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            135 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vm3wke102ypscpwfd1g',
                'name_en' => 'Forests',
                'name_fr' => 'Forêts',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            136 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vmjtn0p8yyknnpyq5n7',
                'name_en' => 'Forming',
                'name_fr' => 'Formage',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            137 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vmz89tj23e17fprphnv',
                'name_en' => 'Forming and Molding Processes',
                'name_fr' => 'Processus de formage et de moulage',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            138 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vnet5nndv64p3phj8cx',
                'name_en' => 'Fortification',
                'name_fr' => 'Fortification/Enrichissement',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            139 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vnzgdp8xdxxj1f314c4',
                'name_en' => 'Fossil Fuels',
                'name_fr' => 'Combustibles fossiles',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            140 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vpee78g616pmfr008q8',
                'name_en' => 'Fraser River-Georgia Basin',
                'name_fr' => 'Fleuve Fraser - bassin de Géorgie',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            141 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vpxzzxsgywzc0n4yhj5',
                'name_en' => 'Freshwater',
                'name_fr' => 'Eau douce',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            142 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vqd0bcy5sa6g0vnn3jb',
                'name_en' => 'Freshwater Lakes',
                'name_fr' => 'Lacs d\'eau douce',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            143 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vqvmv674emx6e7f2e1s',
                'name_en' => 'Friction stir welding',
                'name_fr' => 'Soudage par friction-malaxage',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            144 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vrasapew34d5vsvwx8b',
                'name_en' => 'Froth',
                'name_fr' => 'Mousse',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            145 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vrsexqp338ntpetk5ws',
                'name_en' => 'Fruit',
                'name_fr' => 'Fruits',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            146 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vsahdky4grw0j8201h1',
                'name_en' => 'Fuel Cells',
                'name_fr' => 'Piles à combustible',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            147 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vsqaezw0sfw4fb1j6gy',
                'name_en' => 'Fuel consumption',
                'name_fr' => 'Consommation de carburant',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            148 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vtc7v140b17j2gecn88',
                'name_en' => 'Fuelling Infrastructure',
                'name_fr' => 'Infrastructure de ravitaillement',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            149 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vtv5havytg9ef6kstvw',
                'name_en' => 'Functional',
                'name_fr' => 'Fonctionnelle',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            150 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vv76znsqy41mf4vybfr',
                'name_en' => 'Functional ingredients',
                'name_fr' => 'Ingrédients fonctionnels',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            151 => 
            array (
                'created_at' => '2023-10-27 16:05:46',
                'id' => '01hdry4vvpx3bwf81w0cpzb67a',
                'name_en' => 'Fungal',
                'name_fr' => 'Champignons',
                'updated_at' => '2023-10-27 16:05:46',
            ),
            152 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4vw4xxj86zrd2341kjwx',
                'name_en' => 'Fusarium head blight',
                'name_fr' => 'Fusariose de l’épi',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            153 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4vwm61pywntbet1jtyaz',
                'name_en' => 'Future Fuels',
                'name_fr' => 'Carburants de l\'avenir',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            154 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4vx4znkxm39ag779b83a',
                'name_en' => 'Garden Soil',
                'name_fr' => 'Terre de jardin',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            155 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4vxqx9mvxxva2bjzgw11',
                'name_en' => 'Gas Hydrates',
                'name_fr' => 'Les hydrates de gaz',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            156 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4vy7hzxtv2g8mayef73p',
                'name_en' => 'Gas industry',
                'name_fr' => 'Industrie gazière',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            157 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4vyqtr1ss9cmrq48wyzt',
                'name_en' => 'Genetic diversity',
                'name_fr' => 'Diversité génétique',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            158 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4vz7jyn4pm02njtw8ap4',
                'name_en' => 'Genetic Engineering',
                'name_fr' => 'Génie génétique',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            159 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4vzrmf366xatgnsb5bqf',
                'name_en' => 'Genetic Toxicology',
                'name_fr' => 'Toxicologie génétique',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            160 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4w09cgvnac5a0d0mpaee',
                'name_en' => 'Genetically Modified Foods',
                'name_fr' => 'Aliment génétiquement modifié',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            161 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4w0tk9mtdf28prncq18e',
                'name_en' => 'Genetics',
                'name_fr' => 'Génétique',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            162 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4w1d8vthpce8gzkktag5',
                'name_en' => 'Genomics',
                'name_fr' => 'Génomiques',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            163 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4w27v95h2hmmt7wzw1k6',
                'name_en' => 'Genomics techniques',
                'name_fr' => 'Techniques génomiques',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            164 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4w2zs9zbqpyk12391fpf',
                'name_en' => 'Geochemistry',
                'name_fr' => 'Géochimie',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            165 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4w3fb0hpjd6txk0yrdnf',
                'name_en' => 'Geochemistry - Inorganic',
                'name_fr' => 'Géochimie - inorganique',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            166 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4w40g9vjchk2xq2cckdx',
                'name_en' => 'Geochemistry - Organic',
                'name_fr' => 'Géochimie - organique',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            167 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4w4gf0pygxrmdnawyy9e',
                'name_en' => 'Geochronology',
                'name_fr' => 'Géochronologie',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            168 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4w59b800nxkkrv98rek8',
                'name_en' => 'Geodesy',
                'name_fr' => 'Géodésie',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            169 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4w5pty5sprfys02wzqa5',
                'name_en' => 'Geodetic Reference Systems',
                'name_fr' => 'Systèmes de réference géodésiques',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            170 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4w6amkpjpc1q51w38g03',
                'name_en' => 'Geographic data',
                'name_fr' => 'Données géographiques',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            171 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4w6wv780sc6xeb38w3hb',
            'name_en' => 'Geographic Information System (GIS)',
            'name_fr' => 'Système d\'information géographique (SIG)',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            172 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4w7gvdw1rqjntmny36zs',
                'name_en' => 'Geographic information systems',
                'name_fr' => 'Système d\'information géographique',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            173 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4w9ef5ayjz9ebnqex100',
                'name_en' => 'Geographical Names',
                'name_fr' => 'Toponymes',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            174 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wa02detxp9bq6bv1t3w',
                'name_en' => 'Geography',
                'name_fr' => 'Géographie',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            175 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wadh37qnmrb5gzqm4k8',
                'name_en' => 'Geological',
                'name_fr' => 'Géologique',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            176 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wawnr3as99d1428ypsd',
                'name_en' => 'Geological mapping',
                'name_fr' => 'Cartographie géologique',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            177 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wbc18xe9e36df3akdwv',
                'name_en' => 'Geology',
                'name_fr' => 'Géologie',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            178 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wbts8d8wk1j52v37fwq',
                'name_en' => 'Geology - Bedrock',
                'name_fr' => 'Géologie du substratum',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            179 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wca8v96xq0w89grm9xb',
                'name_en' => 'Geology - Marine',
                'name_fr' => 'Géologie - marine',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            180 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wcsn3ep7zjeb2f7pbzt',
                'name_en' => 'Geology - Structural',
                'name_fr' => 'Géologie - structurale',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            181 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wda8fz9htpzr50qfka8',
                'name_en' => 'Geology - Surficial',
                'name_fr' => 'Géologie de surface',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            182 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wdzpsyk3fh7pa4tbxk6',
                'name_en' => 'Geomatic',
                'name_fr' => 'Géomatique',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            183 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4weh5q83p70t2hc0kw34',
                'name_en' => 'Geomorphology',
                'name_fr' => 'Géomorphologie',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            184 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wf4fbfwx5mvkmvbqf44',
                'name_en' => 'Geophysics',
                'name_fr' => 'Géophysique',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            185 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wfr4jb2gqfy7tppbcjq',
                'name_en' => 'Georgia Basin',
                'name_fr' => 'Bassin de Georgia',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            186 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wg9nknq3391zx7ey5vc',
                'name_en' => 'Geoscience',
                'name_fr' => 'Géoscience',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            187 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wgt9m750axmgp9hex45',
                'name_en' => 'Geoscience - Marine',
                'name_fr' => 'Géoscience - marine',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            188 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wh98jt8f3mcxt9z1a8j',
                'name_en' => 'Geospatial Analysis',
                'name_fr' => 'Analyse géospatiale',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            189 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4whta3pytqm9hfqfnxts',
                'name_en' => 'Geospatial Data Management',
                'name_fr' => 'Gestion de données géospatiales',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            190 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wja42wjw9yz5xbthj58',
                'name_en' => 'GIS - Geographical Info System',
                'name_fr' => 'SIG - Système d\'info géographique',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            191 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wjv3kt86zt8j9x8zgf0',
                'name_en' => 'GIS Technician',
                'name_fr' => 'Technicien du SIG',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            192 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wkarn48k337nj68prhf',
                'name_en' => 'Glaciers',
                'name_fr' => 'Glaciers',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            193 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wkp0j5tmgphy83871nv',
            'name_en' => 'Global Navigation Satellite Systems (GNSS)',
            'name_fr' => 'Systèmes globaux de radiolocalisation par satellite (GNSS)',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            194 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wm64mawd880m5808rdg',
                'name_en' => 'Governance and Infrastructure Support to First Nations and Inuit Health System',
                'name_fr' => 'Gouvernance et soutien à l\'infrastructure pour le système de Santé des Premières nations et des Inuits',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            195 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wmnyvwgq8sv46hw971f',
                'name_en' => 'Government and Management Support',
                'name_fr' => 'Appui à la gouvernance et à la gestion',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            196 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wncc5xmba9ed17xn0yn',
                'name_en' => 'GPS',
                'name_fr' => 'GPS',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            197 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wnts034szmqns0ehrbs',
                'name_en' => 'GPS radio-occultation',
                'name_fr' => 'Radio-occultation GPS',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            198 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wp935gvh5qexyzpp8ex',
                'name_en' => 'GPU',
            'name_fr' => 'Unité de traitement graphique (UTG)',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            199 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wps22dxp4n0d7eh5hyf',
                'name_en' => 'Grain quality',
                'name_fr' => 'Qualité des grains',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            200 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wqjv99sfxmend385fk4',
                'name_en' => 'Grain technology',
                'name_fr' => 'Technologie céréalière',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            201 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wr3w5xy9p1g7fx4w72m',
                'name_en' => 'Grand Banks of Newfoundland',
                'name_fr' => 'Grands Bancs de Terre-Neuve',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            202 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wrkvmgks1hs0yf5mzza',
                'name_en' => 'Grapes, plant virus vectors',
                'name_fr' => 'Raisins, vecteurs des phytovirus',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            203 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4ws3v6e1zt808jpf70j3',
                'name_en' => 'Grassland birds',
                'name_fr' => 'Oiseaux des prairies',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            204 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wsmkk443p56kkncpw9e',
                'name_en' => 'Grasslands',
                'name_fr' => 'Surfaces en herbe',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            205 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wt903qx6amazpjsqkag',
                'name_en' => 'Grazing',
                'name_fr' => 'Pâturage',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            206 => 
            array (
                'created_at' => '2023-10-27 16:05:47',
                'id' => '01hdry4wtthvmk45rh9mvddc0k',
                'name_en' => 'Grazing management',
                'name_fr' => 'Gestion des pâturages',
                'updated_at' => '2023-10-27 16:05:47',
            ),
            207 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4wvb0qyx493vmb3bw2h5',
                'name_en' => 'Great Lakes',
                'name_fr' => 'Grands Lacs',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            208 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4wvxg9pnvc3wxd9s4bme',
                'name_en' => 'Great Lakes Water Quality Agreement',
                'name_fr' => 'Accord relatif à la qualité de l\'eau dans les Grands Lacs',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            209 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4wweh3frwegeefjxqqs3',
                'name_en' => 'Great Lakes-St. Lawrence',
                'name_fr' => 'Grands Lacs - St-Laurent',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            210 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4wx6fc9gbb9xpmytbtkf',
                'name_en' => 'Green roof technology',
                'name_fr' => 'Technologie de toits verts',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            211 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4wxtxjzvqb5f7x7kp98p',
                'name_en' => 'Green technologies',
                'name_fr' => 'Technologies vertes',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            212 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4wyb3fmwcecb25y9d6dj',
                'name_en' => 'Greenhouse',
                'name_fr' => 'L’environnement serricole',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            213 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4wyzkas2rvr1vzmfamds',
                'name_en' => 'Greenhouse and vegetable',
                'name_fr' => 'Serriculture et des légumes',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            214 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4wzgqfkhvqwebnyh4xwc',
                'name_en' => 'Greenhouse crop',
                'name_fr' => 'L\'environnement serricole',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            215 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x01h7btazn5qvx3f3cg',
            'name_en' => 'Greenhouse Gas (GHG) Reduction',
            'name_fr' => 'Réduction des gaz à effet de serre (GES)',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            216 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x0jbe3nqfv87wscnw8m',
                'name_en' => 'Greenhouse gases',
                'name_fr' => 'Gaz à effet de serre',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            217 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x15btp37pdqy04kbtwx',
                'name_en' => 'Grid Integration',
                'name_fr' => 'Intégration au réseau',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            218 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x207rhbpx0xg0a26n19',
                'name_en' => 'Ground beetle',
                'name_fr' => 'Des carabes',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            219 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x2qq72th7j6j4dnbs7f',
                'name_en' => 'Ground Control',
                'name_fr' => 'Contrôle du sol',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            220 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x3amez0htd6rs9fc415',
                'name_en' => 'Groundwater',
                'name_fr' => 'Eau souterraine',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            221 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x41c7vabngy5h5zmcft',
                'name_en' => 'Groundwater science',
                'name_fr' => 'Science des eaux souterraines',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            222 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x4kxqrecep7w7rcfc9w',
                'name_en' => 'Gulf of Maine',
                'name_fr' => 'Golfe du Maine',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            223 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x58dg6btphf479yqv5q',
                'name_en' => 'Gulf of St. Lawrence',
                'name_fr' => 'Golfe du St-Laurent',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            224 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x5r8tgc5rwy2tz01cs8',
                'name_en' => 'Gulfwatch',
                'name_fr' => 'Surveillance de golfe',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            225 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x6az8jf4vc4m7pcm8zw',
                'name_en' => 'Habitat',
                'name_fr' => 'Habitat',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            226 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x6vrfv59v34z471tegr',
                'name_en' => 'Habitat replenishment',
                'name_fr' => 'Reconstitution des habitats',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            227 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x7dxvsy6r5jnsbk42jz',
                'name_en' => 'Habitat selection',
                'name_fr' => 'Sélection de l\'habitat',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            228 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x80ysnd3t39k79kn7qr',
                'name_en' => 'Haida Gwaii',
                'name_fr' => 'Haida Gwaii',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            229 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x8kx853pc8yhfysx0h2',
                'name_en' => 'Haptics',
                'name_fr' => 'Haptiques',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            230 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x97tv0y6sgkw1xw2g38',
                'name_en' => 'Hardware',
                'name_fr' => 'Matériel',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            231 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4x9q7jn7zm0n720261f2',
                'name_en' => 'Harvesting',
                'name_fr' => 'Récolte',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            232 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xa9y8k32aywyctvq27m',
                'name_en' => 'Hazard Identification',
                'name_fr' => 'Détermination des dangers',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            233 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xav0ny59f4eshk5f2pd',
                'name_en' => 'Health',
                'name_fr' => 'Santé',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            234 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xbamh70sx2pvcd3y3mk',
                'name_en' => 'Health and Wellness',
                'name_fr' => 'Santé et bien-être animal',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            235 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xbvreas4y8t48t73r97',
                'name_en' => 'Health Behaviour',
                'name_fr' => 'Comportements de santé',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            236 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xcdgz5cm61pmyv0d23p',
                'name_en' => 'Health Benefits',
                'name_fr' => 'Effets positifs sur la santé',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            237 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xcz4tmj4gkdxfe8n7yr',
                'name_en' => 'Health Information',
                'name_fr' => 'Information sur la santé',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            238 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xdjz6x98zsfxbhaexty',
                'name_en' => 'Health physics',
                'name_fr' => 'Radioprotection',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            239 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xe3d0fyz1avfxxkb0zs',
                'name_en' => 'Health Products',
                'name_fr' => 'Produits de santé',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            240 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xens81anb48c0dm2nme',
                'name_en' => 'Health promotion',
                'name_fr' => 'Promotion de la santé',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            241 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xf5p575ad009sp5jsym',
                'name_en' => 'Health Sciences',
                'name_fr' => 'Sciences de la santé',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            242 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xfn76k0v8zzeg5bkp8y',
                'name_en' => 'Health System Renewal',
                'name_fr' => 'Renouvellement du système de santé',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            243 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xg6se4bxm001qw29f54',
                'name_en' => 'Heat Transfer',
                'name_fr' => 'Transfert de chaleur',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            244 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xgpnwcak70zf58fn2g2',
            'name_en' => 'Heating, Ventilation and Air Conditioning (HVAC)',
            'name_fr' => 'Chauffage, ventilation et climatisation (CVC)',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            245 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xh4qkw9fyhd1jsk7b8q',
                'name_en' => 'Heavy metals',
                'name_fr' => 'Métaux lourds',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            246 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xhr7d7g1kv1nym0b4np',
                'name_en' => 'Heavy Vehicle Engineering and testing',
                'name_fr' => 'Ingénierie et essais de véhicules lourds',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            247 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xj8qhmskvs5z9v0yrm1',
                'name_en' => 'Helminth',
                'name_fr' => 'Helminthes',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            248 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xjsvw8kvksbrx0dq3hq',
                'name_en' => 'Hemp',
                'name_fr' => 'Du chanvre',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            249 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xk8y630be2ydf6ban3c',
                'name_en' => 'Herbicide-resistant plants',
                'name_fr' => 'Plantes résistant aux herbicides',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            250 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xkvf8m07jk256dwvqbz',
                'name_en' => 'Herbicides',
                'name_fr' => 'Herbicides',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            251 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xm9k1ydgje5af0pfsjw',
                'name_en' => 'Herpetofauna',
                'name_fr' => 'Herpétofaune',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            252 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xmq6pp8ye5h2znjy1ad',
                'name_en' => 'High performance rail',
                'name_fr' => 'Transport ferroviaire haute performance',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            253 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xn76bk304dq3bps87aa',
                'name_en' => 'HiPrOx Power Systems',
                'name_fr' => 'Réseau énergétique HiPrOx',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            254 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xnp7wv8mqfnpeveqe2m',
                'name_en' => 'Horizontal gene transfer',
                'name_fr' => 'Transfert horizontal de gènes',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            255 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xp11pvcazqb5k62qmac',
                'name_en' => 'Horizontal Reference Systems',
                'name_fr' => 'Systèmes de référence horizontaux',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            256 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xphrfj5781scwzr29ny',
                'name_en' => 'Hormone Profiling',
                'name_fr' => 'Profilage hormonal',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            257 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xq2bwx4k0586nt726ae',
                'name_en' => 'Horticulture',
                'name_fr' => 'Horticulture',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            258 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xqj6g4za36kv7ps0k1e',
                'name_en' => 'Host plant resistance',
                'name_fr' => 'Résistance des plantes hôtes',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            259 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xr9psdzy67mp3efcq2b',
                'name_en' => 'House Dust',
                'name_fr' => 'Poussière domestique',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            260 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xrxrs6p9rk9ngckf33z',
                'name_en' => 'Housing research',
                'name_fr' => 'Recherche sur l’habitation',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            261 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xshhh95b2c61hsr4ndr',
                'name_en' => 'Hudson Bay',
                'name_fr' => 'Baie d\'Hudson',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            262 => 
            array (
                'created_at' => '2023-10-27 16:05:48',
                'id' => '01hdry4xt5gy9pet5fnsynjp55',
                'name_en' => 'Hudson Strait',
                'name_fr' => 'Détroit d\'Hudson',
                'updated_at' => '2023-10-27 16:05:48',
            ),
            263 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4xtm35ncf33hphs96faz',
                'name_en' => 'Hudson-James Bay',
                'name_fr' => 'Baie d\'Hudson - Baie James',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            264 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4xv6zemc599b65bfqwvk',
                'name_en' => 'Human',
                'name_fr' => 'Humaine',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            265 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4xvnhd0j6m808jxv5dqz',
                'name_en' => 'Human Exposure Research',
                'name_fr' => 'Recherche sur l\'exposition humaine',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            266 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4xw666hp103xy78ht6fa',
                'name_en' => 'Human Health Risk Assessment',
                'name_fr' => 'Évaluation des risques pour la santé humaine',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            267 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4xwpm3vx0r6j4hrh2gf7',
                'name_en' => 'Human impacts',
                'name_fr' => 'Incidence de l\'homme',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            268 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4xx97rbmd643edgvzvc8',
                'name_en' => 'Human resources',
                'name_fr' => 'Ressources humaines',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            269 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4xxr773xbmdbhqvwa3z5',
                'name_en' => 'Human Resources Management',
                'name_fr' => 'Gestion des ressources humaines',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            270 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4xy998tqfcr5jsfjejhb',
                'name_en' => 'Human-computer interaction',
                'name_fr' => 'Interaction personne-machine',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            271 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4xyrqtjfs3d7pqxkavb1',
                'name_en' => 'Human-machine interactivity',
                'name_fr' => 'Interactivité homme-machine',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            272 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4xz83149p58g9vq9we4n',
                'name_en' => 'Hurricanes',
                'name_fr' => 'Ourogan',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            273 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4xzte1pg6ery304gb3bm',
                'name_en' => 'Hydroacoustic',
                'name_fr' => 'Hydroacoustique',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            274 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y0c3rhk8sqbyp6c9059',
                'name_en' => 'Hydrobiology',
                'name_fr' => 'Hydrobiologie',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            275 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y19vf0ngt80q6n13x3n',
                'name_en' => 'Hydrocarbons',
                'name_fr' => 'Hydrocarbures',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            276 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y1r927n7kjfy5zbp0d0',
                'name_en' => 'Hydroelectric plants',
                'name_fr' => 'Centrale hydroélectrique',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            277 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y292gcqcrqb5hd9d5x5',
                'name_en' => 'Hydrogen',
                'name_fr' => 'Hydrogène',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            278 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y2tmd5q1e52sra6j4q4',
                'name_en' => 'Hydrogeology',
                'name_fr' => 'Hydrogéologie',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            279 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y3aqhpa96ee6tvns3hy',
                'name_en' => 'Hydrography',
                'name_fr' => 'Hydrographie',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            280 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y3v890kzpy09ygcej1r',
                'name_en' => 'Hydrography and Seabed Mapping',
                'name_fr' => 'Hydrographie et cartographie du fond marin',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            281 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y4c7kt576vr03yqyqe3',
                'name_en' => 'Hydrological',
                'name_fr' => 'Ressources hydrologiques',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            282 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y4xqvmwm1zdx9md3f4w',
                'name_en' => 'Hydrology',
                'name_fr' => 'Hydrologie',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            283 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y5ee61shf9695hnvd6z',
                'name_en' => 'Hydrostratigraphy',
                'name_fr' => 'Hydrostratigraphie',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            284 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y6157mkw26n6nyf6zyz',
                'name_en' => 'Ice',
                'name_fr' => 'Glace',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            285 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y6nnqt8jh1s0xwswcy7',
                'name_en' => 'Ice jams',
                'name_fr' => 'Embâcles',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            286 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y7a8yrq93gwh8fy1byt',
                'name_en' => 'Ice mechanics',
                'name_fr' => 'Mécanique des glaces',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            287 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y7z4c8fmny3xjjgf9c3',
                'name_en' => 'Iceberg',
                'name_fr' => 'Iceberg',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            288 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y8h94geznrgr9v1aaw3',
            'name_en' => 'Ichneumonid (parasitic) wasp',
                'name_fr' => 'Des ichneumonidés parasitoïdes',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            289 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y95tej2g9gwm9z3zk38',
                'name_en' => 'Icing / deicing',
                'name_fr' => 'Givrage / dégivrage',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            290 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4y9s5asgq1avc87a8f1q',
                'name_en' => 'Illegal Logging',
                'name_fr' => 'Exploitation illégale',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            291 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yacd5n4aqw0gem6c37m',
                'name_en' => 'Image Analytics',
                'name_fr' => 'Analyse de l\'image',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            292 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yb2gwkgv46zxhe7cqw2',
                'name_en' => 'Immunology',
                'name_fr' => 'Immunologie',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            293 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4ybn2d0eff9cndqsh92e',
                'name_en' => 'Immunotoxicology',
                'name_fr' => 'Immunotoxicologie',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            294 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4ycde6713gybmdc0yezd',
                'name_en' => 'Impacts of urbanization',
                'name_fr' => 'Incidence de l\'urbanisation',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            295 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yd3gne58hszrnafr2y1',
                'name_en' => 'Implants',
                'name_fr' => 'Implants',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            296 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4ydns2pep2qmywf4wj2y',
                'name_en' => 'In-vitro digestion',
                'name_fr' => 'Digestion In Vitro',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            297 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4ye2pvxqcjekqa0cx5xh',
                'name_en' => 'Indigenous collaborations',
                'name_fr' => 'Collaborations autochtones',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            298 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yeps81gnzw68v4eppq4',
                'name_en' => 'Indoor Air',
                'name_fr' => 'Air intérieur',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            299 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yf2abe8pv90agcw4r43',
                'name_en' => 'Indoor air quality',
                'name_fr' => 'Qualité de l\'air intérieur',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            300 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yfjarak04rm4t25mxfh',
                'name_en' => 'Industrial',
                'name_fr' => 'Industrielle',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            301 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yg3wk6aj98qgq2tk6jp',
                'name_en' => 'Industrial Biotechnology',
                'name_fr' => 'Biotechnologies industrielles',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            302 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4ygm998grmfweayv1323',
                'name_en' => 'Industrial Designs',
                'name_fr' => 'Dessins industriels',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            303 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yhbfqx8z3x1fz3b6vc6',
                'name_en' => 'Industrial impacts',
                'name_fr' => 'Incidence de l\'industrie',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            304 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yhww2cwwb0sgvj99zbn',
                'name_en' => 'Industrial Research',
                'name_fr' => 'Recherche industrielle',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            305 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yja7z7p89p0242nqrsx',
                'name_en' => 'Industrial technology',
                'name_fr' => 'Technologie industrielle',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            306 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yjx3yw713x850sbr78v',
                'name_en' => 'Infections',
                'name_fr' => 'Infections',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            307 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4ykdgft4mf8x4pcwp6mb',
                'name_en' => 'Infectious diseases',
                'name_fr' => 'Maladie infectieuse',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            308 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4ykskxvh4rx2edzhne8z',
                'name_en' => 'Information Management',
                'name_fr' => 'Gestion de l\'information',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            309 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4ymb4khsd45ekdkbq1ye',
                'name_en' => 'Information network',
                'name_fr' => 'Réseau d\'information',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            310 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yn0ed38z993f45296vz',
                'name_en' => 'Information systems',
                'name_fr' => 'Système d\'information',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            311 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4ynh4kg60sj4a3d3vrey',
                'name_en' => 'Information Technology',
                'name_fr' => 'Technologie de l\'information',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            312 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yp2s4ewczqvw5bc5s4x',
                'name_en' => 'Infrastructure',
                'name_fr' => 'Infrastructure',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            313 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4ypj14ms0qrxe3e7xybz',
                'name_en' => 'Inhalation Exposures',
                'name_fr' => 'Exposition par inhalation',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            314 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yq2e4s3d313mj0b7q7h',
                'name_en' => 'Inland waters',
                'name_fr' => 'Eaux intérieures',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            315 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yqetsqhqkmpv9jgq3fb',
                'name_en' => 'Inorganic Chemistry',
                'name_fr' => 'Chimie inorganique',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            316 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yqx2nj3qbgw2bydmsgp',
                'name_en' => 'Inorganic contaminants',
                'name_fr' => 'Contaminants inorganiques',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            317 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yrdarn83wtsmr2v22md',
                'name_en' => 'Insect',
                'name_fr' => 'Des insectes',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            318 => 
            array (
                'created_at' => '2023-10-27 16:05:49',
                'id' => '01hdry4yryq1d9h50xj2x8vgjz',
                'name_en' => 'Insect ecology',
                'name_fr' => 'Écologie des insectes',
                'updated_at' => '2023-10-27 16:05:49',
            ),
            319 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4ysgzv489q0m50x0rn0s',
                'name_en' => 'Insect molecular',
                'name_fr' => 'Moléculaire des insectes',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            320 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4yt0pss4qjkv45n6am41',
                'name_en' => 'Insect pathology',
                'name_fr' => 'Pathologie des insectes',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            321 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4ytr06vwrda03dk97x5x',
                'name_en' => 'Insect pest management',
                'name_fr' => 'Lutte contre les insectes nuisibles',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            322 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4yv90abcgrf40hdx6evv',
                'name_en' => 'Insect pests',
                'name_fr' => 'Les insectes nuisibles',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            323 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4yvvk7445ejgeb33rwbs',
                'name_en' => 'Insect-plant interactions',
                'name_fr' => 'Interactions plantes-insectes',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            324 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4ywhdh50ar4373ttj8zw',
                'name_en' => 'Insecticide toxicology',
                'name_fr' => 'Toxicologie des insecticides',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            325 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4yxcrsm9bxp9zav6wnkd',
                'name_en' => 'Insects',
                'name_fr' => 'Insectes',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            326 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4yxw1dcfrgj2z0xdsd9j',
                'name_en' => 'Insects - Baculovirus',
                'name_fr' => 'Insectes - Baculovirus',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            327 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4yye564be68gnbg117yg',
                'name_en' => 'Insects - Integrated pest management',
                'name_fr' => 'Insectes -  Lutte antiparasitaire intégrée',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            328 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4yz3f721ba3b23q3jq79',
                'name_en' => 'Insects - Plant-insect interactions',
                'name_fr' => 'Insectes - Interactions plantes-insectes',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            329 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4yznd94fx9bb43scmyhr',
                'name_en' => 'Instrumentation',
                'name_fr' => 'Instrumentation',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            330 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z0fa4wh5jzzzp8sfej3',
                'name_en' => 'Insulation',
                'name_fr' => 'Isolation thermique',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            331 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z15fm4h8kmbv1waztwf',
            'name_en' => 'Integrated biology (zoology)',
            'name_fr' => 'Biologie intégrée (zoologie)',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            332 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z1pvf1vy7knbqs5gzk4',
                'name_en' => 'Integrated circuit layout designs',
                'name_fr' => 'Schémas de configuration de circuits intégrés',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            333 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z28jak9881k1qtyewwx',
                'name_en' => 'Integrated management',
                'name_fr' => 'Gestion intégrée',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            334 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z2rdng16ma9148s1nm4',
                'name_en' => 'Integrated Oceans Management Science',
                'name_fr' => 'Science de la gestion intégrée des océans',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            335 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z3arkbv0bcavnrfka37',
                'name_en' => 'Integrated pest management',
                'name_fr' => 'Lutte antiparasitaire intégrée',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            336 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z3vha8zft3wjc9811gp',
                'name_en' => 'Integrated pest management – small fruits',
                'name_fr' => 'Lutte antiparasitaire intégrée – petits fruits',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            337 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z4g5dcmcc32kjmm1pe2',
                'name_en' => 'Integration',
                'name_fr' => 'Intégration',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            338 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z50qj12qew58a67j7n1',
                'name_en' => 'Integration Management',
                'name_fr' => 'Gestion de l\'intétration',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            339 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z5qe4mzy65dhym5174t',
                'name_en' => 'Intellectual Property',
                'name_fr' => 'Propriété intellectuelle',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            340 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z671v3c5s50kmzwpk8k',
                'name_en' => 'Intelligent systems',
                'name_fr' => 'Système intelligent',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            341 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z6ry52hqt214npaq8wa',
                'name_en' => 'Intelligent Transportation Systems',
                'name_fr' => 'Systèmes de transport intelligents',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            342 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z799qb7b51syxpz9n8b',
                'name_en' => 'Interactive',
                'name_fr' => 'Interactive',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            343 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z7va05989sgjbwwty95',
                'name_en' => 'Interactive AR/VR',
                'name_fr' => 'RV/RA Interactive',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            344 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z8a26fkedmc9fc6m9dk',
                'name_en' => 'Interactive Simulation',
                'name_fr' => 'Simulation interactive',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            345 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z8xermqdkbsb42crwab',
                'name_en' => 'Interior Plains',
                'name_fr' => 'Plaines intérieures',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            346 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4z9fafkcdyknp5mj11vm',
                'name_en' => 'Interior Platform',
                'name_fr' => 'Quai intérieure',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            347 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4za19vnhhqtveepsayj0',
                'name_en' => 'Internal Services',
                'name_fr' => 'Services internes',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            348 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zah4f43x9vtrpqs7sj8',
                'name_en' => 'International guidelines',
                'name_fr' => 'Lignes directrices internationales',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            349 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zb2w2ns70kbnyhsn89w',
                'name_en' => 'International Health Affairs',
                'name_fr' => 'Affaires internationales de santé',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            350 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zbkh14gwserqw7cesn1',
                'name_en' => 'International telecommunications',
                'name_fr' => 'Télécommunications internationales',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            351 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zc45peyr20wes9cp6gp',
                'name_en' => 'Internationally Protected Persons Health',
                'name_fr' => 'Santé des personnes jouissant d\'une protection internationale',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            352 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zcn38s2sm1st398bggq',
                'name_en' => 'Internet',
                'name_fr' => 'Internet',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            353 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zd7bheknp6335ft2z9a',
                'name_en' => 'Internet of things',
                'name_fr' => 'Internet des objets',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            354 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zdr3p5n0c2hqjttwv9w',
                'name_en' => 'Intestinal',
                'name_fr' => 'Intestinale',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            355 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zee2z3905srxe4m4d6j',
                'name_en' => 'Intranets',
                'name_fr' => 'Intranet',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            356 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zevtwzc52zt3qa31y71',
                'name_en' => 'Invasive insect pests',
                'name_fr' => 'Insectes nuisibles invasifs',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            357 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zfsrcjgd96t6q3mdbzm',
                'name_en' => 'Invasive Species',
                'name_fr' => 'Espèces envahissantes',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            358 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zg92w47nsrs7rkfct8r',
                'name_en' => 'Inventions',
                'name_fr' => 'Invention',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            359 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zgt1fw8j7tj5tfytav7',
                'name_en' => 'Invertebrate Stock Assessment',
                'name_fr' => 'Évaluation des stocks d\'invertébrés',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            360 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zhfeqd4hsxnqgkx54tn',
                'name_en' => 'Ionization and Radiation',
                'name_fr' => 'Ionisation et radiation',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            361 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zj6xh8jwxrz5madg823',
                'name_en' => 'Irrigation',
                'name_fr' => 'Irrigation',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            362 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zjvdapvg2gfjf2y15nm',
                'name_en' => 'Irrigation agronomy',
                'name_fr' => 'Agronomie de l’irrigation',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            363 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zkaz57rf94gx0x8szzx',
                'name_en' => 'Irrigation and drainage',
                'name_fr' => 'L’irrigation et du drainage',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            364 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zkwykjdjvzmytk77238',
                'name_en' => 'Island',
                'name_fr' => 'Île',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            365 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zmbe5ggnqzjme7bytt1',
                'name_en' => 'Kimberley Process',
                'name_fr' => 'Processus de Kimberley',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            366 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zms5c0dqc67xpq4pd5h',
                'name_en' => 'Knowledge and technology transfer',
                'name_fr' => 'Transfert des connaissances et des technologies',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            367 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zn6re4e8g3ca01g74fw',
                'name_en' => 'Knowledge brokering',
                'name_fr' => 'Transmission du savoir',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            368 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4znqq8dq6k1ctwthkgwq',
                'name_en' => 'Knowledge management',
                'name_fr' => 'Gestion du savoir',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            369 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zp46zqk25rdmd3hw4dp',
                'name_en' => 'Knowledge Transfer',
                'name_fr' => 'Transfert des connaissances',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            370 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zppzjdww87xe0xq06se',
                'name_en' => 'Knowledge translation',
                'name_fr' => 'L’application des connaissances',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            371 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zq7wwmmgxzvrmhdb5ks',
                'name_en' => 'Labelling',
                'name_fr' => 'Étiquetage',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            372 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zqvav7kqradz7txe24w',
                'name_en' => 'Laboratories',
                'name_fr' => 'Laboratoire',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            373 => 
            array (
                'created_at' => '2023-10-27 16:05:50',
                'id' => '01hdry4zr805pyz069f4gcmjdy',
                'name_en' => 'Laboratory measurements',
                'name_fr' => 'Mesures en laboratoire',
                'updated_at' => '2023-10-27 16:05:50',
            ),
            374 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry4zrsc03jxhdh526xt99q',
                'name_en' => 'Labrador Sea',
                'name_fr' => 'Mer du Labrador',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            375 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry4zse6aeh1ykn3vgtyb93',
                'name_en' => 'Labrador Shelf',
                'name_fr' => 'Plateau du Labrador',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            376 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry4zsyvttfmsm55zqtkzvs',
                'name_en' => 'Lactation',
                'name_fr' => 'La lactation',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            377 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry4ztgrvnzbp5jh1fw9ahf',
                'name_en' => 'Lactic acid bacteria',
                'name_fr' => 'Bactéries lactiques',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            378 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry4zv1thknznd3afa1yvda',
                'name_en' => 'Lake',
                'name_fr' => 'Lac',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            379 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry4zvf1d9yn7y2a4kx6n45',
                'name_en' => 'Lake Ice',
                'name_fr' => 'Glace de lac',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            380 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry4zw2yp73945b314bbmja',
                'name_en' => 'Lake Winnipeg',
                'name_fr' => 'Lac Winnipeg',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            381 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry4zwmvzmpwce98ep5rfkz',
                'name_en' => 'Lakes',
                'name_fr' => 'Lacs',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            382 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry4zx4mgyedhfjtk6egfeb',
                'name_en' => 'Land Administration',
                'name_fr' => 'Administration des terres',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            383 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry4zxqkgb03kgfzgk5brzf',
                'name_en' => 'Land Cover',
                'name_fr' => 'Couverture terrestre',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            384 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry4zy65ahvgqmdw6k2ahaa',
                'name_en' => 'Land management',
                'name_fr' => 'Gestion des terres',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            385 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry4zyn19v9sy4t5sfceehr',
                'name_en' => 'Land Reclamation',
                'name_fr' => 'Remise en état des terres',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            386 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry4zz5d4k88kxbex10geef',
                'name_en' => 'Land resource specialist',
                'name_fr' => 'Spécialiste des ressources terrestres',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            387 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry4zzrhbc45jwmb77cmasv',
                'name_en' => 'Land Surface/Atmosphere Interactions',
                'name_fr' => 'Interactions entre la surface des terres et l’atmosphère',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            388 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry5008xj9a9dg460py9mb3',
                'name_en' => 'Land use',
                'name_fr' => 'L’utilisation des terres',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            389 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry500q7zbawdycm0fhbej6',
                'name_en' => 'Land-use planning and management',
                'name_fr' => 'Aménagement et gestion des terres',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            390 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry5015f7b8cqxxm61gkp5x',
                'name_en' => 'Landbirds',
                'name_fr' => 'Oiseaux terrestres',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            391 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry501sqaamrascdx5tey5z',
                'name_en' => 'Lands - Aboriginal Lands',
                'name_fr' => 'Terres - terres autochtones',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            392 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry502anz1x9bzpr1qwvage',
                'name_en' => 'Lands - National Parks',
                'name_fr' => 'Terres - parcs nationaux',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            393 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry502vvb8wv71nb6pysk5e',
                'name_en' => 'Lands - Offshore',
                'name_fr' => 'Terres - extracôtiers',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            394 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry503yeayjmfhkfz33a92y',
                'name_en' => 'Lands - Protected Areas',
                'name_fr' => 'Terres - aires protégées',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            395 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry504enn8p1npn4g64j95v',
                'name_en' => 'Lands - The North',
                'name_fr' => 'Terres - le nord',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            396 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry504y9986jnyztdb867h1',
                'name_en' => 'Lands Survey',
                'name_fr' => 'Arpentage des terres',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            397 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry505ehqr6g1594jc26hg5',
                'name_en' => 'Landscape',
                'name_fr' => 'Paysage',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            398 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry505yws6vxnk5njkdaetc',
                'name_en' => 'Landscape and watershed management',
                'name_fr' => 'Gestion des paysages et des bassins versants',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            399 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry506e01fx44tdqmdyrp30',
                'name_en' => 'Landslides',
                'name_fr' => 'Glissements de terrain',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            400 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry5071jwk6p8mb9jb0jb4e',
                'name_en' => 'Laser additive manufacturing',
                'name_fr' => 'Fabrication additive par laser',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            401 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry507jdnrvpare1dt6hee8',
                'name_en' => 'Laser technologies',
                'name_fr' => 'Technologies laser',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            402 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry5082by944w134k5mdqhd',
                'name_en' => 'Laser technology',
                'name_fr' => 'Technologie laser',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            403 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry508k23djnk73dgeztjpq',
                'name_en' => 'Laser welding',
                'name_fr' => 'Soudage laser',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            404 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry5094grgk1z85b72xq06z',
                'name_en' => 'Leaf beetle',
                'name_fr' => 'Du chrysomèle',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            405 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry509m5763yxdkbgxpc69e',
                'name_en' => 'Leaf rust',
                'name_fr' => 'Rouille des feuilles',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            406 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50a5116bageh7kr82q0c',
                'name_en' => 'Leaf spots of cereals',
                'name_fr' => 'Taches foliaires des céréales',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            407 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50as20hrpcb2k50jgzsk',
                'name_en' => 'Leafhopper',
                'name_fr' => 'La cicadelle',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            408 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50bb4c2nchmka0gmda8n',
                'name_en' => 'Legal',
                'name_fr' => 'Juridique',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            409 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50bv0bh9v0cszm3nb3mw',
                'name_en' => 'Legal Boundaries',
                'name_fr' => 'Limites juridiques',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            410 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50c938aewmx11ahs8ka8',
                'name_en' => 'Legal Boundaries - Offshore',
                'name_fr' => 'Limites juridiques - littoral',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            411 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50csr266eqa2qj8ar774',
                'name_en' => 'Legal Boundaries - Terrestrial',
                'name_fr' => 'Limites juridiques - terrestres',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            412 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50d9bfhtnjnsv8qsras5',
                'name_en' => 'Legal testimony',
                'name_fr' => 'Témoignage devant les tribunaux',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            413 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50dv8w15tggg098mp80h',
                'name_en' => 'Legislation',
                'name_fr' => 'Législation',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            414 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50ea03c94h3h9ykcsbyz',
                'name_en' => 'Length',
                'name_fr' => 'Longueurs',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            415 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50etjsy8e3566dz989ph',
                'name_en' => 'Lentic Systems',
                'name_fr' => 'Systèmes lénitiques',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            416 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50fczqbby89pmbt0vje8',
                'name_en' => 'Lentils',
                'name_fr' => 'Lentilles',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            417 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50fw6qm8x638e9m9mfcc',
                'name_en' => 'Licensing',
                'name_fr' => 'Licences d\'exploitation',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            418 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50gdzwyzr9ftfex06tzx',
                'name_en' => 'Lightweighting',
                'name_fr' => 'Allègement des véhicules',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            419 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50gz5zsvfh5px0z61zv3',
                'name_en' => 'Limnology',
                'name_fr' => 'Limnologie',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            420 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50hehxq4ahfdz428h7tt',
                'name_en' => 'Limnology/ freshwater ecology',
                'name_fr' => 'Limnologie/écologie de l’eau potable',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            421 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50hw1s4mhtx2rfm3k4bq',
                'name_en' => 'Limnology/Freshwater',
                'name_fr' => 'Limnologie/eau douce',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            422 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50j99kzgtqj2rrnqb1pw',
                'name_en' => 'Lipids and fatty acids',
                'name_fr' => 'Lipides et acides gras',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            423 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50jsrq6a125tb1c6npgw',
                'name_en' => 'Liquified Natural Gas',
                'name_fr' => 'Le gaz naturel liquéfié',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            424 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50k8wavn7rj9xxvqt24y',
                'name_en' => 'Livestock',
                'name_fr' => 'Animaux d\'élevage',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            425 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50krwh27fmx7g112rmcq',
                'name_en' => 'Livestock diseases',
                'name_fr' => 'Maladies du bétail',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            426 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50mjd3vx7v9rfn1r9r3r',
                'name_en' => 'Livestock management',
                'name_fr' => 'Gestion du bétail',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            427 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50n4f70cewact44ascq7',
                'name_en' => 'Livestock phenomics',
                'name_fr' => 'Phénomique du bétail',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            428 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50nryx0m8sk9k1ev0m4s',
                'name_en' => 'Lotic Systems',
                'name_fr' => 'Systèmes lotiques',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            429 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50par931f693c9kq47tr',
                'name_en' => 'Lumber',
                'name_fr' => 'Bois d\'oeuvre',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            430 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50pzzywfrc2533wbbkvf',
                'name_en' => 'Machine Learning',
                'name_fr' => 'Apprentissage automatique',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            431 => 
            array (
                'created_at' => '2023-10-27 16:05:51',
                'id' => '01hdry50qpc6y0sp90zmbh5gdg',
                'name_en' => 'Machine readable data',
                'name_fr' => 'Données lisibles par machine',
                'updated_at' => '2023-10-27 16:05:51',
            ),
            432 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry50r716kw2wypqewcd227',
                'name_en' => 'Machinery',
                'name_fr' => 'Machinerie',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            433 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry50rs8b23meac6h10q75w',
                'name_en' => 'Mackenzie Delta',
                'name_fr' => 'Delta du Mackenzie',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            434 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry50s8b8c2bza02zs1rxwp',
                'name_en' => 'Mackenzie Mountains',
                'name_fr' => 'Montagnes du Mackenzie',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            435 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry50ssw620y1b9fb3wqcec',
                'name_en' => 'Mackenzie River',
                'name_fr' => 'Fleuve Mackenzie',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            436 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry50t9nsws1kfbm7d3ekjz',
                'name_en' => 'Mackenzie Valley',
                'name_fr' => 'Vallée du Mackenzie',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            437 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry50ts1dynsb6b6ceqspv9',
                'name_en' => 'Macroinvertebrates',
                'name_fr' => 'Macro-invertébrés',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            438 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry50vaedtt72ptjhgy11bd',
                'name_en' => 'Macronutrients',
                'name_fr' => 'Macronutriments',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            439 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry50vsp0pd0kt1apxm7ve5',
                'name_en' => 'Management',
                'name_fr' => 'Gestion',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            440 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry50w91s7rq4qyrj3pr8yr',
                'name_en' => 'Management agronomy',
                'name_fr' => 'Agronomie de la gestion',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            441 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry50ww4mvmqfss1709vq2z',
                'name_en' => 'Management and Oversight',
                'name_fr' => 'Gestion et surveillance',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            442 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry50xesmdpkpjqem1dm75c',
                'name_en' => 'Management information systems',
                'name_fr' => 'Système d\'information de gestion',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            443 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry50xzssq5s6fqvkmhe3hk',
                'name_en' => 'Management of mineral fertilizers',
                'name_fr' => 'Gestion des engrais minéraux',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            444 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry50z1heajjas1khhe52fx',
                'name_en' => 'Manufacturing industry',
                'name_fr' => 'Industrie de la fabrication',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            445 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry50zjyh05kp6gyq0vf3tz',
                'name_en' => 'Manure and slurry management',
                'name_fr' => 'Gestion des fumiers et lisiers',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            446 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry5101htgeg4mt6pck3dxn',
                'name_en' => 'Marine',
                'name_fr' => 'Milieu marin',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            447 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry510jc2vg1p69ajqybyda',
                'name_en' => 'Marine biotoxins',
                'name_fr' => 'Biotoxines marines',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            448 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry5113p5hnhmbbqc77nj4y',
                'name_en' => 'Marine ecosystem',
                'name_fr' => 'Écosystème marin',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            449 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry511nhpq34t19pgknw7pb',
                'name_en' => 'Marine Environments',
                'name_fr' => 'Milieux marins',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            450 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51252zwj0fv5t8hygs2t',
                'name_en' => 'Marine geohazards',
                'name_fr' => 'Géorisques marins',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            451 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry512qabfwgfazyhtyrze0',
                'name_en' => 'Marine mammal',
                'name_fr' => 'Mammifères marins',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            452 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry5136hw55ywfb94pf9j26',
                'name_en' => 'Marine navigation',
                'name_fr' => 'Navigation maritime',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            453 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry513th6d6eej8vws5j7kc',
                'name_en' => 'Marine renewable energy',
                'name_fr' => 'Énergie marine renouvelable',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            454 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry514b55yt3mwzw97w8hwa',
                'name_en' => 'Marine Resource Development',
                'name_fr' => 'Mise en valeur des ressources marines',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            455 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry514w24p0yk9425ck5brv',
                'name_en' => 'Marine toxins',
                'name_fr' => 'Toxines marines',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            456 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry515h8yybgg0f4pj29gt1',
                'name_en' => 'Maritimes',
                'name_fr' => 'Maritimes',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            457 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry5161bx7xg1wtvj4smw3s',
                'name_en' => 'Marker assisted',
                'name_fr' => 'Assistée par les marqueurs',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            458 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry516hsz9bqt8nz7qremkz',
                'name_en' => 'Mass and related quantities',
                'name_fr' => 'Masses et grandeurs apparentées',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            459 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry5178dnj5hq8fa0v6y6wy',
                'name_en' => 'Material processing',
                'name_fr' => 'Transformation des matériaux',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            460 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry517vctcsfcent4hkx0mf',
                'name_en' => 'Materials',
                'name_fr' => 'Matériaux',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            461 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry518dn20pkdh4ssttx63s',
                'name_en' => 'Materials - High Energy',
                'name_fr' => 'Matériaux à haute énergie',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            462 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry518xbp158bqame2572y8',
                'name_en' => 'Materials - Light Weight',
                'name_fr' => 'Matériaux légers',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            463 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry519cgwmbtq0ze8ebhpxa',
                'name_en' => 'Materials Evaluation',
                'name_fr' => 'Évaluation des matériaux',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            464 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry519yntzdj6cvrgz8rfdv',
                'name_en' => 'Materials for Energy Technologies',
                'name_fr' => 'Matériaux pour technologies énergétiques',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            465 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51afvfvbnw9hb5dbh4k5',
                'name_en' => 'Materials Processing',
                'name_fr' => 'Traitement des matériaux',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            466 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51azpdn1qcn7raj5290f',
                'name_en' => 'Materials technology',
                'name_fr' => 'Technologie des matériaux',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            467 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51bfvw105c0xdswwgx9v',
                'name_en' => 'Materials testing',
                'name_fr' => 'Essai des matériaux',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            468 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51bznqtchh1gk8z4kp89',
                'name_en' => 'Maternal health',
                'name_fr' => 'Santé maternelle',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            469 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51cgh4mcevpe04sez7fs',
                'name_en' => 'Mathematics',
                'name_fr' => 'Mathématiques',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            470 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51d2x36xax00b55kb6qh',
                'name_en' => 'Mathematics and Statistics',
                'name_fr' => 'Statistique mathématique',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            471 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51dm5zmyegwtetmxd34v',
                'name_en' => 'Measurement Science',
                'name_fr' => 'Métrologie',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            472 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51e12hk2sccb1ahapjs4',
                'name_en' => 'Measurement Technologies & Methodologies',
                'name_fr' => 'Technologies & méthodologies de mesure',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            473 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51edfjk9mq34shqa1zjc',
                'name_en' => 'Meat',
                'name_fr' => 'La viande',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            474 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51f01nttp9k6emtzyycx',
                'name_en' => 'Meat quality',
                'name_fr' => 'Qualité de la viande',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            475 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51fp7z5zh9gkacm9ymk2',
                'name_en' => 'Meat quality - applied bioinstrumentation',
                'name_fr' => 'Qualité de la viande – bioinstrumentation appliquée',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            476 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51g266srf9c57c446sdy',
                'name_en' => 'Meat quality and nutrition',
                'name_fr' => 'Qualité de la viande et nutrition',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            477 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51gffxw95me6tvhnf60j',
                'name_en' => 'Meat quality and processing',
                'name_fr' => 'Qualité et transformation des viandes',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            478 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51gyg98jsscc81rw2sb5',
                'name_en' => 'Meat science',
                'name_fr' => 'Science de la viande',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            479 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51hdb0pppz56qwj5701k',
                'name_en' => 'Mechanical assembling',
                'name_fr' => 'Assemblage mécanique',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            480 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51hvbv9khjpkwvy2abxn',
                'name_en' => 'Mechanical Engineering',
                'name_fr' => 'Génie mécanique',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            481 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51jdb0e2teeyfj6asmvg',
                'name_en' => 'Mechanisms of Action',
                'name_fr' => 'Mécanismes d\'action',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            482 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51jwabq61q0xccd5f6be',
                'name_en' => 'Medical devices',
                'name_fr' => 'Instruments médicaux',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            483 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51kacb2aq76jxk6x3xw4',
                'name_en' => 'Medical physics',
                'name_fr' => 'Physique médicale',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            484 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51m30fgeeksjyw8b2m0t',
                'name_en' => 'Medical products industry',
                'name_fr' => 'Industrie des produits médicaux',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            485 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51mk6x0k07ecdrmk4d9r',
                'name_en' => 'Medical technology',
                'name_fr' => 'Technologies médicales',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            486 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51n3vgzbp4c15jq8ypb1',
                'name_en' => 'Medicine',
                'name_fr' => 'Médecine',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            487 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51ngp196hktdw8bskwcx',
                'name_en' => 'Membrane separation and extraction processes',
                'name_fr' => 'Procédés de séparation des membranes et d\'extraction',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            488 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51nz7gp763qn3n5qbaye',
                'name_en' => 'Mensuration',
                'name_fr' => 'Mesurage',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            489 => 
            array (
                'created_at' => '2023-10-27 16:05:52',
                'id' => '01hdry51pzqyvkhcc7h07z58x5',
                'name_en' => 'Mercury',
                'name_fr' => 'Mercure',
                'updated_at' => '2023-10-27 16:05:52',
            ),
            490 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry51qga130f4y86028x3q2',
                'name_en' => 'Metabolic',
                'name_fr' => 'Métabolique',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            491 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry51r1zch6a3kqhx5nxvk1',
                'name_en' => 'Metadata Management',
                'name_fr' => 'Gestion des métadonnées',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            492 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry51rp8sqt4c6rv5wrp1pw',
                'name_en' => 'Metal Active Gas welding',
            'name_fr' => 'Soudage à l’arc sous protection de gaz inerte avec fil-électrode fusible (ou soudage MIG)',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            493 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry51s578q9rsq9awrkfbpw',
                'name_en' => 'Metal Bioaccessibility',
                'name_fr' => 'Biodisponibilité des métaux',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            494 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry51snfs7hnvqt7gg6w0jx',
                'name_en' => 'Metal Inert Gas welding',
            'name_fr' => 'Soudage à l’arc en atmosphère active (ou soudage MAG)',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            495 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry51t75n0wng6sc5j1ezrw',
                'name_en' => 'Metal levels',
                'name_fr' => 'Teneurs en métaux',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            496 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry51tqzxgekppy12rzmfgs',
                'name_en' => 'Metallurgy',
                'name_fr' => 'Métallurgie',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            497 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry51v6k0tehjgvsfcwgp65',
                'name_en' => 'Metals',
                'name_fr' => 'Métaux',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            498 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry51vs4rkqdyrn6dkg4wk8',
                'name_en' => 'Meteorites',
                'name_fr' => 'Météorites',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            499 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry51w9nvmhcrv7g9nms5hx',
                'name_en' => 'Methods development',
                'name_fr' => 'Mise au point de méthodes',
                'updated_at' => '2023-10-27 16:05:53',
            ),
        ));
        \DB::table('expertises')->insert(array (
            0 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry51wsj42xm89xcap1bejb',
                'name_en' => 'Micro-moth',
                'name_fr' => 'Des microlépidoptères',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            1 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry51xbgabfyk4676knd4jn',
                'name_en' => 'Micro-organism collection',
                'name_fr' => 'Collection de micro-organismes',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            2 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry51y86gcf8psg075spatq',
                'name_en' => 'Micro-organisms',
                'name_fr' => 'Microorganismes',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            3 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry51yz9bw0dhfd5mzaf701',
                'name_en' => 'Microbial',
                'name_fr' => 'Microbiens',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            4 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry51zheqt2vq12t420xpfy',
                'name_en' => 'Microbial fermentation',
                'name_fr' => 'Fermentation microbienne',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            5 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry5200722sjfp4b7846r6c',
                'name_en' => 'Microbiology',
                'name_fr' => 'Microbiologie',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            6 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry520kxxf4pgn3qsvfhajb',
                'name_en' => 'Microelectronics',
                'name_fr' => 'Microélectronique',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            7 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry5215ckkc5p13armns1b6',
                'name_en' => 'Microfabrication',
                'name_fr' => 'Microfabrication',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            8 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry521tqmb1c029gdkm4mgn',
                'name_en' => 'Microinvertebrates',
                'name_fr' => 'Micro-invertébrés',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            9 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry522e7nwwmw6201p6ye98',
                'name_en' => 'Micrometeorology',
                'name_fr' => 'Micrométéorologie',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            10 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry5231ccsvzgje08wzn0ft',
                'name_en' => 'Micronutrients',
                'name_fr' => 'Micronutriments',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            11 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry523k408hk2x15zf17nr7',
                'name_en' => 'Microphysics',
                'name_fr' => 'Microphysique',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            12 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry524799q7vwqev20hszn8',
                'name_en' => 'Microscopy',
                'name_fr' => 'Microscopie',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            13 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry524q5b1t2v2femh4jym3',
                'name_en' => 'Mid-Atlantic Ocean',
                'name_fr' => 'Océan mi-Atlantique',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            14 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry525906ynsbrybph141hs',
                'name_en' => 'Migration',
                'name_fr' => 'Migration',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            15 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry525snxqhzkq7fps48gba',
                'name_en' => 'Migratory birds',
                'name_fr' => 'Oiseaux migrateurs',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            16 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry526mszgjnhww5y4ws12g',
                'name_en' => 'Migratory Birds Convention Act',
                'name_fr' => 'Loi sur la Convention concernant les oiseaux migrateurs',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            17 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52739zwghkkvy17gtqzk',
                'name_en' => 'Military engineering',
                'name_fr' => 'Génie militaire',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            18 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry527mvad4yp9skseny4fj',
                'name_en' => 'Military technology',
                'name_fr' => 'Technologie militaire',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            19 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52854zmxn0t3qqgftpeb',
                'name_en' => 'Mine Integrity',
                'name_fr' => 'L\'intégrité des mines',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            20 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry528p1zgh0qcg4rctatss',
                'name_en' => 'Mine Ventilation',
                'name_fr' => 'Ventilation des mines',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            21 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry5297zb4aq4cx3zw572nn',
                'name_en' => 'Mineral Analysis',
                'name_fr' => 'L\'analyse minérale',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            22 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry529rt4rw1jyn01v8najs',
                'name_en' => 'Mineral Commodities',
                'name_fr' => 'Produits minéraux',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            23 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52anfxnzzg4be7aad4je',
                'name_en' => 'Mineral Exploration',
                'name_fr' => 'Exploration minière',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            24 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52b9any5h2mmvhnk99eg',
                'name_en' => 'Mineral Processing',
                'name_fr' => 'Traitement des minéraux',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            25 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52bt44042w6rbda7y0b6',
                'name_en' => 'Mineral Resource Potential',
                'name_fr' => 'Potentiel de ressources minérales',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            26 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52ccz22k2zk04atwracd',
                'name_en' => 'Mineral Trade',
                'name_fr' => 'Commerce des minerais',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            27 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52cy1dkd0bg65yqvxgtz',
                'name_en' => 'Mineralogy',
                'name_fr' => 'Minéralogie',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            28 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52dfre7c15ev5xcm9zjy',
                'name_en' => 'Mineralogy and Rock Properties',
                'name_fr' => 'Minéralogie et propriétés de la roche',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            29 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52e1xdf4sh7td694fpmk',
                'name_en' => 'Minerals',
                'name_fr' => 'Minéraux',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            30 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52ej19cm39ywdxjj946h',
                'name_en' => 'Minerals - Industrial',
                'name_fr' => 'Minéraux - industriel',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            31 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52f4fby07340wz54pzpp',
                'name_en' => 'Minerals and Metals',
                'name_fr' => 'Minéraux et métaux',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            32 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52fmccbm3d5j9dw7kk0g',
                'name_en' => 'Mining',
                'name_fr' => 'Exploitation de mines',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            33 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52g42kvzq7yczefc8znv',
                'name_en' => 'Mining & Milling Energy Management',
                'name_fr' => 'Exploitation minière et gestion des usines de traitement',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            34 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52gmzmr5v7ydac7fvydd',
                'name_en' => 'Mining - Deep',
                'name_fr' => 'Exploitation minière - grande profondeur',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            35 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52h3z84fekeet9ca3jkx',
                'name_en' => 'Mining - Green',
                'name_fr' => 'Exploitation minière - vert',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            36 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52hh5cn02p9rs92jamjs',
                'name_en' => 'Mining - Metals',
                'name_fr' => 'Exploitation minière - métaux',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            37 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52j2fedvja1k6msq4tay',
                'name_en' => 'Mining - Reclamation',
                'name_fr' => 'Extraction -- Remise en état rentable',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            38 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52jndf4pa5b6yad9x9hd',
                'name_en' => 'Mining Environment',
                'name_fr' => 'L\'environnement minier',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            39 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52k3jjf4khvk6k95n7h1',
                'name_en' => 'Mining Extraction',
                'name_fr' => 'Extraction minière',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            40 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52kkssbcbse0myhy4a6r',
                'name_en' => 'Mining industry',
                'name_fr' => 'Industrie minière',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            41 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52m4gb02wvxbadw165z0',
                'name_en' => 'Mining Methods',
                'name_fr' => 'Méthodes minières',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            42 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52mnepcb8g2bbhqxbmhk',
                'name_en' => 'Mining technology',
                'name_fr' => 'Technologie minière',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            43 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52n69mg5tv78fmgxfy8d',
                'name_en' => 'Minor use pesticide',
                'name_fr' => 'Des pesticides à usage limité',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            44 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52nqbvr3dtxe6tadakgt',
                'name_en' => 'Minor use pesticides',
                'name_fr' => 'Pesticides à usage limité',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            45 => 
            array (
                'created_at' => '2023-10-27 16:05:53',
                'id' => '01hdry52p8xjq52cmjg9t5tnwh',
                'name_en' => 'Mites',
                'name_fr' => 'Mites',
                'updated_at' => '2023-10-27 16:05:53',
            ),
            46 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52pvt19zh5asq8pp5rgk',
                'name_en' => 'Mixed-wood Forests',
                'name_fr' => 'Forêts mixtes',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            47 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52qdptp6tdcj3gk2k8xq',
                'name_en' => 'Model Forests',
                'name_fr' => 'Forêts modèles',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            48 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52qxbfyg8capfr3sdddd',
                'name_en' => 'Modeling',
                'name_fr' => 'Modélisation',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            49 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52rfq948448pehnm0n3k',
                'name_en' => 'Modelling and process simulation',
                'name_fr' => 'Modélisation et simulation de procédés',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            50 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52rzm5rs56hw42kpcf9x',
                'name_en' => 'Modelling Software',
                'name_fr' => 'Logiciel de modélisation',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            51 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52sgvqc1693z8gdt6khp',
                'name_en' => 'Molecular',
                'name_fr' => 'Moléculaire',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            52 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52t1qggae9s8s55f6y9t',
                'name_en' => 'Molecular bacteriology',
                'name_fr' => 'Bactériologie moléculaire',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            53 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52tnw6wqhcdwgw58h7pv',
                'name_en' => 'Molecular biology',
                'name_fr' => 'Biologie moléculaire',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            54 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52v4fgsh700hk7bdzt7y',
                'name_en' => 'Molecular genetics',
                'name_fr' => 'Génétique moléculaire',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            55 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52vn3yxacv3pad8r7z6q',
                'name_en' => 'Molecular plant',
                'name_fr' => 'Moléculaire des plantes',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            56 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52w7aha6v5j4qyrhm11s',
                'name_en' => 'Molecular spectroscopy',
                'name_fr' => 'Spectroscopie moléculaire',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            57 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52wqq9cn55car54vmn7y',
                'name_en' => 'Molecular taxonomic characterization',
                'name_fr' => 'Caractérisation taxonomique moléculaire',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            58 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52xmws3sfsfvn8597v8v',
                'name_en' => 'Molecular weed science',
                'name_fr' => 'Malherbologie moléculaire',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            59 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52y65a9tf81gz17p42yc',
                'name_en' => 'Monitoring',
                'name_fr' => 'Surveillance',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            60 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52yr5y3kpjwc106yxd5p',
                'name_en' => 'Monitoring and Process Control',
                'name_fr' => 'Surveillance et contrôle de procédé',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            61 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52z8mwxjdwq088nbrczz',
                'name_en' => 'Monte-Carlo Methods',
                'name_fr' => 'Méthodes Monte-Carlo',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            62 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry52zscr1sg55pz8nh524x',
                'name_en' => 'Mushroom',
                'name_fr' => 'Des champignons',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            63 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry530d3bdsre1rx9ye01r3',
                'name_en' => 'Mussels',
                'name_fr' => 'Moules',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            64 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry530x086sg935ha5x70tn',
                'name_en' => 'Mustard',
                'name_fr' => 'Graine de moutarde',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            65 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry531bgbv6dngsbnqn7kpk',
                'name_en' => 'Mutagenesis and Carcinogenesis',
                'name_fr' => 'Mutagenèse et carcinogenèse',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            66 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry531wehxmnf2ayctzpzve',
                'name_en' => 'Mycology',
                'name_fr' => 'Mycologie',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            67 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry532k2q09pnsxdr0w855h',
                'name_en' => 'Mycotoxigenic fungi',
                'name_fr' => 'Des champignons mycotoxigènes',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            68 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53382mmq96hrjzp3v9nm',
                'name_en' => 'Mycotoxin',
                'name_fr' => 'Mycotoxines',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            69 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry533rpckdcbw34mjsk9wh',
                'name_en' => 'Mycotoxins and allergens',
                'name_fr' => 'Mycotoxines et allergènes',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            70 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry5349nptr24vc8wgsrks4',
                'name_en' => 'Nano metrology',
                'name_fr' => 'Nanométrologie',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            71 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry534tnzk1kp2v2y287qfx',
                'name_en' => 'Nanomaterials',
                'name_fr' => 'Nanomatériaux',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            72 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry535a3918bk3xc8vtk33v',
                'name_en' => 'Nanotechnology',
                'name_fr' => 'Nanotechnologie',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            73 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry535vch9e6brcbhaprzp6',
                'name_en' => 'Nanotechnology and nanomaterials',
                'name_fr' => 'Nanotechnologie et nanomatériaux',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            74 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry536dt2e8gczy8sdntw6n',
                'name_en' => 'National plant virus collection',
                'name_fr' => 'Collection nationale de phytovirus',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            75 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry537e4sqhc10kqfzw0van',
                'name_en' => 'Native Forest Pest',
                'name_fr' => 'Ravageurs forestiers indigènes',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            76 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry537zmq1anhyy86924w69',
                'name_en' => 'Native species',
                'name_fr' => 'Espèces indigènes',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            77 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry538fa3cnmzh4s1h41fxx',
                'name_en' => 'Natural enemies',
                'name_fr' => 'Ennemis naturels',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            78 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry538zt55zsmv4b5e7z558',
                'name_en' => 'Natural Gas',
                'name_fr' => 'Gaz naturel',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            79 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry539fx06px8hf2c33jp2r',
                'name_en' => 'Natural Gas Liquids',
                'name_fr' => 'Liquides de gaz naturel',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            80 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53a03515jj1n0c4m7kwj',
                'name_en' => 'Natural Hazards',
                'name_fr' => 'Risques naturels',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            81 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53ahknzfsr4txtktpne4',
                'name_en' => 'Natural Health Products',
                'name_fr' => 'Produits de santé naturels',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            82 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53b3c3fncevv8e4xtvjc',
                'name_en' => 'Natural language processing',
                'name_fr' => 'Traitement du langage naturel',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            83 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53bmvhbabbjysp07kdcb',
                'name_en' => 'Natural products',
                'name_fr' => 'Produits naturels',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            84 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53c3918096eyc5gkg3kn',
                'name_en' => 'Natural products chemistry',
                'name_fr' => 'Chimie des produits naturels',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            85 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53ck1vgt8xgadgwsfsw0',
                'name_en' => 'Natural resource management',
                'name_fr' => 'Gestion des ressources naturelles',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            86 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53d46sn984ym4kfh90k6',
                'name_en' => 'Nature & Wildlife',
                'name_fr' => 'Nature et faune',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            87 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53dmw7df9gx7jqd9tq0k',
                'name_en' => 'Navigation',
                'name_fr' => 'Navigation',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            88 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53e5mytjqryv7qhqhq12',
                'name_en' => 'Navigation systems',
                'name_fr' => 'Système d\'aide à la navigation',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            89 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53epc5djhdcpyevt6zvp',
                'name_en' => 'NE Newfoundland Shelf',
                'name_fr' => 'Plateau nord-est de Terre-Neuve',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            90 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53f63d78dtngx0wbwv5b',
                'name_en' => 'Nematode',
                'name_fr' => 'Des nématodes',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            91 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53fq1k5dx0rvvdbya9m0',
                'name_en' => 'Nematology',
                'name_fr' => 'Nématologie',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            92 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53g8shhz4wp4w51ydzgn',
                'name_en' => 'Neural Network',
                'name_fr' => 'Réseau neuronal',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            93 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53gx41bfs5rneagzzqjc',
                'name_en' => 'Neurosciences',
                'name_fr' => 'Neurosciences',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            94 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53hdrpf3j4jtdyr65t38',
                'name_en' => 'Neurotoxicology',
                'name_fr' => 'Neurotoxicologie',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            95 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53htt0jdxntrhnff1t83',
                'name_en' => 'New crop agronomy and adaptation',
                'name_fr' => 'Agronomie et adaptation des nouvelles cultures',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            96 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53ja4qrqxgnsstwhyv7c',
            'name_en' => 'Nitrogen biofertilizers (bacteria)',
            'name_fr' => 'Biofertilisants azotés (bactéries)',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            97 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53jsztv9231p05zv3wt9',
                'name_en' => 'Nitrogen cycling',
                'name_fr' => 'Cycle de l’azote',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            98 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53k8mhsjd4gqym4ch6fj',
                'name_en' => 'NMR Spectroscopy',
                'name_fr' => 'Spectroscopie de Résonance Magnétique Nucléaire',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            99 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53kte6exffdse9qtbdt1',
                'name_en' => 'Non-Aqueous Processes',
                'name_fr' => 'Processus non aqueuse',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            100 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53mdrjtspe6g4h4kxmqh',
            'name_en' => 'Non-destructive Testing (NDT)',
            'name_fr' => 'Essais non destructifs (END)',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            101 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53mzack79spgpmp4yqbq',
            'name_en' => 'Non-Insured Health Benefits (supplementary) for First Nations and Inuit',
            'name_fr' => 'Services de santé non assurés (prestations supplémentaires) pour les Premières nations et les Inuits',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            102 => 
            array (
                'created_at' => '2023-10-27 16:05:54',
                'id' => '01hdry53ndq17258f8tgb2gs7e',
                'name_en' => 'Non-metals',
                'name_fr' => 'Substances non métalliques',
                'updated_at' => '2023-10-27 16:05:54',
            ),
            103 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53nz1207vdhp70nxv3ed',
                'name_en' => 'Non-point Sources',
                'name_fr' => 'Sources non ponctuelles',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            104 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53pfaham4ye8zd4dw9j4',
                'name_en' => 'North America',
                'name_fr' => 'Amérique du Nord',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            105 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53pzp525zedhhjxpqwdp',
                'name_en' => 'North Atlantic Ocean',
                'name_fr' => 'Océan Atlantique nord',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            106 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53qpxnrtnt34qvz9xabx',
                'name_en' => 'Northern Canada',
                'name_fr' => 'Nord canadien',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            107 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53r8975d7n7w3557t632',
                'name_en' => 'Northern Resources',
                'name_fr' => 'Ressources du Nord',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            108 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53rrw0geg84qfcxphfax',
                'name_en' => 'Northern rivers',
                'name_fr' => 'Rivières du Nord',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            109 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53s8k21kry1t8mgwdb88',
                'name_en' => 'NoSQL',
                'name_fr' => 'NoSQL',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            110 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53sznexg48096qbdkv9h',
                'name_en' => 'Novel bacterial species',
                'name_fr' => 'Nouvelles espèces de bactéries',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            111 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53thxwe1xpqs3y3rwk8j',
                'name_en' => 'Novel Foods',
                'name_fr' => 'Aliments nouveaux',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            112 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53v3kketcwv6dzt242sh',
                'name_en' => 'Novel processing technologies',
                'name_fr' => 'Nouvelles technologies de transformation',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            113 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53vqtmhayznbatb2dgs9',
                'name_en' => 'Novel-trait plants',
                'name_fr' => 'Plantes porteuses de nouvelles caractéristiques',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            114 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53wgzntem6mfqkdjwab1',
                'name_en' => 'Nowcasting',
                'name_fr' => 'Prévision immédiate',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            115 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53xcjx5k95k0qfpg68yb',
                'name_en' => 'Nuclear Energy',
                'name_fr' => 'Énergie nucléaire',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            116 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53xywec73jm6f252sqsg',
                'name_en' => 'Nuclear magnetic resonance',
                'name_fr' => 'Résonance magnétique nucléaire',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            117 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53ybyhxprpg03qqvdt7s',
                'name_en' => 'Nuclear physics',
                'name_fr' => 'Physique nucléaire',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            118 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53yv28b7a5c4qcdwmec5',
                'name_en' => 'Nuclear technology',
                'name_fr' => 'Technologie nucléaire',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            119 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53za1r2taat6byeb3tny',
                'name_en' => 'Numeric modelling',
                'name_fr' => 'Modélisation numérique',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            120 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry53zwa8n4dnpwyq8z7svc',
                'name_en' => 'Nutrient cycling',
                'name_fr' => 'Cycle des éléments nutritifs',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            121 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry540cn06w528vama1jb72',
                'name_en' => 'Nutrient management',
                'name_fr' => 'Gestion des éléments nutritifs',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            122 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry540xnyt1v684px7rnz1t',
                'name_en' => 'Nutrients',
                'name_fr' => 'Éléments nutritifs',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            123 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry541dsxmbab67tnfqp59v',
                'name_en' => 'Nutrition',
                'name_fr' => 'Nutrition',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            124 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry541zb5k49d2rk535neap',
                'name_en' => 'Nutritional value of ruminant feed',
                'name_fr' => 'Valeur nutritive des aliments pour ruminants',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            125 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry542fy87xthh58kz1khks',
                'name_en' => 'Oat',
                'name_fr' => 'L\'avoine',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            126 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry5431cm9zmpaq6brvt2xf',
                'name_en' => 'Observational astronomy',
                'name_fr' => 'Astronomie d\'observation',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            127 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry543j340sr6709reytewj',
                'name_en' => 'Ocean',
                'name_fr' => 'Océan',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            128 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54443hs8jw2r6pbzwmsh',
                'name_en' => 'Ocean climate',
                'name_fr' => 'Climat des océans',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            129 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry544p74xyzcbt6j38n9w1',
                'name_en' => 'Ocean colour',
                'name_fr' => 'Couleur de l\'océan',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            130 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54579nsjrsm85znad2kk',
                'name_en' => 'Ocean Data Collection Science',
                'name_fr' => 'Science de la collecte de données océanographiques',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            131 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry545sd05afp1vdnczbr7n',
                'name_en' => 'Ocean Engineering',
                'name_fr' => 'Génie océanique',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            132 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry5469b8xsg15b48t4sm5v',
                'name_en' => 'Ocean Engineering and Innovation',
                'name_fr' => 'Génie océanique et innovation',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            133 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry546sjcnfyf5r3kgamaza',
                'name_en' => 'Ocean Modeling',
                'name_fr' => 'Modélisation océanique',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            134 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry547fc0zwc13ckrmrp0y7',
                'name_en' => 'Ocean Monitoring',
                'name_fr' => 'Surveillance des océans',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            135 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry5486ap9ypyk0ce16tf1j',
                'name_en' => 'Oceanography',
                'name_fr' => 'Océanographie',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            136 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry548p0hg8sn17m79a38wg',
                'name_en' => 'Oceans act',
                'name_fr' => 'Législation marine',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            137 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54977rgfhvht3mrb3pnb',
                'name_en' => 'Office automation',
                'name_fr' => 'Bureautique',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            138 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry549rh2xngjw0ca915c51',
                'name_en' => 'Official Language Minority Community Development',
                'name_fr' => 'Développement des communautés de langue officielle en situation minoritaire',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            139 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54a9m7rfb9eqy60ck8qg',
                'name_en' => 'Oil Sand Processing',
                'name_fr' => 'Exploitation des sables bitumineux',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            140 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54at08tzp8rp6mxh86yt',
                'name_en' => 'Oil Sands',
                'name_fr' => 'Sables bitumineux',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            141 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54baaagep74brcd0cpxs',
                'name_en' => 'Oil spills',
                'name_fr' => 'Déversements de pétrole',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            142 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54bs6j9kx6f42j0zs5ab',
                'name_en' => 'Oil spills and petroleum',
                'name_fr' => 'Déversements de pétrole et d\'hydrocarbures',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            143 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54c68ccbg02q7qw1s9k2',
                'name_en' => 'Oilseed',
                'name_fr' => 'Oléagineux',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            144 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54ctyyj1hvbr1fhfh0pk',
                'name_en' => 'Online analytics',
                'name_fr' => 'Analytique en direct',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            145 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54ddjeew8mhbksb6n12x',
                'name_en' => 'Optical fibre lasers and sensors',
                'name_fr' => 'Capteurs et lasers à fibre optique',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            146 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54dx9tn6j442d8krwzx7',
                'name_en' => 'Optimization',
                'name_fr' => 'Optimisation',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            147 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54ecgcy8wpc87z3vh8hy',
                'name_en' => 'Oral exposures',
                'name_fr' => 'Exposition par voie orale',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            148 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54eyrwj1j2wywhn0ec8b',
                'name_en' => 'Ore Deposits',
                'name_fr' => 'Gisements de minerai',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            149 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54fe5nykgbn2xtbq728p',
                'name_en' => 'Ores Processing',
                'name_fr' => 'Traitement des minerais',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            150 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54fx9bc34gfqf4jn9qe1',
                'name_en' => 'Organic compounds',
                'name_fr' => 'Composés organiques',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            151 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54gerg23ps6tamcz094v',
                'name_en' => 'Organic semiconductors',
                'name_fr' => 'Semiconducteurs organiques',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            152 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54gtsj7xsba4s5fdxqzy',
                'name_en' => 'Organizational sciences',
                'name_fr' => 'Sciences organisationnelles',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            153 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54hf8gqw646n13jem7fv',
                'name_en' => 'Organotins',
                'name_fr' => 'Organoétains',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            154 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54hy8mznr750r9zaypbn',
                'name_en' => 'Oxidative stress',
                'name_fr' => 'Stress oxydatif',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            155 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54jc3na09fspgffvafa8',
                'name_en' => 'Ozone',
                'name_fr' => 'Ozone',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            156 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54jtc0cth8j5tjkstzbt',
                'name_en' => 'Pacific Margin',
                'name_fr' => 'Marge Pacifique',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            157 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54k8j38bcp06zv7gs1xb',
                'name_en' => 'Pacific Ocean',
                'name_fr' => 'Océan Pacifique',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            158 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54kq7386vszsejygzd8p',
                'name_en' => 'Pacific Ocean - North',
                'name_fr' => 'Océan Pacifique - nord',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            159 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54ma2tt265me0tpxywff',
                'name_en' => 'Packaging',
                'name_fr' => 'Emballage',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            160 => 
            array (
                'created_at' => '2023-10-27 16:05:55',
                'id' => '01hdry54mweky8ahqw4rzmzt6c',
                'name_en' => 'Palaeontology',
                'name_fr' => 'Paléontologie',
                'updated_at' => '2023-10-27 16:05:55',
            ),
            161 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54ndzv84engr0hjqhr9k',
                'name_en' => 'Paleontology - Invertebrate',
                'name_fr' => 'Paléontologie des invertébrés',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            162 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54nxpq5vk8rfpd9vkfvc',
                'name_en' => 'Paleontology - Macro',
                'name_fr' => 'Macro paléontologie',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            163 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54ph15ddnfapc46xn2b0',
                'name_en' => 'Paleontology - Micro',
                'name_fr' => 'Micro paléontologie',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            164 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54q0m3ztcj8mng56vtte',
                'name_en' => 'Paleontology - Vertebrate',
                'name_fr' => 'Paléontologie des vertébrés',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            165 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54qggamr4tv16wz8nxbv',
                'name_en' => 'Pandemic',
                'name_fr' => 'Pandémie',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            166 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54qzvbn60d3ctr3bz7py',
                'name_en' => 'Paper',
                'name_fr' => 'Papier',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            167 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54rfnf5e12x3p89gcad1',
                'name_en' => 'Paper mills',
                'name_fr' => 'Usines de papier',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            168 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54s1farta0178v3zs809',
                'name_en' => 'Paramedics',
                'name_fr' => 'Paramédics',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            169 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54sh6pb2hqxvgdrabeve',
                'name_en' => 'Parameterization of gravity waves',
                'name_fr' => 'Paramétrage des ondes de gravité',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            170 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54t1bkhj5kkhxy9qsg6h',
                'name_en' => 'Parasite',
                'name_fr' => 'Parasites',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            171 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54tmpk5qdv22a6zrty8e',
                'name_en' => 'Parasitology',
                'name_fr' => 'Parasitologie',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            172 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54vw78n3ar0yth1e7wh6',
                'name_en' => 'Particle physics',
                'name_fr' => 'Physique des particules',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            173 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54wmx1bdca6nnezwxcgp',
                'name_en' => 'Particulate matter',
                'name_fr' => 'Particules',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            174 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54x8tdsp6w73dszk1xvg',
                'name_en' => 'Passenger Conveyances',
                'name_fr' => 'Transporteurs communs',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            175 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54xqz9edb5efxky4t4hc',
                'name_en' => 'Passive microwave imager radiances',
                'name_fr' => 'Données de radiance fournies par imageur hyperfréquences passif',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            176 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54y6xnqdz5pf7wq91w6a',
                'name_en' => 'Pasture systems',
                'name_fr' => 'Systèmes de pâturage',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            177 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54yq82kybqzmavvqkwkf',
                'name_en' => 'Patents',
                'name_fr' => 'Brevets',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            178 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54z9z85qgff5tjke4evr',
                'name_en' => 'Pathogenes',
                'name_fr' => 'Pathogènes',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            179 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry54ztfp419jvefc1sm834',
                'name_en' => 'Pathology',
                'name_fr' => 'Pathologie',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            180 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry550fbnjn737q8qbfjqpn',
                'name_en' => 'Peace Athabasca Delta',
                'name_fr' => 'Delta Paix-Athabasca',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            181 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry5518w1fy7tdxjktktn3z',
                'name_en' => 'Peas',
                'name_fr' => 'Pois',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            182 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry551p4f3vf34pvahte416',
                'name_en' => 'Peatland',
                'name_fr' => 'Tourbière',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            183 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry5524adksv9f1nkhny8sn',
                'name_en' => 'Pelagic',
                'name_fr' => 'Pélagique',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            184 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry552pvpqp2t7rgw1gpaq3',
                'name_en' => 'Pelagic Species',
                'name_fr' => 'Espèces pélagiques',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            185 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry5538yrxgte99q8njeteh',
                'name_en' => 'Perennial cereals biomass',
                'name_fr' => 'Céréales pérennes pour améliorer la biomasse',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            186 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry553vpz9y983syjwf1f95',
                'name_en' => 'Perimeter sensing',
                'name_fr' => 'Surveillance périmétrique',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            187 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry554a9jenm8w5t36pyjdd',
                'name_en' => 'Permafrost',
                'name_fr' => 'Pergélisol',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            188 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry554wyz113gws7p9n8p75',
            'name_en' => 'Persistent organic pollutants (POPs)',
            'name_fr' => 'Polluants organiques persistants (POP)',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            189 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry555ck7dp57eryygd7vvn',
                'name_en' => 'Personal digital assistant',
                'name_fr' => 'Assistant numérique personnel',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            190 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry555wn5b8d5ysa2vj7415',
                'name_en' => 'Pest management',
                'name_fr' => 'Lutte antiparasitaire',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            191 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry556eqh2qv09v1rtbgrwh',
                'name_en' => 'Pesticide Regulation',
                'name_fr' => 'Réglementation des pesticides',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            192 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry556zbyf121446kppbd21',
                'name_en' => 'Pesticide residues',
                'name_fr' => 'Résidus de pesticides',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            193 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry557myxm2egssmg675b37',
                'name_en' => 'Pesticide Risk Reduction',
                'name_fr' => 'Réduction des risques liés aux pesticides',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            194 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry5583w0hcsqcxgxghb9pd',
                'name_en' => 'Pesticides',
                'name_fr' => 'Pesticides',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            195 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry558kgj3fv9rkh91xga3v',
                'name_en' => 'Pesticides and herbicides',
                'name_fr' => 'Pesticides et herbicides',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            196 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry5596qdgs7q82z20e7017',
                'name_en' => 'Petrochemicals',
                'name_fr' => 'Produits pétrochimiques',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            197 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry559p4572qnn8abf8hqe9',
                'name_en' => 'Petrography',
                'name_fr' => 'Pétrographie',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            198 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55a7kmzwzerbvcys4y1h',
                'name_en' => 'Petroleum industry',
                'name_fr' => 'Industrie pétrolière',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            199 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55as14zs7b2rvpcz730t',
                'name_en' => 'Petroleum Products',
                'name_fr' => 'Produits pétroliers',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            200 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55bc6bevmw69tt7j8ysv',
                'name_en' => 'Petroleum Systems',
                'name_fr' => 'Systèmes pétroliers',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            201 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55c2410fqdcg8et8qy7m',
                'name_en' => 'Petrology',
                'name_fr' => 'Pétrologie',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            202 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55cnyhpenfg456jtxjrq',
                'name_en' => 'Pharmaceutical Human Drugs',
                'name_fr' => 'Produits pharmaceutiques destinés à l\'usage humain',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            203 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55d8c4htkez4jd8g4f0p',
                'name_en' => 'Pharmaceuticals',
                'name_fr' => 'Produits pharmaceutiques',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            204 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55dwfdvbz96zhvzs4h8v',
                'name_en' => 'Pharmaceuticals and personal care products',
                'name_fr' => 'Produits pharmaceutiques et produits de soins personnels',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            205 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55ehn97cx6033dh5xn10',
                'name_en' => 'Pharmacokinetics/Dynamics',
                'name_fr' => 'Pharmacocinétique et pharmacodynamie',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            206 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55f60v071e9x3d4kv7zm',
                'name_en' => 'Pharmacology',
                'name_fr' => 'Pharmacologie',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            207 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55frwqceg6zf7ctpbaev',
                'name_en' => 'Phenology',
                'name_fr' => 'Phénologie',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            208 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55garbw1c9gb9cb84kzx',
                'name_en' => 'Photochemical smog',
                'name_fr' => 'Smog photochimique',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            209 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55gvg44pyam622d0gdtb',
                'name_en' => 'Photochemistry',
                'name_fr' => 'Photochimie',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            210 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55h95q4xvmh22fxhhadp',
                'name_en' => 'Photometry and Radiometry',
                'name_fr' => 'Photométrie et radiométrie',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            211 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55hscg8p84gvd2shd9qx',
                'name_en' => 'Photonic Devices',
                'name_fr' => 'Dispositifs photoniques',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            212 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55jbefm5c9rgr9rs7f59',
                'name_en' => 'Photonics',
                'name_fr' => 'Photonique',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            213 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55jwfx0bjkevtxq7ebxt',
                'name_en' => 'Physical',
                'name_fr' => 'Physique',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            214 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55kd4h783srjmqzvzg7w',
                'name_en' => 'Physical Modelling',
                'name_fr' => 'Modélisation physique',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            215 => 
            array (
                'created_at' => '2023-10-27 16:05:56',
                'id' => '01hdry55kxzagh5gkgsxddanvq',
                'name_en' => 'Physical oceanography',
                'name_fr' => 'Océanographie physique',
                'updated_at' => '2023-10-27 16:05:56',
            ),
            216 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55mdcacg5z3j1qwb8r9r',
                'name_en' => 'Physical sciences',
                'name_fr' => 'Sciences physiques',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            217 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55mv4n9tp076s9g7nnfz',
                'name_en' => 'Physicochemical processes',
                'name_fr' => 'Procédés physicochimiques',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            218 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55namcg7z2mhc779ns57',
                'name_en' => 'Physiology',
                'name_fr' => 'Physiologie',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            219 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55nrq87739bcjhq11yyp',
                'name_en' => 'Physiology & Biochemistry',
                'name_fr' => 'Physiologie et biochimie',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            220 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55pav2zsvce8wtq201ra',
                'name_en' => 'Phytochemical',
                'name_fr' => 'Agents phytochimiques',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            221 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55psshsnsa0w2t148hn4',
                'name_en' => 'Phytopathogens',
                'name_fr' => 'Phytopathogène',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            222 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55q9ts0by2k662a77ctx',
                'name_en' => 'Phytopathology',
                'name_fr' => 'Phytopathologie',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            223 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55qwf6jahb5wmwa11kn0',
                'name_en' => 'Phytoplankton',
                'name_fr' => 'Phytoplancton',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            224 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55rdnc928d0ga07marqz',
                'name_en' => 'Phytoplasm',
                'name_fr' => 'Phytoplasmes',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            225 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55ryectk39mvvjqsc87c',
                'name_en' => 'Phytosterols',
                'name_fr' => 'Phytostérols',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            226 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55sfeq5e9amk3t2df5bb',
            'name_en' => 'Pinnipeds (seals, walruses)',
            'name_fr' => 'Pinnipèdes (phoques, morses)',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            227 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55t75mh4d2xwhmk4n8yb',
                'name_en' => 'Pipelines',
                'name_fr' => 'Oléoducs',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            228 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55txkq80bxhv0hg7t55w',
                'name_en' => 'Placers',
                'name_fr' => 'Gisement alluvial',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            229 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55vs26qnmv4y6666cmnf',
                'name_en' => 'Plant',
                'name_fr' => 'Phytophysiologie',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            230 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55w9rwrbr5tasjzdq019',
                'name_en' => 'Plant bacterial taxonomy',
                'name_fr' => 'Taxonomie bactérienne des plantes',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            231 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55wsghn5a9snjapecqta',
                'name_en' => 'Plant cell technologies',
                'name_fr' => 'Technologies à base de cellules végétales',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            232 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55xbww2vxj2b8q3g887t',
                'name_en' => 'Plant disease management',
                'name_fr' => 'Lutte contre les maladies végétales',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            233 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55xtprzt4thhjh0dkv9m',
                'name_en' => 'Plant diseases',
                'name_fr' => 'Ravageur des plantes',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            234 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55ya2akx6kkhgpshvggp',
                'name_en' => 'Plant germplasm development – apple, sweet cherry',
                'name_fr' => 'Élaboration de matériel phytogénétique – pomme, cerise douce',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            235 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55yr6dsjq7zy5g12y31q',
                'name_en' => 'Plant Hardiness',
                'name_fr' => 'Rusticité des plantes',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            236 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55za26ytb1t6dqmrk7c5',
                'name_en' => 'Plant pathogen interactions',
                'name_fr' => 'Interactions des pathogènes végétaux',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            237 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry55zqrxdst1y4eem7pjn8',
                'name_en' => 'Plant pests',
                'name_fr' => 'Plantes envahissantes',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            238 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry5607x4xpvh7j684aqyze',
                'name_en' => 'Plant production',
                'name_fr' => 'Production végétale',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            239 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry560n1crbtev0zfnc9d6a',
                'name_en' => 'Plant reproduction',
                'name_fr' => 'Reproduction des plantes',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            240 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56192s8ntvqxem4jk3sy',
                'name_en' => 'Plant stress',
                'name_fr' => 'Stress chez les végétaux',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            241 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry561tp7h5apw3x01e0zft',
                'name_en' => 'Plant viruses',
                'name_fr' => 'Phytovirus',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            242 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry562e4wxt5mv1dx32pxm8',
                'name_en' => 'Plant-feeding mite',
                'name_fr' => 'Des acariens phytovores',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            243 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56314ctdvqq7v021xbdq',
                'name_en' => 'Planthopper',
                'name_fr' => 'Du fulgore',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            244 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry563cqv6bsjszww02bkeh',
                'name_en' => 'Plants',
                'name_fr' => 'Des végétaux',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            245 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry563scswvrzwx09mmabn8',
                'name_en' => 'Plutonic',
                'name_fr' => 'Plutonique',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            246 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry564cyvt4vzg92nsh90wh',
                'name_en' => 'Polar bears',
                'name_fr' => 'Ours polaires',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            247 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry564t6bea0d2d8gzq6wje',
                'name_en' => 'Polar Continental Shelf',
                'name_fr' => 'Plateau continental polaire',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            248 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry565ac11hspdvb513krpj',
                'name_en' => 'Police and Law Enforcement',
                'name_fr' => 'Police et application de la loi',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            249 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry565vg072q311x2bep4w1',
                'name_en' => 'Policy',
                'name_fr' => 'Politique',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            250 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry566dd4pfehnmsr8bm47w',
                'name_en' => 'Policy Analysis',
                'name_fr' => 'Analyse des politiques',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            251 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry566ydd1vxfw5n0mz3fzr',
                'name_en' => 'Policy Development',
                'name_fr' => 'Élaboration de politiques',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            252 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry567fa0gfm2f45d9tr6dr',
                'name_en' => 'Pollinating and parasitoid fly',
                'name_fr' => 'Des mouches pollinisatrices et parasitoïdes',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            253 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56811qzbrt8a4tpth7fa',
                'name_en' => 'Pollinator',
                'name_fr' => 'Pollinisateurs',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            254 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry568jag5tv5q49e8z6nq2',
                'name_en' => 'Pollutants',
                'name_fr' => 'Polluants',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            255 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry569a20x4pahsf9bjkv8x',
                'name_en' => 'Pollution & Waste',
                'name_fr' => 'Pollution et déchets',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            256 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry569thsq81hvg5t2v1kzn',
                'name_en' => 'Pollution prevention',
                'name_fr' => 'Prévention de la pollution',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            257 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56aaa8gjdwxpgkky923a',
            'name_en' => 'Polycyclic aromatic hydrocarbons (PAHs)',
            'name_fr' => 'Hydrocarbures aromatiques polycycliques (HAP)',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            258 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56ar3h03e0jr5fkf6v66',
                'name_en' => 'Polymer Composites',
                'name_fr' => 'Composites à base de polymères',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            259 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56b9bfr6wzvbse8hzhr5',
                'name_en' => 'Polymers',
                'name_fr' => 'Polymères',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            260 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56bwd163316b7gn3yqwx',
                'name_en' => 'Polysaccharides',
                'name_fr' => 'Polysaccharides',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            261 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56c62vkey26d31nsg5d5',
                'name_en' => 'Population Dynamics',
                'name_fr' => 'Dynamique des populations',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            262 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56crnt6ye81crpymfhan',
                'name_en' => 'Population ecology',
                'name_fr' => 'Écologie des populations',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            263 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56d8nh4spt2a6ecgxbza',
                'name_en' => 'Population genetics',
                'name_fr' => 'Génétique des populations',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            264 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56dym4hsbv0x8r7zxgwd',
                'name_en' => 'Population monitoring techniques',
                'name_fr' => 'Techniques de surveillance des populations',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            265 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56eg1fkk1zg2mvxmnhbf',
                'name_en' => 'Population tracking and analysis',
                'name_fr' => 'Suivi et analyse de la population',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            266 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56ezembkcxmkyped9nhk',
                'name_en' => 'Pork',
                'name_fr' => 'Le cheptel porc',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            267 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56fejerqwhgwqfwr7wkj',
                'name_en' => 'Pork behaviour and welfare',
                'name_fr' => 'Comportement et bien-être du porc',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            268 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56fw856230fszbqa8b7n',
                'name_en' => 'Pork carcass quality',
                'name_fr' => 'Qualité de la carcasse de porc',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            269 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56gje3j63tjffbazbe44',
                'name_en' => 'Pork lactation',
                'name_fr' => 'La lactation du porc',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            270 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56h2vdjjpn3dke2cw2ck',
                'name_en' => 'Post Combustion Capture',
                'name_fr' => 'Captage postcombustion',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            271 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56hj06zt6w54enbrqybs',
                'name_en' => 'Post-harvest',
                'name_fr' => 'Postrécolte',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            272 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56j4tvgrbhvqvjp11yth',
                'name_en' => 'Potato',
                'name_fr' => 'Pommes de terre',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            273 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56jg5zkbbgdtpnvyn60v',
                'name_en' => 'Potato plant',
                'name_fr' => 'Phytopathologie de la pomme de terre',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            274 => 
            array (
                'created_at' => '2023-10-27 16:05:57',
                'id' => '01hdry56k87cc4drc5a1px5f0r',
                'name_en' => 'Poultry',
                'name_fr' => 'le cheptel volaille',
                'updated_at' => '2023-10-27 16:05:57',
            ),
            275 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56krfwadtv5m53jt9dh1',
                'name_en' => 'Powder Metallurgy',
                'name_fr' => 'Métallurgie des poudres',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            276 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56mcwm3dbrp2pnebtn46',
                'name_en' => 'Power bed fusion',
                'name_fr' => 'Fusion sur lit de poudre',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            277 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56n01p63n1vf6ktkp1ws',
                'name_en' => 'Power Cycles',
                'name_fr' => 'Impulsion motrice',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            278 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56nkt5e3kqz9j5jjym0e',
                'name_en' => 'Prairie Provinces',
                'name_fr' => 'Provinces des Prairies',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            279 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56p6cqhjwzhasp3v5f0j',
                'name_en' => 'Precambrian Geology',
                'name_fr' => 'Géologie Précambrien',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            280 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56pvnqmmd6x59prmekvc',
                'name_en' => 'Precious Metals',
                'name_fr' => 'Métaux précieux',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            281 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56qc9pkckc7r8qn7th58',
                'name_en' => 'Precipitation',
                'name_fr' => 'Précipitation',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            282 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56qwfw42tm3phmhhgsrr',
                'name_en' => 'Precipitation Processes and Measurement',
                'name_fr' => 'Mesures et processus liés aux précipitations',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            283 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56rd0x17z5xmqhfjgaqg',
                'name_en' => 'Precision farming',
                'name_fr' => 'Agriculture de précision',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            284 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56s11zkw5kbydnted6nf',
                'name_en' => 'Predacious fly',
                'name_fr' => 'Des mouches prédatrices',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            285 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56sm7b3hfcf9gqadgh01',
                'name_en' => 'Primary industry',
                'name_fr' => 'Industrie primaire',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            286 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56th9ehwjcjz28wja5yt',
                'name_en' => 'Printable electronics',
                'name_fr' => 'Électronique imprimable',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            287 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56v5gyswf305mq38ed1g',
                'name_en' => 'Printing technologies',
                'name_fr' => 'Technologies d\'impression',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            288 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56vnv9x0k937p846w1ve',
                'name_en' => 'Prion',
                'name_fr' => 'Prions',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            289 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56w7q31xky7xxchczjab',
                'name_en' => 'Probiotics',
                'name_fr' => 'Probiotiques',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            290 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56wq6kvdpbssrtnz53me',
                'name_en' => 'Processes Connectivity',
                'name_fr' => 'Connectivité des procédés',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            291 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56xasrz65wn74d7dy1dh',
                'name_en' => 'Processing',
                'name_fr' => 'Traitement',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            292 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56xtb0nxfch07wxm5n8d',
                'name_en' => 'Production',
                'name_fr' => 'Production culturale',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            293 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56y9jptsgedqpgtmyeb5',
                'name_en' => 'Production pathology',
                'name_fr' => 'Pathologie de la production',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            294 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56ysq2htp7bczkh3xvnx',
                'name_en' => 'Profiles',
                'name_fr' => 'Profils',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            295 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56zb4qyg5556077v7her',
                'name_en' => 'Program Management',
                'name_fr' => 'Gestion de programme',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            296 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry56zw0ntg8p8as5gnfs09',
                'name_en' => 'Project management',
                'name_fr' => 'Gestion de projet',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            297 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry570dppj7a33kejn63x1j',
                'name_en' => 'Propulsion systems',
                'name_fr' => 'Systèmes de propulsion',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            298 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry5712ssv089hbw26hmq4w',
                'name_en' => 'Protected Areas',
                'name_fr' => 'Zones protégées',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            299 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry571pfjraftthxe589rkh',
                'name_en' => 'Protein',
                'name_fr' => 'Des protéines',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            300 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57299y81nx6v03mewqkj',
                'name_en' => 'Protein Engineering',
                'name_fr' => 'Ingénierie des protéines',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            301 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry572r9cxsatkh9b24nh3w',
                'name_en' => 'Protein metabolism',
                'name_fr' => 'Métabolisme protéique',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            302 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry573b5mnerhspd8m8kaz4',
                'name_en' => 'Proteomics',
                'name_fr' => 'Protéomique',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            303 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry573vw7jqvy3gcnrvnpb7',
                'name_en' => 'Prototyping',
                'name_fr' => 'Prototypage',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            304 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry574844edy6h1n37pz209',
                'name_en' => 'Protozoa',
                'name_fr' => 'Protozoaires',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            305 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry574swydbjgxhzebhmh2g',
                'name_en' => 'Psychology',
                'name_fr' => 'Psychologie',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            306 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57577yw68b4jg1qf2bzf',
                'name_en' => 'Psychosocial and Community Resilience',
                'name_fr' => 'Résilience psychosociale et communautaire',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            307 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry575wmbrkxgf7xc58qytb',
                'name_en' => 'Public Health',
                'name_fr' => 'Santé publique',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            308 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry576gj1nepvh75d64mjy8',
                'name_en' => 'Public Service Health',
                'name_fr' => 'Santé des fonctionnaires fédéraux',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            309 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57714p6nd3fzdk8y8r06',
                'name_en' => 'Publishing and Editing',
                'name_fr' => 'Publication et édition',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            310 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry577nbm4jcxp4s0v0pd99',
                'name_en' => 'Pulp',
                'name_fr' => 'Pulpe',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            311 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry5785vymx6hnacrp28bxn',
                'name_en' => 'Pulp and paper',
                'name_fr' => 'Pâtes et papier',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            312 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry578r07r4qknn4x6kmepk',
                'name_en' => 'Pulp and paper industry',
                'name_fr' => 'Industrie des pâtes et papiers',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            313 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57972qve9rrpkcewgap6',
                'name_en' => 'Pulse',
                'name_fr' => 'Légumineuses',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            314 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry579strnta2zcx5pnk128',
                'name_en' => 'Pulse diseases',
                'name_fr' => 'Maladies des légumineuses',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            315 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57a9e164ka7b3qyn1dj0',
                'name_en' => 'Quality assurance',
                'name_fr' => 'Assurance de la qualité',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            316 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57atfsr5s9xsgwyqdb46',
                'name_en' => 'Quantum',
                'name_fr' => 'Quantique',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            317 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57bfcgbn392q57540yvf',
                'name_en' => 'Quantum communication components and networks',
                'name_fr' => 'Composants et réseaux pour la communication quantique',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            318 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57bzyz5gfej7qqar97t0',
                'name_en' => 'Quantum computing components and design',
                'name_fr' => 'Conception et composants de l\'informatique quantique',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            319 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57chpkfbdgayzr3rj5b5',
                'name_en' => 'Quantum PNT',
            'name_fr' => 'Positionnement, navigation et synchronisation (PNS) quantiques',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            320 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57d7ptp7ytvw79z5tfwj',
                'name_en' => 'Quantum sensor technologies',
                'name_fr' => 'Technologies de capteurs quantiques',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            321 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57dqa2rqy40hdd2pnn9a',
                'name_en' => 'Queen Charlotte Basin',
                'name_fr' => 'Bassin de la Reine Charlotte',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            322 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57e9fag30f56arpf3mwe',
                'name_en' => 'Radar',
                'name_fr' => 'Radar',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            323 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57evenk8qc895ry0qpe5',
                'name_en' => 'Radar & Monitoring',
                'name_fr' => 'Radar et surveillance',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            324 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57fcwsweemf0hxzcdhxm',
                'name_en' => 'RADARSAT',
                'name_fr' => 'RADARSAT',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            325 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57fw9y9fh02es2jmkq3s',
                'name_en' => 'Radiation',
                'name_fr' => 'Radiation',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            326 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57gf7zen2a03mym45321',
                'name_en' => 'Radiation detection',
                'name_fr' => 'Détection du rayonnement',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            327 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57gxq93r57jqb61hpysm',
                'name_en' => 'Radiation Emitting Devices',
                'name_fr' => 'Dispositifs émettant des radiations',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            328 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57he2cz1ytq7qd5mmbfv',
                'name_en' => 'Radiative transfer',
                'name_fr' => 'Transfert radiatif',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            329 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57hx4528pa265b5fs8f3',
                'name_en' => 'Radio',
                'name_fr' => 'Radio',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            330 => 
            array (
                'created_at' => '2023-10-27 16:05:58',
                'id' => '01hdry57jexx2z7ayws4jgkmyw',
                'name_en' => 'Radioactive Waste - High level',
                'name_fr' => 'Déchets hautement radioactifs',
                'updated_at' => '2023-10-27 16:05:58',
            ),
            331 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57k07pyngyagj63trbd6',
                'name_en' => 'Radioactive Waste - Low level',
                'name_fr' => 'Déchets à radioactité faible',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            332 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57kps5cn6ecyhfwmzws0',
                'name_en' => 'Radioactivity',
                'name_fr' => 'Radioactivité',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            333 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57m88hystxqkkgjntsj8',
                'name_en' => 'Radiological-nuclear',
                'name_fr' => 'Radio-nucléaire',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            334 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57mpac14j8nszgv8nspm',
                'name_en' => 'Radionavigation',
                'name_fr' => 'Radionavigation',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            335 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57n539n85a7t43pdffk2',
                'name_en' => 'Rail',
                'name_fr' => 'Rail',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            336 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57npjfef7g23pvx4twxy',
                'name_en' => 'Rail vehicle engineering',
                'name_fr' => 'Génie des véhicules ferroviaires',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            337 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57p4a7qdtkpg81d3ytqe',
                'name_en' => 'Rain & Stormwater',
                'name_fr' => 'Pluie et eaux de ruissellement',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            338 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57pn1dsc4ced1dkafksr',
                'name_en' => 'Rainforest',
                'name_fr' => 'Forêt tropicale',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            339 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57q5ckr2g9ezkc64rvev',
                'name_en' => 'Range plant',
                'name_fr' => 'Plantes de parcours',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            340 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57qnxetdw2w9v81sbpq1',
                'name_en' => 'Raptors',
                'name_fr' => 'Rapaces',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            341 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57r68kcznpz1zq0w1as2',
                'name_en' => 'Rare Earth Metals',
                'name_fr' => 'Métaux des terres rares',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            342 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57rwkq17e37hpn4j8vw9',
                'name_en' => 'Re-evaluation of Older Products',
                'name_fr' => 'Réévaluation des produits plus anciens',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            343 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57ssqkbp8vwa80w8hvty',
                'name_en' => 'Real Property',
                'name_fr' => 'Biens immobiliers',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            344 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57t720sjfpxe9a8jrrtv',
                'name_en' => 'Real-Time Simulation',
                'name_fr' => 'Simulation en temps réel',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            345 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57tsdkmqdew4gc55qe4y',
                'name_en' => 'Recombination',
                'name_fr' => 'La recombinaison',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            346 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57v9g3c1w6dppvm1cvv3',
                'name_en' => 'Recycling',
                'name_fr' => 'Recyclage',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            347 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57vs8rkrb4kh7zndx1pf',
                'name_en' => 'Red River',
                'name_fr' => 'Rivière Rouge',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            348 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57wb3rxtqy3z13ydd5gv',
                'name_en' => 'Refineries',
                'name_fr' => 'Raffineries',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            349 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57wz925b3kdf6786npda',
                'name_en' => 'Refining',
                'name_fr' => 'Raffinage',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            350 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57xgm2qd9akvv3kgnzr5',
                'name_en' => 'Regional Mapping',
                'name_fr' => 'Cartographie régionale',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            351 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57y21ma43ah14m4g4n67',
                'name_en' => 'Regulation & Enforcement',
                'name_fr' => 'Réglementation et application de la loi',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            352 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57ykdcb648nvx4c81dxj',
                'name_en' => 'Regulatory Toxicology',
                'name_fr' => 'Toxicologie réglementaire',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            353 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57z8re3a8fggd2qe0brj',
                'name_en' => 'Rehabilitation',
                'name_fr' => 'Réhabilitation',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            354 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry57zwycwvkrv3fkhk99nv',
                'name_en' => 'Remediation',
                'name_fr' => 'Restauration',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            355 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry580g4gyehrqq23yaet05',
            'name_en' => 'Remote (off-grid) energy systems',
                'name_fr' => 'Filière énergétique éloignées/hors réseau',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            356 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry5810zqsepjjn4w9b03q0',
                'name_en' => 'Remote Data Management',
                'name_fr' => 'Gestion des données à distance',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            357 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry581mj0686c66jgqr79c8',
                'name_en' => 'Remote Predictive Mapping',
                'name_fr' => 'Télécartographie predictive',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            358 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry5827gzax6tyx3tbq3xk5',
                'name_en' => 'Remote sensing',
                'name_fr' => 'Télédétection',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            359 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry582qy1hfsepjgzzgfmne',
                'name_en' => 'Remote Sensing - Hyperspectral',
                'name_fr' => 'Télédétection - hyperspectrale',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            360 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58397pevpr5s25jv7rew',
                'name_en' => 'Remote Sensing - Optical',
                'name_fr' => 'Télédétection - optique',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            361 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry583tqfeyt1nffk6e36s0',
            'name_en' => 'Remote Sensing - Synthetic Aperture Radar (SAR)',
                'name_fr' => 'Télédétection - Radar à synthèse d\'ouverture',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            362 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry584f9qg5d04t8w91k8p7',
                'name_en' => 'Renewable Energy',
                'name_fr' => 'Énergie renouvelable',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            363 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58555hgsfxgh0xgsxy0k',
                'name_en' => 'Renewable Fuels',
                'name_fr' => 'Carburant renouvable',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            364 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry585pyksby2s6brmr8rzh',
                'name_en' => 'Renewables - Geothermal Energy',
                'name_fr' => 'Énergies renouvelables - Énergie géothermique',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            365 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry586798bb9txtggdaqwgq',
                'name_en' => 'Renewables - Hydroelectricity',
                'name_fr' => 'Énergies renouvelables - Hydroélectricité',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            366 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry586qt053vyv1e9mq8351',
                'name_en' => 'Renewables - Marine Energy',
                'name_fr' => 'Énergies renouvelables – Marine',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            367 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry5878y7rhzhjq1f60rayr',
                'name_en' => 'Renewables - Solar Energy',
                'name_fr' => 'Énergies renouvelables – Solaire',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            368 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry587tz2gt0m7xvazqhjsh',
                'name_en' => 'Renewables - Waste',
                'name_fr' => 'Énergies renouvelables – Déchets',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            369 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry588ajpgemeg52p61pjz0',
                'name_en' => 'Reproductive and Developmental Toxicology',
                'name_fr' => 'Toxicologie de la reproduction et du développement',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            370 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry588t8xktr3mf06k52a0x',
                'name_en' => 'Reproductive Technology',
                'name_fr' => 'Technologie de la reproduction',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            371 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry589c6f02tmgwtc6v5p8z',
                'name_en' => 'Reproductive Toxicology',
                'name_fr' => 'Toxicologie de la reproduction',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            372 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry589vfz85n4ypy5r7pxmp',
                'name_en' => 'Reprography',
                'name_fr' => 'Reprographie',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            373 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58adgkaj9qsa8vsvb4xb',
                'name_en' => 'Reptiles',
                'name_fr' => 'Reptiles',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            374 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58b3fhj5hj90vs6ersrw',
                'name_en' => 'Reservoir',
                'name_fr' => 'Réservoir',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            375 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58bkcevpxzw6f6zrf77f',
                'name_en' => 'Residential Contaminants',
                'name_fr' => 'Contaminants dans les résidences',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            376 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58c23r44qybw09bdp989',
                'name_en' => 'Residue',
                'name_fr' => 'Résidus',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            377 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58chdmeax57yftaj6te8',
                'name_en' => 'Resource extraction impacts',
                'name_fr' => 'Incidence de l\'extraction des ressources',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            378 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58d0z17sw480zqqkwvqq',
                'name_en' => 'Resource management',
                'name_fr' => 'Gestion des ressources',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            379 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58dhy5b40bvqg1hfz978',
                'name_en' => 'Resource Management Services',
                'name_fr' => 'Services de gestion des ressources',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            380 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58e2sgm4jhtjwse0kbsf',
                'name_en' => 'Resources',
                'name_fr' => 'Ressources',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            381 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58eg2x059nv1qrc5bw89',
                'name_en' => 'Rhizobia',
                'name_fr' => 'Des rhizobiums',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            382 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58f2nhwmbysb2t6yyqc6',
                'name_en' => 'Rickettsia',
                'name_fr' => 'Rickettsies',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            383 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58fnwzkt5s93d3y4r9wz',
                'name_en' => 'Riparian',
                'name_fr' => 'Rivulaire',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            384 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58g3zpqty5rdqg7ynsys',
            'name_en' => 'Riparian (shoreline) habitats',
                'name_fr' => 'Habitats riverains',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            385 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58gmny60gmq3n1e9dhq0',
                'name_en' => 'Risk analysis',
                'name_fr' => 'Analyse du risque',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            386 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58h48yzj0pqgrezappy0',
                'name_en' => 'Risk Assessment',
                'name_fr' => 'Évaluation des risques',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            387 => 
            array (
                'created_at' => '2023-10-27 16:05:59',
                'id' => '01hdry58hkhb39pzg0r9na0sah',
                'name_en' => 'Risk Assessment Methodology',
                'name_fr' => 'Méthode d\'évaluation des risques',
                'updated_at' => '2023-10-27 16:05:59',
            ),
            388 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58j4n1fcx4sqhqddwjr0',
                'name_en' => 'Risk management',
                'name_fr' => 'Gestion des risques',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            389 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58jpej12dqzfkh47s7hy',
                'name_en' => 'River',
                'name_fr' => 'Rivière',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            390 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58k6tmq9jt9nqzkxa4sp',
                'name_en' => 'River Engineering',
                'name_fr' => 'Génie fluvial',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            391 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58kp0axe360ezba8jyqx',
                'name_en' => 'River Ice',
                'name_fr' => 'Glace de rivière',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            392 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58m1324e0gnmjnjq9qtt',
                'name_en' => 'Road',
                'name_fr' => 'Routier',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            393 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58mg5t1yxy2sks9j8dn0',
                'name_en' => 'Robotics',
                'name_fr' => 'Robotique',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            394 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58my63e15knz7bxq0a5d',
                'name_en' => 'Rock Breakage',
                'name_fr' => 'L\'abattage de roche',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            395 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58nbesf5w1xvtf5m3h1r',
                'name_en' => 'Rocks - Igneous',
                'name_fr' => 'Roches - ignées',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            396 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58nx5nggr24fzp2azrh3',
                'name_en' => 'Rocks - Metamorphic',
                'name_fr' => 'Roches - métamorphiques',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            397 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58pcn9r1qcbecby3teq0',
                'name_en' => 'Rocks - Sedimentary',
                'name_fr' => 'Roches - sédimentaires',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            398 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58pyfb79k0mh39dga9r6',
                'name_en' => 'Rocky Mountains',
                'name_fr' => 'Les Rocheuses',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            399 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58qdctxkz82a6p6wd27s',
                'name_en' => 'Rolling',
                'name_fr' => 'Laminage',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            400 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58qyn2rshdpnty75ptsj',
                'name_en' => 'Rumen',
                'name_fr' => 'Rumen',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            401 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58rh81a2s6hdf1nf3cez',
                'name_en' => 'Rumen metagenomics',
                'name_fr' => 'Métagénomique du rumen',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            402 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58s1xr7ee7afdzsxcxya',
                'name_en' => 'Ruminant',
                'name_fr' => 'Ruminants',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            403 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58shg9s210fd2mtv6sjd',
                'name_en' => 'Rust and smut fungi',
                'name_fr' => 'Des champignons de la rouille et du charbon des plantes',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            404 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58szss16cj5jt9fqe3xz',
                'name_en' => 'Safety',
                'name_fr' => 'Sécurité',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            405 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58tbg94xerq70k5yafv2',
                'name_en' => 'Safety and Security',
                'name_fr' => 'Sûreté et sécurité',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            406 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58ty1m66nfzvt5z8my14',
                'name_en' => 'Saint Lawrence River',
                'name_fr' => 'Fleuve Saint-Laurent',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            407 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58vmqvtyd76gsncjkrtw',
                'name_en' => 'Saltwater intrusions',
                'name_fr' => 'Invasions d\'eau salée',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            408 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58w40e8gmhtrem3z75n6',
                'name_en' => 'Sampling Technologies',
                'name_fr' => 'Technologies d\'échantillonnage',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            409 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58wmvrxd440qvvcshc4n',
                'name_en' => 'Saskatchewan River',
                'name_fr' => 'Rivière Saskatchewan',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            410 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58x55pd36sdm5k3hdcrh',
                'name_en' => 'Satellite Data',
                'name_fr' => 'Données satellitaires',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            411 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58xt8pa2xgth45apef2w',
                'name_en' => 'Satellite Imagery',
                'name_fr' => 'Imagerie satellitaire',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            412 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58ycnb4r6hjq9kffg17h',
                'name_en' => 'Satellite remote sensing',
                'name_fr' => 'Télédétection par satellite',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            413 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58yxg0kqah0dgtz4k5vb',
                'name_en' => 'Satellite systems',
                'name_fr' => 'Systèmes par satellite',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            414 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58zer3sqvhgz2qz6ebpn',
                'name_en' => 'Satellite technology',
                'name_fr' => 'Technologie des satellites',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            415 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry58zyz81zs4gtw5q4d8mj',
                'name_en' => 'Satellite telemetry',
                'name_fr' => 'Télémesure satellitaire',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            416 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry590kwz610kzrpxvrptk2',
                'name_en' => 'Satellites',
                'name_fr' => 'Satellite',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            417 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry5916a153mvyf9z1611zc',
                'name_en' => 'Sawmill',
                'name_fr' => 'Scierie',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            418 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry591t8pzgw9tt831e7vyr',
                'name_en' => 'Science & Technology Communication',
                'name_fr' => 'Communications en science et techn.',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            419 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry592bxey6wzbjy87zbgth',
                'name_en' => 'Science Assessment',
                'name_fr' => 'Évaluation scientifique',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            420 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry592tthpdw0cnjys6jq15',
                'name_en' => 'Science management',
                'name_fr' => 'Gestion des sciences',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            421 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry593cgszs3ytp0ka9j81r',
                'name_en' => 'Science Policy',
                'name_fr' => 'Politique scientifique',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            422 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry593xc4d8x6fhb1jxr2mk',
                'name_en' => 'Science-policy integration',
                'name_fr' => 'Intégration des politiques scientifiques',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            423 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry594c06ankb267x4ph9n0',
                'name_en' => 'Sciences',
                'name_fr' => 'Sciences',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            424 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry594xatacpfbgnpvf22n1',
                'name_en' => 'Scientific communications',
                'name_fr' => 'Communications scientifique',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            425 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry595fqh7k7hk2d2wjes5t',
                'name_en' => 'Scientific equipment',
                'name_fr' => 'Équipement scientifique',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            426 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry595yebqp5mn61xy2dgmt',
                'name_en' => 'Scientific information',
                'name_fr' => 'Information scientifique',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            427 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry596h238j9j9mqep9dfvy',
                'name_en' => 'Scientific research',
                'name_fr' => 'Recherche scientifique',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            428 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry5976hezmwz26ckxq48e7',
                'name_en' => 'Scotian Shelf',
                'name_fr' => 'Plateau écossais',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            429 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry597qdxzjdwt6qj4m21bd',
                'name_en' => 'Sea Ice',
                'name_fr' => 'Glace océanique',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            430 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry5985dktvs1wm4e0xtq7x',
                'name_en' => 'Sea ice modelling',
                'name_fr' => 'Modélisation de la glace de mer',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            431 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry598nz7gde5xdyh82j2a9',
                'name_en' => 'Seabirds',
                'name_fr' => 'Oiseaux marins',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            432 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry59950mz60qvf80t64r66',
                'name_en' => 'Secondary metabolism',
                'name_fr' => 'Métabolisme secondaire',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            433 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry599va8dkhwtpadh2whs6',
                'name_en' => 'Security Materials Tech',
                'name_fr' => 'Technologies des matériaux de sécurité',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            434 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry59ab4c0744c4z2jq1gkg',
                'name_en' => 'Sediment',
                'name_fr' => 'Sédiments',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            435 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry59avgsm43c9fjxb0eyek',
                'name_en' => 'Sedimentology',
                'name_fr' => 'Sédimentologie',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            436 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry59b9dmnfqav2fg25rkpe',
                'name_en' => 'Seeds',
                'name_fr' => 'Semences',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            437 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry59bx44y525jz2m3qfy61',
                'name_en' => 'Seismology',
                'name_fr' => 'Séismologie',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            438 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry59cgkq3vs3g46qrcmmm0',
                'name_en' => 'Semiconductors',
                'name_fr' => 'Semiconducteurs',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            439 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry59d23v7neyqaxz2sgdq6',
                'name_en' => 'Sensors',
                'name_fr' => 'Capteurs',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            440 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry59dq1t5ckv44shcsp291',
                'name_en' => 'Sensory evaluation',
                'name_fr' => 'Analyse sensorielle',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            441 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry59eb4ej40enspt36x8fv',
                'name_en' => 'Sentiment Analysis',
                'name_fr' => 'Analyse de sentiments',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            442 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry59ewx68b8szspr3p2ny6',
                'name_en' => 'Sequestration',
                'name_fr' => 'Phase emprisonnée',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            443 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry59faxb377tqxbe6pff5a',
                'name_en' => 'Severe weather',
                'name_fr' => 'Temps violent',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            444 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry59fx9nzxftpwpn8gsk6q',
                'name_en' => 'Shale',
                'name_fr' => 'Schiste',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            445 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry59gg7rn1qn77rjhxv0r8',
                'name_en' => 'Shale gas',
                'name_fr' => 'Gaz de schiste',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            446 => 
            array (
                'created_at' => '2023-10-27 16:06:00',
                'id' => '01hdry59gxtb4qrqv5tdjpbh06',
                'name_en' => 'Shellfish/Invertebrates',
                'name_fr' => 'Mollusques/Crustacés/Invertébrés',
                'updated_at' => '2023-10-27 16:06:00',
            ),
            447 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59hk6r3ep2x0c0znf7zx',
                'name_en' => 'Shorebirds',
                'name_fr' => 'Oiseaux de rivage',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            448 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59j5r3pdp5aczhkrfpnv',
                'name_en' => 'Silviculture',
                'name_fr' => 'Sylviculture',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            449 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59jmwchr5m99jqcy3cx9',
                'name_en' => 'Simulation',
                'name_fr' => 'Simulation',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            450 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59k14zh92pwydavbg66s',
                'name_en' => 'Simulation & Numerical Modelling',
                'name_fr' => 'Simulation et modélisation numérique',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            451 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59kmsrj6xmpye4cbysf2',
                'name_en' => 'Simulation and Digital Health',
                'name_fr' => 'Simulation et Santé numérique',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            452 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59m9ak3g4nz3wrtvqw2d',
                'name_en' => 'Simulation and Predictive AI',
                'name_fr' => 'Simulation et IA prédictive',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            453 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59mr0ab8d22vcwr8zejn',
                'name_en' => 'Simulations',
                'name_fr' => 'Simulations',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            454 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59n7q2x6sb4gf1j75esj',
                'name_en' => 'Site remediation',
                'name_fr' => 'Assainissement des lieux',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            455 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59nq1nvsksgc6sp3nn93',
                'name_en' => 'Small fruit entomology',
                'name_fr' => 'Entomologie des petits fruits',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            456 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59p5zf3ghx7wwqmefmpx',
                'name_en' => 'Smart Grid',
                'name_fr' => 'Réseau électrique intelligent',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            457 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59pqs036jaq8zndaq1hj',
                'name_en' => 'Smart Materials',
                'name_fr' => 'Matériaux intelligents',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            458 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59q7gm1rwc4dmxat1jxc',
                'name_en' => 'Smart Parts',
                'name_fr' => 'Pièces intelligentes',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            459 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59qxjfpjk5dcmtrvfy55',
                'name_en' => 'Smart Tooling',
                'name_fr' => 'Outillage intelligent',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            460 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59ret5fp5s3e15zk6mak',
                'name_en' => 'Smut, ergot and crown rust of oats',
                'name_fr' => 'Charbon, ergot et rouille de la couronne de l’avoine',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            461 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59s1qe2m0aypej4395ca',
                'name_en' => 'Snow',
                'name_fr' => 'Neige',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            462 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59sj5tezjv10jcvpwfhm',
                'name_en' => 'Snow Processes and Measurement',
                'name_fr' => 'Mesures et processus liés à la neige',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            463 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59t787mwcygbb29h734m',
                'name_en' => 'Snowmelt',
                'name_fr' => 'Fonte des neiges',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            464 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59tt37kd6a1mqs1sybdj',
                'name_en' => 'Snowpacks',
                'name_fr' => 'Stock nival',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            465 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59vc557v6a54n35k710d',
                'name_en' => 'Snowstorms',
                'name_fr' => 'Tempêtes de neige',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            466 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59w1yeaxv66hk3swytfb',
                'name_en' => 'Social Sciences',
                'name_fr' => 'Sciences sociales',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            467 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59wk95t57aqay4wd9n26',
                'name_en' => 'Sociology',
                'name_fr' => 'Sociologie',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            468 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59x4k6dhyf1xtk42e8n9',
                'name_en' => 'Software',
                'name_fr' => 'Logiciel',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            469 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59xprj4bezy1p5010rb3',
                'name_en' => 'Software development',
                'name_fr' => 'Développement de logiciels',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            470 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59y6a2ar28be61by12c3',
                'name_en' => 'Soil',
                'name_fr' => 'Sol',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            471 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59ypyayt17hahnx71awb',
                'name_en' => 'Soil and crop nitrogen cycling',
                'name_fr' => 'Cycle de l’azote dans les sols et dans les cultures',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            472 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59z587z107qcn3j2vjkd',
                'name_en' => 'Soil and environmental science',
                'name_fr' => 'Science des sols et de l’environnement',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            473 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry59znjaek17s3581fdjx1',
                'name_en' => 'Soil and geospatial science',
                'name_fr' => 'Sols et science géospatiale',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            474 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a05aqdr9nb9jz1yasxc',
                'name_en' => 'Soil conservation',
                'name_fr' => 'Conservation des sols',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            475 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a0pdersk4p3bq867v7m',
                'name_en' => 'Soil fertility',
                'name_fr' => 'Fertilité des sols',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            476 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a17vb1vz49g2cd5f0fq',
                'name_en' => 'Soil fertilization',
                'name_fr' => 'Fertilisation des sols',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            477 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a1tvn9234gw9k9xpr2r',
                'name_en' => 'Soil management',
                'name_fr' => 'Gestion des sols',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            478 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a2cncbrv2na1z4xs6z1',
                'name_en' => 'Soil mite',
                'name_fr' => 'Des acariens des sols',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            479 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a2v7a4a7vs9kz7q7p5x',
                'name_en' => 'Soil nutrients',
                'name_fr' => 'Éléments nutritifs du sols',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            480 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a3dsx5nd8a8wxsgj9y8',
                'name_en' => 'Soil pedology',
                'name_fr' => 'Pédologie des sols',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            481 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a3ysdgvyrjsnjd5979t',
                'name_en' => 'Soil physical quality',
                'name_fr' => 'Qualité physique des sols',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            482 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a4f9e9a4nphv6azewde',
                'name_en' => 'Soil physics',
                'name_fr' => 'Physique des sols',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            483 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a50xd0j31c8qs19nh4z',
                'name_en' => 'Soil science',
                'name_fr' => 'Sciences des sols',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            484 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a5ga3gjynx74gwj2qs2',
                'name_en' => 'Soil-plant-atmosphere interactions',
                'name_fr' => 'Interactions sol-plante-atmosphère',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            485 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a62adzppxr8z9egafbk',
                'name_en' => 'Soil-water-crop',
                'name_fr' => 'Sol-eau-culture',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            486 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a6hrhy36w13enyhn09v',
                'name_en' => 'Solar heating',
                'name_fr' => 'Chauffage solaire',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            487 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a71j7vngbgbzzv4twvh',
                'name_en' => 'Solar-terrestrial science',
                'name_fr' => 'Sciences Soleil-Terre',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            488 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a7mmyhazf4emzqnvw9j',
                'name_en' => 'Solvent',
                'name_fr' => 'Solvant',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            489 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a872trf6yfa96qn6zy3',
                'name_en' => 'Solvents',
                'name_fr' => 'Solvants',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            490 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a8wk9ndpt79p4zgvfev',
                'name_en' => 'Songbirds',
                'name_fr' => 'Oiseaux chanteurs',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            491 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5a9mkweyb1hzy220kywz',
                'name_en' => 'Source Rocks',
                'name_fr' => 'Roches mères',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            492 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5aa4n24b33xagkj9k8kh',
                'name_en' => 'South Atlantic Ocean',
                'name_fr' => 'Océan Atlantique sud',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            493 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5aapq22jrcqys94k0e0c',
                'name_en' => 'Soyabean',
                'name_fr' => 'Soya',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            494 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5abbb3zk5qrnt6kygbjh',
                'name_en' => 'Soybean quality',
                'name_fr' => 'Qualité des graines de soya',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            495 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5ac1bhtaszbny06yb1t9',
                'name_en' => 'Space exploration',
                'name_fr' => 'Exploration spatiale',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            496 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5achtnvjwhkrq56zhr2r',
                'name_en' => 'Space Physics',
                'name_fr' => 'Physique de l\'espace',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            497 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5ad406qanqxb5h997kca',
                'name_en' => 'Space sciences',
                'name_fr' => 'Sciences de l\'espace',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            498 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5adk8b8dpjtb0fgrttb5',
                'name_en' => 'Space station',
                'name_fr' => 'Station orbitale',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            499 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5ae5m8k90tdj2hx6x6y0',
                'name_en' => 'Space technology',
                'name_fr' => 'Technologie spatiale',
                'updated_at' => '2023-10-27 16:06:01',
            ),
        ));
        \DB::table('expertises')->insert(array (
            0 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5aekpce44fpg59by7a9c',
                'name_en' => 'Space Weather',
                'name_fr' => 'Météo spatiale',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            1 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5af4yaw79dcs3gxjrj47',
                'name_en' => 'Spatial analysis',
                'name_fr' => 'Analyse spatiale',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            2 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5afft9e5sq9m4fs9q359',
                'name_en' => 'Spatial Data Standards',
                'name_fr' => 'Normalisation des données spatiales',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            3 => 
            array (
                'created_at' => '2023-10-27 16:06:01',
                'id' => '01hdry5ag43468kt9qbzgjzrag',
                'name_en' => 'Spatial Standards',
                'name_fr' => 'Normes spatiales',
                'updated_at' => '2023-10-27 16:06:01',
            ),
            4 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5agp9jwkzfke503p6nm4',
                'name_en' => 'Speciality coating',
                'name_fr' => 'Revêtements spécialisés',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            5 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5ah4wd5w1fpfpacpx4xv',
                'name_en' => 'Species at risk',
                'name_fr' => 'Espèces en péril',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            6 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5ahjpg6mfvkkg3vr4z9n',
                'name_en' => 'Species at Risk Act',
                'name_fr' => 'Loi sur les espèces en péril',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            7 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5aj0z378kf1mkre65x3a',
                'name_en' => 'Spring wheat',
                'name_fr' => 'Blé de printemps',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            8 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5ajj2bcx4eh02gfz6pzd',
                'name_en' => 'SQL',
                'name_fr' => 'SQL',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            9 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5ak24vc6x9ght4xqqxen',
                'name_en' => 'Stable isotope ecology',
                'name_fr' => 'Milieu d\'isotope stable',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            10 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5akjnkkef5vmz3mqs6hp',
                'name_en' => 'Stable isotope fingerprinting',
                'name_fr' => 'Cartographie des isotopes stables',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            11 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5am3qsb2qy1j48b55csc',
                'name_en' => 'Stable Isotopes',
                'name_fr' => 'Isotopes stable',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            12 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5amm8as795fw8qvwj8rm',
                'name_en' => 'Stand-off CBRNE measurement',
                'name_fr' => 'Détection d\'agents CBRNE à distance',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            13 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5an4cb2p5qr30bf1pk1s',
                'name_en' => 'Standards',
                'name_fr' => 'Normes',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            14 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5ankbm36hxm4ryq98nj1',
                'name_en' => 'Standards/Method Development',
                'name_fr' => 'Élaboration de normes et de méthodes',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            15 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5ap2tbvw5nme6bcbvvk9',
                'name_en' => 'Starch-based functional food bioproducts',
                'name_fr' => 'Bioproduits des alimentaires fonctionnels à base d’amidon',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            16 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5apnhvfpx9j2esysj00r',
                'name_en' => 'Starch-based functional food industrial uses',
                'name_fr' => 'Applications industrielles des alimentaires fonctionnels à base d’amidon',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            17 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5aqhgykepckrxc3e0qmf',
                'name_en' => 'Starch-based functional food ingredients',
                'name_fr' => 'Ingrédients alimentaires fonctionnels à base d’amidon',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            18 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5ar03k6tk95khbxswx35',
                'name_en' => 'Starters',
                'name_fr' => 'Ferments',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            19 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5arh9ew9qac916gkmzeb',
                'name_en' => 'Static',
                'name_fr' => 'Statique',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            20 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5as6g2g4zvy4f0p3aa2a',
                'name_en' => 'Static Global Positioning',
                'name_fr' => 'Positionnement global statique',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            21 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5asqw9dkszfh5j8sqmm8',
                'name_en' => 'Statistical Learning',
                'name_fr' => 'Apprentissage statistique',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            22 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5atbk7z47szz8nx4w036',
                'name_en' => 'Statistics',
                'name_fr' => 'Statisques',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            23 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5attbtbewtx0fn0ah4vd',
                'name_en' => 'Statistics and Economics',
                'name_fr' => 'Statistiques et économie',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            24 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5av96wpza0amy3040ay9',
            'name_en' => 'Steam assisted gravity drainage (SAGD)',
            'name_fr' => 'Drainage par gravité au moyen de vapeur (DGMV)',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            25 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5avt8ahcf0jr6b86fgs7',
                'name_en' => 'Steam Generation',
                'name_fr' => 'Production de vapeur',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            26 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5awcsjrm26gvhb3pjcr5',
                'name_en' => 'Stem cells',
                'name_fr' => 'Cellule souche',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            27 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5aww10tbqmphv6qe0q30',
                'name_en' => 'Stored product',
                'name_fr' => 'Produits entreposés',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            28 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5axb4jv9vgwhvm0r5522',
                'name_en' => 'Stormwater management',
                'name_fr' => 'Gestion des eaux de ruissellement',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            29 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5axtm52hjy3x9aj5qf7v',
                'name_en' => 'Stratigraphy',
                'name_fr' => 'Stratigraphie',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            30 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5ay905gmejtrc59y52xy',
                'name_en' => 'Stream',
                'name_fr' => 'Ruisseau',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            31 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5ays68jz39x9qvp3av7m',
                'name_en' => 'Street Dust',
                'name_fr' => 'Poussière de rue',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            32 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5azg8ceq7et1kgp6pyg4',
                'name_en' => 'Stress',
                'name_fr' => 'Stress',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            33 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b00bat7zng5dmth8hyk',
                'name_en' => 'Structural Engineering',
                'name_fr' => 'Ingénierie structurale',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            34 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b0hpsv25brv5k2qegm9',
                'name_en' => 'Structural Geology',
                'name_fr' => 'Géologie structural',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            35 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b134rnmzdgcf4vw8099',
                'name_en' => 'Structural Integrity',
                'name_fr' => 'Intégrité structurale',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            36 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b1mznc0tg8r0w3ktdj6',
            'name_en' => 'Sub-organismal level (cellular, molecular)',
            'name_fr' => 'Niveau des sous-organismes (cellulaire, moléculaire)',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            37 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b25jth6567af4f3hpbn',
                'name_en' => 'Substance Use and Abuse',
                'name_fr' => 'Consommation et abus de substances',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            38 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b2mnn9r16d9mhkg11d1',
                'name_en' => 'Subsurface water inputs',
                'name_fr' => 'Alimentation en eau souterraine',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            39 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b35whpdxemsng78r5g1',
                'name_en' => 'Supervised Learning',
                'name_fr' => 'Apprentissage supervisé',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            40 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b3mgk3sw8wajrw6ss6c',
                'name_en' => 'Surface analysis',
                'name_fr' => 'Analyse de surface',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            41 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b44qexex64gzr167dbx',
                'name_en' => 'Surficial Deposits',
                'name_fr' => 'Dépôts de surface',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            42 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b4my9swa4te5cv43hxy',
                'name_en' => 'Surveying',
                'name_fr' => 'Arpentage',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            43 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b523jvybwgdhg8yrs3g',
                'name_en' => 'Sustainability',
                'name_fr' => 'Durabilité',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            44 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b5njs4mc80rj6wgdnp0',
                'name_en' => 'Sustainable agriculture',
                'name_fr' => 'Agriculture durable',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            45 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b67t6m4f8pc6xr1w8je',
                'name_en' => 'Sustainable Development',
                'name_fr' => 'Développement durable',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            46 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b6pdt5d0grp83jqht1k',
                'name_en' => 'Sustainable Environmental Health',
                'name_fr' => 'Hygiène de l\'environnement durable',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            47 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b74n1pfya0d7hc5tgad',
                'name_en' => 'Sustainable production systems: HOLOS',
                'name_fr' => 'Systèmes de production durables : HOLOS',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            48 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b7pj9mw7bhcm0k6cxqx',
                'name_en' => 'Sustainable systems agronomy',
                'name_fr' => 'Agronomie des systèmes durables',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            49 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b859za3n619ge4gchbg',
                'name_en' => 'Symbionts',
                'name_fr' => 'Symbionte',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            50 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b8vsb7jb22kpynwmg6j',
                'name_en' => 'Symbiotic nitrogen-fixing bacteria',
                'name_fr' => 'Bactéries symbiotiques fixatrices d’azote ',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            51 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b9c0j27qdppjk60402n',
                'name_en' => 'Systematics',
                'name_fr' => 'Systématique',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            52 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5b9yamd3nqxk18mgxfvj',
                'name_en' => 'Systems integration',
                'name_fr' => 'Intégration des systèmes',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            53 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5bae0jvf3vb2e053tetr',
            'name_en' => 'Tachinid (parasitic) fly',
            'name_fr' => 'Des tachinaires (parasitoïdes)',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            54 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5bax5pen2qa7qvaz3r6x',
                'name_en' => 'Taiga',
                'name_fr' => 'Taïga',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            55 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5bbj9tk18x0vfyy7t35b',
                'name_en' => 'Tailing',
                'name_fr' => 'Résidus miniers',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            56 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5bc17fkx80mjbnmnt8xe',
                'name_en' => 'Taste and odour',
                'name_fr' => 'Goût et odeur',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            57 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5bcgv30gprer33tj68qe',
                'name_en' => 'Technoeconomic analysis',
                'name_fr' => 'Analyse technoéconomique',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            58 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5bczsgfxrjq1k4sc3qqn',
                'name_en' => 'Technological innovation',
                'name_fr' => 'Innovation technologique',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            59 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5bdfpa9bxapq2ncmc0hj',
                'name_en' => 'Technologies',
                'name_fr' => 'Technologies',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            60 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5be0c3n86sptr7xmazfc',
                'name_en' => 'Technology',
                'name_fr' => 'Technologie',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            61 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5bejgdjb8n1ac8hcxstg',
                'name_en' => 'Technology policy',
                'name_fr' => 'Politique technologique',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            62 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5bf4yxydcpwz263s063c',
                'name_en' => 'Technology transfer',
                'name_fr' => 'Transfert technologique',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            63 => 
            array (
                'created_at' => '2023-10-27 16:06:02',
                'id' => '01hdry5bfm5dxdx930m7mdcw57',
                'name_en' => 'Tectonics',
                'name_fr' => 'Tectoniques',
                'updated_at' => '2023-10-27 16:06:02',
            ),
            64 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bg5hanb5psd1mymmsv8',
                'name_en' => 'Telecommunications',
                'name_fr' => 'Télécommunications',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            65 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bgnfnh2jhxxry19pq8j',
                'name_en' => 'Telecommunications networks',
                'name_fr' => 'Réseau de télécommunications',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            66 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bh4b933a8ppek39x77d',
                'name_en' => 'Telecommunications policy',
                'name_fr' => 'Politique en matière de télécommunications',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            67 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bhh92zx35j2dnc230hy',
                'name_en' => 'Teleconferencing',
                'name_fr' => 'Téléconférence',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            68 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bj3p56nfdq48g10hecf',
                'name_en' => 'Teleconnection',
                'name_fr' => 'Téléconnexion',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            69 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bjkgam4c78y2q9jss5f',
                'name_en' => 'Telehealth',
                'name_fr' => 'Télésanté',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            70 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bk352hx5edmeam5gz13',
                'name_en' => 'Telemedicine',
                'name_fr' => 'Télémédecine',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            71 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bkkpyn9wghnmsng5cy5',
                'name_en' => 'Telephones',
                'name_fr' => 'Téléphone',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            72 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bm38qr2ny47s95wb5h9',
                'name_en' => 'Television',
                'name_fr' => 'Télévision',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            73 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bmk24wzf5rcwmnwn7qd',
                'name_en' => 'Terrestrial molluscs',
                'name_fr' => 'Mollusques terrestres',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            74 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bmze9tvt9k5bxzxwdkb',
                'name_en' => 'Testing',
                'name_fr' => 'Essai',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            75 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bngc04bwvmwg381fkt6',
                'name_en' => 'Text Analytics',
                'name_fr' => 'Analytique du texte',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            76 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bpcsq5kpfaee2m18h1t',
                'name_en' => 'Text Collection',
                'name_fr' => 'Collection de texte',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            77 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bpt9cze2mtaam3gxvby',
                'name_en' => 'Text Processing',
                'name_fr' => 'Traitement de texte',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            78 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bq9hmj6r4fkd4y03xaf',
                'name_en' => 'Therapeutics',
                'name_fr' => 'Thérapeutique',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            79 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bqrj6jb5dcabrpb6ab6',
                'name_en' => 'Thermal Energy Storage',
                'name_fr' => 'Stockage de l\'énergie thermique',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            80 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5br58prk8ddpe2qddd7b',
                'name_en' => 'Thermal Maturity',
                'name_fr' => 'Maturité thermique',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            81 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5brp9kfp27wa0j2av78e',
                'name_en' => 'Thermal Spray',
                'name_fr' => 'Projection thermique',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            82 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bsa2r3ba8q0bxfwtvvk',
                'name_en' => 'Thermometry',
                'name_fr' => 'Thermométrie',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            83 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bstn7zcy3w5mmtbpeky',
                'name_en' => 'Tides and Water Levels',
                'name_fr' => 'Marées et niveaux d\'eau',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            84 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bt72yecjr0abxwsb5qb',
                'name_en' => 'Tides, Water Levels, Storm Surge and Erosion Studies',
                'name_fr' => 'Études sur les marées, les niveaux d’eau, les ondes de tempête et l\'érosion',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            85 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5btm1jwt62nv9h17n7bd',
                'name_en' => 'Time and Frequency',
                'name_fr' => 'Temps et fréquences',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            86 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bv72560wjgnx1dc5ac8',
                'name_en' => 'Tobacco',
                'name_fr' => 'Tabac',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            87 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bvqs7pfdev9eykqpchq',
                'name_en' => 'Tools',
                'name_fr' => 'Outillage',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            88 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bw7srre1gp8dnm6rpwe',
                'name_en' => 'Top predator species',
                'name_fr' => 'Espèces de prédateurs de niveau trophique supérieur',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            89 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bwp18j9670vnz44vh6e',
                'name_en' => 'Topographic',
                'name_fr' => 'Topographique',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            90 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bx5de5qs76cfhq2mmqn',
                'name_en' => 'Topography',
                'name_fr' => 'Topographie',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            91 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bxn8xb63086ev0et84y',
                'name_en' => 'Toponymy',
                'name_fr' => 'Toponymie',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            92 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5by2wz4n3vf9qdkbh3bf',
                'name_en' => 'Toxicity',
                'name_fr' => 'Toxicité',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            93 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bykm7xc29711pc7f6xx',
                'name_en' => 'Toxicogenomics',
                'name_fr' => 'Toxicogénomique',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            94 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bz2vkhtvv27np396waj',
                'name_en' => 'Toxicology',
                'name_fr' => 'Toxicologie',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            95 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5bzkw0z8295xvv5qn7mp',
                'name_en' => 'Toxins',
                'name_fr' => 'Toxines',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            96 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c04fn03b1bs5hhzrdv0',
                'name_en' => 'Trace-metal dynamics',
                'name_fr' => 'Dynamique des éléments traces métalliques',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            97 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c0ns226vtr5k85rtw2g',
                'name_en' => 'Track maintenance planning',
                'name_fr' => 'Planification de l\'entretien des voies ferrées',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            98 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c15dvj1m77bjk1axvpk',
                'name_en' => 'Training',
                'name_fr' => 'Formation',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            99 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c1pj77wmfz9mw3gwywm',
                'name_en' => 'Training Software',
                'name_fr' => 'Logiciels de formation',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            100 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c26pr2qpz69nw58a86r',
                'name_en' => 'Transborder data flow',
                'name_fr' => 'Flux transfrontière de données',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            101 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c2s0ycr00ma4yms378p',
                'name_en' => 'Transformation',
                'name_fr' => 'Transformation',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            102 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c38nrs4845wdf49rgff',
                'name_en' => 'Translation',
                'name_fr' => 'Traduction',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            103 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c3r13vvq3wc0qt57jva',
                'name_en' => 'Transparency',
                'name_fr' => 'Transparence',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            104 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c4844dv7mnmg1084me4',
                'name_en' => 'Transportation',
                'name_fr' => 'Transport',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            105 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c4sccgvngm7dsfv2rex',
                'name_en' => 'Travel and Other Administrative Services',
                'name_fr' => 'Voyage et autres services administratifs',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            106 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c59br8pfykys3yt0ggc',
                'name_en' => 'Tree Breeding',
                'name_fr' => 'Amélioration des arbres',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            107 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c5v95jvvgp2vwwapypp',
                'name_en' => 'Tree development',
                'name_fr' => 'Développement des arbres',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            108 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c6cyvfhsy6vya4dh05f',
                'name_en' => 'Tree fruit evaluation',
                'name_fr' => 'Évaluation des fruits de verger',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            109 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c6wkmr7jr328ypsrgsp',
                'name_en' => 'Trees',
                'name_fr' => 'Arbres',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            110 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c7dcpvy2bc3877n2zq3',
                'name_en' => 'Trends and variability',
                'name_fr' => 'Tendances et variabilité',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            111 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c7y2ze8m1zqgxrps9m9',
                'name_en' => 'Triage',
                'name_fr' => 'Triage',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            112 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c8es4f3acwmyspwb63k',
                'name_en' => 'Triage Software',
                'name_fr' => 'Logiciels de triage',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            113 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c90j80yafwr0r2bqnc6',
                'name_en' => 'Triticale',
                'name_fr' => 'Triticale',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            114 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5c9htq8dezg1b3n5d7ep',
                'name_en' => 'Tsunamis',
                'name_fr' => 'Tsunami',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            115 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5ca00s65cxtk1crhn0hz',
                'name_en' => 'Tundra',
                'name_fr' => 'Toundra',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            116 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5cahxvgprt8f3wh29h2a',
                'name_en' => 'Ultra-fast laser technology',
                'name_fr' => 'Technologie laser ultrarapide',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            117 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5cb2n44pcnvvasxr2gqe',
                'name_en' => 'Unconventional Energy',
                'name_fr' => 'Énergie non-conventionnelle',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            118 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5cbjbabt1ry8xckbbbxd',
                'name_en' => 'Unsupervised Learning',
                'name_fr' => 'Apprentissage non supervisé',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            119 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5cc6r562e81xk24wrhcr',
                'name_en' => 'Upgrading',
                'name_fr' => 'Valorisation',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            120 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5ccmr9f7x9je1e15tz99',
                'name_en' => 'Uranium',
                'name_fr' => 'Uranium',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            121 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5cd24fm2bthb9v8s6fqr',
                'name_en' => 'Urban',
                'name_fr' => 'Urbaine',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            122 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5cdk3xtz81znma3g3fka',
                'name_en' => 'Urban ecosystems',
                'name_fr' => 'Écosystèmes urbains',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            123 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5ce4p6h0q1666n13y7wq',
                'name_en' => 'Urban Environments',
                'name_fr' => 'Milieu urbain',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            124 => 
            array (
                'created_at' => '2023-10-27 16:06:03',
                'id' => '01hdry5cen8hapc0kt429maajs',
                'name_en' => 'Urban Forestry',
                'name_fr' => 'Foresterie urbaine',
                'updated_at' => '2023-10-27 16:06:03',
            ),
            125 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cf59m6cyc058av619bn',
                'name_en' => 'Urban Meteorology',
                'name_fr' => 'Météorologie urbaine',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            126 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cfmggdecynkdvxqnf6q',
                'name_en' => 'UV radiation',
                'name_fr' => 'Rayons UV',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            127 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cg66120yre1hac6xjgx',
                'name_en' => 'Vaccine',
                'name_fr' => 'Vaccins',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            128 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cgqa67rccg6kv35dajy',
                'name_en' => 'Value added processing',
                'name_fr' => 'Transformation à valeur ajoutée',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            129 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5ch846ddngrrdmd30e4c',
                'name_en' => 'Vancouver Island Shelf',
                'name_fr' => 'Plateau de l\'île de Vancouver',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            130 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5chm7cfnf23y6qc447h5',
                'name_en' => 'Vascular plants',
                'name_fr' => 'Plantes vasculaires',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            131 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cj2fjwjd83zqweyn4eb',
                'name_en' => 'Vegetable cropping systems',
                'name_fr' => 'Systèmes de culture de légumes',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            132 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cjja17ssmmt5kr9q83k',
                'name_en' => 'Vegetation',
                'name_fr' => 'Végétation',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            133 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5ck2rg275tpzegqszd49',
                'name_en' => 'Vegetation Management',
                'name_fr' => 'Aménagement de la végétation',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            134 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cknp3y95gp0p5bhnbxy',
                'name_en' => 'Vehicle accident and incident resolution',
                'name_fr' => 'Résolution d\'accidents et d\'incidents de véhicule',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            135 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cm65wr4s9xqdpsqdmy5',
                'name_en' => 'Vehicle characterization',
                'name_fr' => 'Caractérisation de véhicules',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            136 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cmqger3byfjy89mv7nf',
                'name_en' => 'Vermiform',
                'name_fr' => 'Vermiformes',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            137 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cng18qzycapfmmx7b5h',
                'name_en' => 'Vertical Reference Systems',
                'name_fr' => 'Systèmes de référence verticaux',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            138 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cp1xp50p0j16cgj3eq5',
                'name_en' => 'Veterinary diagnostics',
                'name_fr' => 'Diagnostic vétérinaire',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            139 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cphv9rw6seh52qs30we',
                'name_en' => 'Veterinary Drugs',
                'name_fr' => 'Médicaments à usage vétérinaire',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            140 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cq0dh4wn3nvn88vpa8n',
                'name_en' => 'Virology',
                'name_fr' => 'Virologie',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            141 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cqhhjregd38qmx15e1b',
                'name_en' => 'Virtual Assistants',
                'name_fr' => 'Assistants virtuels',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            142 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cr2jkfvxkjwxqcamgjh',
                'name_en' => 'Virus',
                'name_fr' => 'Virus',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            143 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5crmyftyvh96ka8sv67d',
                'name_en' => 'Virus-like organisms',
                'name_fr' => 'Organismes apparentés à des virus',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            144 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cs6g4cj3rhjf0gwfy4z',
                'name_en' => 'Vitamin metabolism in cows',
                'name_fr' => 'Métabolisme des vitamines chez la vache',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            145 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5csr8djnem1ynvxcc90g',
                'name_en' => 'Vitamin metabolism in swine',
                'name_fr' => 'Métabolisme des vitamines chez le porc',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            146 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5ct8720g3fayqt1atkhk',
                'name_en' => 'Viticulture',
                'name_fr' => 'Viticulture',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            147 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5ctsatz49wstfptrcatr',
                'name_en' => 'Volatile Organic Compounds',
                'name_fr' => 'Composés organiques peu volatils',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            148 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cvcce8qz3gamtjsv7h2',
                'name_en' => 'Volcanoes',
                'name_fr' => 'Volcan',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            149 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cvvkaf2b40z2ts0b5nr',
                'name_en' => 'Volcanology',
                'name_fr' => 'Volcanologie',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            150 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cwft8qvwgezcbgtgacv',
                'name_en' => 'Vulnerability thresholds',
                'name_fr' => 'Seuils de vulnérabilité',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            151 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cwxaqxxzn2qv3gezfbt',
                'name_en' => 'Waste heat capture and utilization',
                'name_fr' => 'Capture et utilisation de la chaleur résiduelle',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            152 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cxasg7fcsvdjkcb82fj',
                'name_en' => 'Waste Management',
                'name_fr' => 'Gestion des déchets',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            153 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cxsqfy40kecaqdbpmm6',
                'name_en' => 'Waste-to-Resource',
                'name_fr' => 'Déchets à ressources',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            154 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cy86ph5qeje7pwcshg4',
                'name_en' => 'Wastewater',
                'name_fr' => 'Eaux usées',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            155 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cyt27sy3hwpcwkt6da8',
                'name_en' => 'Water',
                'name_fr' => 'Eau',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            156 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5cz8cp641rx2b8qrfyjd',
                'name_en' => 'Water and nutrient management',
                'name_fr' => 'Gestion de l’eau et des éléments nutritifs',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            157 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5czrwzda32hemy9231qq',
                'name_en' => 'Water management',
                'name_fr' => 'Gestion de l\'eau',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            158 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d0bdj30jsfkrs5p34vj',
                'name_en' => 'Water Quality',
                'name_fr' => 'Qualité de l\'eau',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            159 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d0t710qbm25mynnhz3j',
                'name_en' => 'Water quantity',
                'name_fr' => 'Quantité d\'eau',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            160 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d18vvpbwd7bs2mfs86m',
                'name_en' => 'Water resource',
                'name_fr' => 'Ressources hydriques',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            161 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d1pbmh0p2p260wawbv8',
                'name_en' => 'Water resource management',
                'name_fr' => 'Gestion des ressources en eau',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            162 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d28vm5tvtm16q2qe2zc',
                'name_en' => 'Water Treatment',
                'name_fr' => 'Traitement de l\'eau',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            163 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d2pkzar1p3kmjt9c1k4',
                'name_en' => 'Waterbirds',
                'name_fr' => 'Oiseaux aquatiques',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            164 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d36x8jvbaepz0bwd9t4',
                'name_en' => 'Waterfowl',
                'name_fr' => 'Sauvagine',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            165 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d3m3ag5709avayzc509',
                'name_en' => 'Watersheds',
                'name_fr' => 'Bassins hydrologiques',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            166 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d44e7bp4vxcctb6q6gw',
                'name_en' => 'Weather & Meteorology',
                'name_fr' => 'Temps et météorologie',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            167 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d4jmkw843cpd12bje34',
                'name_en' => 'Weather Modification Information Act',
                'name_fr' => 'Loi sur les renseignements en matière de modification du temps',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            168 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d52zh0nk2gydz4mv4a2',
                'name_en' => 'Web Applications',
                'name_fr' => 'Applications Web',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            169 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d5ep6r8edr97v6enjsc',
                'name_en' => 'Web Sites',
                'name_fr' => 'Site Web',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            170 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d5ymbh7sdn90cw98rv3',
                'name_en' => 'Weed',
                'name_fr' => 'Mauvaises herbes',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            171 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d6fg7aykjvy91qp8h9k',
                'name_en' => 'Weed biology',
                'name_fr' => 'Biologie des mauvaises herbes',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            172 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d6yk6daczsfedchstb9',
                'name_en' => 'Weed ecology',
                'name_fr' => 'Écologie des mauvaises herbes',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            173 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d7e16gt9yavq4nrpcjc',
                'name_en' => 'Weed science',
                'name_fr' => 'Malherbologie',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            174 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d7yzkk1hgm04yrx7gtj',
                'name_en' => 'Weevil/pest beetle',
                'name_fr' => 'Des charançons/coléoptères indésirables',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            175 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d8h4e1bwhayk17cwwwd',
                'name_en' => 'Welding',
                'name_fr' => 'Soudage',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            176 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d92tfcjtyyjp62j42ag',
                'name_en' => 'Western Canada',
                'name_fr' => 'L\'Ouest canadien',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            177 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5d9sfawysk4jfh427trr',
                'name_en' => 'Western Queen Charlotte Margin',
                'name_fr' => 'Marge ouest de la Reine Charlotte',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            178 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5daa7f2y9hd78x11hh74',
                'name_en' => 'Wetland ecology',
                'name_fr' => 'Écologie des zones humides',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            179 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5daxjnyh35bfg7x8v3sp',
                'name_en' => 'Wetlands',
                'name_fr' => 'Zones humides',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            180 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5dbd29yr3fc0584v46fg',
                'name_en' => 'Wheat',
                'name_fr' => 'Blé',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            181 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5dbwvd0t6rckktjb9hsv',
                'name_en' => 'Wheat molecular',
                'name_fr' => 'Moléculaire du blé',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            182 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5dcb42gb3cfffyn3cvfv',
                'name_en' => 'Wheel/rail interactions',
                'name_fr' => 'Interactions roue-rail',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            183 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5dcww0wehd1pp3akpew8',
                'name_en' => 'Wildlife',
                'name_fr' => 'Faune',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            184 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5ddcgxqfrb5qhwysqcnk',
                'name_en' => 'Wildlife monitoring',
                'name_fr' => 'Surveillance de la faune',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            185 => 
            array (
                'created_at' => '2023-10-27 16:06:04',
                'id' => '01hdry5ddy1cmya6ade11y5rpz',
                'name_en' => 'Wildlife Populations',
                'name_fr' => 'Population faunique',
                'updated_at' => '2023-10-27 16:06:04',
            ),
            186 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5def49t2y63ka1tjesbd',
                'name_en' => 'Wind and wave analysis',
                'name_fr' => 'Analyse des vents et des vagues',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            187 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5df0zkngcyx9wver00rr',
                'name_en' => 'Windsor-Quebec Corridor',
                'name_fr' => 'Corridor-Québec-Windsor',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            188 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dfhbyfv83bcvm1z1mqw',
                'name_en' => 'Wine',
                'name_fr' => 'Vin',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            189 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dg0tbq81jx3ks05nxjw',
                'name_en' => 'Winter wheat',
                'name_fr' => 'Blé d\'hiver',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            190 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dgg7775070t6dmc5phb',
                'name_en' => 'Women\'s Health',
                'name_fr' => 'Santé des femmes',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            191 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dgwzgp3r4sd8pcm489p',
                'name_en' => 'Wood Fibre',
                'name_fr' => 'Fibre de bois',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            192 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dhapxg68q6sfx50kzwe',
                'name_en' => 'Wood Preservation',
                'name_fr' => 'Préservation du bois',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            193 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dht9qxkevgx3zafhq27',
                'name_en' => 'Wood Supply',
                'name_fr' => 'Approvisionnement en bois',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            194 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dj8e0mrn6bva01n9hrf',
                'name_en' => 'Wood-based Panels',
                'name_fr' => 'Panneaux en fibre de bois',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            195 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5djr0xms3sf93cgw0yhw',
                'name_en' => 'Workplace Hazardous Materials Information System',
                'name_fr' => 'Système d\'information sur les matières dangereuses utilisées au travail',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            196 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dk8ab1ht72nd23d58f1',
                'name_en' => 'Workplace Health',
                'name_fr' => 'Santé en milieu de travail',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            197 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dkqyb5b93rq7g6x1van',
                'name_en' => 'Xenotransplantation',
                'name_fr' => 'Xénotransplantation',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            198 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dm64t4ahs4zd7ex7x2z',
                'name_en' => 'Yeast',
                'name_fr' => 'Levures',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            199 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dmwwkfzx9rspbd4a5ff',
                'name_en' => 'Zoonosis',
                'name_fr' => 'Zoonoses',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            200 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dnbmtc8h382ezz0r6zm',
                'name_en' => 'Zooplankton',
                'name_fr' => 'Zooplancton',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            201 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dntxqndgtbv3c8pv6tr',
                'name_en' => 'Zoosporic fungi',
                'name_fr' => 'Des champignons zoosporiques',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            202 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dp8z2rart34j29yyfjn',
                'name_en' => 'Shellfish',
                'name_fr' => 'Mollusques et crustacés',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            203 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dpp2c530dvb5nbz944t',
                'name_en' => 'Wild / Cultured interactions',
                'name_fr' => 'Interactions entre les espèces sauvages et les espèces cultivées',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            204 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dq8msve6ctj1awc3p8m',
                'name_en' => 'Biology and Ecology',
                'name_fr' => 'Biologie et écologie',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            205 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dr6rv9yds50m10100za',
                'name_en' => 'Benthic Ecology',
                'name_fr' => 'Écologie benthique',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            206 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5drpga405mtrd5zm48gv',
                'name_en' => 'Fish Biology',
                'name_fr' => 'Biologie des poissons',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            207 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dsetyb62fabw5a9yxvk',
                'name_en' => 'Invertebrate Biology',
                'name_fr' => 'Biologie des invertébrés',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            208 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dt6jyvtq6vrnkbh0sz3',
                'name_en' => 'Harmful Algal Blooms',
                'name_fr' => 'Efflorescences algales nuisibles',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            209 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dtnhagfm9wb7b6hraw2',
                'name_en' => 'Marine Mammal Biology',
                'name_fr' => 'Biologie des mammifères marins',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            210 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dv5mtndna1q0rc185af',
                'name_en' => 'Freshwater Ecology',
                'name_fr' => 'Écologie des eaux douces',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            211 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dvnybbrnzmzt9vz5vne',
                'name_en' => 'Ecosystems Features',
                'name_fr' => 'Caractéristiques des écosystèmes',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            212 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dw61dc5kh8va8qce9wn',
                'name_en' => 'Marine Protected Areas',
                'name_fr' => 'Zones marines protégées',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            213 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dwqyk6s0ej7qdz4bwh2',
                'name_en' => 'Cold-water corals and sponges',
                'name_fr' => 'Coraux et éponges d\'eau froide',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            214 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dxb7xyh1pjnp69ghcgz',
                'name_en' => 'Hydrothermal vents',
                'name_fr' => 'Foyers hydrothermaux',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            215 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dxttbncjyx11hxesj8q',
                'name_en' => 'Seamounts',
                'name_fr' => 'Monts sous-marins',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            216 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dyaad6g4767rwzfgq6h',
                'name_en' => 'Eelgrass',
                'name_fr' => 'La zostère',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            217 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dyr6c68qavnhk491h2z',
                'name_en' => 'Fisheries and Stock Groups',
                'name_fr' => 'Pêcheries et groupes de stocks',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            218 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dz9awdkq8a7kvwkagc7',
                'name_en' => 'Management Strategies',
                'name_fr' => 'Stratégies de gestion',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            219 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5dzrsrc7f7j7gh356dkg',
                'name_en' => 'Stock Assessments',
                'name_fr' => 'Évaluation des stocks',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            220 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e07h2qry1k5ncpwdmt2',
                'name_en' => 'Crustaceans',
                'name_fr' => 'Crustacés',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            221 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e0qzzpn1s0kredgxpf8',
                'name_en' => 'Groundfish',
                'name_fr' => 'Poissons de fond',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            222 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e190sq1jjgs6rn8652j',
                'name_en' => 'Large pelagics ',
                'name_fr' => 'Grands pélagiques ',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            223 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e1rf0jaaf3tn9vfa5vk',
                'name_en' => 'Other Stock Groups',
                'name_fr' => 'Autres groupes d\'actions',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            224 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e2jv3djrbseyxzwrfek',
                'name_en' => 'Salmonids',
                'name_fr' => 'Salmonidés',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            225 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e32hw2s5ff46kk7s9xh',
                'name_en' => 'Small pelagics',
                'name_fr' => 'Petits pélagiques',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            226 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e3r1bs1p434jnhcvpre',
                'name_en' => 'Survey design',
                'name_fr' => 'Conception de l\'enquête',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            227 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e4arhj2zmac8t81g5fn',
                'name_en' => 'Mollusks',
                'name_fr' => 'Mollusques',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            228 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e4ny22xh7088j26gz8h',
                'name_en' => 'Designatable Units',
                'name_fr' => 'Unités désignables',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            229 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e5c74w9nrqswzxkm1ks',
                'name_en' => 'Introgression',
                'name_fr' => 'Introgression',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            230 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e5w8fm5p0y5drvp6e8f',
                'name_en' => 'Stock Structure',
                'name_fr' => 'Structure du stock',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            231 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e6c67ev6my5e2xrnx8w',
                'name_en' => 'Estuarine',
                'name_fr' => 'Estuaire',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            232 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e6wtcvka78z5bzz3dzd',
                'name_en' => 'Modeling, Statistics and Bioinformatics',
                'name_fr' => 'Modélisation, statistiques et bioinformatique',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            233 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e7edykmp4g4dpzm2tjm',
                'name_en' => 'Current modeling',
                'name_fr' => 'Modélisation des courants',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            234 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e7w7j900h25hr7qrb3a',
                'name_en' => 'Fisheries modeling',
                'name_fr' => 'Modélisation de la pêche',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            235 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e8dj064nczg55j28efh',
                'name_en' => 'Oceans modeling',
                'name_fr' => 'Modélisation des océans',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            236 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e8xszjzzdr0c44qfckb',
                'name_en' => 'Quantitative modeling',
                'name_fr' => 'Modélisation quantitative',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            237 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e99zbbw6apdzcn8ta74',
                'name_en' => 'Spatial modeling',
                'name_fr' => 'Modélisation spatiale',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            238 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5e9qq669dhjqzvteg44r',
                'name_en' => 'Spatiotemporal statistics',
                'name_fr' => 'Statistiques spatio-temporelles',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            239 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5ea7dr8d1tzm4k60sfgq',
                'name_en' => 'Ocean chemistry',
                'name_fr' => 'Chimie des océans',
                'updated_at' => '2023-10-27 16:06:05',
            ),
            240 => 
            array (
                'created_at' => '2023-10-27 16:06:05',
                'id' => '01hdry5eas6dwrgrf8g4gxfgc4',
                'name_en' => 'Physical chemistry',
                'name_fr' => 'Chimie physique',
                'updated_at' => '2023-10-27 16:06:05',
            ),
        ));
        
        
    }
}