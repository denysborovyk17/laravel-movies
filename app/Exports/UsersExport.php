<?php declare(strict_types=1);

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{FromCollection, ShouldAutoSize, WithHeadings};

class UsersExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        return User::where('name', 'like', 'A%')
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at'
        ];
    }
}
