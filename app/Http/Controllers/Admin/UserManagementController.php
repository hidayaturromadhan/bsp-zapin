<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));
        $role = trim((string) $request->get('role'));
        $status = trim((string) $request->get('status'));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->when($role !== '', function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->when($status !== '', function ($query) use ($status) {
                if ($status === 'active') {
                    $query->where('is_active', true);
                }

                if ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => User::roleOptions(),
            'search' => $search,
            'selectedRole' => $role,
            'selectedStatus' => $status,
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'roles' => User::roleOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(array_keys(User::roleOptions()))],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active'),
            'email_verified_at' => now(),
            'active_session_id' => null,
            'active_login_at' => null,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun user berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => User::roleOptions(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $authUser = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => ['required', 'string', Rule::in(array_keys(User::roleOptions()))],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
        ]);

        if ($authUser && $authUser->id === $user->id && $validated['role'] !== User::ROLE_ADMIN) {
            return back()
                ->withInput()
                ->with('error', 'Anda tidak dapat mengubah role akun admin yang sedang digunakan.');
        }

        $newStatus = $request->boolean('is_active');

        if ($authUser && $authUser->id === $user->id && $newStatus === false) {
            return back()
                ->withInput()
                ->with('error', 'Anda tidak dapat menonaktifkan akun yang sedang digunakan.');
        }

        if ($user->role === User::ROLE_ADMIN && $validated['role'] !== User::ROLE_ADMIN) {
            $activeAdminCount = User::query()
                ->where('role', User::ROLE_ADMIN)
                ->where('is_active', true)
                ->where('id', '!=', $user->id)
                ->count();

            if ($activeAdminCount < 1) {
                return back()
                    ->withInput()
                    ->with('error', 'Minimal harus ada satu akun admin aktif.');
            }
        }

        if ($user->role === User::ROLE_ADMIN && $newStatus === false) {
            $activeAdminCount = User::query()
                ->where('role', User::ROLE_ADMIN)
                ->where('is_active', true)
                ->where('id', '!=', $user->id)
                ->count();

            if ($activeAdminCount < 1) {
                return back()
                    ->withInput()
                    ->with('error', 'Minimal harus ada satu akun admin aktif.');
            }
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $newStatus,
        ]);

        if ($newStatus === false) {
            $user->forceFill([
                'active_session_id' => null,
                'active_login_at' => null,
            ])->save();
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data akun berhasil diperbarui.');
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        $user->forceFill([
            'password' => $validated['password'],
            'active_session_id' => null,
            'active_login_at' => null,
        ])->save();

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'Password akun berhasil direset. User akan diminta login ulang.');
    }

    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        $authUser = $request->user();

        if ($authUser && $authUser->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun yang sedang digunakan.');
        }

        $newStatus = !$user->is_active;

        if ($user->role === User::ROLE_ADMIN && $newStatus === false) {
            $activeAdminCount = User::query()
                ->where('role', User::ROLE_ADMIN)
                ->where('is_active', true)
                ->where('id', '!=', $user->id)
                ->count();

            if ($activeAdminCount < 1) {
                return back()->with('error', 'Minimal harus ada satu akun admin aktif.');
            }
        }

        $user->forceFill([
            'is_active' => $newStatus,
            'active_session_id' => $newStatus ? $user->active_session_id : null,
            'active_login_at' => $newStatus ? $user->active_login_at : null,
        ])->save();

        return back()->with(
            'success',
            $newStatus
                ? 'Akun berhasil diaktifkan kembali.'
                : 'Akun berhasil dinonaktifkan.'
        );
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $authUser = $request->user();

        if ($authUser && $authUser->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        if ($user->role === User::ROLE_ADMIN) {
            $activeAdminCount = User::query()
                ->where('role', User::ROLE_ADMIN)
                ->where('is_active', true)
                ->where('id', '!=', $user->id)
                ->count();

            if ($activeAdminCount < 1) {
                return back()->with('error', 'Minimal harus ada satu akun admin aktif.');
            }
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun user berhasil dihapus.');
    }
}