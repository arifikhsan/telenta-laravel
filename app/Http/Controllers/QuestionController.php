<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;


class QuestionController extends Controller
{
    public function index(): Response
    {
        $questions = Question::all();
        return Inertia::render('Questions', ['questions' => $questions]);
    }
}
