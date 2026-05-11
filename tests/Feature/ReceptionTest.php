<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ReceptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authorized_user_can_view_reception_page(): void
    {
        $user = User::factory()->create();
        Permission::create(['name' => 'visits.view', 'guard_name' => 'web']);

        $user->givePermissionTo('visits.view');

        $response = $this->actingAs($user)->get(route('reception.index'));

        $response->assertOk();
        $response->assertSee('Réception');
    }
}
