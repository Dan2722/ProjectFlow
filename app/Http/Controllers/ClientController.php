<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Notifications\SystemActivityNotification;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->get();
        return view('clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'required|string|max:20',
            'project_name' => 'required|string|max:255',
            
        ]);

        $client = Client::create([
            'name'         => $request->name,
            'company_name' => $request->company_name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'project_name' => $request->project_name,
            'user_id'      => auth()->id(),
        ]);

        // إشعار الإضافة
        if (auth()->check()) {
            auth()->user()->notify(new SystemActivityNotification(
                'إضافة عميل',
                'تمت إضافة العميل: ' . $client->name,
                route('clients.index')
            ));
        }

        return redirect()->back()->with('success', 'تمت إضافة العميل بنجاح');
    }

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
            'user_id'      => auth()->id(),
        ]);

        // إشعار التعديل
        if (auth()->check()) {
            auth()->user()->notify(new SystemActivityNotification(
                'تعديل عميل',
                'تم تعديل بيانات العميل: ' . $client->name,
                route('clients.index')
            ));
        }

        return redirect()->back()->with('success', 'تم تعديل بيانات العميل بنجاح');
    }

    public function destroy(Client $client)
    {
        $clientName = $client->name;
        $client->delete();

        // إشعار الحذف
        if (auth()->check()) {
            auth()->user()->notify(new SystemActivityNotification(
                'حذف عميل',
                'تم حذف العميل: ' . $clientName,
                route('clients.index')
            ));
        }

        return redirect()->back()->with('success', 'تم حذف العميل بنجاح');
    }
}
