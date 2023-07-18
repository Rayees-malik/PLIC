<?php

namespace App\Datatables;

use App\Models\QualityControlRecord;
use Silber\Bouncer\BouncerFacade as Bouncer;

class QualityControlRecordsDatatable extends BaseDatatable
{
    protected $customFilters = true;

    public function datatable($query)
    {
        $datatable = datatables($query)
            ->editColumn('id', function ($qualityControlRecord) {
                return '<a href="' . route('qc.edit', $qualityControlRecord['id']) . '" class="text-link">' . $qualityControlRecord['id'] . '</a>';
            })
            ->editColumn('po_number', function ($qualityControlRecord) {
                return $qualityControlRecord->po_number;
            })
            ->editColumn('product.stock_id', function ($qualityControlRecord) {
                return $qualityControlRecord->product->stock_id;
            })
            ->editColumn('warehouse.name', function ($qualityControlRecord) {
                return $qualityControlRecord->warehouse->number . ' - ' . $qualityControlRecord->warehouse->name;
            })
            // ->editColumn('status', function ($vendor) {
            //     return $vendor->getStatus();
            // })
            ->addColumn('action', function ($qualityControlRecord) {
                $editButton = Bouncer::can('edit', $qualityControlRecord) ? '<a href="' . route('qc.edit', $qualityControlRecord['id']) . '" class="link-btn table-btn"><i class="material-icons">edit</i>Edit</a>' : '';
                $printFormDownload = Bouncer::canany(['view', 'edit'], $qualityControlRecord) ? '<a href="' . route('qc.print.download', $qualityControlRecord['id']) . '" class="link-btn table-btn"><i class="material-icons">print</i>Print</a>' : '';
                $labellingFormDownloadButton = Bouncer::canany(['view', 'edit'], $qualityControlRecord) ? '<a href="' . route('qc.labelling-form.download', $qualityControlRecord['id']) . '" class="link-btn table-btn"><i class="material-icons">label</i>Labelling</a>' : '';

                return $editButton . $printFormDownload . $labellingFormDownloadButton;
            })
            ->rawcolumns(['id', 'action'])
            ->filter(function ($query) {
                if (request()->warehouse_id) {
                    $query->where('warehouse_id', request()->warehouse_id);
                }
            }, true);

        return $datatable;
    }

    public function query()
    {
        $query = QualityControlRecord::query()
            ->with(['product:id,name,stock_id', 'vendor:id,name', 'user:id,name', 'warehouse:id,name,number']);

        return $query;
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'ID',
                'data' => 'id',
            ],
            [
                'title' => 'Stock ID',
                'data' => 'product.stock_id',
            ],
            [
                'title' => 'PO Number',
                'data' => 'po_number',
            ],
            [
                'title' => 'Lot Number',
                'data' => 'lot_number',
            ],
            [
                'title' => 'Warehouse',
                'data' => 'warehouse.name',
            ],
            // [
            //     'title' => 'Status',
            //     'data' => 'status',
            // ],
            [
                'title' => '',
                'data' => 'action',
                'orderable' => false,
                'searchable' => false,
                'exportable' => false,
                'printable' => false,
            ],
        ];
    }
}
