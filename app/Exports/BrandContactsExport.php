<?php

namespace App\Exports;

use App\Models\Brand;

class BrandContactsExport extends CSVExport
{
    public function export()
    {
        $brands = Brand::with([
            'vendor' => function ($query) {
                $query->with('contacts')->select('id');
            },
            'contacts',
        ])->select('id', 'brand_number', 'category_code', 'vendor_id', 'name')->active()->get();

        $data = [['Brand', 'Brand Number', 'Category Code', 'Name', 'Role', 'Title', 'Email', 'Phone']];
        foreach ($brands as $brand) {
            foreach ($brand->contacts as $contact) {
                $data[] = [
                    $brand->name,
                    $brand->brand_number,
                    $brand->category_code,
                    $contact->name,
                    $contact->role,
                    $contact->position,
                    $contact->email,
                    $contact->phone,
                ];
            }

            foreach ($brand->vendor->contacts as $contact) {
                $data[] = [
                    $brand->name,
                    $brand->brand_number,
                    $brand->category_code,
                    $contact->name,
                    $contact->role,
                    $contact->position,
                    $contact->email,
                    $contact->phone,
                ];
            }
        }

        return $this->downloadFile($data, 'brand_contacts.csv');
    }
}
