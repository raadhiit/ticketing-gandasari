<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\ErpSsoToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErpSsoLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_erp_sso_login_redirects_to_ticket_create_page(): void
    {
        $department = Department::create([
            'name' => 'Information Technology',
            'erp_code' => 'IT',
            'description' => 'Imported from ERP',
        ]);

        $user = User::factory()->create([
            'department_id' => $department->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $plainToken = str_repeat('a', 64);

        ErpSsoToken::create([
            'token_hash' => hash('sha256', $plainToken),
            'user_id' => $user->id,
            'expires_at' => now()->addMinutes(2),
        ]);

        $response = $this->get(route('erp.sso.login', ['token' => $plainToken]));

        $response->assertRedirect(route('tickets.create', absolute: false));
        $this->assertAuthenticatedAs($user);

        $this->assertDatabaseHas('erp_sso_tokens', [
            'user_id' => $user->id,
        ]);

        $this->assertNotNull(ErpSsoToken::query()->first()?->used_at);
    }
}
