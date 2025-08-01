<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    // showAll
    public function showAll()
    {
        $courses = Course::all();

        return response()->json([
            'ok' => true,
            'data' => $courses,
        ], 200);
    }
}
