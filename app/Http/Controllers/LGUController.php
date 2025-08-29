<?php

namespace App\Http\Controllers;

use App\Models\LGU;
use Illuminate\Http\Request;

class LGUController extends Controller
{
    public function index() {
        $lgus = LGU::all();
        return view('lgus', compact('lgus'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'envelope_system' => 'nullable|string|max:255',

        ]);

        LGU::create($request->only('name','location','envelope_system'));
        return redirect()->route('lgus.index');
    }

    public function update(Request $request, LGU $lgu) {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
             'envelope_system' => 'nullable|string|max:255', // add this

        ]);

        $lgu->update($request->only('name','location','envelope_system'));
        return redirect()->route('lgus.index');
    }

    public function destroy(LGU $lgu) {
        $lgu->delete();
        return redirect()->route('lgus.index');
    }
    public function show($id)
{
    $lgu = LGU::findOrFail($id);
    return view('viewlgu', compact('lgu'));

}
public function home()
{
    $lguCount = LGU::count(); // get the number of LGUs in the database
    return view('home', compact('lguCount'));
}

}
