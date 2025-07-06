@extends('layouts.app')

@section('title', 'Generate Gaji - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Generate Gaji</h1>
            <a href="{{ route('salaries.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('salaries.store') }}" method="POST">
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
                                            {{ $teacher->nama_lengkap }} ({{ $teacher->mata_pelajaran }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="bulan" class="form-label">Bulan</label>
                                <select class="form-select @error('bulan') is-invalid @enderror"
                                        id="bulan" name="bulan" required>
                                    <option value="">Pilih Bulan</option>
                                    <option value="January" {{ old('bulan') == 'January' ? 'selected' : '' }}>Januari</option>
                                    <option value="February" {{ old('bulan') == 'February' ? 'selected' : '' }}>Februari</option>
                                    <option value="March" {{ old('bulan') == 'March' ? 'selected' : '' }}>Maret</option>
                                    <option value="April" {{ old('bulan') == 'April' ? 'selected' : '' }}>April</option>
                                    <option value="May" {{ old('bulan') == 'May' ? 'selected' : '' }}>Mei</option>
                                    <option value="June" {{ old('bulan') == 'June' ? 'selected' : '' }}>Juni</option>
                                    <option value="July" {{ old('bulan') == 'July' ? 'selected' : '' }}>Juli</option>
                                    <option value="August" {{ old('bulan') == 'August' ? 'selected' : '' }}>Agustus</option>
                                    <option value="September" {{ old('bulan') == 'September' ? 'selected' : '' }}>September</option>
                                    <option value="October" {{ old('bulan') == 'October' ? 'selected' : '' }}>Oktober</option>
                                    <option value="November" {{ old('bulan') == 'November' ? 'selected' : '' }}>November</option>
                                    <option value="December" {{ old('bulan') == 'December' ? 'selected' : '' }}>Desember</option>
                                </select>
                                @error('bulan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <select class="form-select @error('tahun') is-invalid @enderror"
                                        id="tahun" name="tahun" required>
                                    <option value="">Pilih Tahun</option>
                                    @for($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}" {{ old('tahun', date('Y')) == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                @error('tahun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bonus" class="form-label">Bonus</label>
                                <input type="number" class="form-control @error('bonus') is-invalid @enderror"
                                       id="bonus" name="bonus" value="{{ old('bonus', 0) }}" min="0">
                                @error('bonus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="potongan" class="form-label">Potongan</label>
                                <input type="number" class="form-control @error('potongan') is-invalid @enderror"
                                       id="potongan" name="potongan" value="{{ old('potongan', 0) }}" min="0">
                                @error('potongan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                  id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Sistem akan otomatis menghitung gaji berdasarkan data absensi dan gaji pokok guru yang dipilih.
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('salaries.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Generate Gaji</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
