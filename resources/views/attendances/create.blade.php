@extends('layouts.app')

@section('title', 'Tambah Absensi - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Tambah Absensi</h1>
            <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('attendances.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="teacher_id" class="form-label">Guru</label>
                                <select class="form-select @error('teacher_id') is-invalid @enderror"
                                        id="teacher_id" name="teacher_id" required>
                                    <option value="">Pilih Guru</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                       id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jam_masuk" class="form-label">Jam Masuk</label>
                                <input type="time" class="form-control @error('jam_masuk') is-invalid @enderror"
                                       id="jam_masuk" name="jam_masuk" value="{{ old('jam_masuk') }}">
                                @error('jam_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jam_keluar" class="form-label">Jam Keluar</label>
                                <input type="time" class="form-control @error('jam_keluar') is-invalid @enderror"
                                       id="jam_keluar" name="jam_keluar" value="{{ old('jam_keluar') }}">
                                @error('jam_keluar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror"
                                id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="hadir" {{ old('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="tidak_hadir" {{ old('status') == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                            <option value="terlambat" {{ old('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ old('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                  id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('attendances.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
