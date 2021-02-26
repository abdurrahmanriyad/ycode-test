<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PeopleTest extends TestCase
{
    /** @test */
    public function a_user_can_browse_people_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_make_request_to_fetch_all_people()
    {
        Http::fake(function () {
            return Http::response([
                'records' => []
            ]);
        });

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->get('/ajax/people');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_fetches_all_people_as_json()
    {
        Http::fake(function () {
            return Http::response([
                'records' => []
            ]);
        });

        $response = $this->withHeaders([
            'Accept' => 'Application/json'
        ])->get('/ajax/people');

        $response->assertJson([]);
    }

    /** @test */
    public function it_aborts_if_request_to_get_all_people_not_wants_json()
    {
        Http::fake(function () {
            return Http::response([
                'records' => []
            ]);
        });

        $response = $this->withHeaders([
            'Accept' => 'text/html'
        ])->get('/ajax/people');

        $response->assertNotFound();
    }

    /** @test */
    public function it_can_make_request_to_create_new_member()
    {
        Storage::fake('public');

        Http::fake(function () {
            return Http::response([
                'fields' => [
                    'Name' => 'Abdur Rahman',
                    'Email' => 'riyad.abdur@gmail.com'
                ]
            ]);
        });

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->post('/ajax/people', [
                'name' => 'Abdur Rahman',
                'email' => 'riyad.abdur@gmail.com',
                'photo' => UploadedFile::fake()->image('test.jpg')
            ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_expects_only_jpg_file_during_create_member()
    {
        Storage::fake('public');

        Http::fake(function () {
            return Http::response([
                'fields' => [
                    'Name' => 'Abdur Rahman',
                    'Email' => 'riyad.abdur@gmail.com'
                ]
            ]);
        });

        // tests if photo is optional
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->post('/ajax/people', [
                'name' => 'Abdur Rahman',
                'email' => 'riyad.abdur@gmail.com'
            ]);

        $response->assertJsonValidationErrors([]);

        // tests if png file not supported
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->post('/ajax/people', [
                'name' => 'Abdur Rahman',
                'email' => 'riyad.abdur@gmail.com',
                'photo' => UploadedFile::fake()->image('test.png')
            ]);

        $response->assertJsonValidationErrors(['photo']);

        // tests if gif file not supported
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->post('/ajax/people', [
                'name' => 'Abdur Rahman',
                'email' => 'riyad.abdur@gmail.com',
                'photo' => UploadedFile::fake()->image('test.gif')
            ]);

        $response->assertJsonValidationErrors(['photo']);

    }

    /** @test */
    public function it_validates_name_during_create_member()
    {
        Storage::fake('public');

        Http::fake(function () {
            return Http::response([
                'fields' => [
                    'Name' => 'Abdur Rahman',
                    'Email' => 'riyad.abdur@gmail.com'
                ]
            ]);
        });

        // tests name is minimum 2 characters
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->post('/ajax/people', [
                'name' => 'a',
                'email' => 'riyad.abdur@gmail.com',
                'photo' => UploadedFile::fake()->image('test.jpg')
            ]);

        $response->assertJsonValidationErrors(['name']);

        // tests name is not more than 100 characters
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->post('/ajax/people', [
                'name' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.",
                'email' => 'riyad.abdur@gmail.com',
                'photo' => UploadedFile::fake()->image('test.jpg')
            ]);

        $response->assertJsonValidationErrors(['name']);

        // tests name is required
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->post('/ajax/people', [
                'name' => "",
                'email' => 'riyad.abdur@gmail.com',
                'photo' => UploadedFile::fake()->image('test.jpg')
            ]);

        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_validates_email_during_create_member()
    {
        Storage::fake('public');

        Http::fake(function () {
            return Http::response([
                'fields' => [
                    'Name' => 'Abdur Rahman',
                    'Email' => 'riyad.abdur@gmail.com'
                ]
            ]);
        });

        // tests email is minimum 5 characters long
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->post('/ajax/people', [
                'name' => 'a',
                'email' => 'a@b.',
                'photo' => UploadedFile::fake()->image('test.jpg')
            ]);

        $response->assertJsonValidationErrors(['email']);

        // tests email is not more than 100 character
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->post('/ajax/people', [
                'name' => "Abdur Rahman",
                'email' => 'riyad.abdurtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest@gmail.com',
                'photo' => UploadedFile::fake()->image('test.jpg')
            ]);

        $response->assertJsonValidationErrors(['email']);

        // tests email is required
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])
            ->post('/ajax/people', [
                'name' => "Abdur Rahman",
                'email' => '',
                'photo' => UploadedFile::fake()->image('test.jpg')
            ]);

        $response->assertJsonValidationErrors(['email']);
    }
}
