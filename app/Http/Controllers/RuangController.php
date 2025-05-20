<?php

namespace App\Http\Controllers;

use App\Models\RuangModel;
use App\Models\LantaiModel; // Add this for the relationship
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class RuangController extends Controller
{
    public function index()
    {
        $activeMenu = 'ruang';
        $breadcrumb = (object) [
            'title' => 'Data Ruang',
            'list' => ['Home', 'Ruang']
        ];

        $lantai = LantaiModel::all();

        return view('ruang.index', [
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb,
            'lantai' => $lantai
        ]);
    }

    public function list(Request $request)
    {
        $ruang = RuangModel::with('lantai') // Eager load the relationship
            ->select('ruang_id', 'ruang_nama', 'lantai_id');

        return DataTables::of($ruang)
            ->addIndexColumn()
            ->addColumn('lantai_nama', function ($ruang) { // Add lantai name column
                return $ruang->lantai->lantai_nama ?? '-';
            })
            ->addColumn('aksi', function ($ruang) {
                $btn  = '<button onclick="modalAction(\'' . url('/ruang/' . $ruang->ruang_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/ruang/' . $ruang->ruang_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/ruang/' . $ruang->ruang_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Ruang',
            'list' => ['Home', 'Ruang', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Ruang baru'
        ];

        $activeMenu = 'ruang';
        $lantai = LantaiModel::all(); // Get all lantai for dropdown

        return view('ruang.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'lantai' => $lantai // Pass lantai to view
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruang_nama' => 'required|unique:m_ruang',
            'lantai_id' => 'required|integer|exists:m_lantai,lantai_id',
        ]);

        RuangModel::create($request->all());

        return redirect('/ruang')->with('success', 'Data berhasil ditambahkan');
    }

    public function create_ajax()
    {
        $lantai = LantaiModel::all(); // Get all lantai for dropdown
        return view('ruang.create_ajax', ['lantai' => $lantai]);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'ruang_nama' => ['required', 'string', 'max:100', 'unique:m_ruang,ruang_nama'],
                'lantai_id' => ['required', 'integer', 'exists:m_lantai,lantai_id'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            RuangModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $ruang = RuangModel::find($id);
        $lantai = LantaiModel::all(); // Get all lantai for dropdown
        return view('ruang.edit_ajax', [
            'ruang' => $ruang,
            'lantai' => $lantai
        ]);
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'ruang_nama' => 'required|min:3|unique:m_ruang,ruang_nama,' . $id . ',ruang_id',
                'lantai_id' => 'required|integer|exists:m_lantai,lantai_id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $check = RuangModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
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
        $ruang = RuangModel::with('lantai')->find($id); // Load relationship
        return view('ruang.confirm_ajax')->with('ruang', $ruang);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $ruang = RuangModel::find($id);
            if ($ruang) {
                try {
                    $ruang->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data ruang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
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

    public function show_ajax(string $id)
    {
        $ruang = RuangModel::with('lantai')->find($id); // Load relationship
        return view('ruang.show_ajax', ['ruang' => $ruang]);
    }

    public function import()
    {
        $lantai = LantaiModel::all(); // Get all lantai for reference
        return view('ruang.import', ['lantai' => $lantai]);
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_ruang' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_ruang');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            $errors = [];
            $validLantai = LantaiModel::pluck('lantai_id')->toArray(); // Get valid lantai IDs

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        // Validate lantai_id exists
                        if (!in_array($value['B'], $validLantai)) {
                            $errors[] = "Baris $baris: Lantai ID {$value['B']} tidak valid";
                            continue;
                        }

                        $insert[] = [
                            'ruang_nama' => $value['A'],
                            'lantai_id' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($errors)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Beberapa data tidak valid',
                        'errors' => $errors
                    ]);
                }

                if (count($insert) > 0) {
                    RuangModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
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
        $ruang = RuangModel::with('lantai') // Load relationship
            ->select('ruang_id', 'ruang_nama', 'lantai_id')
            ->orderBy('ruang_id')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Ruang');
        $sheet->setCellValue('C1', 'Lantai');
        $sheet->setCellValue('D1', 'Lantai ID');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($ruang as $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->ruang_nama);
            $sheet->setCellValue('C'.$baris, $value->lantai->lantai_nama ?? '-');
            $sheet->setCellValue('D'.$baris, $value->lantai_id);
            $baris++;
            $no++;
        }

        foreach(range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Ruang');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Ruang '.date('Y-m-d H:i:s').'.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $ruang = RuangModel::with('lantai') // Load relationship
            ->select('ruang_id', 'ruang_nama', 'lantai_id')
            ->orderBy('ruang_id')
            ->get();

        $pdf = Pdf::loadView('ruang.export_pdf', ['ruang' => $ruang]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();
        return $pdf->stream('Data Ruang '.date('Y-m-d H:i:s').'.pdf');
    }
}