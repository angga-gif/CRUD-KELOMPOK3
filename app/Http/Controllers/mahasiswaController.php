<?php

namespace App\Http\Controllers;

use App\Models\mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class mahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $katakunci = $request->katakunci;
        $jumlahbaris = 5;
        if(strlen($katakunci)) {
            $data = mahasiswa::where('npm', 'like', "%$katakunci%")
                    ->orWhere('nama', 'like', "%$katakunci%")
                    ->orWhere('kelas', 'like', "%$katakunci%")
                    ->orWhere('jurusan', 'like', "%$katakunci%")
                    ->paginate($jumlahbaris);
        }else{
            $data = mahasiswa::orderBy('npm', 'desc')->paginate($jumlahbaris);
        }
        return View('mahasiswa.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Session::flash('npm',$request->npm);
        Session::flash('nama',$request->nama);
        Session::flash('kelas',$request->kelas);
        Session::flash('jurusan',$request->jurusan);

        $request->validate([
            'npm'=>'required|numeric|unique:mahasiswa,npm',
            'nama'=>'required',
            'kelas'=>'required',
            'jurusan'=>'required',
        ],[
            'npm.required'=>'NPM wajib diisi',
            'npm.numeric'=>'NPM wajib berisi angka', 
            'npm.unique'=>'NPM yang diisi sudah ada dalam database', 
            'nama.required'=>'NAMA wajib diisi', 
            'kelas.required'=>'KELAS wajib diisi',  
            'jurusan.required'=>'JURUSAN wajib diisi', 
        ]);
        $data = [
            'npm'=>$request->npm,
            'nama'=>$request->nama,
            'kelas'=>$request->kelas,
            'jurusan'=>$request->jurusan,
        ];
        mahasiswa::create($data);
        return redirect()->to('mahasiswa')->with('success', 'Berhasil Menambahkan Data');
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
        $data = mahasiswa::where('npm',$id)->first();
        return view('mahasiswa.edit')->with('data', $data);
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
        $request->validate([
            'nama'=>'required',
            'kelas'=>'required',
            'jurusan'=>'required',
        ], [
            'nama.required'=>'NAMA wajib diisi', 
            'kelas.required'=>'KELAS wajib diisi',  
            'jurusan.required'=>'JURUSAN wajib diisi', 
        ]);
        $data = [
            'nama'=>$request->nama,
            'kelas'=>$request->kelas,
            'jurusan'=>$request->jurusan,
        ];
        mahasiswa::where('npm',$id)->update($data);
        return redirect()->to('mahasiswa')->with('success', 'Berhasil Melakukan Update Data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        mahasiswa::where('npm', $id)->delete();
        return redirect()->to('mahasiswa')->with('success','Berhasil Mengahapus Data');
    }
}
