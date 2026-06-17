<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Info;
use App\Services\AssetStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class InfoController extends AdminController
{
    public function index()
    {
        $infos = Info::get();
        $route = explode('.', Route::currentRouteName());
        $InfoLanguages = Info::count();
        $contacts = Contact::first();
        $contactsCheck = Contact::count();

        if (isset($route[1])) {
            foreach ($infos as $item) {
                if ($route[2] == $item->language) {
                    if ($item->language == 'en_US' || $item->language == 'pt_BR') {
                        return redirect(route('info'))->with('err', 'Informações já adicionada');
                    }
                }
            }
        }

        return view('admin.info', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'infos' => $infos,
            'route' => $route,
            'infoLanguages' => $InfoLanguages,
            'contacts' => $contacts,
            'contactsCheck' => $contactsCheck
        ]);
    }

    public function addInfo(Request $request)
    {
        if (!Info::where('language', $request->language)->count() > 0) {
            $addInfo = new Info();
            $addInfo->language = $request->language;
            $addInfo->name = $request->name;
            $addInfo->position = $request->position;
            $addInfo->fullname = $request->fullname;
            $addInfo->description = $request->description;
            $addInfo->save();
            
            return redirect(route('info'))->with('success', 'Informações adicionada com sucesso');
        } else {
            return redirect(route('info'))->with('err', 'Informações já adicionada');
        }
    }

    public function updateInfo(Request $request)
    {
        Info::where('id', $request->id)->update([
            'name' => $request->name,
            'position' => $request->position,
            'fullname' => $request->fullname,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Dados atualizados com sucesso');
    }

    public function addContacts(Request $request)
    {
        $storage = app(AssetStorageService::class);
        $addContacts = new Contact();
        $addContacts->instagram = $request->instagram ?? null;
        $addContacts->twitter = $request->twitter ?? null;
        $addContacts->github = $request->github ?? null;
        $addContacts->linkedin = $request->linkedin ?? null;
        $addContacts->email_personal = $request->email_personal ?? null;
        $addContacts->email_business = $request->email_business ?? null;
        $addContacts->tel = $request->tel ?? null;

        if ($request->hasFile("csv") && $request->file("csv")->isValid()) {
            $csv = $request->file("csv");
            $addContacts->csv = $storage->putUploadedFile($csv, 'csv');
        }

        $addContacts->save();
        return back()->with('success', 'Informações de contato adicionada com sucesso');
    }

    public function updateContacts(Request $request)
    {
        $storage = app(AssetStorageService::class);
        $contacts = Contact::where('id', $request->id);
        
        $contactsGET = $contacts->first();
        $csvNamePath = $contactsGET?->csv;
        
        if ($request->hasFile("csv") && $request->file("csv")->isValid()) {
            $csv = $request->file("csv");
            $csvNamePath = $storage->putUploadedFile($csv, 'csv');

            if (!empty($contactsGET?->csv) && $contactsGET->csv !== $csvNamePath) {
                $storage->delete($contactsGET->csv);
            }
        }

        $contacts->update([
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'github' => $request->github,
            'linkedin' => $request->linkedin,
            'email_personal' => $request->email_personal,
            'email_business' => $request->email_business,
            'tel' => $request->tel,
            'csv' => $csvNamePath
        ]);

        return back()->with('success', 'Dados atualizados com sucesso');
    }
}
