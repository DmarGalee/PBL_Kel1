<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PeriodeModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class PeriodeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Periode',
            'list' => ['Home', 'Periode']
        ];

        $page = (object) [
            'title' => 'Daftar periode yang terdaftar dalam sistem'
        ];

        $activeMenu = 'periode'; // set menu yang sedang aktif

        return view('periode.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Ambil data periode dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $periodes = PeriodeModel::select('periode_id', 'periode_name', 'is_active');

        return DataTables::of($periodes)
            ->addIndexColumn()
            ->addColumn('aksi', function ($periode) {
                $btn = '<button onclick="modalAction(\'' . url('/periode/' . $periode->periode_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/periode/' . $periode->periode_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/periode/' . $periode->periode_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->editColumn('is_active', function ($periode) {
                return $periode->is_active ? 'Aktif' : 'Tidak Aktif';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan halaman form tambah periode
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Periode',
            'list' => ['Home', 'Periode', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah periode baru'
        ];

        $activeMenu = 'periode';

        return view('periode.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan data periode baru
    public function store(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|string|max:4|unique:m_periode,periode_id',
            'periode_name' => 'required|string|max:100',
            'is_active' => 'required|boolean'
        ]);

        PeriodeModel::create([
            'periode_id' => $request->periode_id,
            'periode_name' => $request->periode_name,
            'is_active' => $request->is_active,
        ]);

        return redirect('/periode')->with('success', 'Data periode berhasil disimpan');
    }

    // Menampilkan detail periode
    public function show(string $id)
    {
        $periode = PeriodeModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Periode',
            'list' => ['Home', 'Periode', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Periode'
        ];

        $activeMenu = 'periode';

        return view('periode.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'periode' => $periode, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan halaman form edit periode
    public function edit(string $id)
    {
        $periode = PeriodeModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit Periode',
            'list' => ['Home', 'Periode', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit periode'
        ];

        $activeMenu = 'periode';

        return view('periode.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'periode' => $periode, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan perubahan data periode
    public function update(Request $request, string $id)
    {
        $request->validate([
            'periode_id' => 'required|string|max:4|unique:m_periode,periode_id,' . $id . ',periode_id',
            'periode_name' => 'required|string|max:100',
            'is_active' => 'required|boolean'
        ]);

        PeriodeModel::find($id)->update([
            'periode_id' => $request->periode_id,
            'periode_name' => $request->periode_name,
            'is_active' => $request->is_active,
        ]);

        return redirect('/periode')->with('success', 'Data periode berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = PeriodeModel::find($id);

        if (!$check) {
            return redirect('/periode')->with('error', 'Data periode tidak ditemukan');
        }

        try {
            PeriodeModel::destroy($id);
            return redirect('/periode')->with('success', 'Data periode berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/periode')->with('error', 'Data periode gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function show_ajax(string $id)
    {
        $periode = PeriodeModel::find($id);
        return view('periode.show_ajax', ['periode' => $periode]);
    }
    
    public function create_ajax()
    {
        $periodes = PeriodeModel::select('periode_id', 'periode_name')->get();

        return view('periode.create_ajax', ['periodes' => $periodes]);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'periode_id' => 'required|string|max:4|unique:m_periode,periode_id',
                'periode_name' => 'required|string|max:100',
                'is_active' => 'required|boolean'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            PeriodeModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data periode berhasil disimpan'
            ]);
        }
    }

    public function edit_ajax(string $id)
    {
        $periode = PeriodeModel::find($id);
        return view('periode.edit_ajax', ['periode' => $periode]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'periode_id' => 'required|string|max:4|unique:m_periode,periode_id,' . $id . ',periode_id',
                'periode_name' => 'required|string|max:100',
                'is_active' => 'required|boolean'
            ];
            
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }
            
            $check = PeriodeModel::find($id);
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
        return redirect('/periode');
    }

    public function confirm_ajax(string $id)
    {
        $periode = PeriodeModel::find($id);

        return view('periode.confirm_ajax', ['periode' => $periode]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $periode = PeriodeModel::find($id);

            if ($periode) {
                try {
                    $periode->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak dapat dihapus karena masih terhubung dengan data lain.'
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
    
    public function import()
    {
        return view('periode.import');
    }
    
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
    
            $rules = [
                'file_periode' => ['required', 'mimes:xlsx', 'max:1024']
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $file = $request->file('file_periode');
    
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
                            'periode_id' => $value['A'],
                            'periode_name' => $value['B'],
                            'is_active' => strtolower($value['C']) === 'aktif' ? 1 : 0,
                            'created_at' => now(),
                        ];
                    }
                }
    
                if (count($insert) > 0) {
                    PeriodeModel::insertOrIgnore($insert);
                }
    
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_excel()
    {
        $periodes = PeriodeModel::select(
            'periode_id',
            'periode_name',
            'is_active'
        )
        ->orderBy('periode_id')
        ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Periode');
        $sheet->setCellValue('C1', 'Nama Periode');
        $sheet->setCellValue('D1', 'Status');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($periodes as $periode) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $periode->periode_id);
            $sheet->setCellValue('C' . $baris, $periode->periode_name);
            $sheet->setCellValue('D' . $baris, $periode->is_active ? 'Aktif' : 'Tidak Aktif');
            $no++;
            $baris++;
        }

        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Periode');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Periode_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
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
        $periodes = PeriodeModel::select(
            'periode_id',
            'periode_name',
            'is_active'
        )
        ->orderBy('periode_id')
        ->get();

        $pdf = PDF::loadView('periode.export_pdf', ['periodes' => $periodes]);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Periode '.date('Y-m-d H-i-s').'.pdf');
    }
}