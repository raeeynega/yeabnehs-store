<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('is_active', true)->orderBy('sort_order')->get();
        return view('courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $course->load('lessons');
        $relatedCourses = Course::where('is_active', true)
            ->where('id', '!=', $course->id)
            ->limit(3)
            ->get();
        return view('courses.show', compact('course', 'relatedCourses'));
    }
}
