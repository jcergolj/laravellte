<?php

namespace Tests\Feature\Http\Livewire\Profile;

use App\Events\ProfileImageUploaded;
use App\Http\Livewire\Profile\UpdateImage;
use Database\Factories\UserFactory;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\Profile\UpdateImage */
class UpdateImageTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\User */
    private $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();

        Event::fake();
        Storage::fake('avatar');

        $this->imageFile = UploadedFile::fake()
            ->image('image.jpg', 1000, 1000);
    }

    /** @test */
    public function profile_page_contains_image_livewire_component()
    {
        $this->actingAs(create_user())
            ->get(route('profile.users.index'))
            ->assertSeeLivewire('profile.update-image');
    }

    /** @test */
    public function guest_cannot_access_component()
    {
        $this->expectException(AuthenticationException::class);

        Livewire::test(UpdateImage::class)
            ->call('submit');
    }

    /** @test */
    public function user_can_update_image()
    {
        Livewire::actingAs($this->user)
            ->test(UpdateImage::class)
            ->set('image', $this->imageFile)
            ->call('submit')
            ->assertRedirect(route('profile.users.index'));

        $this->assertNotNull($this->user->fresh()->image);
        Storage::disk('avatar')->assertExists($this->user->image);

        $this->assertNotNull(session()->get('flash'), 'Message is missing.');
    }

    /** @test */
    public function event_profile_image_uploaded_is_dispatched()
    {
        Livewire::actingAs($this->user)
            ->test(UpdateImage::class)
            ->set('image', $this->imageFile)
            ->call('submit');

        Event::assertDispatched(ProfileImageUploaded::class, 1);

        $user = $this->user;
        Event::assertDispatched(ProfileImageUploaded::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
    }

    /** @test */
    public function old_user_image_is_deleted()
    {
        $user = UserFactory::new()->create([
            'image' => 'abc123.jpg',
        ]);

        Storage::disk('avatar')
            ->putFileAs('', UploadedFile::fake()->image('abc123.jpg', 1000, 1000), 'abc123.jpg');

        Livewire::actingAs($user)
                ->test(UpdateImage::class)
                ->set('image', $this->imageFile)
                ->call('submit');

        Storage::disk('avatar')->assertExists($user->fresh()->image);

        Storage::disk('avatar')->assertMissing('abc123.jpg');
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        Livewire::actingAs($this->user)
            ->test(UpdateImage::class)
            ->set($clientFormInput, $clientFormValue)
            ->call('submit')
            ->assertHasErrors([$clientFormInput => $rule]);
    }

    public function clientFormValidationProvider()
    {
        return [
            'Test image is required' => ['image', '', 'required'],
            'Test image is image file' => ['image', UploadedFile::fake()->create('document.pdf', 100), 'image'],
            'Test image width must be greater than 100' => ['image', UploadedFile::fake()->image('image.jpg', 1, 1000), 'dimensions'],
            'Test image height must be greater than 100' => ['image', UploadedFile::fake()->image('image.jpg', 1000, 1), 'dimensions'],
        ];
    }
}
