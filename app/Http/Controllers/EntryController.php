<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // If it's an AJAX request for DataTables
        if ($request->ajax()) {
            if ($user->role === 'admin' || $user->role === 'client') {
                $query = Entry::with('staff')->select('entries.*');
            }
            else {
                $query = Entry::where('staff_id', $user->id)->with('staff')->select('entries.*');
            }

            return DataTables::of($query)
                ->addColumn('staff_name', function ($entry) {
                return $entry->staff ? $entry->staff->name : 'Unassigned';
            })
                ->addColumn('image_url', function ($entry) {
                return $entry->image ? asset('storage/' . $entry->image) : 'https://ui-avatars.com/api/?name=' . urlencode($entry->name) . '&background=random';
            })
                ->with('staff_members', User::where('role', 'staff')->get())
                ->make(true);
        }

        // Return initial data for non-ajax or metadata
        return response()->json([
            'role' => $user->role,
            'staff_members' => $user->role === 'admin' ?User::where('role', 'staff')->get() : []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'amount' => 'required|numeric',
            'staff_id' => 'required|exists:users,id',
        ]);

        $entry = Entry::create($request->all());

        return response()->json(['message' => 'Entry created successfully', 'entry' => $entry]);
    }

    public function show(Entry $entry)
    {
        return response()->json(['entry' => $entry->load('staff')]);
    }

    public function update(Request $request, Entry $entry)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'amount' => 'required|numeric',
            'staff_id' => 'required|exists:users,id',
        ]);

        $entry->update($request->all());

        return response()->json(['message' => 'Entry updated successfully', 'entry' => $entry]);
    }

    public function destroy(Entry $entry)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($entry->image) {
            Storage::disk('public')->delete($entry->image);
        }

        $entry->delete();

        return response()->json(['message' => 'Entry deleted successfully']);
    }

    /**
     * Update the image for an entry (Staff only)
     */
    public function updateImage(Request $request, Entry $entry)
    {
        if (auth()->user()->id !== $entry->staff_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!$request->hasFile('image')) {
            return response()->json([
                'message' => 'The image failed to upload. This usually happens when the file exceeds PHP\'s upload_max_filesize or post_max_size limits.',
                'errors' => ['image' => ['The image failed to upload.']]
            ], 422);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        $path = $request->file('image')->store('entries', 'public');

        if ($entry->image) {
            Storage::disk('public')->delete($entry->image);
        }

        $entry->update(['image' => $path]);

        return response()->json(['message' => 'Image uploaded successfully', 'image_url' => asset('storage/' . $path)]);
    }
}
