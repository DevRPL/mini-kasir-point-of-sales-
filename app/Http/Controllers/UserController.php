<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private const COLS = ['id', 'name', 'email', 'role', 'is_active'];

    private function rules($id = null, bool $creating = false): array
    {
        return [
            'name'      => 'required|string|max:100',
            'email'     => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($id)],
            'role'      => ['required', Rule::in(['admin', 'kasir'])],
            'is_active' => 'boolean',
            'password'  => [$creating ? 'required' : 'nullable', 'string', 'min:6', 'max:100'],
        ];
    }

    public function index() { return User::orderBy('name')->get(self::COLS); }

    public function store(Request $r)
    {
        $d = $r->validate($this->rules(null, true));
        $d['password'] = Hash::make($d['password']);
        return response()->json(User::create($d)->only(self::COLS), 201);
    }

    public function update(Request $r, User $user)
    {
        $d = $r->validate($this->rules($user->id));
        if ($user->id === $r->user()->id && (($d['role'] ?? 'admin') !== 'admin' || ($d['is_active'] ?? true) === false)) {
            abort(422, 'Anda tidak dapat menurunkan peran atau menonaktifkan akun sendiri.');
        }
        empty($d['password']) ? $d = array_diff_key($d, ['password' => 1]) : $d['password'] = Hash::make($d['password']);
        $user->update($d);
        return $user->only(self::COLS);
    }

    public function destroy(Request $r, User $user)
    {
        if ($user->id === $r->user()->id) abort(422, 'Anda tidak dapat menghapus akun sendiri.');
        $user->delete();
        return response()->noContent();
    }
}
