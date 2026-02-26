<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // In production, send email notification or store in DB
        // For now, just redirect with success message

        return back()->with('success', 'Thank you for your message! We\'ll get back to you soon.');
    }
}
