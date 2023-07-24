<?php

namespace App\Exports;

use App\Models\OrderDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportFile implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function headings(): array
    {
        $headings = [
            'Tên sản phẩm',
            'Số lượng bán ra',
            'Tông doanh thu',
            'Thời gian'
        ];
        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFF00']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '00FF00']], // Background màu xanh
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:D1')->applyFromArray($this->styles($event->sheet));
            },
        ];
    }

    public function collection()
    {
        return OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')->selectRaw('order_details.name, sum(order_details.quantity) as quantity, SUM(order_details.price) as total, DATE(order_details.created_at) as date')
            ->whereRaw('MONTH(order_details.created_at) = ?', 5)
            ->whereRaw('YEAR(order_details.created_at) = ?', 2023)
            ->where('orders.status', 'delivered')
            ->groupBy('name', 'price', 'date')
            ->get();
    }


}
