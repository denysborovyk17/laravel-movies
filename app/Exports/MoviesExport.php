<?php declare(strict_types=1);

namespace App\Exports;

use App\Models\Movie;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{FromCollection, ShouldAutoSize, WithHeadings};

class MoviesExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        return Movie::with('director')
            ->where('rating', '>', 7)
            ->orderBy('rating', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'title',
            'director',
            'image',
            'slug',
            'description',
            'year',
            'genre',
            'rating',
            'status'
        ];
    }
}
