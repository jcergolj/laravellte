<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ResizeImage implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $file = Storage::disk('avatar')->get("{$event->user->image}");

        Image::make($file)
            ->resize(100, 100)
            ->save(config('filesystems.disks.avatar.root')."/{$event->user->image}");
    }
}
