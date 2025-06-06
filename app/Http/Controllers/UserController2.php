<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\LevelModel;
// use Validator;
use Illuminate\Support\Facades\Validator;
use Monolog\Level;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\PDF;


class UserController2 extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $acttiveMenu = 'user'; // set menu yang aktif

        $level = LevelModel::all(); // ambil data level untuk ditampilkan di form

        return view('user1.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $acttiveMenu]);
        // $user = UserModel::with('level')->get();
        // return view('user', ['data' => $user]);
    }

    // ambil data user dalam bentuk json untuk datatables
    public function list(Request $request) {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level');

        //filter data user berdasarkan level_id                             
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }
    
        return DataTables::of($users)
            ->addIndexColumn() //Menambahkan kolom index / no urut(default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($user) { //menambahkan kolom aksi
                // $btn = '<a href="'.url('/user/' .$user->user_id).'" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="'.url('/user/' .$user->user_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="'.url('/user/'.$user->user_id).'">'
                //      . csrf_field() . method_field('DELETE')
                //      . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah anda yakin menghapus data ini?\');">Hapus</button></form>';
                
                $btn = '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn; 
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true); 
    }

    // Menampilkan halaman form tambah user
    public function create(){
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all(); // ambil data level untuk ditampilkan di form
        $acttiveMenu = 'user'; // set menu yang aktif
        return view('user1.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $acttiveMenu]);
    }

    public function store(Request $request){
        $request->validate([
            // Username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',  // Nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'required|min:5', // Password harus diisi dan minimal 5 karakter
            'level_id' => 'required|integer' // Level ID harus diisi dan berupa angka
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password), //password dienkripsi sebelum disimpan
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    public function show(string $id) {
        $user = UserModel::with('level')->find($id);
        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail user'
        ];

        $acttiveMenu = 'user'; // set menu yang aktif

        return view('user1.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $acttiveMenu]);
    }

    // Menampilkan halaman form edit user
    public function edit(string $id){
        $user = UserModel::find($id);
        $level = LevelModel::all(); 

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit user'
        ];

        $acttiveMenu = 'user'; // set menu yang aktif

        return view('user1.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'level' => $level, 'activeMenu' => $acttiveMenu]);
    }

    // Menyimpan perubahan data user
    public function update(Request $request, string $id){
        $request->validate([
            // Username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
            'username' => 'required|string|min:3|unique:m_user,username,'.$id . ',user_id',
            'nama' => 'required|string|max:100',  // Nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'nullable|min:5', // Password harus diisi dan minimal 5 karakter
            'level_id' => 'required|integer' // Level ID harus diisi dan berupa angka
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password, //password dienkripsi sebelum disimpan
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    public function destroy(string $id){
        $check = UserModel::find($id);
        if (!$check){ // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }

        try{
            UserModel::destroy($id); // hapus data user

            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {

            // jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/user')->with('error', 'Data user tidak bisa dihapus karena masih terdapat data yang terkait');
        }
    }

    public function create_ajax(){
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('user1.create_ajax')
                    ->with('level', $level);
    }

    public function store_ajax(Request $request){
        //cek apakah request baru berupa ajax
        if($request->ajax() || $request->wantsJson()){
            $rules = [
                'level_id'  => 'required|integer',
                'username'  => 'required|string|min:3|unique:m_user,username',
                'nama'      => 'required|string|max:100',
                'password'  => 'required|min:6'
            ];

            //use illuminate\Support\Facades\Validator
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json([
                    'status'=> false, //reponse status, false: error/gagal, true: brehasil
                    'message' => 'Validasi gagal',
                    'msfField'  => $validator->errors(), //pesan error validasi
                ]);
            }

            UserModel::create($request->all());
            return response()->json([
                'status'    => true,
                'message'   => 'Data user berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    //Menampilkan halaman form edit user ajax
    public function edit_ajax(string $id){
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user1.edit_ajax',['user' => $user, 'level' => $level]);
    }

    //fungsi update_ajax()
    public function update_ajax(Request $request, $id){
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|max:20|unique:m_user,username,'.$id.',user_id',
                'nama' => 'required|max:100',
                'password' => 'nullable|min:6|max:20'
            ];
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }
            $check = UserModel::find($id);
            if ($check) {
                if(!$request->filled('password') ){ // jika password tidak diisi, maka hapus dari request
                    $request->request->remove('password');
                }

                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else{
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id){
        $user = UserModel::find($id);

        return view('user1.confirm_ajax', ['user' => $user]);
    }

    // public function delete_ajax(Request $request, $id){
    //     //ceek apakah request dari ajaz
    //     if ($request->ajax() || $request->wantsJson()){
    //         $user = UserModel::find($id);
    //         if ($user){
    //             $user->delete();
    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Data berhasil dihapus'
    //             ]);
    //         }else{
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Data tidak di temukan'
    //             ]);
    //         }
    //         return redirect('/');
    //     }
    // }

    public function delete_ajax(Request $request, $id){
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);
            if ($user) {
                try {
                    $user->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak dapat dihapus karena masih terdapat tabel lain yang terkait dengan data ini.'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }

    public function createAjax(){
        return view('user1.create_ajax');
    }

    public function import()
    {
        return view('user1.import');
    }
    public function import_ajax(Request $request)
    {
        if($request->ajax() || $request->wantsJson()){
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_user' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_user'); // ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // ambil data excel
            $insert = [];
            if(count($data) > 1){ // jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if($baris > 1){ // baris ke 1 adalah header, maka lewati
                        $insert[] = [
                            'username' => $value['A'],
                            'password' => $value['B'],
                            'nama' => $value['C'],
                            'level_id' => $value['D'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                if(count($insert) > 0){
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    UserModel::insert($insert);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_excel()
    {
        // ambil data user yang akan di export
        $user = UserModel::select('username',  'password', 'nama', 'level_id')
            // ->orderBy('kategori_id')
            // ->with('kategori')
            ->get();
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();   // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Password');
        $sheet->setCellValue('D1', 'Nama');
        $sheet->setCellValue('E1', 'ID Level');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);   // bold header

        $no = 1;             // nomor data dimulai dari 1
        $baris = 2;          // baris data dimulai dari baris ke 2
        foreach ($user as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->username);
            $sheet->setCellValue('C' . $baris, $value->password);
            $sheet->setCellValue('D' . $baris, $value->nama);
            $sheet->setCellValue('E' . $baris, $value->level_id);
            $baris++;
            $no++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data user'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data user ' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf(){
        $user = UserModel::select('level_id', 'username', 'nama')
            ->orderBy('level_id')
            ->with('level')
            ->get();

        // use Barryvdh\DOmPDF\Facade as PDF;
        $pdf = PDF::loadView('user1.export_pdf', ['user' => $user]);
        $pdf->setPaper('a4', 'portrait'); // set kertas A4 potrait
        $pdf->setOption("isRemoteEnabled", true); // set agar bisa menampilkan gambar dari url
        $pdf->render();

        return $pdf->stream('Data user ' .date('Y-m-d_H-i-s').'.pdf'); // download file pdf
    }
}