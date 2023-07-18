<?php

namespace App\Exports;

use App\Helpers\ZipHelper;
use App\Media;
use Illuminate\Http\Request;

class ProductImagesExport
{
    const HIGHJUMP_HEIGHT = 288;

    const HIGHJUMP_WIDTH = 288;

    public function export(Request $request)
    {
        $stockIds = explode(' ', preg_replace('/\ +/', ' ', preg_replace('/[^A-Za-z0-9\ ]/', ' ', $request->stock_ids)));

        $includeImages = $request->images ?: false;
        $includeLabelFlats = $request->labelflats ?: false;
        if (! $includeImages && ! $includeLabelFlats) {
            flash('You must include at least one type of image.', 'danger');

            return redirect()->back();
        }

        $collections = [];
        if ($includeImages) {
            $collections[] = 'product';
        }
        if ($includeLabelFlats) {
            $collections[] = 'label_flat';
        }

        $media = Media::with(['model' => function ($query) {
            $query->select('id', 'stock_id');
        }])->whereHasMorph('model', \App\Models\Product::class, function ($query) use ($stockIds) {
            $query->whereIn('stock_id', $stockIds);
        })->whereIn('collection_name', $collections)->get();

        if (! $media->count()) {
            flash('There were no images found for the selection.', 'danger');

            return redirect()->back();
        }

        $files = [];
        foreach ($media as $file) {
            $filename = $file->model->stock_id . ($file->collection_name == 'label_flat' ? '_label' : '');

            $imagePath = $file->getPath();
            $extension = pathinfo($file->file_name, PATHINFO_EXTENSION);

            $files["{$filename}.{$extension}"] = $imagePath;
        }

        $zipFile = ZipHelper::zipFiles($files, true);

        return ZipHelper::download($zipFile, 'product_images.zip');
    }
}
