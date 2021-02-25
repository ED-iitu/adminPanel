<?php

namespace App\Http\Controllers;

use App\Contacts;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contacts::all();

        return view('contacts.index', [
            'contacts' => $contacts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contacts  $contacts
     * @return \Illuminate\Http\Response
     */
    public function show(Contacts $contacts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contacts  $contacts
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contacts = Contacts::find($id);

        return view('contacts.edit', [
            'contacts' => $contacts
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contacts  $contacts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contacts $contacts)
    {
        $contacts = Contacts::find($request->id);

        $data = [
            'first_phone' => $request->first_phone,
            'second_phone' => $request->second_phone,
            'first_email' => $request->first_email,
            'second_email' => $request->second_email,
            'address_kz' => $request->address_kz,
            'address_eu' => $request->address_eu,
        ];

        $contacts->update($data);

        return redirect()->back()->with('success', 'Адрес обновлен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contacts  $contacts
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contacts $contacts)
    {
        //
    }


    public function getContacts(Request $request)
    {
        $lang = $request->lang ?? 'ru';
        $contacts = Contacts::where('lang', $lang)->get();

        if ($contacts->isEmpty()) {
            return response(['error' => 'Контакты не найдены'], 404);
        }

        return response(['contacts' => $contacts], 200);
    }
}
