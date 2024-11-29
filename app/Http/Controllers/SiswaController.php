<?php

namespace App\Http\Controllers;

use App\Models\siswa;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function show(string $id): View
    {
        //get Data db
        $siswas = DB::table('siswas')
            ->join('users','siswas.id_user','=','user.id')
            ->select(
                'siswas.*',
                'users.name',
                'users.email',
            )
            ->paginate(10);

        return view('admin.siswa.index',compact('siswas'));   
    }
    public function create(): View
    {
        return view('admin.siswa.create');
    }
    public function store(Request $request): RedirectResponse
    {
        //validation form
        $validated = $request->validate([
            'name'      => 'required|string|max:250',
            'email'      => 'required|email|max:250|unique:users',
            'password'  => 'required|,min:8|confirmed',
            'image'     => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nis'       => 'required|numeric',
            'tingkatan' => 'required',
            'jurusan'   => 'required',
            'kelaa'     => 'required',
            'hp'        => 'required|numeric'

        ]);

        $image = $request->file('image');
        $image->storeAs('public/siswas', $image->hashName());

        $id_akun = $this->insertAccount($request->name, $request->email, $request->password);

        siswa::create([
            'id_user'         =>$id_akun,
            'image'           =>$image->hasName(),
            'nis'             =>$request->nis,
            'tingkatan'       =>$request->tingkatan,
            'jurusan'         =>$request->jurusan,
            'kelas'           =>$request->kelas,
            'hp'              =>$request->hp,
            'status'          => 1,

        ]);

        //redirect tp index
        return redirect()->route('siswa.index')->with(['succes'=> 'Data Berhasil Disismpan!']);
    }

    public function insertAccount(string $name, string $email, string $password)
    {
        user::create([
            'name'      => $name,
            'email'     => $email,
            'password'  => hash::make($password),
            'usertype'  => 'siswa'
        ]);

        $id = DB::table('users')->where('email',$email)->value('id');

        return $id;
    }
    public function search(string $cari){

        $siswa = DB::table('siswas')
        ->join('users', 'siswas.id_user', '=', 'users.id')
        ->select(
            'siswas.*',
            'users.name',
            'users.email'
        )->where('users.name', 'like', '%'. $cari. "%")
        ->orWhere('siswas.nis', 'like', '%'. $cari. '%')
        ->orWhere('users.email', 'like', '%'. $cari. '%')
        ->Paginate(10);

        return view('admin.siswa.index', compact('siswas'));
    }
}
