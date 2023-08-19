<?php

namespace App\Exports;

use App\Models\OrderDetail;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportFile implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;

    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $data = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
            ->selectRaw('order_details.name, sum(order_details.quantity) as quantity, SUM(order_details.price) as total, DATE(order_details.created_at) as date')
            ->whereRaw('MONTH(order_details.created_at) = ?', $this->data['month'])
            ->whereRaw('YEAR(order_details.created_at) = ?', $this->data['year'])
            ->where('orders.status', 'delivered')
            ->groupBy('name', 'price', 'date')
            ->get();

        return view('admin.Export.ExportView', [
            'data' => $data,
            'month' => $this->data['month'],
            'year' => $this->data['year'],
            'username' => $this->data['username']
        ]);
    }

}
