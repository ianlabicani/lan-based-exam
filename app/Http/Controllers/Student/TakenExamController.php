<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\TakenExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TakenExamController extends Controller
{
    /**
     * Display a listing of taken exams for a specific exam.
     */
    public function index($examId)
    {
        // Verify the exam belongs to the authenticated teacher
        $exam = Exam::whereHas('teachers', function ($query) {
            $query->where('teacher_id', Auth::id());
        })->findOrFail($examId);

        $takenExams = TakenExam::with(['user', 'answers.item', 'exam.items'])
            ->where('exam_id', $exam->id)
            ->orderBy('submitted_at', 'desc')
            ->get()
            ->map(function ($takenExam) {
                // Compare answers for each takenExam
                $takenExam->answer_comparison = $this->compareAnswers($takenExam->exam->items, $takenExam->answers);

                return $takenExam;
            });

        return view('teacher.exams.takers', compact('exam', 'takenExams'));
    }

    /**
     * Display details of a specific taken exam.
     */
    public function show($examId, $takenExamId)
    {
        // Verify the exam belongs to the authenticated teacher
        $exam = Exam::whereHas('teachers', function ($query) {
            $query->where('teacher_id', Auth::id());
        })->findOrFail($examId);

        $takenExam = TakenExam::with(['user', 'answers.item', 'exam.items'])
            ->where('exam_id', $exam->id)
            ->findOrFail($takenExamId);

        // Compare exam items with student answers
        $comparison = $this->compareAnswers($takenExam->exam->items, $takenExam->answers);

        return view('teacher.exams.taken-exam-details', compact('exam', 'takenExam', 'comparison'));
    }

    /**
     * Compare exam items with student answers
     */
    private function compareAnswers($examItems, $studentAnswers)
    {
        // Create a lookup for student answers by exam_item_id
        $answerLookup = $studentAnswers->keyBy('exam_item_id');

        return $examItems->map(function ($item) use ($answerLookup) {
            $studentAnswer = $answerLookup->get($item->id);
            $correctAnswer = $this->getCorrectAnswer($item);

            $isCorrect = false;
            $studentResponse = null;
            $pointsEarned = 0;

            if ($studentAnswer) {
                $studentResponse = $studentAnswer->answer;
                $isCorrect = $this->checkAnswer($item, $studentAnswer->answer, $correctAnswer);
                $pointsEarned = $studentAnswer->points_earned ?? 0;
            }

            return [
                'exam_item_id' => $item->id,
                'type' => $item->type,
                'question' => $item->question,
                'points' => $item->points,
                'points_earned' => $pointsEarned,
                'correct_answer' => $correctAnswer,
                'student_answer' => $studentResponse,
                'is_correct' => $isCorrect,
                'answered' => $studentAnswer !== null,
                'options' => $item->options ?? null,
                'pairs' => $item->pairs ?? null,
                'expected_answer' => $item->expected_answer ?? null,
            ];
        });
    }

    /**
     * Get correct answer for a single exam item
     */
    private function getCorrectAnswer($item)
    {
        switch ($item->type) {
            case 'mcq':
                $options = collect($item->options ?? []);
                $correctIndex = $options->search(function ($opt) {
                    return is_array($opt)
                        ? (! empty($opt['correct']))
                        : (! empty($opt->correct));
                });

                return $correctIndex !== false ? $correctIndex : null;

            case 'truefalse':
                return $item->answer;

            case 'matching':
                return $item->pairs;

            case 'fillblank':
            case 'fill_blank':
                return $item->expected_answer;

            case 'shortanswer':
                return $item->expected_answer;

            case 'essay':
                return 'Manual grading required';

            default:
                return null;
        }
    }

    /**
     * Check if student answer is correct
     */
    private function checkAnswer($item, $studentAnswer, $correctAnswer)
    {
        if ($correctAnswer === null || $correctAnswer === 'Manual grading required') {
            return null; // Cannot auto-check
        }

        switch ($item->type) {
            case 'mcq':
                return (int) $studentAnswer === (int) $correctAnswer;

            case 'truefalse':
                $expected = strtolower(trim((string) $correctAnswer));
                $student = strtolower(trim((string) $studentAnswer));

                return $expected === $student;

            case 'matching':
                return $studentAnswer === $correctAnswer;

            case 'fillblank':
            case 'fill_blank':
            case 'shortanswer':
                return strtolower(trim((string) $studentAnswer)) === strtolower(trim((string) $correctAnswer));

            case 'essay':
                return null; // Manual grading

            default:
                return null;
        }
    }

    /**
     * Update points for a manually graded answer.
     */
    public function updatePoints(Request $request, $examId, $takenExamId, $answerId)
    {
        // Verify the exam belongs to the authenticated teacher
        $exam = Exam::whereHas('teachers', function ($query) {
            $query->where('teacher_id', Auth::id());
        })->findOrFail($examId);

        $takenExam = TakenExam::where('exam_id', $exam->id)->findOrFail($takenExamId);

        $answer = $takenExam->answers()->findOrFail($answerId);

        $validated = $request->validate([
            'points_earned' => 'required|integer|min:0|max:'.$answer->item->points,
        ]);

        $answer->update($validated);

        // Recalculate total points for the taken exam
        $totalPoints = $takenExam->answers()->sum('points_earned');
        $takenExam->update(['total_points' => $totalPoints]);

        return redirect()->back()->with('success', 'Points updated successfully!');
    }
}
