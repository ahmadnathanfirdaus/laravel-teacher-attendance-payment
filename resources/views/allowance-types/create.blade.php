@extends('layouts.app')

@section('title', 'Tambah Jenis Tunjangan')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Jenis Tunjangan Baru
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('allowance-types.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('allowance-types.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Nama Jenis Tunjangan <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="Contoh: Tunjangan Kehadiran, Tunjangan Prestasi" required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="default_amount">Jumlah Default <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number"
                                                class="form-control @error('default_amount') is-invalid @enderror"
                                                id="default_amount" name="default_amount"
                                                value="{{ old('default_amount') }}" placeholder="0" min="0" required>
                                            @error('default_amount')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="calculation_type">Perhitungan <span class="text-danger">*</span></label>
                                        <select class="form-control @error('calculation_type') is-invalid @enderror"
                                            id="calculation_type" name="calculation_type" required>
                                            <option value="">Pilih Perhitungan</option>
                                            <option value="per_hari"
                                                {{ old('calculation_type', 'per_hari') == 'per_hari' ? 'selected' : '' }}>
                                                Per Hari</option>
                                            <option value="per_bulan"
                                                {{ old('calculation_type', 'per_hari') == 'per_bulan' ? 'selected' : '' }}>
                                                Per Bulan</option>
                                        </select>
                                        @error('calculation_type')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3" placeholder="Deskripsi jenis tunjangan (opsional)">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Informasi:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Nama jenis tunjangan harus unik dan tidak boleh sama</li>
                                        <li>Jumlah default akan digunakan sebagai nilai awal saat menambahkan tunjangan ke
                                            guru</li>
                                        <li>Nilai tunjangan per guru masih dapat disesuaikan setelah jenis tunjangan ini
                                            dibuat</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Jenis Tunjangan
                            </button>
                            <a href="{{ route('allowance-types.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Format currency input
            $('#default_amount').on('input', function() {
                var value = this.value.replace(/[^\d]/g, '');
                this.value = value;
            });
        });
    </script>
@endsection
