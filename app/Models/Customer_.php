<?php

namespace App\Models;

class Customer
{
    private static $test = [
        [
            "no_lab" => "123456",
            "nama" => "Fajar Mukhlis Imananda",
            "tanggal" => "Senin, 14/02/2022",
            "jenis_kelamin" => "Laki-Laki",
            "usia" => "21 Tahun 0 Bulan",
            "dokter_pengirim" => "Dr. Muhammad Syahrul Majid, S.Kom."
        ],
        [
            "no_lab" => "789101",
            "nama" => "Oki Riski Fatulloh",
            "tanggal" => "Senin, 14/02/2022",
            "jenis_kelamin" => "Laki-Laki",
            "usia" => "21 Tahun 3 Bulan",
            "dokter_pengirim" => "Dr. Muhammad Syahrul Majid, S.Kom."
        ],
        [
            "no_lab" => "112131",
            "nama" => "Muhammad Novani Fajar",
            "tanggal" => "Senin, 14/02/2022",
            "jenis_kelamin" => "Laki-Laki",
            "usia" => "21 Tahun 1 Bulan",
            "dokter_pengirim" => "Dr. Muhammad Syahrul Majid, S.Kom."
        ],
        [
            "no_lab" => "789101",
            "nama" => "Oki Riski Fatulloh",
            "tanggal" => "Senin, 14/02/2022",
            "jenis_kelamin" => "Laki-Laki",
            "usia" => "21 Tahun 3 Bulan",
            "dokter_pengirim" => "Dr. Muhammad Syahrul Majid, S.Kom."
        ],
        [
            "no_lab" => "789101",
            "nama" => "Oki Riski Fatulloh",
            "tanggal" => "Senin, 14/02/2022",
            "jenis_kelamin" => "Laki-Laki",
            "usia" => "21 Tahun 3 Bulan",
            "dokter_pengirim" => "Dr. Muhammad Syahrul Majid, S.Kom."
        ]
    ];

    private static function dataToCollection()
    {
        return collect(self::$test);
    }

    public static function getTestDataAll()
    {
        $testData = static::dataToCollection();
        return $testData->all();
    }

    public static function getTestDataWhere($name)
    {
        $testData = static::dataToCollection();
        $testData = $testData->where('nama', $name);
        return $testData->all();
    }
}
