<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function show(Client $client)
    {
        return view('clients.profile', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'inn' => 'required|string|max:12',
            'ogrn' => 'required|string|max:13',
            'ownership_form' => 'required|string',
            'legal_address' => 'required|string',
            'phone' => 'required|string',
            'contact_person' => 'required|string',
        ]);

        $client->update($validated);

        return back()->with('success', 'Карточка клиента успешно обновлена');
    }
}