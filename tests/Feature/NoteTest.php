<?php

use App\Models\BikeRack;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->owner = User::factory()->create();

    $this->bikeRack = BikeRack::factory()
        ->for($this->owner, 'owner')
        ->create();

    $this->note = Note::factory()
        ->for($this->bikeRack, 'bikeRack')
        ->create();
});

function authenticateNoteActor(
    $test,
    string $channel,
    User $user,
): string {
    if ($channel === 'api') {
        Sanctum::actingAs($user);

        return '/api';
    }

    $test->actingAs($user);

    return '';
}

test('Власник може створити примітку', function (
    string $channel
): void {
    $prefix = authenticateNoteActor(
        $this,
        $channel,
        $this->owner,
    );

    $url = "{$prefix}/bike-racks/{$this->bikeRack->id}/notes";

    $payload = [
        'title' => 'Нова примітка',
        'body' => 'Текст нової примітки.',
    ];

    if ($channel === 'api') {
        $this->postJson($url, $payload)
            ->assertCreated()
            ->assertJsonPath(
                'data.title',
                $payload['title'],
            )
            ->assertJsonPath(
                'data.bike_rack_id',
                $this->bikeRack->id,
            );
    } else {
        $this->post($url, $payload)
            ->assertRedirect()
            ->assertSessionHas('success');
    }

    $this->assertDatabaseHas('notes', [
        'bike_rack_id' => $this->bikeRack->id,
        ...$payload,
    ]);
})->with(['web', 'api']);

test('Власник може редагувати примітку', function (
    string $channel
): void {
    $prefix = authenticateNoteActor(
        $this,
        $channel,
        $this->owner,
    );

    $url = "{$prefix}/bike-racks/{$this->bikeRack->id}"
        . "/notes/{$this->note->id}";

    $originalBody = $this->note->body;

    $payload = [
        'title' => 'Оновлений заголовок',
    ];

    if ($channel === 'api') {
        $this->patchJson($url, $payload)
            ->assertOk()
            ->assertJsonPath(
                'data.title',
                $payload['title'],
            );
    } else {
        $this->patch($url, $payload)
            ->assertRedirect()
            ->assertSessionHas('success');
    }

    $this->assertDatabaseHas('notes', [
        'id' => $this->note->id,
        'bike_rack_id' => $this->bikeRack->id,
        'title' => $payload['title'],
        'body' => $originalBody,
    ]);
})->with(['web', 'api']);

test('Власник може видалити примітку', function (
    string $channel
): void {
    $prefix = authenticateNoteActor(
        $this,
        $channel,
        $this->owner,
    );

    $url = "{$prefix}/bike-racks/{$this->bikeRack->id}"
        . "/notes/{$this->note->id}";

    if ($channel === 'api') {
        $this->deleteJson($url)
            ->assertNoContent();
    } else {
        $this->delete($url)
            ->assertRedirect(route(
                'bike-racks.show',
                ['bike_rack' => $this->bikeRack],
            ));
    }

    $this->assertDatabaseMissing('notes', [
        'id' => $this->note->id,
    ]);
})->with(['web', 'api']);

test('Чужий користувач не може редагувати примітку', function (
    string $channel
): void {
    $otherOwner = User::factory()->create();

    $prefix = authenticateNoteActor(
        $this,
        $channel,
        $otherOwner,
    );

    $url = "{$prefix}/bike-racks/{$this->bikeRack->id}"
        . "/notes/{$this->note->id}";

    $payload = [
        'title' => 'Спроба чужої зміни',
    ];

    $response = $channel === 'api'
        ? $this->patchJson($url, $payload)
        : $this->patch($url, $payload);

    $response->assertForbidden();

    $this->assertDatabaseHas('notes', [
        'id' => $this->note->id,
        'title' => $this->note->title,
        'body' => $this->note->body,
    ]);
})->with(['web', 'api']);

test('Примітка повинна належати парковці з URL', function (
    string $channel
): void {
    $prefix = authenticateNoteActor(
        $this,
        $channel,
        $this->owner,
    );

    $otherBikeRack = BikeRack::factory()
        ->for($this->owner, 'owner')
        ->create();

    $url = "{$prefix}/bike-racks/{$otherBikeRack->id}"
        . "/notes/{$this->note->id}";

    $payload = [
        'title' => 'Спроба підміни ID',
    ];

    $response = $channel === 'api'
        ? $this->patchJson($url, $payload)
        : $this->patch($url, $payload);

    $response->assertNotFound();

    $this->assertDatabaseHas('notes', [
        'id' => $this->note->id,
        'bike_rack_id' => $this->bikeRack->id,
        'title' => $this->note->title,
    ]);
})->with(['web', 'api']);

test('Не можна зберегти порожній текст примітки', function (
    string $channel
): void {
    $prefix = authenticateNoteActor(
        $this,
        $channel,
        $this->owner,
    );

    $url = "{$prefix}/bike-racks/{$this->bikeRack->id}"
        . "/notes/{$this->note->id}";

    $payload = [
        'title' => 'Новий заголовок',
        'body' => '',
    ];

    if ($channel === 'api') {
        $this->patchJson($url, $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['body']);
    } else {
        $editUrl = route('bike-racks.notes.edit', [
            'bike_rack' => $this->bikeRack,
            'note' => $this->note,
        ]);

        $this->from($editUrl)
            ->patch($url, $payload)
            ->assertRedirect($editUrl)
            ->assertSessionHasErrors(['body'])
            ->assertSessionHasInput(
                'title',
                $payload['title'],
            );
    }

    $this->assertDatabaseHas('notes', [
        'id' => $this->note->id,
        'title' => $this->note->title,
        'body' => $this->note->body,
    ]);
})->with(['web', 'api']);
