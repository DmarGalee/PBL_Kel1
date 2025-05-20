<?php

namespace App\Http\Controllers;

use App\Models\GedungModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class GedungController extends Controller
{
    private $breadcrumb;
    private $page;
    private $activeMenu;

    public function __construct()
    {
        $this->breadcrumb = (object) [
            'title' => '',
            'list' => []
        ];

        $this->page = (object) [
            'title' => ''
        ];

        $this->activeMenu = 'gedung';
    }

    public function index()
    {
        $this->breadcrumb->title = 'Daftar Gedung';
        $this->breadcrumb->list = ['Home', 'Gedung'];
        $this->page->title = 'Daftar gedung dalam sistem';

        return view('gedung.index', [
            'breadcrumb' => $this->breadcrumb,
            'page' => $this->page,
            'activeMenu' => $this->activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $gedung = GedungModel::select('m_gedung.*');

        return DataTables::of($gedung)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $editUrl = url('/gedung/' . $row->gedung_id . '/edit_ajax');
                $deleteUrl = url('/gedung/' . $row->gedung_id . '/delete_ajax');
                return '
                    <button onclick="modalAction(\'' . $editUrl . '\')" class="btn btn-sm btn-primary">Edit</button>
                    <button onclick="modalAction(\'' . $deleteUrl . '\')" class="btn btn-sm btn-danger">Hapus</button>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        return view('gedung.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'gedung_kode' => 'required|string|min:2|unique:m_gedung,gedung_kode',
                'gedung_nama' => 'required|string|min:3|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ], 422);
            }

            GedungModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data gedung berhasil disimpan',
            ]);
        }
        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $gedung = GedungModel::findOrFail($id);
        return view('gedung.edit_ajax', compact('gedung'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'gedung_kode' => 'required|string|min:2|unique:m_gedung,gedung_kode,' . $id . ',gedung_id',
                'gedung_nama' => 'required|string|min:3|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $gedung = GedungModel::find($id);

            if ($gedung) {
                $gedung->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data gedung berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $gedung = GedungModel::find($id);
        return view('gedung.confirm_ajax', compact('gedung'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $gedung = GedungModel::find($id);

            if ($gedung) {
                $gedung->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data gedung berhasil dihapus',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                ], 404);
            }
        }

        return redirect('/');
    }
}