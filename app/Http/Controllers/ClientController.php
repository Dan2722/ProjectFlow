<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // عرض قائمة العملاء
    public function index()
    {
        $clients = Client::with('user')->get();
        return view('clients.index', compact('clients'));
    }

    // إضافة عميل جديد
    public function store(Request $request)
    {
        $request->validate([
            'phone'        => 'required|string|max:20',
            'company_name' => 'required|string|max:255',
        ]);

        Client::create([
            'phone'        => $request->phone,
            'company_name' => $request->company_name,
            'user_id'      => auth()->id() ?? 1,
        ]);

        return redirect()->back()->with('success', 'تمت إضافة العميل بنجاح!');
    }
}