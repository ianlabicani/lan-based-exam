<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    /**
     * Display a listing of exams.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $exams = $user->exams()
            ->with('items') // Eager load items to get count
            ->select([
                'exams.id',
                'exams.title',
                'exams.description',
                'exams.starts_at',
                'exams.ends_at',
                'exams.year',
                'exams.sections',
                'exams.status',
                'exams.total_points',
                'exams.created_at',
                'exams.updated_at',
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->makeHidden(['pivot']);

        return view('teacher.exams.index', compact('exams'));
    }

    /**
     * Show the form for creating a new exam.
     */
    public function create()
    {
        return view('teacher.exams.create');
    }

    /**
     * Store a newly created exam in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'year' => 'required|string',
            'sections' => 'required', // string like "E,F" or array
            'total_points' => 'integer|min:0',
            'tos' => 'required|array',
        ]);

        // Normalize sections (accepts comma-separated string or array)
        $sectionsInput = $request->input('sections');
        if (is_string($sectionsInput)) {
            $sections = array_values(array_filter(array_map(static function ($s) {
                return trim($s);
            }, explode(',', $sectionsInput)), static fn ($v) => $v !== ''));
        } elseif (is_array($sectionsInput)) {
            $sections = array_values(array_filter(array_map(static function ($s) {
                return is_string($s) ? trim($s) : $s;
            }, $sectionsInput), static fn ($v) => $v !== '' && $v !== null));
        } else {
            $sections = [];
        }

        if (empty($sections)) {
            return back()->withErrors(['sections' => 'At least one section is required.'])->withInput();
        }

        $payload = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'year' => $validated['year'],
            'sections' => $sections,
            'status' => 'draft', // Always create as draft
            'total_points' => $validated['total_points'] ?? 0,
            'tos' => $validated['tos'],
        ];

        // Create exam (via relation to auto-attach teacher)
        $exam = $request->user()->exams()->create($payload);

        // Redirect to the exam show page to add items
        return redirect()->route('teacher.exams.show', $exam->id)
            ->with('success', 'Exam created successfully! Now you can add questions to your exam.');
    }

    /**
     * Display the specified exam.
     */
    public function show($id)
    {
        $exam = Exam::with(['items' => function ($query) {
            $query->orderBy('id', 'asc');
        }])
            ->whereHas('teachers', function ($query) {
                $query->where('teacher_id', Auth::id());
            })
            ->findOrFail($id);

        $examItems = $exam->items;

        return view('teacher.exams.show', compact('exam', 'examItems'));
    }

    /**
     * Show the form for editing the specified exam.
     */
    public function edit($id)
    {
        $exam = Exam::whereHas('teachers', function ($query) {
            $query->where('teacher_id', Auth::id());
        })
            ->findOrFail($id);

        return view('teacher.exams.edit', compact('exam'));
    }

    /**
     * Update the specified exam in storage.
     */
    public function update(Request $request, string $id)
    {
        $exam = Exam::whereHas('teachers', function ($query) {
            $query->where('teacher_id', Auth::id());
        })->findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'starts_at' => 'sometimes|date',
            'ends_at' => 'sometimes|date|after:starts_at',
            'year' => 'sometimes|string',
            'sections' => 'sometimes', // string or array
            'status' => 'sometimes|in:draft,ready,published,ongoing,closed,graded,archived',
            'total_points' => 'sometimes|integer|min:0',
            'tos' => 'sometimes|array',
        ]);

        $payload = $validated;

        // Normalize sections when provided
        if ($request->has('sections')) {
            $sectionsInput = $request->input('sections');
            if (is_string($sectionsInput)) {
                $sections = array_values(array_filter(array_map(static function ($s) {
                    return trim($s);
                }, explode(',', $sectionsInput)), static fn ($v) => $v !== ''));
            } elseif (is_array($sectionsInput)) {
                $sections = array_values(array_filter(array_map(static function ($s) {
                    return is_string($s) ? trim($s) : $s;
                }, $sectionsInput), static fn ($v) => $v !== '' && $v !== null));
            } else {
                $sections = [];
            }

            $payload['sections'] = $sections;
        }

        $exam->update($payload);

        return redirect()->route('teacher.exams.show', $exam->id)
            ->with('success', 'Exam updated successfully!');
    }

    /**
     * Update exam status.
     */
    public function updateStatus(Request $request, $id)
    {
        $exam = Exam::whereHas('teachers', function ($query) {
            $query->where('teacher_id', Auth::id());
        })->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:draft,ready,published,ongoing,closed,graded,archived',
        ]);

        // Use the transition method to ensure valid state changes
        if ($exam->transitionTo($validated['status'])) {
            return redirect()->route('teacher.exams.show', $exam->id)
                ->with('success', 'Exam status updated successfully!');
        }

        return redirect()->route('teacher.exams.show', $exam->id)
            ->with('error', 'Invalid status transition. Please follow the exam lifecycle.');
    }

    /**
     * Remove the specified exam from storage.
     */
    public function destroy(string $id)
    {
        $exam = Exam::whereHas('teachers', function ($query) {
            $query->where('teacher_id', Auth::id());
        })->findOrFail($id);

        $exam->delete();

        return redirect()->route('teacher.exams.index')
            ->with('success', 'Exam deleted successfully!');
    }

    /**
     * Get exam takers
     */
    public function getExamTakers(int $id)
    {
        $exam = Exam::whereHas('teachers', function ($query) {
            $query->where('teacher_id', Auth::id());
        })->findOrFail($id);

        $takers = $exam->takenExams()->with('user')->get()->pluck('user')->filter();

        return view('teacher.exams.partials.takers', compact('exam', 'takers'));
    }
}
