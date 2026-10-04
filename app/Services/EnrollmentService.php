<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Rules\PakistaniMobile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class EnrollmentService
{
    public function create(Course $course, array $data): Enrollment
    {
        return $course->enrollments()->create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => PakistaniMobile::normalize($data['phone']) ?? $data['phone'],
            'message' => $data['message'] ?? null,
            'status' => Enrollment::STATUS_NEW,
        ]);
    }

    /**
     * @param array{q?: ?string, course_id?: ?string, status?: ?string, period?: ?string, date_from?: ?string, date_to?: ?string} $filters
     */
    public function filtered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        [$from, $to] = $this->dateRange($filters);

        return Enrollment::query()
            ->with('course')
            ->when(! empty($filters['q']), function ($query) use ($filters) {
                $term = '%' . addcslashes(trim($filters['q']), '%_\\') . '%';
                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhereHas('course', fn ($course) => $course->where('title', 'like', $term));
                });
            })
            ->when(! empty($filters['course_id']), fn ($query) => $query->where('course_id', $filters['course_id']))
            ->when(! empty($filters['status']) && $filters['status'] !== 'all', fn ($query) => $query->where('status', $filters['status']))
            ->when($from, fn ($query) => $query->where('created_at', '>=', $from))
            ->when($to, fn ($query) => $query->where('created_at', '<=', $to))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /** Enrollment counts per status, for the filter chips. */
    public function statusCounts(): array
    {
        return Enrollment::query()->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status')->all();
    }

    public function periods(): array
    {
        return [
            'today' => 'Today',
            'week' => 'Last 7 days',
            'month' => 'This month',
            'custom' => 'Custom range',
        ];
    }

    /** @return array{0: ?Carbon, 1: ?Carbon} */
    private function dateRange(array $filters): array
    {
        return match ($filters['period'] ?? null) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'week' => [now()->subDays(6)->startOfDay(), now()->endOfDay()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'custom' => [
                ! empty($filters['date_from']) ? Carbon::parse($filters['date_from'])->startOfDay() : null,
                ! empty($filters['date_to']) ? Carbon::parse($filters['date_to'])->endOfDay() : null,
            ],
            default => [null, null],
        };
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
