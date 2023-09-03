<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Team;
use File;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $searchTerm = request()->get('s');
        $teams = Team::orWhere('fullname', 'LIKE', "%$searchTerm%")->latest()->paginate(15);
        return view('admin.team.index')
            ->with(compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.team.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $this->validate(request(), [
            'fullname' => 'required',
            'designation' => 'required',
            'email' => 'required',
            'email' => 'required',
            'facebook_id' => 'required',
            'twitter_id' => 'required',
            'pinterest_id' => 'required',
            'team_img' => 'required'
        ]);

       $fileName = null;
       if (request()->hasFile('team_img')) {
            $file = request()->file('team_img');
            $fileName = md5($file->getClientOriginalName()) . time() . "." . $file->getClientOriginalExtension();
            $file->move('./uploads/', $fileName);
       }

        Team::create([
            'fullname' => request()->get('fullname'),
            'designation' => request()->get('designation'),
            'telephone' => request()->get('telephone'),
            'mobile' => request()->get('mobile'),
            'email' => request()->get('email'),
            'facebook_id' => request()->get('facebook_id'),
            'twitter_id' => request()->get('twitter_id'),
            'pinterest_id' => request()->get('pinterest_id'),
            'profile' => request()->get('profile'),
            'team_img' => $fileName,
            'status' => 'DEACTIVE'
        ]);

        return redirect()->to('/admin/team');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $team = Team::find($id);
        return view('admin.team.edit')
            ->with(compact('team'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $team = Team::find($id);

        $currentImage = $team->team_img;
        $fileName = null;
        if (request()->hasFile('team_img')) {
            $file = request()->file('team_img');
            $fileName = md5($file->getClientOriginalName()) . time() . "." . $file->getClientOriginalExtension();
            $file->move('./uploads/', $fileName);
        }

        $team->update([
           'fullname' => request()->get('fullname'),
            'designation' => request()->get('designation'),
            'telephone' => request()->get('telephone'),
            'mobile' => request()->get('mobile'),
            'email' => request()->get('email'),
            'facebook_id' => request()->get('facebook_id'),
            'twitter_id' => request()->get('twitter_id'),
            'pinterest_id' => request()->get('pinterest_id'),
            'profile' => request()->get('profile'),
            'team_img' => ($fileName) ? $fileName : $currentImage,
        ]);

        if ($fileName) {
            File::delete('./uploads/' . $currentImage);
        }

        return redirect()->to('/admin/team');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) 
        {
            $team = Team::find($id);
            $currentImage = $team->team_img;
            $team->delete();
            File::delete('./uploads/' . $currentImage);
            return 'true';
        }
        return redirect()->back();
    }

    public function status(Request $request, $id)
    {
        if ($request->ajax()) {
            $team = Team::find($id);
            $newStatus = ($team->status == 'DEACTIVE') ? 'ACTIVE' : 'DEACTIVE';
            $team->update([
                'status' => $newStatus
            ]);
        return $newStatus;
        }
        return redirect()->back();
    }

    public function status_active(Request $request)
    {
        if ($request->ajax()) {
            foreach ($request->statusAll as $key => $id) {
                Team::where('id', $id)->update(['status' => 'ACTIVE']);
            }
            $record = Team::find($request->statusAll);
            return $record;
        }
        return false;
    }

    public function status_deactive(Request $request)
    {
        if ($request->ajax()) {
            foreach ($request->statusAll as $key => $id) {
                Team::where('id', $id)->update(['status' => 'DEACTIVE']);
            }
            $record = Team::find($request->statusAll);
            return $record;
        }
        return false;
    }

    public function delete_all(Request $request)
    {
        if ($request->ajax()) {
            foreach ($request->statusAll as $key => $id) {
                Team::where('id', $id)->delete();
            }
            $record = Team::find($request->statusAll);
            return $record;
        }
        return false;
    }
}
