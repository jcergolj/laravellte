<?php

namespace Tests\Unit\Listeners;

use App\Events\ProfileImageUploaded;
use App\Listeners\ResizeImage;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** @see \App\Listeners\ResizeImage */
class ResizeImageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function profile_image_is_resized()
    {
        Storage::disk('avatar')
            ->putFileAs('', UploadedFile::fake()->image('abc123.jpg', 1000, 1000), 'abc123.jpg');

        $event = new ProfileImageUploaded($user = UserFactory::new()->create(['image' => 'abc123.jpg']));
        $listener = new ResizeImage();
        $listener->handle($event);

        $imageProperties = getimagesize(config('filesystems.disks.avatar.root')."/{$user->image}");

        $this->assertSame(100, $imageProperties[0]);
        $this->assertSame(100, $imageProperties[1]);

        Storage::disk('avatar')->delete("{$user->image}");
    }
}
