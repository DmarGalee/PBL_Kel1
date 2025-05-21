<?php

namespace App\Http\Controllers;


use App\Models\LantaiModel;
use App\Models\GedungModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class LantaiController extends Controller
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

        $this->activeMenu = 'lantai';
    }

    public function index()
    {
        $this->breadcrumb->title = 'Daftar Lantai';
        $this->breadcrumb->list = ['Home', 'Lantai'];
        $this->page->title = 'Daftar lantai dalam sistem';

        $gedungs = GedungModel::all();

        return view('lantai.index', [
            'breadcrumb' => $this->breadcrumb,
            'page' => $this->page,
            'activeMenu' => $this->activeMenu,
            'gedungs' => $gedungs
        ]);
    }

    public function list(Request $request)
    {
        $lantai = LantaiModel::with('gedung')
            ->select('m_lantai.*');

        // Apply filter if gedung_id is provided
        if ($request->gedung_id) {
            $lantai->where('gedung_id', $request->gedung_id);
        }

        return DataTables::of($lantai)
            ->addIndexColumn()
            ->addColumn('nama_gedung', function ($lantai) {
                return $lantai->gedung->gedung_nama ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                $editUrl = url('/lantai/' . $row->lantai_id . '/edit_ajax');
                $deleteUrl = url('/lantai/' . $row->lantai_id . '/delete_ajax');
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
        $gedungs = GedungModel::all();
        return view('lantai.create_ajax', compact('gedungs'));
    }
    
    // Menyimpan lantai baru (AJAX)
    public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'lantai_nomor' => 'required|string|min:2|unique:m_lantai,lantai_nomor',
            'deskripsi' => 'nullable|string|max:255',
            'gedung_id' => 'required|integer|exists:m_gedung,gedung_id',
        ];

        $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ], 422);
            }

            LantaiModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil disimpan',
            ]);
        }
        return redirect('/');
    }

    
    public function edit_ajax(string $id)
    {
        $lantai = LantaiModel::findOrFail($id);
        $gedungs = GedungModel::all(); // Ambil data gedung untuk dropdown
        return view('lantai.edit_ajax', compact('lantai', 'gedungs'));
    }
    
    // Menyimpan perubahan data lantai (AJAX)
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'lantai_nomor' => 'required|string|min:2|unique:m_lantai,lantai_nomor,' . $id . ',lantai_id',
                'deskripsi' => 'nullable|string|max:255',
                'gedung_id' => 'required|integer|exists:m_gedung,gedung_id',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $lantai = LantaiModel::find($id);
    
            if ($lantai) {
                $lantai->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data lantai berhasil diupdate'
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
        $lantai = LantaiModel::find($id);
        return view('lantai.confirm_ajax', compact('lantai'));
    }
    
    // Menghapus data lantai (AJAX)
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $lantai = LantaiModel::find($id);
    
            if ($lantai) {
                $lantai->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data lantai berhasil dihapus',
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