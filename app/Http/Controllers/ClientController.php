<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // عرض قائمة العملاء
    public function index()
    {
        $clients = Client::latest()->get();
        return view('clients.index', compact('clients'));
    }

    // حفظ عميل جديد في قاعدة البيانات
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'required|string|max:20',
            'project_name' => 'required|string|max:255',
        ]);

        Client::create([
            'name'         => $request->name,
            'company_name' => $request->company_name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'project_name' => $request->project_name,
        ]);

        return redirect()->back()->with('success', 'تمت إضافة العميل بنجاح');
    }

    // تحديث بيانات عميل في قاعدة البيانات
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'required|string|max:20',
            'project_name' => 'required|string|max:255',
        ]);

        $client->update([
            'name'         => $request->name,
            'company_name' => $request->company_name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'project_name' => $request->project_name,
        ]);

        return redirect()->back()->with('success', 'تم تعديل بيانات العميل بنجاح');
    }

    // حذف العميل من قاعدة البيانات
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->back()->with('success', 'تم حذف العميل بنجاح');
    }
}