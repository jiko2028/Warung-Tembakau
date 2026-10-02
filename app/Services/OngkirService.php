<?php

namespace App\Services;

/**
 * OngkirService
 * 
 * Service untuk perhitungan ongkos kirim manual tanpa API pihak ketiga.
 * Base location toko: Jogja (Yogyakarta)
 * 
 * Perhitungan wilayah:
 * - Dalam Kota: Pengiriman dalam wilayah Jogja/Yogyakarta
 * - Luar Kota: Pengiriman ke luar Jogja tapi masih dalam Pulau Jawa
 * - Luar Pulau: Pengiriman ke luar Pulau Jawa
 */
class OngkirService
{
    /**
     * Hitung ongkos kirim berdasarkan wilayah dan total berat
     *
     * @param string $wilayah 'dalam_kota', 'luar_kota', atau 'luar_pulau'
     * @param int $totalBerat Total berat dalam gram
     * @return array ['ongkir_wilayah' => int, 'ongkir_berat' => int, 'total_ongkir' => int]
     */
    public function hitungOngkir($wilayah, $totalBerat)
    {
        // Hitung ongkir berdasarkan wilayah
        $ongkirWilayah = $this->hitungOngkirWilayah($wilayah);

        // Hitung ongkir berdasarkan berat
        $ongkirBerat = $this->hitungOngkirBerat($totalBerat);

        // Total ongkir
        $totalOngkir = $ongkirWilayah + $ongkirBerat;

        return [
            'ongkir_wilayah' => $ongkirWilayah,
            'ongkir_berat' => $ongkirBerat,
            'total_ongkir' => $totalOngkir,
        ];
    }

    /**
     * Hitung ongkir berdasarkan wilayah pengiriman
     *
     * @param string $wilayah
     * @return int
     */
    private function hitungOngkirWilayah($wilayah)
    {
        switch ($wilayah) {
            case 'dalam_kota':
                return 10000;
            case 'luar_kota':
                return 15000;
            case 'luar_pulau':
                return 20000;
            default:
                return 10000;
        }
    }

    /**
     * Hitung ongkir berdasarkan total berat
     *
     * @param int $totalBerat Total berat dalam gram
     * @return int
     */
    private function hitungOngkirBerat($totalBerat)
    {
        // Convert ke kg
        $beratKg = $totalBerat / 1000;

        if ($beratKg <= 1) {
            return 5000;
        } else {
            return 10000;
        }
    }
}
