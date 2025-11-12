<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class NoticeController extends Controller
{
      public function index()
    {
    //  $notices = Notice::where('date', '>=', Carbon::today())
    // ->orderBy('date', 'asc')  // ascending so nearest Notices appear first
    // ->paginate(10);
    $notices = Notice::all();
        return view('backend.notice.index', compact('notices'));
    }

    public function create()
    {
        //
        return view('backend.notice.create');
    }

   
 public function store(Request $request)
{
    $request->validate([
        'notice_date'  => 'required|date',
        'expiry_date'  => 'required|date|after_or_equal:notice_date',
        'content'      => 'required|string|max:1000',
        'title'        => 'required|string|max:255',
        'attachment'   => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'audience'     => 'required|array',
    ]);

    // Handle file upload (if any)
    $filePath = null;
    if ($request->hasFile('attachment')) {
        $filePath = $request->file('attachment')->store('notices', 'public');
    }

    // Create the notice
    Notice::create([
        'notice_date'  => $request->notice_date,
        'expiry_date'  => $request->expiry_date,
        'content'      => $request->content,
        'title'        => $request->title,
        'attachment'   => $filePath,
        'audience'     => implode(',', $request->audience ?? []),
    ]);

    return redirect()
        ->route('notice.index')
        ->with('success', 'Notice added successfully!');
}

   
    public function show(string $id)
    {
       $notice = Notice::findOrFail($id);

    // Wrap it in a collection so the Blade foreach/forelse works
    $notices = collect([$notice]);
        return view('backend.notice.show',compact('notices'));
    }

    
    public function edit(string $id)
    {
        //
        $notice = Notice::findOrFail($id);
        return view('backend.notice.edit', compact('notice'));
    }

  
  public function update(Request $request, string $id)
{
    $notice = Notice::findOrFail($id);

    $request->validate([
        'expiry_date' => 'required|date',
        'notice_date' => 'required|date',
        'title' => 'required|string|max:255',
        'content' => 'required|string|max:255',
        'audience' => 'array|nullable',
        'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // optional file validation
    ]);

    // Prepare update data
    $data = [
        'notice_date' => $request->notice_date,
        'expiry_date' => $request->expiry_date,
        'title'       => $request->title,
        'content'     => $request->content,
        'remark'      => $request->remark,
        'audience'    => implode(',', $request->audience ?? []),
    ];

    // Handle attachment replacement
    if ($request->hasFile('attachment')) {
        // Delete old file if exists
        if ($notice->attachment && Storage::disk('public')->exists($notice->attachment)) {
            Storage::disk('public')->delete($notice->attachment);
        }

        // Create a unique, descriptive name for the new file
        $file = $request->file('attachment');
        $filename = Str::slug($request->title) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Store file under 'notices' directory
        $path = $file->storeAs('notices', $filename, 'public');

        // Save the new path
        $data['attachment'] = $path;
    }

    // Update notice
    $notice->update($data);

    return redirect()->route('notice.index')->with('success', 'Notice updated successfully!');
}


   
    public function destroy(string $id)
{
    $notice = Notice::findOrFail($id);

    // Delete attachment file if it exists
    if ($notice->attachment && Storage::exists($notice->attachment)) {
        Storage::delete($notice->attachment);
    }

    // Delete the notice record
    $notice->delete();

    return redirect()->route('notice.index')->with('success', 'Notice deleted successfully along with its attachment!');
}

}
