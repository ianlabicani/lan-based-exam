<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\TakenExam;
use App\Models\TakenExamAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExamController extends Controller
{
    /**
     * Display available exams for the student
     */
    public function index()
    {
        $user = Auth::user();

        // Get published exams for student's year and section
        $exams = Exam::where('status', 'published')
            ->where(function ($query) use ($user) {
                // Check if student's year is in the year array
                $query->whereJsonContains('year', $user->year)
                    ->orWhereRaw('JSON_CONTAINS(year, ?)', [json_encode($user->year)]);
            })
            ->where(function ($query) use ($user) {
                // Check if student's section is in the sections array
                $query->whereJsonContains('sections', $user->section)
                    ->orWhereRaw('JSON_CONTAINS(sections, ?)', [json_encode($user->section)]);
            })
            ->with('items')
            ->orderBy('starts_at', 'desc')
            ->get()
            ->map(function ($exam) use ($user) {
                // Check if student has already taken this exam
                $takenExam = TakenExam::where('exam_id', $exam->id)
                    ->where('user_id', $user->id)
                    ->first();

                $exam->taken = $takenExam !== null;
                $exam->taken_exam = $takenExam;
                $exam->is_available = $this->isExamAvailable($exam);

                return $exam;
            });

        return view('student.exams.index', compact('exams'));
    }

    /**
     * Show exam taking interface
     */
    public function take($id)
    {
        $user = Auth::user();
        $exam = Exam::with('items')->findOrFail($id);

        // Verify exam is available
        if (!$this->isExamAvailable($exam)) {
            return redirect()->route('student.exams.index')
                ->with('error', 'This exam is not currently available.');
        }

        // Check if student already took this exam
        $takenExam = TakenExam::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->first();

        if ($takenExam && $takenExam->submitted_at) {
            return redirect()->route('student.exams.results', $exam->id)
                ->with('info', 'You have already submitted this exam.');
        }

        // If not started, create taken exam record
        if (!$takenExam) {
            $takenExam = TakenExam::create([
                'exam_id' => $exam->id,
                'user_id' => $user->id,
                'started_at' => now(),
                'total_points' => 0
            ]);
        }

        // Load existing answers
        $answers = $takenExam->answers()->with('item')->get()->keyBy('exam_item_id');

        return view('student.exams.take', compact('exam', 'takenExam', 'answers'));
    }

    /**
     * Save student answer (AJAX)
     */
    public function saveAnswer(Request $request, $id)
    {
        $user = Auth::user();
        $exam = Exam::findOrFail($id);

        $validated = $request->validate([
            'item_id' => 'required|exists:exam_items,id',
            'answer' => 'required'
        ]);

        $takenExam = TakenExam::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Check if already submitted
        if ($takenExam->submitted_at) {
            return response()->json(['error' => 'Exam already submitted'], 403);
        }

        // Save or update answer
        $answer = TakenExamAnswer::updateOrCreate(
            [
                'taken_exam_id' => $takenExam->id,
                'exam_item_id' => $validated['item_id']
            ],
            [
                'answer' => $validated['answer']
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Answer saved successfully'
        ]);
    }

    /**
     * Submit exam and calculate score
     */
    public function submit(Request $request, $id)
    {
        $user = Auth::user();
        $exam = Exam::with('items')->findOrFail($id);

        $takenExam = TakenExam::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Check if already submitted
        if ($takenExam->submitted_at) {
            return redirect()->route('student.exams.results', $exam->id)
                ->with('info', 'You have already submitted this exam.');
        }

        DB::beginTransaction();
        try {
            // Auto-grade objective questions
            foreach ($exam->items as $item) {
                $answer = TakenExamAnswer::where('taken_exam_id', $takenExam->id)
                    ->where('exam_item_id', $item->id)
                    ->first();

                if ($answer) {
                    $pointsEarned = $this->gradeAnswer($item, $answer->answer);
                    $answer->update(['points_earned' => $pointsEarned]);
                }
            }

            // Calculate total points
            $totalPoints = $takenExam->answers()->sum('points_earned');

            // Mark as submitted
            $takenExam->update([
                'submitted_at' => now(),
                'total_points' => $totalPoints
            ]);

            DB::commit();

            return redirect()->route('student.exams.results', $exam->id)
                ->with('success', 'Exam submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to submit exam. Please try again.');
        }
    }

    /**
     * Show exam results
     */
    public function results($id)
    {
        $user = Auth::user();
        $exam = Exam::with('items')->findOrFail($id);

        $takenExam = TakenExam::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->with('answers.item')
            ->firstOrFail();

        if (!$takenExam->submitted_at) {
            return redirect()->route('student.exams.take', $exam->id)
                ->with('info', 'Please complete and submit the exam first.');
        }

        // Calculate statistics
        $totalQuestions = $exam->items->count();
        $answeredQuestions = $takenExam->answers->count();
        $correctAnswers = $takenExam->answers->where('points_earned', '>', 0)->count();
        $percentage = $exam->total_points > 0 ? ($takenExam->total_points / $exam->total_points) * 100 : 0;

        return view('student.exams.results', compact(
            'exam',
            'takenExam',
            'totalQuestions',
            'answeredQuestions',
            'correctAnswers',
            'percentage'
        ));
    }

    /**
     * Check if exam is currently available
     */
    private function isExamAvailable($exam)
    {
        if ($exam->status !== 'published') {
            return false;
        }

        $now = Carbon::now();
        $startsAt = Carbon::parse($exam->starts_at);
        $endsAt = Carbon::parse($exam->ends_at);

        return $now->between($startsAt, $endsAt);
    }

    /**
     * Grade a single answer
     */
    private function gradeAnswer($item, $studentAnswer)
    {
        switch ($item->type) {
            case 'mcq':
                $correctOption = null;
                foreach ($item->options as $index => $option) {
                    if (isset($option['correct']) && $option['correct']) {
                        $correctOption = $index;
                        break;
                    }
                }
                return (int)$studentAnswer === $correctOption ? $item->points : 0;

            case 'truefalse':
                $expected = strtolower(trim($item->answer));
                $student = strtolower(trim($studentAnswer));
                return $expected === $student ? $item->points : 0;

            case 'fillblank':
            case 'fill_blank':
                $expected = strtolower(trim($item->expected_answer));
                $student = strtolower(trim($studentAnswer));
                return $expected === $student ? $item->points : 0;

            case 'shortanswer':
            case 'essay':
                // Manual grading required
                return 0;

            case 'matching':
                // For now, exact match required
                return $studentAnswer === $item->pairs ? $item->points : 0;

            default:
                return 0;
        }
    }
}
