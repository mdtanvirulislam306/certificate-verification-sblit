<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function index(Request $request): Response
    {
        $courses = Course::query()
            ->withCount(['modules', 'assessments'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Courses/Index', [
            'courses' => $courses,
            'filters' => ['search' => $request->search],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Courses/Form', [
            'course' => null,
            'defaults' => $this->emptyForm(),
        ]);
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $course = Course::create([
            'name' => $request->name,
            'duration' => $request->duration,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->syncChildren($course, $request);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function edit(Course $course): Response
    {
        $course->load(['modules', 'assessments']);

        return Inertia::render('Admin/Courses/Form', [
            'course' => $course,
            'defaults' => [
                'name' => $course->name,
                'duration' => $course->duration,
                'is_active' => $course->is_active,
                'modules' => $course->modules->map(fn ($m) => [
                    'name' => $m->name,
                    'default_total' => $m->default_total,
                ])->values()->all(),
                'assessments' => $course->assessments->map(fn ($a) => [
                    'name' => $a->name,
                    'default_total' => $a->default_total,
                ])->values()->all(),
            ],
        ]);
    }

    public function update(StoreCourseRequest $request, Course $course): RedirectResponse
    {
        $course->update([
            'name' => $request->name,
            'duration' => $request->duration,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->syncChildren($course, $request);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course deleted.');
    }

    private function syncChildren(Course $course, StoreCourseRequest $request): void
    {
        $course->modules()->delete();
        $course->assessments()->delete();

        $modules = [];
        foreach (array_values($request->input('modules', [])) as $index => $module) {
            if (blank($module['name'] ?? null)) {
                continue;
            }
            $modules[] = [
                'name' => $module['name'],
                'default_total' => max(1, (int) ($module['default_total'] ?? 100)),
                'sort_order' => $index,
            ];
        }

        $assessments = [];
        foreach (array_values($request->input('assessments', [])) as $index => $assessment) {
            if (blank($assessment['name'] ?? null)) {
                continue;
            }
            $assessments[] = [
                'name' => $assessment['name'],
                'default_total' => max(1, (int) ($assessment['default_total'] ?? 100)),
                'sort_order' => $index,
            ];
        }

        $course->modules()->createMany($modules);
        $course->assessments()->createMany($assessments);
    }

    private function emptyForm(): array
    {
        return [
            'name' => '',
            'duration' => '',
            'is_active' => true,
            'modules' => [
                ['name' => '', 'default_total' => 100],
            ],
            'assessments' => [
                ['name' => '', 'default_total' => 100],
            ],
        ];
    }
}
