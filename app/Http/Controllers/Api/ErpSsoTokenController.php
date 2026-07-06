<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\ErpSsoToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ErpSsoTokenController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'erp_user_id' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'department_code' => ['required', 'string', 'max:100'],
            'department_name' => ['required', 'string', 'max:100'],
        ]);

        $result = DB::transaction(function () use ($request, $data) {
            $departmentCode = trim($data['department_code']);
            $departmentName = trim($data['department_name']);

            $department = Department::where('erp_code', $departmentCode)->first();

            if (! $department) {
                $department = Department::where('name', $departmentName)->first();
            }

            if ($department) {
                $department->update([
                    'erp_code' => $department->erp_code ?: $departmentCode,
                    'name' => $department->name ?: $departmentName,
                    'description' => $department->description ?: 'Imported from ERP',
                ]);
            } else {
                $department = Department::create([
                    'erp_code' => $departmentCode,
                    'name' => $departmentName,
                    'description' => 'Imported from ERP',
                ]);
            }

            $user = User::firstOrNew([
                'email' => strtolower($data['email']),
            ]);

            if (! $user->exists) {
                $user->password = Str::password(32);
                $user->is_active = true;
            }

            $user->fill([
                'erp_user_id' => $data['erp_user_id'],
                'name' => $data['name'],
                'email' => strtolower($data['email']),
                'department_id' => $department->id,
            ]);

            $user->save();

            if (! $user->hasAnyRole(['User', 'IT ERP', 'superadmin'])) {
                $user->assignRole('User');
            }

            $plainToken = Str::random(64);

            ErpSsoToken::create([
                'token_hash' => hash('sha256', $plainToken),
                'user_id' => $user->id,
                'expires_at' => now()->addMinutes((int) config('services.erp.sso_ttl_minutes', 2)),
                'erp_user_id' => $data['erp_user_id'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return [
                'plain_token' => $plainToken,
            ];
        });

        return response()->json([
            'login_url' => route('erp.sso.login', ['token' => $result['plain_token']]),
            'expires_in' => ((int) config('services.erp.sso_ttl_minutes', 2)) * 60,
        ]);
    }
}