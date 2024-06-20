<?php

namespace Pearls\Media\Traits;

trait MediaControllerTrait
{
    protected function handleAttachments($request, $model, $key, $disk)
    {
        if ($request->hasFile($key)) {
            $prefix = \Str::slug(class_basename($model));
            foreach ($request->file($key) as $attachment) {
                $model->addMedia($attachment)
                    ->withCustomProperties(['root' => $prefix . '-' . $model->id])
                    ->toMediaCollection($prefix . '-' . $disk, $disk);
            }
        }
    }
}
