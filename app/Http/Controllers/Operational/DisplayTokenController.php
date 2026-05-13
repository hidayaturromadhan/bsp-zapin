<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\OperationalDisplayToken;
use Illuminate\Http\Request;

class DisplayTokenController extends Controller
{
    public function index()
    {
        $tokens = OperationalDisplayToken::query()
            ->latest('id')
            ->get();

        return view('operational.display-tokens.index', [
            'tokens' => $tokens,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'expired_at' => ['nullable', 'date'],
        ]);

        OperationalDisplayToken::create([
            'name' => $validated['name'],
            'token' => OperationalDisplayToken::generateSecureToken(),
            'is_active' => true,
            'expired_at' => $validated['expired_at'] ?? null,
        ]);

        return redirect()
            ->route('operational.display-tokens.index')
            ->with('success', 'Token display berhasil dibuat.');
    }

    public function update(Request $request, OperationalDisplayToken $displayToken)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'expired_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $displayToken->update([
            'name' => $validated['name'],
            'expired_at' => $validated['expired_at'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('operational.display-tokens.index')
            ->with('success', 'Token display berhasil diperbarui.');
    }

    public function reset(OperationalDisplayToken $displayToken)
    {
        $displayToken->update([
            'token' => OperationalDisplayToken::generateSecureToken(),
            'last_accessed_at' => null,
        ]);

        return redirect()
            ->route('operational.display-tokens.index')
            ->with('success', 'Token berhasil di-reset. Link lama tidak bisa digunakan lagi.');
    }

    public function activate(OperationalDisplayToken $displayToken)
    {
        $displayToken->update([
            'is_active' => true,
        ]);

        return redirect()
            ->route('operational.display-tokens.index')
            ->with('success', 'Token display berhasil diaktifkan.');
    }

    public function deactivate(OperationalDisplayToken $displayToken)
    {
        $displayToken->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('operational.display-tokens.index')
            ->with('success', 'Token display berhasil dinonaktifkan.');
    }

    public function destroy(OperationalDisplayToken $displayToken)
    {
        $displayToken->delete();

        return redirect()
            ->route('operational.display-tokens.index')
            ->with('success', 'Token display berhasil dihapus.');
    }
}