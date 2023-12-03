<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MemberController extends Controller
{
    /**
     * API
     */
    public function api()
    {
        $members = Member::all();
        return datatables()->of($members)->addIndexColumn()->make(true);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.member.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $latestMember = Member::latest()->first();
            $code = (int)$latestMember->code + 1 ?? 1;

            $member = new Member();
            $member->code = add_zeros_at_front($code, 5);
            $member->name = $request->name;
            $member->phone = $request->phone;
            $member->address = $request->address;

            $member->save();

            return response()->json('Member created successfully', 201);
        } catch (\Exception $e) {
            Log::error('Error creating member: ' . $e->getMessage());
            return response()->json('Internal Server Error = ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return response()->json($member);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        try {
            $member->update([
                'name' => $request->input('name')
            ]);

            return response()->json('Category updated successfully', 200);
        } catch (\Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());
            return response()->json('Internal Server Error = ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        try {
            $member->delete();

            return response()->json('Category deleted successfully', 204);
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            return response()->json('Internal Server Error = ' . $e->getMessage(), 500);
        }
    }
}
