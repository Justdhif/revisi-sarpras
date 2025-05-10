<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::all()->map(function ($user) {
            return [
                'ID' => $user->id,
                'Username' => $user->username,
                'Email' => $user->email,
                'No. HP' => $user->phone,
                'Role' => ucfirst($user->role),
                'Tanggal Dibuat' => $user->created_at->format('Y-m-d H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Username', 'Email', 'No. HP', 'Role', 'Tanggal Dibuat'];
    }
}
