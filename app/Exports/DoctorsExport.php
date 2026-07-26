<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DoctorsExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected Collection $data;
    protected string $title;

    public function __construct(Collection $data, string $title = 'Doctors')
    {
        $this->data = $data;
        $this->title = $title;
    }

    public function collection()
    {
        return $this->data->map(function ($d) {
            $region = $d->territory?->area?->region?->name ?? '';
            $area = $d->territory?->area?->name ?? '';
            $territory = $d->territory?->name ?? '';

            return [
                'Name' => $d->name ?? '',
                'Mobile' => $d->mobile_number ?? '',
                'Email' => $d->email ?? '',
                'Chamber Address' => $d->chamber_address ?? '',
                'Note' => $d->note ?? '',
                'Marketing Rep' => $d->marketing_representative_name ?? '',
                'MR Assigned Date' => $d->mr_assigned_at ? $d->mr_assigned_at->format('Y-m-d') : '',
                'Region' => $region,
                'Area' => $area,
                'Territory' => $territory,
                'Added By' => $d->creator?->name ?? '',
                'Created At' => optional($d->created_at)->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name', 'Mobile', 'Email', 'Chamber Address', 'Note', 'Marketing Rep', 'MR Assigned Date',
            'Region', 'Area', 'Territory', 'Added By', 'Created At',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setCellValue('A1', $this->title);
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        return [
            2 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $headings = $this->headings();
                $sheet->fromArray([$headings], null, 'A2');

                $data = $this->collection()->toArray();
                if (! empty($data)) {
                    $sheet->fromArray($data, null, 'A3');
                }

                $sheet->getStyle('A2:L2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FF374151');

                $highestRow = max(3, $sheet->getHighestRow());
                $sheet->getStyle('A2:L'.$highestRow)->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                    ->getColor()->setARGB('FFCBD5E1');

                $colors = [
                    'FFFDE68A', 'FFA7F3D0', 'FFBFDBFE', 'FFFBCFE8', 'FFE9D5FF', 'FFC7D2FE', 'FFBBF7D0',
                    'FFFEE2E2', 'FFFDE68A', 'FFA7F3D0', 'FFBFDBFE', 'FFE2E8F0',
                ];
                $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
                foreach ($cols as $idx => $col) {
                    $sheet->getStyle($col.'3:'.$col.$highestRow)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB($colors[$idx]);
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
