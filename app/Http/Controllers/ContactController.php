<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Rules\PakistaniMobile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('contact', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20', new PakistaniMobile()],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        if (! empty($data['phone'])) {
            $data['phone'] = PakistaniMobile::normalize($data['phone']);
        }

        ContactMessage::create($data);

        return redirect()->route('contact')->with('status', 'Thank you! Your message has been sent and we will get back to you soon.');
    }
}
