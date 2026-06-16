<?php

namespace App\Http\Controllers\admin;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends AdminController
{
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.contacts', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'messages' => $messages,
        ]);
    }

    public function show(ContactMessage $contactMessage)
    {
        $contactMessage->update(['status' => 'read']);
        
        return view('admin.contactsShow', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'message' => $contactMessage,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        ContactMessage::create($validated);

        return redirect()->back()->with('success', 'Mensagem enviada com sucesso! Entraremos em contato em breve.');
    }

    public function markAsRead(ContactMessage $contactMessage)
    {
        $contactMessage->update(['status' => 'read']);
        return redirect()->back()->with('success', 'Marcado como lido');
    }

    public function markAsResponded(ContactMessage $contactMessage)
    {
        $contactMessage->update(['status' => 'responded']);
        return redirect()->back()->with('success', 'Marcado como respondido');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();
        return redirect()->route('contacts')->with('success', 'Mensagem deletada');
    }
}
