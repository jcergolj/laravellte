<?php

namespace Tests\Unit\Http\Requests\Profile;

use App\Http\Requests\Profile\UpdateImageRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/** @see \App\Http\Profile\UpdateImageRequest */
class UpdateImageRequestTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase;

    /** @var \App\Http\Requests\Profile\UpdateImageRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new UpdateImageRequest([], [], [], [], [
            'image' => UploadedFile::fake()->image('image.jpg', 1000, 1000),
        ]);

        Storage::fake('avatar');
    }

    /** @test */
    public function auth_user_can_edit_his_image()
    {
        $this->subject->persist($user = factory(User::class)->create());

        $this->assertNotNull($user->fresh()->image);

        Storage::disk('avatar')->assertExists("{$user->fresh()->image}");
    }

    /** @test */
    public function old_auth_user_image_is_deleted()
    {
        $user = factory(User::class)->create([
            'image' => 'abc123.jpg',
        ]);

        Storage::disk('avatar')
            ->putFileAs('', UploadedFile::fake()->image('abc123.jpg', 1000, 1000), 'abc123.jpg');

        $this->subject->persist($user);

        Storage::disk('avatar')->assertExists("{$user->image}");

        Storage::disk('avatar')->assertMissing('abc123.jpg');
    }

    /** @test */
    public function auth_user_can_only_edit_his_image()
    {
        $joe = factory(User::class)->create([
            'image' => 'joe.jpg',
        ]);

        Storage::disk('avatar')
            ->putFileAs('', UploadedFile::fake()->image('joe.jpg', 1000, 1000), 'joe.jpg');

        $this->subject->persist($jane = factory(User::class)->create());

        $this->assertSame('joe.jpg', $joe->fresh()->image);
        Storage::disk('avatar')->assertExists("{$joe->image}");
    }

    /**
     * @test
     */
    public function rules()
    {
        $this->assertValidationRules([
            'image' => [
                'required',
                'image',
                'dimensions:min_width=100,min_height=100',
            ],
        ], $this->subject->rules());
    }
}
