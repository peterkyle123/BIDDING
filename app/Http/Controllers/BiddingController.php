<?php

namespace App\Http\Controllers;

use App\Models\Bidding;
use App\Models\LGU;   
use Illuminate\Http\Request;

class BiddingController extends Controller
{
    public function index()
    {
        $biddings = Bidding::with('lgu')->get();
        $lgus = LGU::all(); // fetch all LGUs for dropdown
        return view('bidding', compact('biddings', 'lgus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'abc' => 'required|numeric',
            'pre_bid' => 'required|date',
            'bid_submission' => 'required|date',
            'bid_opening' => 'required|date',
            'lgu_id' => 'required|exists:lgus,id',
            'delivery_schedule' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',

        ]);

        Bidding::create($request->only([
            'project_name',
            'abc',
            'pre_bid',
            'bid_submission',
            'bid_opening',
            'lgu_id',
            'reference_number',      // ✅ New
            'delivery_schedule',     // ✅ New
        ]));

        return redirect()->route('biddings.index')->with('success', 'Bidding project added!');
    }

    public function update(Request $request, Bidding $bidding)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'abc' => 'required|numeric',
            'pre_bid' => 'required|date',
            'bid_submission' => 'required|date',
            'bid_opening' => 'required|date',
            'lgu_id' => 'required|exists:lgus,id',
            'delivery_schedule' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            // ✅ New
        ]);

        $bidding->update($request->only([
            'project_name',
            'abc',
            'pre_bid',
            'bid_submission',
            'bid_opening',
            'lgu_id',
            'reference_number',      // ✅ New
            'delivery_schedule',     // ✅ New
        ]));

        return redirect()->route('biddings.index')->with('success', 'Bidding project updated!');
    }

    public function destroy(Bidding $bidding)
    {
        $bidding->delete();
        return redirect()->route('biddings.index')->with('success', 'Bidding project deleted!');
    }
}
