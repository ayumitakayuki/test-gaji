<?php

namespace App\Services;

class PenilaianKinerjaService
{
    public function konversiHurufKeNilai(?string $huruf): int
    {
        return match (strtoupper((string) $huruf)) {
            'A' => 100,
            'B' => 85,
            'C' => 70,
            'D' => 55,
            'E' => 40,
            default => 0,
        };
    }

    public function hitung(array $data): array
    {
        $fields = [
            'disiplin',
            'tanggung_jawab',
            'kualitas_kerja',
            'produktivitas',
            'kerja_sama',
            'inisiatif',
        ];

        $total = 0;
        $count = 0;

        foreach ($fields as $field) {
            $nilai = $this->konversiHurufKeNilai($data[$field] ?? null);

            if ($nilai > 0) {
                $total += $nilai;
                $count++;
            }
        }

        $nilaiAkhir = $count > 0
            ? round($total / $count, 2)
            : 0;

        if ($nilaiAkhir >= 90) {
            $predikat = 'A';
        } elseif ($nilaiAkhir >= 80) {
            $predikat = 'B';
        } elseif ($nilaiAkhir >= 70) {
            $predikat = 'C';
        } elseif ($nilaiAkhir >= 60) {
            $predikat = 'D';
        } else {
            $predikat = 'E';
        }

        return [
            'nilai_akhir' => $nilaiAkhir,
            'predikat' => $predikat,
            'nominal_kenaikan_gaji' => 0,
        ];
    }
}