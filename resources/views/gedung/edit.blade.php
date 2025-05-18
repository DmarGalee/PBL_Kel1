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
            <a href="{{ url('gedung') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        @else
            <form method="POST" action="{{ url('/gedung/'.$gedung->gedung_id) }}" class="form-horizontal">
                @csrf
                {!! method_field('PUT') !!}
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Kode Gedung</label>
                    <div class="col-11">
                        <input type="text" class="form-control" id="gedung_kode" name="gedung_kode" value="{{ old('gedung_kode', $gedung->gedung_kode) }}" required>
                        @error('gedung_kode')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Nama Gedung</label>
                    <div class="col-11">
                        <input type="text" class="form-control" id="gedung_nama" name="gedung_nama" value="{{ old('gedung_nama', $gedung->gedung_nama) }}" required>
                        @error('gedung_nama')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Waktu Dibuat</label>
                    <div class="col-11">
                        <input type="text" class="form-control" id="created_at" name="created_at" value="{{ old('created_at', $gedung->created_at) }}" readonly>
                        @error('created_at')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Waktu Diupdate</label>
                    <div class="col-11">
                        <input type="text" class="form-control" id="updated_at" name="updated_at" value="{{ old('updated_at', $gedung->updated_at) }}" readonly>
                        @error('updated_at')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label"></label>
                    <div class="col-11">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-default ml-1" href="{{ url('gedung') }}">Kembali</a>
                    </div>
                </div>
            </form>
        @endempty
    </div>
</div>

@endsection

@push('css')
@endpush

@push('js')
@endpush
