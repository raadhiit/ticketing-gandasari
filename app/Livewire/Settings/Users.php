<?php

namespace App\Livewire\Settings;

use App\Models\Department;
use App\Models\User;
use App\Support\ActivityLogger;
use Flux\Flux;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Title('Pengguna')]
class Users extends Component
{
    public ?int $editId = null;

    public string $name = '';

    public string $email = '';

    public ?int $department_id = null;

    public string $password = '';

    public string $password_confirmation = '';

    public string $role = 'User';

    public bool $is_active = true;

    public bool $showForm = false;

    protected function rules(): array
    {
        $uniqueEmail = Rule::unique('users', 'email')->ignore($this->editId);

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $uniqueEmail],
            'department_id' => ['nullable', 'exists:departments,id'],
            'role' => ['required', 'exists:roles,name'],
            'password' => [$this->editId ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => [$this->editId ? 'nullable' : 'required'],
            'is_active' => ['boolean'],
        ];
    }

    public function create(): void
    {
        $this->authorize('create', User::class);

        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $user = User::findOrFail($id);

        $this->authorize('update', $user);

        $this->editId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->department_id = $user->department_id;
        $this->role = $user->roles->first()?->name ?? 'User';
        $this->is_active = $user->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->authorize($this->editId ? 'update' : 'create', User::class);

        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'department_id' => $this->department_id,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->editId], $data);

        $user->syncRoles([$this->role]);

        ActivityLogger::log(
            $this->editId ? 'updated' : 'created',
            $this->editId
                ? "Memperbarui pengguna {$user->name}"
                : "Menambahkan pengguna {$user->name}",
            subjectType: User::class,
            subjectId: $user->id,
            properties: ['role' => $this->role, 'email' => $user->email],
        );

        Flux::toast($this->editId ? 'Pengguna berhasil diperbarui' : 'Pengguna berhasil ditambahkan', variant: 'success');

        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $user = User::findOrFail($id);

        $this->authorize('delete', $user);

        $this->dispatch('confirm-open', name: 'confirm-delete', title: __('Hapus Pengguna'), message: __('Yakin ingin menghapus pengguna ini?'), method: 'delete', param: $id, variant: 'danger', confirmLabel: __('Ya'));
        $this->dispatch('modal-show', name: 'confirm-delete');
    }

    public function delete(int $id): void
    {
        $user = User::findOrFail($id);

        $this->authorize('delete', $user);

        ActivityLogger::log(
            'deleted',
            "Menghapus pengguna {$user->name}",
            subjectType: User::class,
            subjectId: $user->id,
            properties: ['email' => $user->email],
        );

        $user->delete();

        Flux::toast('Pengguna berhasil dihapus', variant: 'success');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editId = null;
        $this->name = '';
        $this->email = '';
        $this->department_id = null;
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'User';
        $this->is_active = true;
        $this->showForm = false;
    }

    public function render()
    {
        return view('livewire.settings.users', [
            'users' => User::with('roles', 'department')->orderBy('name')->get(),
            'departments' => Department::orderBy('name')->pluck('name', 'id'),
            'roles' => Role::orderBy('name')->pluck('name'),
        ]);
    }
}
