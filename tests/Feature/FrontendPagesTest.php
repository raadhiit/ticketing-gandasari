<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketComment;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendPagesTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $requester;

    private Ticket $ticket;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);

        $department = Department::create(['name' => 'Information Technology']);
        $category = TicketCategory::create(['name' => 'Hardware']);

        $this->admin = User::factory()->create(['is_active' => true, 'department_id' => $department->id]);
        $this->admin->assignRole('superadmin');

        $this->requester = User::factory()->create(['is_active' => true, 'department_id' => $department->id]);
        $this->requester->assignRole('User');

        $this->ticket = Ticket::create([
            'ticket_number' => 'TKT-20261023-0001',
            'requester_id' => $this->requester->id,
            'department_id' => $department->id,
            'category_id' => $category->id,
            'title' => 'Printer tidak bisa mencetak',
            'description' => '<div>Printer lantai 2 macet.</div>',
            'priority' => 'MEDIUM',
            'status' => 'OPEN',
        ]);
    }

    public function test_admin_can_render_every_redesigned_page_inside_the_shell(): void
    {
        $pages = [
            route('dashboard'),
            route('tickets.index'),
            route('tickets.create'),
            route('tickets.show', $this->ticket),
            route('reports'),
            route('activity-log'),
            route('settings.departments'),
            route('settings.ticket-categories'),
            route('settings.users'),
            route('profile.edit'),
        ];

        foreach ($pages as $page) {
            $this->actingAs($this->admin)
                ->get($page)
                ->assertOk()
                ->assertSee('Ticketing — PT Gandasari Group Investama');
        }
    }

    public function test_requester_can_render_their_pages_and_edit_an_open_ticket(): void
    {
        $this->actingAs($this->requester)->get(route('tickets.index'))
            ->assertOk()
            ->assertSee('Ticket Saya')
            ->assertSee('TKT-20261023-0001');

        $this->actingAs($this->requester)->get(route('tickets.create'))
            ->assertOk()
            ->assertSee('Diisi otomatis dari akun Anda.');

        $this->actingAs($this->requester)->get(route('tickets.edit', $this->ticket))
            ->assertOk()
            ->assertSee('Simpan Perubahan');
    }

    public function test_ticket_detail_toolbar_depends_on_role(): void
    {
        $this->actingAs($this->admin)->get(route('tickets.show', $this->ticket))
            ->assertOk()
            ->assertSee('Ubah Status')
            ->assertSee('Ubah Agent')
            ->assertSee('Buat Ticket');

        $this->actingAs($this->requester)->get(route('tickets.show', $this->ticket))
            ->assertOk()
            ->assertSee('Print')
            ->assertDontSee('Ubah Status')
            ->assertDontSee('Ubah Agent');
    }

    public function test_internal_replies_are_only_visible_to_support(): void
    {
        TicketComment::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => $this->admin->id,
            'comment' => '<div>Catatan rahasia tim IT</div>',
            'is_internal' => true,
        ]);

        $this->actingAs($this->admin)->get(route('tickets.show', $this->ticket))
            ->assertSee('Catatan rahasia tim IT');

        $this->actingAs($this->requester)->get(route('tickets.show', $this->ticket))
            ->assertDontSee('Catatan rahasia tim IT');
    }

    public function test_closed_ticket_shows_notice_and_hides_the_reply_form(): void
    {
        $this->ticket->update(['status' => 'CLOSED']);

        $this->actingAs($this->requester)->get(route('tickets.show', $this->ticket))
            ->assertOk()
            ->assertSee('Ticket Closed')
            ->assertSee('buat ticket baru')
            ->assertSee('balasan baru tidak dapat ditambahkan')
            ->assertDontSee('Tulis balasan untuk ticket ini...');
    }
}
