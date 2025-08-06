<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class SiteController extends Controller
{
    function setContacts(Request $request) 
    {
        $data = Contact::create([
            'fullname' => $request->full_name,
            'course' => $request->course,
            'message' => $request->message,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Malumotlaringiz qabul qilindi',
        ], 200);
    }

    function getAll(Request $request) 
    {
        $contacts = Contact::all();

        return response()->json([
            'ok' => true,
            'data' => $contacts,
        ], 200);
        
    }
}
