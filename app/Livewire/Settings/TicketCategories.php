<?php

namespace App\Livewire\Settings;

use App\Models\TicketCategory;
use App\Support\ActivityLogger;
use Flux\Flux;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Kategori Ticket')]
class TicketCategories extends Component
{
    use WithPagination;

    public ?int $editId = null;

    public string $name = '';

    public string $description = '';

    public bool $showForm = false;

    public int $perPage = 10;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:ticket_categories,name,' . $this->editId],
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
        $this->validate();

        if ($this->editId) {
            $category = TicketCategory::findOrFail($this->editId);

            $this->authorize('update', $category);

            $category->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            ActivityLogger::log(
                'updated',
                "Memperbarui kategori {$category->name}",
                subjectType: TicketCategory::class,
                subjectId: $category->id,
            );

            Flux::toast('Kategori berhasil diperbarui', variant: 'success');
        } else {
            $this->authorize('create', TicketCategory::class);

            $category = TicketCategory::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            ActivityLogger::log(
                'created',
                "Menambahkan kategori {$category->name}",
                subjectType: TicketCategory::class,
                subjectId: $category->id,
            );

            Flux::toast('Kategori berhasil ditambahkan', variant: 'success');
        }

        $this->resetForm();
        $this->resetPage();
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

        ActivityLogger::log(
            'deleted',
            "Menghapus kategori {$category->name}",
            subjectType: TicketCategory::class,
            subjectId: $category->id,
        );

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
        $categories = TicketCategory::orderBy('name')->paginate($this->perPage);
        $totalCategories = TicketCategory::count();
        return view('livewire.settings.ticket-categories', [
            'categories' => $categories,
            'totalCategories' => $totalCategories,
        ]);
    }
}
