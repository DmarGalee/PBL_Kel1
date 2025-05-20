<?php

namespace App\Http\Controllers;

use App\Models\FasilitasModel;
use App\Models\RuangModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class FasilitasController extends Controller
{
    public function index()
    {
        $activeMenu = 'fasilitas';
        $breadcrumb = (object) [
            'title' => 'Data Fasilitas',
            'list' => ['Home', 'Fasilitas']
        ];

        $ruang = RuangModel::all();
        $kategori = KategoriModel::all();

        return view('fasilitas.index', [
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb,
            'ruang' => $ruang,
            'kategori' => $kategori
        ]);
    }

    public function list(Request $request)
    {
        $fasilitas = FasilitasModel::with(['ruang', 'kategori'])
            ->select('fasilitas_id', 'fasilitas_kode', 'ruang_id', 'kategori_id', 'status');

        return DataTables::of($fasilitas)
            ->addIndexColumn()
            ->addColumn('ruang_nama', function ($fasilitas) {
                return $fasilitas->ruang->ruang_nama ?? '-';
            })
            ->addColumn('kategori_nama', function ($fasilitas) {
                return $fasilitas->kategori->kategori_nama ?? '-';
            })
            ->addColumn('aksi', function ($fasilitas) {
                $btn  = '<button onclick="modalAction(\'' . url('/fasilitas/' . $fasilitas->fasilitas_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/fasilitas/' . $fasilitas->fasilitas_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/fasilitas/' . $fasilitas->fasilitas_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Fasilitas',
            'list' => ['Home', 'Fasilitas', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Fasilitas baru'
        ];

        $activeMenu = 'fasilitas';
        $ruang = RuangModel::all();
        $kategori = KategoriModel::all();

        return view('fasilitas.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'ruang' => $ruang,
            'kategori' => $kategori
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'fasilitas_kode' => 'required|unique:m_fasilitas',
            'ruang_id' => 'required|integer|exists:m_ruang,ruang_id',
            'kategori_id' => 'required|integer|exists:m_kategori,kategori_id',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:baik,rusak_ringan,rusak_berat'
        ]);

        FasilitasModel::create($request->all());

        return redirect('/fasilitas')->with('success', 'Data berhasil ditambahkan');
    }

    public function create_ajax()
    {
        $ruang = RuangModel::all();
        $kategori = KategoriModel::all();
        return view('fasilitas.create_ajax', [
            'ruang' => $ruang,
            'kategori' => $kategori
        ]);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'fasilitas_kode' => ['required', 'string', 'max:20', 'unique:m_fasilitas,fasilitas_kode'],
                'ruang_id' => ['required', 'integer', 'exists:m_ruang,ruang_id'],
                'kategori_id' => ['required', 'integer', 'exists:m_kategori,kategori_id'],
                'deskripsi' => ['nullable', 'string'],
                'status' => ['required', 'in:baik,rusak_ringan,rusak_berat']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            FasilitasModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $fasilitas = FasilitasModel::find($id);
        $ruang = RuangModel::all();
        $kategori = KategoriModel::all();
        return view('fasilitas.edit_ajax', [
            'fasilitas' => $fasilitas,
            'ruang' => $ruang,
            'kategori' => $kategori
        ]);
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'fasilitas_kode' => 'required|string|max:20|unique:m_fasilitas,fasilitas_kode,' . $id . ',fasilitas_id',
                'ruang_id' => 'required|integer|exists:m_ruang,ruang_id',
                'kategori_id' => 'required|integer|exists:m_kategori,kategori_id',
                'deskripsi' => 'nullable|string',
                'status' => 'required|in:baik,rusak_ringan,rusak_berat'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $check = FasilitasModel::find($id);
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
        $fasilitas = FasilitasModel::with(['ruang', 'kategori'])->find($id);
        return view('fasilitas.confirm_ajax')->with('fasilitas', $fasilitas);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $fasilitas = FasilitasModel::find($id);
            if ($fasilitas) {
                try {
                    $fasilitas->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data fasilitas gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
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
        $fasilitas = FasilitasModel::with(['ruang', 'kategori'])->find($id);
        return view('fasilitas.show_ajax', ['fasilitas' => $fasilitas]);
    }

    public function import()
    {
        $ruang = RuangModel::all();
        $kategori = KategoriModel::all();
        return view('fasilitas.import', [
            'ruang' => $ruang,
            'kategori' => $kategori
        ]);
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_fasilitas' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_fasilitas');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            $errors = [];
            $validRuang = RuangModel::pluck('ruang_id')->toArray();
            $validKategori = KategoriModel::pluck('kategori_id')->toArray();

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        // Validate ruang_id exists
                        if (!in_array($value['B'], $validRuang)) {
                            $errors[] = "Baris $baris: Ruang ID {$value['B']} tidak valid";
                            continue;
                        }

                        // Validate kategori_id exists
                        if (!in_array($value['C'], $validKategori)) {
                            $errors[] = "Baris $baris: Kategori ID {$value['C']} tidak valid";
                            continue;
                        }

                        // Validate status
                        if (!in_array($value['E'], ['baik', 'rusak_ringan', 'rusak_berat'])) {
                            $errors[] = "Baris $baris: Status {$value['E']} tidak valid";
                            continue;
                        }

                        $insert[] = [
                            'fasilitas_kode' => $value['A'],
                            'ruang_id' => $value['B'],
                            'kategori_id' => $value['C'],
                            'deskripsi' => $value['D'],
                            'status' => $value['E'],
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
                    FasilitasModel::insertOrIgnore($insert);
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
        $fasilitas = FasilitasModel::with(['ruang', 'kategori'])
            ->select('fasilitas_id', 'fasilitas_kode', 'ruang_id', 'kategori_id', 'deskripsi', 'status')
            ->orderBy('fasilitas_id')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Fasilitas');
        $sheet->setCellValue('C1', 'Ruang');
        $sheet->setCellValue('D1', 'Kategori');
        $sheet->setCellValue('E1', 'Deskripsi');
        $sheet->setCellValue('F1', 'Status');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($fasilitas as $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->fasilitas_kode);
            $sheet->setCellValue('C'.$baris, $value->ruang->ruang_nama ?? '-');
            $sheet->setCellValue('D'.$baris, $value->kategori->kategori_nama ?? '-');
            $sheet->setCellValue('E'.$baris, $value->deskripsi ?? '-');
            $sheet->setCellValue('F'.$baris, ucfirst(str_replace('_', ' ', $value->status)));
            $baris++;
            $no++;
        }

        foreach(range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Fasilitas');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Fasilitas '.date('Y-m-d H:i:s').'.xlsx';

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
        $fasilitas = FasilitasModel::with(['ruang', 'kategori'])
            ->select('fasilitas_id', 'fasilitas_kode', 'ruang_id', 'kategori_id', 'deskripsi', 'status')
            ->orderBy('fasilitas_id')
            ->get();

        $pdf = Pdf::loadView('fasilitas.export_pdf', ['fasilitas' => $fasilitas]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();
        return $pdf->stream('Data Fasilitas '.date('Y-m-d H:i:s').'.pdf');
    }
}