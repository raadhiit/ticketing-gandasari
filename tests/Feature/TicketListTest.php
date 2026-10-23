<?php

namespace Tests\Feature;

use App\Livewire\Dashboard\Index as Dashboard;
use App\Livewire\Ticket\Index;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TicketListTest extends TestCase
{
    use RefreshDatabase;

    private User $requester;

    private User $agent;

    private User $otherAgent;

    private Department $finance;

    private Department $it;

    private TicketCategory $hardware;

    private TicketCategory $software;

    private int $sequence = 0;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);

        $this->finance = Department::create(['name' => 'Finance']);
        $this->it = Department::create(['name' => 'Information Technology']);
        $this->hardware = TicketCategory::create(['name' => 'Hardware']);
        $this->software = TicketCategory::create(['name' => 'Software']);

        $this->requester = $this->makeUser('User');
        $this->agent = $this->makeUser('IT ERP');
        $this->otherAgent = $this->makeUser('IT ERP');
    }

    protected function tearDown(): void
    {
        Model::preventLazyLoading(false);

        parent::tearDown();
    }

    private function makeUser(string $role): User
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole($role);

        return $user;
    }

    private function makeTicket(string $title, array $attributes = [], ?User $assignedTo = null): Ticket
    {
        $this->sequence++;

        $createdAt = $attributes['created_at'] ?? null;
        unset($attributes['created_at']);

        $ticket = Ticket::create(array_merge([
            'ticket_number' => 'TKT-20261023-'.str_pad((string) $this->sequence, 4, '0', STR_PAD_LEFT),
            'requester_id' => $this->requester->id,
            'department_id' => $this->it->id,
            'category_id' => $this->hardware->id,
            'title' => $title,
            'description' => '<div>Deskripsi</div>',
            'priority' => 'MEDIUM',
            'status' => 'OPEN',
        ], $attributes));

        if ($createdAt) {
            Ticket::whereKey($ticket->id)->update(['created_at' => $createdAt]);
        }

        if ($assignedTo) {
            $ticket->assignments()->create([
                'assigned_to' => $assignedTo->id,
                'assigned_by' => $assignedTo->id,
                'assigned_at' => now(),
            ]);
        }

        return $ticket;
    }

    public function test_support_sees_every_ticket_and_the_my_tickets_tab_narrows_to_their_assignments(): void
    {
        $this->makeTicket('Milik agent satu', assignedTo: $this->agent);
        $this->makeTicket('Milik agent dua', assignedTo: $this->otherAgent);
        $this->makeTicket('Belum ditugaskan');

        Livewire::actingAs($this->agent)
            ->test(Index::class)
            ->assertSee('Milik agent satu')
            ->assertSee('Milik agent dua')
            ->assertSee('Belum ditugaskan')
            ->set('mine', true)
            ->assertSee('Milik agent satu')
            ->assertDontSee('Milik agent dua')
            ->assertDontSee('Belum ditugaskan');
    }

    public function test_my_tickets_flag_does_not_widen_a_regular_users_scope(): void
    {
        $this->makeTicket('Tiket saya');
        $this->makeTicket('Tiket orang lain', ['requester_id' => $this->makeUser('User')->id]);

        Livewire::actingAs($this->requester)
            ->test(Index::class)
            ->set('mine', true)
            ->assertSee('Tiket saya')
            ->assertDontSee('Tiket orang lain');
    }

    public function test_list_can_be_filtered_by_department_and_category(): void
    {
        $this->makeTicket('Laptop rusak', ['department_id' => $this->finance->id, 'category_id' => $this->hardware->id]);
        $this->makeTicket('Aplikasi error', ['department_id' => $this->finance->id, 'category_id' => $this->software->id]);
        $this->makeTicket('Mouse hilang', ['department_id' => $this->it->id, 'category_id' => $this->hardware->id]);

        Livewire::actingAs($this->agent)
            ->test(Index::class)
            ->set('departmentFilter', (string) $this->finance->id)
            ->assertSee('Laptop rusak')
            ->assertSee('Aplikasi error')
            ->assertDontSee('Mouse hilang')
            ->set('categoryFilter', (string) $this->hardware->id)
            ->assertSee('Laptop rusak')
            ->assertDontSee('Aplikasi error')
            ->assertDontSee('Mouse hilang');
    }

    public function test_list_does_not_lazy_load_relations(): void
    {
        foreach (range(1, 3) as $i) {
            $this->makeTicket("Ticket nomor {$i}", assignedTo: $this->agent);
        }

        Model::preventLazyLoading();

        Livewire::actingAs($this->agent)
            ->test(Index::class)
            ->assertOk()
            ->assertSee('Ticket nomor 3');
    }

    public function test_dashboard_supports_last_30_days_and_this_year_periods(): void
    {
        $this->travelTo('2026-10-23 10:00:00');

        $this->makeTicket('Baru dibuat');
        $this->makeTicket('Dua bulan lalu', ['created_at' => now()->subDays(60)]);
        $this->makeTicket('Tahun lalu', ['created_at' => now()->subYear()->subDays(10)]);

        $total = fn (string $period) => Livewire::actingAs($this->agent)
            ->test(Dashboard::class)
            ->set('period', $period)
            ->viewData('stats')['total'];

        $this->assertSame(3, $total('all'));
        $this->assertSame(1, $total('30d'));
        $this->assertSame(2, $total('year'));
    }
}
