<?php

namespace Tests\Unit\Listeners;

use App\Events\ProfileImageUploaded;
use App\Listeners\ResizeImage;
use App\Models\User;
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
        Storage::disk('public')
            ->putFileAs('users/images/', UploadedFile::fake()->image('abc123.jpg', 1000, 1000), 'abc123.jpg');

        $event = new ProfileImageUploaded($user = factory(User::class)->create(['image' => 'abc123.jpg']));
        $listener = new ResizeImage();
        $listener->handle($event);

        $imageProperties = getimagesize(storage_path("app/public/users/images/{$user->image}"));

        $this->assertSame(100, $imageProperties[0]);
        $this->assertSame(100, $imageProperties[1]);

        Storage::disk('public')->delete("users/images/{$user->image}");
    }
}
