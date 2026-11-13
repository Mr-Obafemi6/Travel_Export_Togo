<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $filtre = $request->string('filtre')->toString();

        $messages = ContactMessage::with('utilisateur')
            ->when($filtre === 'non_lus', fn ($query) => $query->where('lu', false))
            ->when($filtre === 'lus', fn ($query) => $query->where('lu', true))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.messages.index', [
            'messages' => $messages,
            'filtre' => $filtre,
            'nonLus' => ContactMessage::where('lu', false)->count(),
        ]);
    }

    public function show(ContactMessage $message): View
    {
        if (! $message->lu) {
            $message->update(['lu' => true]);
        }

        return view('admin.messages.show', [
            'message' => $message->load('utilisateur'),
        ]);
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', 'Le message a été supprimé.');
    }
}
