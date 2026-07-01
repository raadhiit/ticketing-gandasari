<?php

namespace App\Livewire\Settings;

use App\Models\TicketCategory;
use Flux\Flux;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Kategori Ticket')]
class TicketCategories extends Component
{
    public ?int $editId = null;

    public string $name = '';

    public string $description = '';

    public bool $showForm = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:ticket_categories,name,'.$this->editId],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function create(): void
    {
        $this->authorize('create', TicketCategory::class);

        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $category = TicketCategory::findOrFail($id);

        $this->authorize('update', $category);

        $this->editId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description ?? '';
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->authorize($this->editId ? 'update' : 'create', TicketCategory::class);

        $this->validate();

        TicketCategory::updateOrCreate(['id' => $this->editId], [
            'name' => $this->name,
            'description' => $this->description,
        ]);

        Flux::toast($this->editId ? 'Kategori berhasil diperbarui' : 'Kategori berhasil ditambahkan', variant: 'success');

        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $category = TicketCategory::findOrFail($id);

        $this->authorize('delete', $category);

        $this->dispatch('confirm-open', name: 'confirm-delete', title: __('Hapus Kategori'), message: __('Yakin ingin menghapus kategori ini?'), method: 'delete', param: $id, variant: 'danger', confirmLabel: __('Ya'));
        $this->dispatch('modal-show', name: 'confirm-delete');
    }

    public function delete(int $id): void
    {
        $category = TicketCategory::findOrFail($id);

        $this->authorize('delete', $category);

        $category->delete();

        Flux::toast('Kategori berhasil dihapus', variant: 'success');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editId = null;
        $this->name = '';
        $this->description = '';
        $this->showForm = false;
    }

    public function render()
    {
        return view('livewire.settings.ticket-categories', [
            'categories' => TicketCategory::orderBy('name')->get(),
        ]);
    }
}
