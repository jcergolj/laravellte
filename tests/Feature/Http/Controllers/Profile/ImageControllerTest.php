<?php

namespace Tests\Feature\Http\Controllers\Profile;

use App\Events\ProfileImageUploaded;
use App\Http\Controllers\Profile\ImageController;
use App\Http\Requests\Profile\UpdateImageRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use JMac\Testing\Traits\HttpTestAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Profile\ImageController
 */
class ImageControllerTest extends TestCase
{
    use HttpTestAssertions, RefreshDatabase;

    /**
     * @var \Illuminate\Http\UploadedFile
     */
    private $imageFile;

    protected function setUp() : void
    {
        parent::setUp();

        Storage::fake('public');
        Event::fake();

        $this->imageFile = UploadedFile::fake()
            ->image('image.jpg', 1000, 1000);
    }

    /** @test */
    public function controller_uses_update_image_request()
    {
        $this->assertActionUsesFormRequest(
            ImageController::class,
            'update',
            UpdateImageRequest::class
        );
    }

    /** @test */
    public function event_profile_image_uploaded_is_dispatched()
    {
        $this->actingAs($user = factory(User::class)->create())
            ->postJson(route('profile.images.update'), [
                'image' => $this->imageFile,
            ]);

        Event::assertDispatched(ProfileImageUploaded::class, 1);

        Event::assertDispatched(ProfileImageUploaded::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
    }
}
