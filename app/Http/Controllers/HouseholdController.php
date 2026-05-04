<?php
namespace App\Http\Controllers;

use App\Models\Household;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HouseholdController extends Controller {

    // POST /api/household/create
    public function create(Request $request) {
        $request->validate(['name' => 'required|string']);

        // make a random 8 letter invite code
        $household = Household::create([
            'name'        => $request->name,
            'invite_code' => strtoupper(Str::random(8)),
        ]);

        // link this user to the household
        $request->user()->update(['household_id' => $household->id]);

        return response()->json($household->load('members'));
    }

    // POST /api/household/join
    public function join(Request $request) {
        $request->validate(['invite_code' => 'required|string']);

        $household = Household::where(
            'invite_code', strtoupper($request->invite_code)
        )->first();

        if (!$household) {
            return response()->json(['message' => 'Invite code not found'], 404);
        }

        $request->user()->update(['household_id' => $household->id]);

        return response()->json($household->load('members'));
    }
}