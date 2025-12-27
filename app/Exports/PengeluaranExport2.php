<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PengeluaranExport2 implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths, WithColumnFormatting
{
    protected $results;
    protected $datefrForm;
    protected $datetoForm;
    protected $comp_name;

    public function __construct($results, $datefrForm, $datetoForm, $comp_name)
    {
        $this->results = $results;
        $this->datefrForm = $datefrForm;
        $this->datetoForm = $datetoForm;
        $this->comp_name = $comp_name;
    }

    public function collection(): Collection
    {
        $data = collect();

        // Add title rows
        $data->push(['LAPORAN PERTANGGUNG JAWABAN PENGELUARAN DOKUMEN', '', '', '', '', '', '', '', '', '', '', '', '']);
        $data->push([$this->comp_name, '', '', '', '', '', '', '', '', '', '', '', '']);

        if ($this->datefrForm && $this->datetoForm) {
            $datefr = date('d/m/Y', strtotime($this->datefrForm));
            $dateto = date('d/m/Y', strtotime($this->datetoForm));
            $data->push(["PERIODE {$datefr} S.D {$dateto}", '', '', '', '', '', '', '', '', '', '', '', '']);
        }

        $data->push([]); // Empty row

        // Header row 1 (with colspans)
        $data->push([
            'No', 'Jenis Dokumen', 'Dokumen Pabean', '', 'Pengeluaran Barang', '', 'Customer',
            'Kode Barang', 'Nama Barang', 'Satuan', 'Jumlah', 'Nilai Barang', ''
        ]);

        // Header row 2 (sub-headers)
        $data->push([
            '', '', 'Nomor Pendaftaran', 'Tanggal', 'Nomor', 'Tanggal', '',
            '', '', '', '', 'Rupiah', 'USD'
        ]);

        if ($this->results->count() > 0) {
            $no = 0;
            $dpnomor = '';
            $bpbnomor = '';

            foreach ($this->results as $item) {
                if (trim($item->dpnomor) == trim($dpnomor) && trim($item->bpbnomor) == trim($bpbnomor)) {
                    // For merged rows, empty first 7 columns
                    $data->push([
                        '', '', '', '', '', '', '',
                        $item->kode_barang,
                        $item->nama_barang,
                        $item->sat,
                        $item->jumlah == 0 ? '--' : (float)$item->jumlah,
                        $item->nilai_barang == 0 ? '--' : (float)$item->nilai_barang,
                        $item->nilai_barang_usd == 0 ? '--' : (float)$item->nilai_barang_usd
                    ]);
                } elseif (trim($item->dpnomor) == trim($dpnomor) && trim($item->bpbnomor) != trim($bpbnomor)) {
                    // Special case for different bpbnomor but same dpnomor
                    $data->push([
                        '', '', '', '', $item->bpbnomor, date('d/m/Y', strtotime($item->bpbtanggal)), $item->pembeli_penerima,
                        $item->kode_barang,
                        $item->nama_barang,
                        $item->sat,
                        $item->jumlah == 0 ? '--' : (float)$item->jumlah,
                        $item->nilai_barang == 0 ? '--' : (float)$item->nilai_barang,
                        $item->nilai_barang_usd == 0 ? '--' : (float)$item->nilai_barang_usd
                    ]);
                } else {
                    // New document number
                    $no++;
                    $dpnomor = $item->dpnomor;
                    $bpbnomor = $item->bpbnomor;

                    $data->push([
                        $no,
                        $item->jenis_dokumen,
                        $item->dpnomor,
                        date('d/m/Y', strtotime($item->dptanggal)),
                        $item->bpbnomor,
                        date('d/m/Y', strtotime($item->bpbtanggal)),
                        $item->pembeli_penerima,
                        $item->kode_barang,
                        $item->nama_barang,
                        $item->sat,
                        $item->jumlah == 0 ? '--' : (float)$item->jumlah,
                        $item->nilai_barang == 0 ? '--' : (float)$item->nilai_barang,
                        $item->nilai_barang_usd == 0 ? '--' : (float)$item->nilai_barang_usd
                    ]);
                }
            }
        } else {
            $data->push(['NO DATA RESULTS...', '', '', '', '', '', '', '', '', '', '', '', '']);
        }

        $data->push([]); // Empty row
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
            'A' => 5,   // No
            'B' => 15,  // Jenis Dokumen
            'C' => 20,  // Nomor Pendaftaran
            'D' => 12,  // Tanggal Dokumen
            'E' => 20,  // Nomor Pengeluaran
            'F' => 12,  // Tanggal Pengeluaran
            'G' => 25,  // Customer
            'H' => 15,  // Kode Barang
            'I' => 50,  // Nama Barang
            'J' => 8,   // Satuan
            'K' => 12,  // Jumlah
            'L' => 18,  // Nilai Rp
            'M' => 18,  // Nilai USD
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells for title
        $sheet->mergeCells('A1:M1');
        $sheet->mergeCells('A2:M2');
        if ($this->datefrForm && $this->datetoForm) {
            $sheet->mergeCells('A3:M3');
        }

        // Style the title
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Style the company name
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Style the period
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Table headers should be on rows 4 and 5
        $tableHeader1 = 4; // First header row
        $tableHeader2 = 5; // Second header row

        // Merge header cells to match colspan
        $sheet->mergeCells("C{$tableHeader1}:D{$tableHeader1}"); // Dokumen Pabean
        $sheet->mergeCells("E{$tableHeader1}:F{$tableHeader1}"); // Pengeluaran Barang
        $sheet->mergeCells("L{$tableHeader1}:M{$tableHeader1}"); // Nilai Barang

        // Style table headers with background color
        $sheet->getStyle("A{$tableHeader1}:M{$tableHeader2}")->getFont()->setBold(true);
        $sheet->getStyle("A{$tableHeader1}:M{$tableHeader2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$tableHeader1}:M{$tableHeader2}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A{$tableHeader1}:M{$tableHeader2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4F81BD'); // Blue background
        $sheet->getStyle("A{$tableHeader1}:M{$tableHeader2}")->getFont()->getColor()->setRGB('FFFFFF'); // White text

        // Style data rows with borders and center alignment
        $highestRow = $sheet->getHighestRow();
        for ($row = $tableHeader2 + 1; $row <= $highestRow; $row++) {
            $sheet->getStyle("A{$row}:M{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("A{$row}:M{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            // Enable text wrapping for Customer column (G) and Nama Barang column (I)
            $sheet->getStyle("G{$row}")->getAlignment()->setWrapText(true);
            $sheet->getStyle("I{$row}")->getAlignment()->setWrapText(true);
        }

        // Style footer
        $sheet->getStyle("A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells("A{$highestRow}:M{$highestRow}");

        return [];
    }

    public function columnFormats(): array
    {
        return [
            'K' => NumberFormat::FORMAT_NUMBER_00, // Jumlah column - 2 decimal places
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Nilai Rp column - with commas
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Nilai USD column - with commas
        ];
    }
}
