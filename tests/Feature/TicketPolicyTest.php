<?php

namespace Tests\Feature;

use App\Livewire\Ticket\Show;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TicketPolicyTest extends TestCase
{
    use RefreshDatabase;

    private User $requester;

    private User $otherUser;

    private User $agent;

    private User $superadmin;

    private int $sequence = 0;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);

        $this->requester = $this->makeUser('User');
        $this->otherUser = $this->makeUser('User');
        $this->agent = $this->makeUser('IT ERP');
        $this->superadmin = $this->makeUser('superadmin');
    }

    private function makeUser(string $role): User
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole($role);

        return $user;
    }

    private function makeTicket(array $attributes = []): Ticket
    {
        $this->sequence++;

        return Ticket::create(array_merge([
            'ticket_number' => 'TKT-20261023-'.str_pad((string) $this->sequence, 4, '0', STR_PAD_LEFT),
            'requester_id' => $this->requester->id,
            'title' => 'Printer tidak bisa mencetak',
            'description' => '<div>Printer lantai 2 macet.</div>',
            'priority' => 'MEDIUM',
            'status' => 'OPEN',
        ], $attributes));
    }

    public function test_requester_can_delete_their_own_untouched_open_ticket(): void
    {
        $ticket = $this->makeTicket();

        $this->assertTrue($this->requester->can('delete', $ticket));
    }

    public function test_requester_cannot_delete_a_ticket_that_is_no_longer_untouched(): void
    {
        $inProgress = $this->makeTicket(['status' => 'IN_PROGRESS']);

        $assigned = $this->makeTicket();
        $assigned->assignments()->create([
            'assigned_to' => $this->agent->id,
            'assigned_by' => $this->superadmin->id,
            'assigned_at' => now(),
        ]);

        $replied = $this->makeTicket();
        TicketComment::create(['ticket_id' => $replied->id, 'user_id' => $this->agent->id, 'comment' => '<div>Dicek ya</div>', 'is_internal' => false]);

        $internalNote = $this->makeTicket();
        TicketComment::create(['ticket_id' => $internalNote->id, 'user_id' => $this->agent->id, 'comment' => '<div>Catatan</div>', 'is_internal' => true]);

        $someoneElses = $this->makeTicket(['requester_id' => $this->otherUser->id]);

        foreach ([$inProgress, $assigned, $replied, $internalNote, $someoneElses] as $ticket) {
            $this->assertFalse($this->requester->can('delete', $ticket), "Ticket {$ticket->ticket_number} should not be deletable.");
        }
    }

    public function test_support_can_delete_any_ticket(): void
    {
        $ticket = $this->makeTicket(['status' => 'IN_PROGRESS']);

        $this->assertTrue($this->agent->can('delete', $ticket));
        $this->assertTrue($this->superadmin->can('delete', $ticket));
    }

    public function test_nobody_can_reply_to_a_closed_ticket(): void
    {
        $ticket = $this->makeTicket();

        foreach ([$this->requester, $this->agent, $this->superadmin] as $user) {
            $this->assertTrue($user->can('comment', $ticket));
        }

        $ticket->update(['status' => 'CLOSED']);

        foreach ([$this->requester, $this->agent, $this->superadmin] as $user) {
            $this->assertFalse($user->can('comment', $ticket->fresh()));
        }
    }

    public function test_only_superadmin_can_reopen_a_closed_ticket(): void
    {
        $closed = $this->makeTicket(['status' => 'CLOSED']);
        $open = $this->makeTicket();

        $this->assertTrue($this->superadmin->can('reopen', $closed));
        $this->assertFalse($this->agent->can('reopen', $closed));
        $this->assertFalse($this->requester->can('reopen', $closed));
        $this->assertFalse($this->superadmin->can('reopen', $open));
    }

    public function test_requester_can_delete_an_untouched_ticket_from_the_detail_page(): void
    {
        $ticket = $this->makeTicket();

        Livewire::actingAs($this->requester)
            ->test(Show::class, ['ticket' => $ticket])
            ->call('delete')
            ->assertRedirect(route('tickets.index'));

        $this->assertSoftDeleted($ticket);
        $this->assertSame($this->requester->id, Ticket::withTrashed()->find($ticket->id)->deleted_by);
    }

    public function test_requester_cannot_delete_a_replied_ticket_from_the_detail_page(): void
    {
        $ticket = $this->makeTicket();
        TicketComment::create(['ticket_id' => $ticket->id, 'user_id' => $this->agent->id, 'comment' => '<div>Dicek ya</div>', 'is_internal' => false]);

        Livewire::actingAs($this->requester)
            ->test(Show::class, ['ticket' => $ticket])
            ->call('delete')
            ->assertForbidden();

        $this->assertNotSoftDeleted($ticket);
    }

    public function test_reply_to_a_closed_ticket_is_rejected_by_the_server(): void
    {
        $ticket = $this->makeTicket(['status' => 'CLOSED']);

        Livewire::actingAs($this->requester)
            ->test(Show::class, ['ticket' => $ticket])
            ->set('comment', '<div>Masih bermasalah</div>')
            ->call('addComment')
            ->assertForbidden();

        $this->assertSame(0, $ticket->comments()->count());
    }

    public function test_agent_cannot_reopen_but_superadmin_can(): void
    {
        $ticket = $this->makeTicket(['status' => 'CLOSED']);

        Livewire::actingAs($this->agent)
            ->test(Show::class, ['ticket' => $ticket])
            ->call('changeStatus', 'REOPEN')
            ->assertForbidden();

        $this->assertSame('CLOSED', $ticket->fresh()->status);

        Livewire::actingAs($this->superadmin)
            ->test(Show::class, ['ticket' => $ticket])
            ->call('changeStatus', 'REOPEN');

        $this->assertSame('OPEN', $ticket->fresh()->status);
    }
}
