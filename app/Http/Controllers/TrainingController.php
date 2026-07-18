<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrainingController extends Controller
{
    public function index()
    {
        $programs = TrainingProgram::where('is_active', true)->orderBy('sort_order')->get();
        return view('training.index', compact('programs'));
    }

    public function show(TrainingProgram $program)
    {
        $relatedPrograms = TrainingProgram::where('is_active', true)
            ->where('id', '!=', $program->id)
            ->limit(3)
            ->get();
        return view('training.show', compact('program', 'relatedPrograms'));
    }

    public function book(TrainingProgram $program)
    {
        return view('training.book', compact('program'));
    }

    public function submitBooking(Request $request, TrainingProgram $program)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|string',
        ]);

        Booking::create([
            'booking_number' => 'YBS-BK-' . strtoupper(Str::random(8)),
            'trainable_type' => TrainingProgram::class,
            'trainable_id' => $program->id,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->get('customer_phone'),
            'message' => $request->get('message'),
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $request->preferred_time,
            'total' => $program->price,
        ]);

        return redirect()->route('training.booked')
            ->with('success', 'Booking request submitted! We\'ll confirm your session shortly.')
            ->with('booking_number', 'YBS-BK-' . strtoupper(Str::random(8)));
    }

    public function booked()
    {
        return view('training.booked');
    }
}
