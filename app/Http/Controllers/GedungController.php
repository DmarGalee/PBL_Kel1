<?php

namespace App\Http\Controllers;

use App\Models\GedungModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class GedungController extends Controller
{
    public function index()
    {
        $activeMenu = 'gedung';
        $breadcrumb = (object) [
            'title' => 'Data Gedung',
            'list' => ['Home', 'Gedung']
        ];
        
        $page = (object) [
            'title' => 'Daftar Gedung'
        ];

        return view('gedung.index', [
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb,
            'page' => $page
        ]);
    }

    public function list(Request $request)
    {
        $gedung = GedungModel::select('gedung_id', 'gedung_nama', 'gedung_kode', 'description');

        return DataTables::of($gedung)
            ->addIndexColumn()
            ->addColumn('aksi', function ($gedung) {
                $btn  = '<button onclick="modalAction(\'' . url('/gedung/' . $gedung->gedung_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/gedung/' . $gedung->gedung_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/gedung/' . $gedung->gedung_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Gedung',
            'list' => ['Home', 'Gedung', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Gedung baru'
        ];

        $activeMenu = 'gedung';

        return view('gedung.create', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'gedung_kode' => 'required|unique:m_gedung',
            'gedung_nama' => 'required',
            'description' => 'nullable',
        ]);

        GedungModel::create($request->all());

        return redirect('/gedung')->with('success', 'Data berhasil ditambahkan');
    }

    public function create_ajax()
    {
        return view('gedung.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'gedung_kode' => ['required', 'min:3', 'max:20', 'unique:m_gedung,gedung_kode'],
                'gedung_nama' => ['required', 'string', 'max:100'],
                'description' => ['nullable', 'string'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            GedungModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $gedung = GedungModel::find($id);
        return view('gedung.edit_ajax')->with('gedung', $gedung);
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'gedung_kode' => 'required|min:3|unique:m_gedung,gedung_kode,' . $id . ',gedung_id',
                'gedung_nama' => 'required|min:3',
                'description' => 'nullable|string',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $check = GedungModel::find($id);
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
        $gedung = GedungModel::find($id);
        return view('gedung.confirm_ajax')->with('gedung', $gedung);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $gedung = GedungModel::find($id);
            if ($gedung) {
                try {
                    $gedung->delete();
                    return response()->json([
                        'status'=> true,
                        'message'=> 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status'=> false,
                        'message'=> 'Data gedung gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
                    ]);
                } 
            } else {
                return response()->json([
                    'status'=> false,
                    'message'=> 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function show_ajax(string $id)
    {
        $gedung = GedungModel::find($id);
        return view('gedung.show_ajax', ['gedung' => $gedung]);
    }

    public function import()
    {
        return view('gedung.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_gedung' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_gedung');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $insert[] = [
                            'gedung_kode' => $value['A'],
                            'gedung_nama' => $value['B'],
                            'description' => $value['C'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    GedungModel::insertOrIgnore($insert);
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

    public function export_excel() {
        // ambil data gedung yang akan di export
        $gedung = GedungModel::select('gedung_kode', 'gedung_nama', 'description')
            ->orderBy('gedung_kode')
            ->get();

        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Gedung');
        $sheet->setCellValue('C1', 'Nama Gedung');
        $sheet->setCellValue('D1', 'Deskripsi');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($gedung as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->gedung_kode);
            $sheet->setCellValue('C'.$baris, $value->gedung_nama);
            $sheet->setCellValue('D'.$baris, $value->description);
            $baris++;
            $no++;
        }

        foreach(range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data Gedung'); 

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Gedung '.date('Y-m-d H:i:s').'.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s'). ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $gedung = GedungModel::select('gedung_kode', 'gedung_nama', 'description')
            ->orderBy('gedung_kode')
            ->get();

        $pdf = Pdf::loadView('gedung.export_pdf', ['gedung' => $gedung]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();
        return $pdf->stream('Data Gedung '.date('Y-m-d H:i:s').'.pdf');
    }
}