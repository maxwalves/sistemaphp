<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail; // Vamos criar este Mailable depois'
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class EmailTestController extends Controller
{
    public function showEmailTestForm()
    {
        $user = auth()->user();
        $userEncontrado = User::findOrFail($user->id);
        //verifique se o usuário é administrador
        try {
            if (Gate::authorize('view users', $userEncontrado)) {
                
                return view('email.test');
        }
        } catch (\Throwable $th) {
            return view('unauthorized', ['user'=> $user]);
        }
    }

    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'recipients' => 'required|string',
            'message' => 'required|string',
        ]);

        $title = $request->title;
        $recipients = explode(',', $request->recipients);
        $messageContent = $request->message;

        foreach ($recipients as $recipient) {
            Mail::to(trim($recipient))->send(new TestEmail($title, $messageContent));
        }

        return back()->with('success', 'E-mails de teste enviados com sucesso!');
    }
}
