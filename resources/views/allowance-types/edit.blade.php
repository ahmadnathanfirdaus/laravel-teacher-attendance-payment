@extends('layouts.app')

@section('title', 'Edit Jenis Tunjangan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Jenis Tunjangan: {{ $allowanceType->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('allowance-types.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <form action="{{ route('allowance-types.update', $allowanceType) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Jenis Tunjangan <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $allowanceType->name) }}"
                                           placeholder="Contoh: Tunjangan Kehadiran, Tunjangan Prestasi"
                                           required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="default_amount">Jumlah Default <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number"
                                               class="form-control @error('default_amount') is-invalid @enderror"
                                               id="default_amount"
                                               name="default_amount"
                                               value="{{ old('default_amount', $allowanceType->default_amount) }}"
                                               placeholder="0"
                                               min="0"
                                               required>
                                        @error('default_amount')
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
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description"
                                              name="description"
                                              rows="3"
                                              placeholder="Deskripsi jenis tunjangan (opsional)">{{ old('description', $allowanceType->description) }}</textarea>
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
                                        <li>Perubahan jumlah default hanya akan mempengaruhi tunjangan baru yang ditambahkan</li>
                                        <li>Tunjangan yang sudah ada pada guru tidak akan berubah secara otomatis</li>
                                        <li>Jika ingin mengubah tunjangan yang sudah ada, edit secara manual di menu Tunjangan Guru</li>
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
                                    Update Jenis Tunjangan
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
