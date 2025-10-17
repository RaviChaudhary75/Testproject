<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Exports\ProfilesExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ProfileController extends Controller
{

   public function export (){

        try{

            return Excel::download(new ProfilesExport, 'profile.xlsx');
        }catch(\Exception $e){

            return redirect()->back()->with('error', 'Error loading data:'. $e->getMessage());
    }
}

    public function index(Request $request)
    {

        $paging = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = Profile::orderBy('id', 'desc');

        if(!empty($search)){
            $query->where('name', 'like', '%' . $search . '%');
        }

        $profiles = $query->paginate($paging)->appends($request->all());

        return view('profile.index', compact('profiles'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
       $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:profiles,email',
        'phone' => 'required|string|max:15',
        'profile_pic' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'resume.*' => 'nullable|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',

    ]);
// dd($request->all());
        $profilePath = $request->file('profile_pic')?->store('profiles', 'public');

        $resumepath = [];
        if ($request->hasFile('resume')){
            foreach ($request->file('resume') as $file){
                $resumepath[] = $file->store('resumes', 'public');
            }
        }

        $profile = new Profile();
        $profile->name = $request->name;
        $profile->email = $request->email;
        $profile->phone = $request->phone;
        $profile->profile_pic = $profilePath;
        $profile->resume = $resumepath;
        $profile->save();

        return redirect()->back()->with( 'message' , 'Profile created Successfully');
    }

    public function edit($id)
    {
       $profile = Profile::findOrFail($id);
       $profiles = Profile::orderBy('id')->paginate(10);

       return view('profile.index', ['profiles' => $profiles , 'profile' => $profile]);

    }

    public function update(Request $request,  $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:profiles,email,' . $id,
            'phone' => 'required|string|max:15',
            'profile_pic' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'resume.*' => 'nullable|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',
        ]);

        $profile = Profile::findOrFail($id);
        $profilePath = $request->file('profile_pic')?->store('profiles', 'public');

        $existingResumes = is_string($profile->resume) ? json_decode($profile->resume, true) : ($profile->resume ?? []);

    if ($request->hasFile('resume')) {
        foreach ($request->file('resume') as $file) {
            $existingResumes[] = $file->store('resumes', 'public');
        }
        $profile->resume = json_encode($existingResumes);
    }


        $profile->name = $request->name;
        $profile->email = $request->email;
        $profile->phone = $request->phone;
        $profile->profile_pic = $profilePath ?? $profile->profile_pic;
        $profile->save();

        return redirect()->route('profile.index')->with( 'message','Profile updated Successfully');

    }

    public function destroy($id)
    {
        $profile = Profile::findOrFail($id);
        $profile->delete();
        return redirect()->back()->with('delete','Profile deleted Successfully');


    }
}
