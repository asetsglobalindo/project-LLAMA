<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PartnershipController extends Controller
{
    public function index()
    {
        return view('partnership');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'names' => 'required|string|min:2|max:50',
            'email' => 'required|email',
            'message' => 'required|string|min:10|max:500',
        ]);

        $details = [
            'names' => $request->names,
            'email' => $request->email,
            'message' => $request->message,
        ];

        try {
            Mail::raw("Nama: {$request->names}\nEmail: {$request->email}\nPesan: {$request->message}", function ($message) use ($request) {
                $message->to('corcomm.asets.co@gmail.com')
                    ->subject('Asets - Partnership');
            });

            return redirect()->route('partnership')->with('success', 'email send!');
        } catch (\Throwable $th) {
            return redirect()->route('partnership')->with('error', 'email not send or try again later.');
        }
    }
}
