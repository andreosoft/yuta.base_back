<?php
namespace base\libraries;

trait TraitDate {

    public static function times_in_day() {
        return [
            1 => '06-07',
            2 => '07-08',
            3 => '08-09',
            4 => '09-10',
            5 => '10-11',
            6 => '11-12',
            7 => '12-13',
            8 => '13-14',
            9 => '14-15',
            10 => '15-16',
            11 => '16-17',
            12 => '17-18',
            13 => '18-19',
            14 => '19-20',
            15 => '20-21',
            16 => '21-22',
            17 => '22-23',
            18 => '23-24',
            19 => '00-01',
            20 => '01-02',
            21 => '02-03',
            22 => '03-04',
            23 => '04-05',
            24 => '05-06',
        ];
    }

    public static function days_in_week() {
        return [1, 2, 3, 4, 5, 6, 7];
    }

    public static function times_in_month() {
        $ths_mond = strtotime('monday this week');
        return [
            1 => $ths_mond + 604800,
            2 => $ths_mond + 691200,
            3 => $ths_mond + 777600,
            4 => $ths_mond + 864000,
            5 => $ths_mond + 950400,
            6 => $ths_mond + 1036800,
            7 => $ths_mond + 1123200,
            8 => $ths_mond + 1209600,
            9 => $ths_mond + 1296000,
            10 => $ths_mond + 1382400,
            11 => $ths_mond + 1468800,
            12 => $ths_mond + 1555200,
            13 => $ths_mond + 1641600,
            14 => $ths_mond + 1728000,
            15 => $ths_mond + 1814400,
            16 => $ths_mond + 1900800,
            17 => $ths_mond + 1987200,
            18 => $ths_mond + 2073600,
            19 => $ths_mond + 2160000,
            20 => $ths_mond + 2246400,
            21 => $ths_mond + 2332800,
            22 => $ths_mond + 2419200,
            23 => $ths_mond + 2505600,
            24 => $ths_mond + 2592000,
            25 => $ths_mond + 2678400,
            26 => $ths_mond + 2764800,
            27 => $ths_mond + 2851200,
            28 => $ths_mond + 2937600,
            29 => $ths_mond + 3024000,
            30 => $ths_mond + 3110400,
            31 => $ths_mond + 3196800
        ];
    }
    
    public static function rus_week() {
        return [1 => 'Пн', 2 => 'Вт', 3 => 'Ср', 4 => 'Чт', 5 => 'Пт', 6 => 'Сб', 7 => 'Вс'];
    }
    
    public static function time_hours() {
        return [1 => 21600, 2 => 25200, 3 => 28800, 4 => 32400, 5 => 36000, 6 => 39600, 7 => 43200,
                8 => 46800, 9 => 50400, 10 => 54000, 11 => 57600, 12 => 61200, 13 => 64800, 14 => 48400, 15 => 72000,
                16 => 75600, 17 => 79200, 18 => 82800, 19 => 0, 20 => 3600, 21 => 7200, 22 => 10800, 23 => 14400,
                24 => 18000];
    }

}
