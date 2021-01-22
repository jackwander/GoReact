<?php

namespace Tests\Feature;

use App\Http\Livewire\UploadFile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class FilesTest extends TestCase
{
  /** @test */
  public function only_logged_in_users_can_see_the_dashboard()
  {
    $response = $this->get('/dashboard')->assertRedirect('/login');
  }

  /** @test */
  public function only_logged_in_users_can_see_the_upload()
  {
    $response = $this->get('/upload')->assertRedirect('/login');
  }

  /** @test */
  public function authenticated_users_can_see_the_dashboard() {
    $this->actingAs(User::factory()->create());
    $response = $this->get('/dashboard')->assertOk();
  }

  /** @test */
  public function authenticated_users_can_see_the_upload() {
    $this->actingAs(User::factory()->create());
    $response = $this->get('/upload')->assertOk();
  }

  /** @test */
  public function authenticated_users_can_upload_jpg() {
    $this->actingAs(User::factory()->create());
    \Storage::fake('s3');
    $response = Livewire::test(UploadFile::class)
        ->set('file', UploadedFile::fake()->image('image.jpg'))
        ->set('name', 'Test Name')
        ->set('description', 'Test Description')
        ->call('save',);

    \Storage::disk('s3')->assertExists('files/'.$response->s3_name);
  }

  /** @test */
  public function authenticated_users_can_upload_pdf() {
    $this->actingAs(User::factory()->create());
    \Storage::fake('s3');
    $response = Livewire::test(UploadFile::class)
        ->set('file', UploadedFile::fake()->create('document.pdf', 1))
        ->set('name', 'Test Name')
        ->set('description', 'Test Description')
        ->call('save',);

    \Storage::disk('s3')->assertExists('files/'.$response->s3_name);
  }
  /** @test */
  public function authenticated_users_can_upload_mp4() {
    $this->actingAs(User::factory()->create());
    \Storage::fake('s3');
    $response = Livewire::test(UploadFile::class)
        ->set('file', UploadedFile::fake()->create('video.mp4', 1))
        ->set('name', 'Test Name')
        ->set('description', 'Test Description')
        ->call('save',);

    \Storage::disk('s3')->assertExists('files/'.$response->s3_name);
  }

  /** @test */
  public function authenticated_can_view_file() {
    $this->actingAs($user = User::factory()->create());
    \Storage::fake('s3');
    $file = $user->files()->create([
      'filename'=>'Test Name',
      'description'=>'Test Description',
      'url'=>'Test Url',
      's3_name'=>'Test',
      'mime'=> "mp4"
    ]);
    $response = $this->get('/view/'.$file->id)->assertOk();
  }
}
