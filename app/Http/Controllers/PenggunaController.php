<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    public function index()
    {
        try {
            $pengguna = Pengguna::select('id', 'nama', 'email', 'hp', 'role', 'aktif', 'last_login', 'created_at')
                ->orderBy('nama')
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $pengguna,
                'message' => 'Data pengguna berhasil dimuat'
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading pengguna: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data pengguna',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $pengguna = Pengguna::select('id', 'nama', 'email', 'hp', 'role', 'aktif', 'last_login', 'created_at')
                ->findOrFail($id);
                
            return response()->json([
                'success' => true,
                'data' => $pengguna,
                'message' => 'Data pengguna berhasil dimuat'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengguna tidak ditemukan'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:100',
                'email' => 'required|email|unique:pengguna,email|max:255',
                'hp' => 'required|string|max:20',
                'password' => 'required|string|min:6|max:255',
                'role' => ['required', Rule::in(['admin', 'kepsek', 'verifikator_adm', 'keuangan', 'pendaftar'])]
            ], [
                'nama.required' => 'Nama harus diisi',
                'nama.max' => 'Nama maksimal 100 karakter',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'email.max' => 'Email maksimal 255 karakter',
                'hp.required' => 'Nomor HP harus diisi',
                'hp.max' => 'Nomor HP maksimal 20 karakter',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 6 karakter',
                'password.max' => 'Password maksimal 255 karakter',
                'role.required' => 'Role harus dipilih',
                'role.in' => 'Role tidak valid'
            ]);

            // Hash password
            $validated['password'] = Hash::make($validated['password']);
            $validated['aktif'] = true;

            $pengguna = Pengguna::create($validated);
            
            // Remove password from response
            $pengguna->makeHidden(['password']);
            
            Log::info('User created: ' . $pengguna->nama . ' (' . $pengguna->role . ') by user: ' . auth()->user()->nama);
            
            return response()->json([
                'success' => true, 
                'message' => 'User berhasil ditambahkan', 
                'data' => $pengguna
            ], 201);
            
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan user',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $pengguna = Pengguna::findOrFail($id);
            
            $validated = $request->validate([
                'nama' => 'required|string|max:100',
                'email' => 'required|email|max:255|unique:pengguna,email,' . $id,
                'hp' => 'required|string|max:20',
                'role' => ['required', Rule::in(['admin', 'kepsek', 'verifikator_adm', 'keuangan', 'pendaftar'])],
                'password' => 'nullable|string|min:6|max:255'
            ], [
                'nama.required' => 'Nama harus diisi',
                'nama.max' => 'Nama maksimal 100 karakter',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'email.max' => 'Email maksimal 255 karakter',
                'hp.required' => 'Nomor HP harus diisi',
                'hp.max' => 'Nomor HP maksimal 20 karakter',
                'password.min' => 'Password minimal 6 karakter',
                'password.max' => 'Password maksimal 255 karakter',
                'role.required' => 'Role harus dipilih',
                'role.in' => 'Role tidak valid'
            ]);

            // Hash password if provided
            if ($request->filled('password')) {
                $validated['password'] = Hash::make($request->password);
            } else {
                unset($validated['password']);
            }

            $pengguna->update($validated);
            
            // Remove password from response
            $pengguna->makeHidden(['password']);
            
            Log::info('User updated: ' . $pengguna->nama . ' by user: ' . auth()->user()->nama);
            
            return response()->json([
                'success' => true, 
                'message' => 'User berhasil diperbarui', 
                'data' => $pengguna
            ]);
            
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui user',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pengguna = Pengguna::findOrFail($id);
            
            // Prevent deleting current user
            if ($pengguna->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun sendiri'
                ], 400);
            }
            
            // Check if user has related data
            if ($pengguna->pendaftar()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak dapat dihapus karena memiliki data pendaftar'
                ], 400);
            }
            
            $namaUser = $pengguna->nama;
            $pengguna->delete();
            
            Log::info('User deleted: ' . $namaUser . ' by user: ' . auth()->user()->nama);
            
            return response()->json([
                'success' => true, 
                'message' => 'User berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $pengguna = Pengguna::findOrFail($id);
            
            // Prevent deactivating current user
            if ($pengguna->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat mengubah status akun sendiri'
                ], 400);
            }
            
            $pengguna->aktif = !$pengguna->aktif;
            $pengguna->save();
            
            $status = $pengguna->aktif ? 'diaktifkan' : 'dinonaktifkan';
            
            Log::info('User status changed: ' . $pengguna->nama . ' ' . $status . ' by user: ' . auth()->user()->nama);
            
            return response()->json([
                'success' => true,
                'data' => $pengguna->makeHidden(['password']),
                'message' => 'Status user berhasil ' . $status
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error toggling user status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status user',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
