<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class InventoryRemovalExport extends BaseExport
{
    const HEADER = [
        'Signoff ID',
        'Signoff Step Name',
        'Submitted By',
        'Submitted At',
        'Product Name',
        'Stock ID',
        'Size',
        'Brand Name',
        'Quantity',
        'Cost',
        'Ext Cost',
        'Full MCB',
        'Consignment',
        'Expiry',
        'Reserve',
        'Comment',
        'Warehouse Number',
        'Warehouse Name',
        'Completed',
        'Completed At',
        'Approved',
        'Final Approval At',
    ];

    private ?Spreadsheet $spreadsheet = null;

    private $data;

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $startDate = Carbon::parse($request->get('start_date'));
        $endDate = Carbon::parse($request->get('end_date'));

        $this->spreadsheet = new Spreadsheet;

        $filename = 'inventory_removals_' . $startDate->toDateString() . '_' . $endDate->toDateString() . '.xlsx';

        return $this
            ->createSheets()
            ->retrieveData($startDate, $endDate)
            ->transformData()
            ->setHeaders()
            ->insertData()
            ->downloadFile($this->spreadsheet, $filename);
    }

    public function getSpreadsheet()
    {
        return $this->spreadsheet;
    }

    private function createSheets()
    {
        $this->spreadsheet->getActiveSheet()->setTitle('Inventory Removals');

        return $this;
    }

    private function setHeaders()
    {
        $this->spreadsheet
            ->getSheetByName('Inventory Removals')
            ->fromArray(self::HEADER, null, 'A1');

        return $this;
    }

    private function retrieveData($startDate, $endDate)
    {
        $this->data = DB::table('signoffs')
            ->select([
                'signoffs.id AS signoff_id',
                DB::raw('(SELECT name FROM signoff_config_steps scs2 WHERE signoffs.signoff_config_id = scs2.signoff_config_id AND signoffs.step = scs2.step) AS signoff_step_name'),
                'users.name As submitted_by',
                'signoffs.submitted_at As submitted_at',
                'products.name As product_name',
                'products.stock_id As stock_id',
                DB::raw("'' as size"),
                'brands.name As brand_name',
                'inventory_removal_line_items.quantity As quantity',
                'inventory_removal_line_items.cost As cost',
                DB::raw('inventory_removal_line_items.quantity * inventory_removal_line_items.cost AS ext_cost'),
                DB::raw("CASE WHEN inventory_removal_line_items.full_mcb = 1 THEN 'Y' ELSE 'N' END AS full_mcb"),
                DB::raw("CASE WHEN as400_consignment.consignment = 1 THEN 'Y' ELSE 'N' END AS consignment"),
                'inventory_removal_line_items.expiry AS expiry',
                DB::raw("CASE WHEN inventory_removal_line_items.reserve = 1 THEN 'Y' ELSE 'N' END AS reserve"),
                'inventory_removal_line_items.notes AS comment',
                'inventory_removal_line_items.warehouse AS warehouse_number',
                'warehouses.name AS warehouse_name',
                DB::raw("CASE WHEN signoffs.step > 3 THEN 'Y' ELSE 'N' END AS completed"),
                DB::raw('(SELECT MAX(created_at) FROM signoff_responses sr WHERE sr.signoff_id = signoffs.id and sr.step = 3 AND sr.approved = 1 AND sr.comment_only = 0 ORDER BY sr.id DESC) AS completed_at'),
                DB::raw("CASE WHEN signoffs.step > 4 THEN 'Y' ELSE 'N' END AS approved"),
                DB::raw('(SELECT MAX(created_at) FROM signoff_responses sr WHERE sr.signoff_id = signoffs.id and sr.step = 4 AND sr.approved = 1 AND sr.comment_only = 0 ORDER BY sr.id DESC) AS final_approval_at'),
            ])
            ->join(
                'inventory_removals',
                'inventory_removals.id',
                '=',
                'signoffs.proposed_id'
            )
            ->join(
                'inventory_removal_line_items',
                'inventory_removal_line_items.inventory_removal_id',
                '=',
                'inventory_removals.id'
            )
            ->join('users', 'users.id', '=', 'inventory_removals.submitted_by')
            ->join(
                'warehouses',
                'warehouses.number',
                '=',
                'inventory_removal_line_items.warehouse'
            )
            ->join(
                'products',
                'products.id',
                '=',
                'inventory_removal_line_items.product_id'
            )
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->leftJoin(
                'as400_consignment',

                'as400_consignment.brand_id',
                '=',
                'brands.id'
            )
            ->where('signoffs.proposed_type', '=', \App\Models\InventoryRemoval::class)
            ->whereNotNull('signoffs.submitted_at')
            ->whereBetween('signoffs.submitted_at', [$startDate, $endDate])
            ->get();

        return $this;
    }

    private function transformData()
    {
        $this->data = $this->data->map(function ($item) {
            return [
                'signoff_id' => $item->signoff_id,
                'signoff_step_name' => $item->signoff_step_name,
                'submitted_by' => $item->submitted_by,
                'submitted_at' => $item->submitted_at,
                'product_name' => $item->product_name,
                'stock_id' => $item->stock_id,
                'size' => $item->size,
                'brand_name' => $item->brand_name,
                'quantity' => $item->quantity,
                'cost' => $item->cost,
                'ext_cost' => $item->ext_cost,
                'full_mcb' => $item->full_mcb,
                'consignment' => $item->consignment,
                'expiry' => $item->expiry,
                'reserve' => $item->reserve,
                'comment' => $item->comment,
                'warehouse_number' => $item->warehouse_number,
                'warehouse_name' => $item->warehouse_name,
                'completed' => $item->completed,
                'completed_at' => $item->completed_at,
                'approved' => $item->approved,
                'final_approval_at' => $item->final_approval_at,
            ];
        })->toArray();

        return $this;
    }

    private function insertData()
    {
        $this->spreadsheet->getSheetByName('Inventory Removals')->fromArray($this->data, null, 'A2');

        return $this;
    }
}
