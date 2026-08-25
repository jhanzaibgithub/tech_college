<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class EnrollmentService
{
    public function create(Course $course, array $data): Enrollment
    {
        return $course->enrollments()->create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'message' => $data['message'] ?? null,
            'status' => Enrollment::STATUS_NEW,
        ]);
    }

    public function filtered(?string $status = null): Collection
    {
        return Enrollment::query()
            ->with('course')
            ->when($status && $status !== 'all', fn ($query) => $query->where('status', $status))
            ->latest()
            ->get();
    }

    public function monthlyCounts(int $months = 6): array
    {
        $start = now()->startOfMonth()->subMonths($months - 1);
        $rows = Enrollment::query()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->where('created_at', '>=', $start)
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(fn ($row) => $row->year . '-' . $row->month);

        return collect(range(0, $months - 1))->map(function ($offset) use ($start, $rows) {
            $date = (clone $start)->addMonths($offset);
            $key = $date->year . '-' . $date->month;

            return [
                'label' => $date->format('M'),
                'total' => (int) ($rows[$key]->total ?? 0),
            ];
        })->all();
    }

    public function statuses(): array
    {
        return [
            'new' => 'New Requests',
            'confirmed' => 'Confirmed Students',
            'completed' => 'Completed Students',
        ];
    }
}
