<?php

namespace App\Livewire\Settings;

use App\Models\Department;
use App\Support\ActivityLogger;
use Flux\Flux;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Departemen')]
class Departments extends Component
{
    public ?int $editId = null;

    public string $name = '';

    public string $description = '';

    public bool $showForm = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:departments,name,' . $this->editId],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function create(): void
    {
        $this->authorize('create', Department::class);

        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $department = Department::findOrFail($id);

        $this->authorize('update', $department);

        $this->editId = $department->id;
        $this->name = $department->name;
        $this->description = $department->description ?? '';
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editId) {
            $department = Department::findOrFail($this->editId);

            $this->authorize('update', $department);

            $department->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            ActivityLogger::log(
                'updated',
                "Memperbarui departemen {$department->name}",
                subjectType: Department::class,
                subjectId: $department->id,
            );

            Flux::toast('Departemen berhasil diperbarui', variant: 'success');
        } else {
            $this->authorize('create', Department::class);

            $department = Department::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            ActivityLogger::log(
                'created',
                "Menambahkan departemen {$department->name}",
                subjectType: Department::class,
                subjectId: $department->id,
            );

            Flux::toast('Departemen berhasil ditambahkan', variant: 'success');
        }

        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $department = Department::findOrFail($id);

        $this->authorize('delete', $department);

        $this->dispatch('confirm-open', name: 'confirm-delete', title: __('Hapus Departemen'), message: __('Yakin ingin menghapus departemen ini?'), method: 'delete', param: $id, variant: 'danger', confirmLabel: __('Ya'));
        $this->dispatch('modal-show', name: 'confirm-delete');
    }

    public function delete(int $id): void
    {
        $department = Department::findOrFail($id);

        $this->authorize('delete', $department);

        ActivityLogger::log(
            'deleted',
            "Menghapus departemen {$department->name}",
            subjectType: Department::class,
            subjectId: $department->id,
        );

        $department->delete();

        Flux::toast('Departemen berhasil dihapus', variant: 'success');
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
        return view('livewire.settings.departments', [
            'departments' => Department::orderBy('name')->get(),
        ]);
    }
}
