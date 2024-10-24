<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Post;
use App\Models\Post as ModelsPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_render_the_post_component()
    {
        Livewire::test('App\Livewire\Post')
            ->assertStatus(200)
            ->assertViewIs('livewire.post');
    }

    /** @test */
    public function it_can_add_a_post()
    {
        Livewire::test(Post::class)
            ->set('title', 'Test Post')
            ->set('description', 'Test description for post')
            ->call('storePost');

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'description' => 'Test description for post',
        ]);
    }

    /** @test */
    public function it_can_edit_a_post()
    {
        $post = ModelsPost::factory()->create();

        Livewire::test(Post::class)
            ->call('editPost', $post->id)
            ->set('title', 'Updated Title')
            ->set('description', 'Updated description')
            ->call('updatePost');

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'description' => 'Updated description',
        ]);
    }

    /** @test */
    public function it_can_delete_a_post()
    {
        $post = ModelsPost::factory()->create();

        Livewire::test(Post::class)
            ->call('deletePost', $post->id);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    /** @test */
    public function it_requires_title_and_description()
    {
        Livewire::test(Post::class)
            ->call('storePost')
            ->assertHasErrors(['title', 'description']);
    }
}
