<?php

namespace App\Http\Controllers;

use App\Helpers\SignoffStateHelper;
use App\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        // Grab model
        if (is_callable([$request->modelClass, 'allStates'])) {
            $model = $request->modelClass::allStates()->findOrFail($request->modelId);
        } else {
            $model = $request->modelClass::findOrFail($request->modelId);
        }

        $modelId = null;
        if (method_exists($model, 'stateField') && $model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
            $model->createSignoff();
            $model = $model->getLastProposed();
            $modelId = $model->id;
        }

        $file_id = null;
        foreach ($request->files as $collection => $files) {
            $files = Arr::wrap($files);
            foreach ($files as $file) {
                $dbFile = $model->addMedia($file)->withCustomProperties(['multiple_formats' => 1])->toMediaCollection($collection);
                $file_id = $dbFile->id;
            }
        }

        if ($file_id) {
            return response()->json(['success' => true, 'model_id' => $modelId, 'file_id' => $file_id]);
        } else {
            return response()->json(['success' => false, 'model_id' => $modelId]);
        }
    }

    public function setCustomProperty($id, Request $request)
    {
        $media = Media::findOrFail($id);
        $media->setCustomProperty($request->property, $request->value);
        $media->save();

        return response()->json(['success' => true]);
    }

    public function getCustomProperty($id, Request $request)
    {
        $media = Media::findOrFail($id);
        $value = $media->getCustomProperty($request->property);

        return response()->json($value);
    }

    // Moving destroy() method to the related model's controller to better handle permissions checking
    // public function destroy($id)
}
