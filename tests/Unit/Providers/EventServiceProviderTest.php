<?php

namespace Tests\Unit\Providers;

use App\Events\ProfileImageUploaded;
use App\Listeners\ResizeImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @see \App\Providers\EventServiceProvider */
class EventServiceProviderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function resize_image_listener_is_attached_to_profile_image_uploaded_event()
    {
        $this->assertEventHasListener(ProfileImageUploaded::class, ResizeImage::class);
    }
}
