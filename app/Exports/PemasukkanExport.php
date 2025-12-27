<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PemasukkanExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithColumnFormatting
{
    protected $datefrForm;
    protected $datetoForm;
    protected $jenisdok;
    protected $comp_name;
    protected $results;

    public function __construct($datefrForm, $datetoForm, $jenisdok, $comp_name)
    {
        $this->datefrForm = $datefrForm;
        $this->datetoForm = $datetoForm;
        $this->jenisdok   = $jenisdok;
        $this->comp_name  = $comp_name;

        $this->results = DB::table('pemasukan_dokumen')
            ->whereBetween('dptanggal', [$datefrForm, $datetoForm])
            ->where('stat', 1)
            ->when($jenisdok !== 'All', function ($q) use ($jenisdok) {
                $q->where('jenis_dokumen', $jenisdok);
            })
            ->orderBy('dpnomor')
            ->orderBy('dptanggal')
            ->orderBy('bpbnomor')
            ->get();
    }

    public function collection(): Collection
    {
        $data = collect();

        // ===== TITLE =====
        $data->push(['LAPORAN PERTANGGUNG JAWABAN PEMASUKAN DOKUMEN', '', '', '', '', '', '', '', '', '', '', '', '']);
        $data->push([$this->comp_name, '', '', '', '', '', '', '', '', '', '', '', '']);

        $datefr = date('d/m/Y', strtotime($this->datefrForm));
        $dateto = date('d/m/Y', strtotime($this->datetoForm));
        $data->push(["PERIODE {$datefr} S.D {$dateto}", '', '', '', '', '', '', '', '', '', '', '', '']);

        $data->push([]);

        // ===== HEADER =====
        $data->push([
            'No', 'Jenis Dokumen', 'Dokumen Pabean', '', 'Bukti Penerimaan Barang', '',
            'Supplier', 'Kode Barang', 'Nama Barang', 'Satuan', 'Jumlah', 'Nilai Barang', ''
        ]);

        $data->push([
            '', '', 'Nomor Pendaftaran', 'Tanggal', 'Nomor', 'Tanggal', '',
            '', '', '', '', 'Rupiah', 'USD'
        ]);

        // ===== DATA =====
        if ($this->results->isEmpty()) {
            $data->push(['NO DATA RESULTS...', '', '', '', '', '', '', '', '', '', '', '', '']);
        } else {
            $no = 0;
            $dpnomor = '';
            $bpbnomor = '';

            foreach ($this->results as $item) {

                if ($item->dpnomor == $dpnomor && $item->bpbnomor == $bpbnomor) {

                    $data->push([
                        '', '', '', '', '', '', '',
                        $item->kode_barang,
                        $item->nama_barang,
                        $item->sat,
                        $item->jumlah ?: '--',
                        $item->nilai_barang ?: '--',
                        $item->nilai_barang_usd ?: '--'
                    ]);

                } elseif ($item->dpnomor == $dpnomor && $item->bpbnomor != $bpbnomor) {

                    $bpbnomor = $item->bpbnomor;

                    $data->push([
                        '', '', '', '', $item->bpbnomor,
                        date('d/m/Y', strtotime($item->bpbtanggal)),
                        $item->pemasok_pengirim,
                        $item->kode_barang,
                        $item->nama_barang,
                        $item->sat,
                        $item->jumlah ?: '--',
                        $item->nilai_barang ?: '--',
                        $item->nilai_barang_usd ?: '--'
                    ]);

                } else {

                    $no++;
                    $dpnomor  = $item->dpnomor;
                    $bpbnomor = $item->bpbnomor;

                    $data->push([
                        $no,
                        $item->jenis_dokumen,
                        $item->dpnomor,
                        date('d/m/Y', strtotime($item->dptanggal)),
                        $item->bpbnomor,
                        date('d/m/Y', strtotime($item->bpbtanggal)),
                        $item->pemasok_pengirim,
                        $item->kode_barang,
                        $item->nama_barang,
                        $item->sat,
                        $item->jumlah ?: '--',
                        $item->nilai_barang ?: '--',
                        $item->nilai_barang_usd ?: '--'
                    ]);
                }
            }
        }

        $data->push([]);
        $data->push(['~ Swifect Inventory BC ~', '', '', '', '', '', '', '', '', '', '', '', '']);

        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 20,
            'D' => 12,
            'E' => 20,
            'F' => 12,
            'G' => 25,
            'H' => 15,
            'I' => 50,
            'J' => 8,
            'K' => 12,
            'L' => 18,
            'M' => 18,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:M1');
        $sheet->mergeCells('A2:M2');
        $sheet->mergeCells('A3:M3');

        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('C4:D4');
        $sheet->mergeCells('E4:F4');
        $sheet->mergeCells('L4:M4');

        $sheet->getStyle('A4:M5')->getFont()->setBold(true);
        $sheet->getStyle('A4:M5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:M5')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A4:M5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4F81BD');
        $sheet->getStyle('A4:M5')->getFont()->getColor()->setRGB('FFFFFF');

        $dataStart = 6;
        $dataEnd   = $sheet->getHighestRow();

        $sheet->getStyle("A{$dataStart}:M{$dataEnd}")
            ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle("A{$dataStart}:M{$dataEnd}")
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("G{$dataStart}:G{$dataEnd}")->getAlignment()->setWrapText(true);
        $sheet->getStyle("I{$dataStart}:I{$dataEnd}")->getAlignment()->setWrapText(true);

        $sheet->mergeCells("A{$dataEnd}:M{$dataEnd}");
        $sheet->getStyle("A{$dataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }

    public function columnFormats(): array
    {
        return [
            'K' => NumberFormat::FORMAT_NUMBER_00,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}
