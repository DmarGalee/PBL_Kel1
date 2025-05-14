@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        @empty($gedung)
        <div class="alert alert-danger alert-dismissible">
            <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
            Data yang Anda cari tidak ditemukan.
        </div>
        @else
        <table class="table table-bordered table-striped table-hover table-sm">
            <tr>
                <th>ID</th>
                <td>{{ $gedung->gedung_id }}</td>
            </tr>
            <tr>
                <th>Kode Gedung</th>
                <td>{{ $gedung->gedung_kode }}</td>
            </tr>
            <tr>
                <th>Nama Gedung</th>
                <td>{{ $gedung->gedung_nama }}</td>
            </tr>
            <tr>
                <th>Waktu Dibuat</th>
                <td>{{ $gedung->created_at }}</td>
            </tr>
            <tr>
                <th>Waktu Diperbarui</th>
                <td>{{ $gedung->updated_at }}</td>
            </tr>
        </table>
        @endempty
        <a href="{{ url('gedung') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
    </div>
</div>
@endsection

@push('css')
@endpush

@push('js')
@endpush
