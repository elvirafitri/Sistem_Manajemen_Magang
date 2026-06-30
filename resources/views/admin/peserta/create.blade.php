@extends('layouts.panel')

@section('title', 'Tambah Peserta Magang — Admin')
@section('page_title', 'Manajemen peserta magang')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-xl font-bold text-slate-800">Tambah Peserta Baru</h1>
</div>

<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
    <form action="{{ route('admin.peserta.store') }}" method="post" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Foto Profil --}}
            <div class="col-span-1 md:col-span-2">
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Foto Profil <span class="text-xs font-normal text-slate-400 ml-1">(Opsional)</span></label>
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <input type="file" name="avatar" accept="image/jpeg, image/png, image/jpg" class="w-full text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 mb-1">
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Maksimal ukuran 5MB (Hanya JPG, JPEG, PNG)</p>
                @error('avatar')<p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
            </div>

            {{-- Nama --}}
            <div class="col-span-1 border-t border-slate-100 pt-4 md:col-span-2 md:border-0 md:pt-0">
                <h3 class="mb-3 font-semibold text-slate-800">1. Data Akun</h3>
            </div>

            <div class="col-span-1 md:col-span-2">
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input name="name" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap peserta..." class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('name')<p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
            </div>
            
            <div class="col-span-1 md:col-span-2">
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat Email <span class="text-rose-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="contoh@siswa.edu" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('email')<p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Kata Sandi <span class="text-rose-500">*</span></label>
                <input type="password" name="password" required placeholder="Minimal 8 karakter..." class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('password')<p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Ketik Ulang Sandi <span class="text-rose-500">*</span></label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi..." class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            {{-- Data Magang & Kelengkapan --}}
            <div class="mt-2 col-span-1 border-t border-slate-100 pt-4 md:col-span-2">
                <h3 class="mb-2 font-semibold text-slate-800">2. Data Penempatan & Profil Magang</h3>
                <p class="text-[13px] text-slate-500 mb-4">Lengkapi data berikut. (Peserta juga dapat melengkapinya secara mandiri di dasbor mereka).</p>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Periode Mulai <span class="text-rose-500">*</span></label>
                <input type="date" name="periode_mulai" value="{{ old('periode_mulai') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('periode_mulai')<p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Periode Selesai <span class="text-rose-500">*</span></label>
                <input type="date" name="periode_selesai" value="{{ old('periode_selesai') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('periode_selesai')<p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Nomor Telepon (WhatsApp) <span class="text-xs font-normal text-slate-400 ml-1">(Opsional)</span></label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08..." class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('phone')<p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">NIM / NIK / NISN <span class="text-xs font-normal text-slate-400 ml-1">(Opsional)</span></label>
                <input type="text" name="nim" value="{{ old('nim') }}" placeholder="Nomor Induk..." class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('nim')<p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
            </div>

            <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Jenis Program <span class="text-xs font-normal text-slate-400 ml-1">(Opsional)</span></label>
                    <select name="jenis_program" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white">
                        <option value="">— Pilih Program —</option>
                        @foreach(['Prakerin (SMK)', 'Magang Mandiri', 'Kampus Merdeka (MSIB)', 'Penelitian/Tugas Akhir'] as $prog)
                            <option value="{{ $prog }}" @selected(old('jenis_program') == $prog)>{{ $prog }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Institusi / Universitas <span class="text-xs font-normal text-slate-400 ml-1">(Opsional)</span></label>
                    <input type="text" name="institusi" value="{{ old('institusi') }}" placeholder="Asal sekolah/kampus..." class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Jurusan / Prodi <span class="text-xs font-normal text-slate-400 ml-1">(Opsional)</span></label>
                    <input type="text" name="jurusan" value="{{ old('jurusan') }}" placeholder="Jurusan..." class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="col-span-1 md:col-span-2">
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat Lengkap <span class="text-xs font-normal text-slate-400 ml-1">(Opsional)</span></label>
                <textarea name="alamat" rows="2" placeholder="Alamat domisili saat ini..." class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ old('alamat') }}</textarea>
            </div>

            <div class="col-span-1 md:col-span-2">
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Pembimbing Lapangan <span class="text-xs font-normal text-slate-400 ml-1">(Bisa menyusul)</span></label>
                <select name="pembimbing_id" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white">
                    <option value="">— Belum ditetapkan —</option>
                    @foreach($pembimbing as $pb)
                        <option value="{{ $pb->id }}" @selected(old('pembimbing_id') == $pb->id)>{{ $pb->user->name }}</option>
                    @endforeach
                </select>
                @error('pembimbing_id')<p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-8 flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
            <a href="{{ route('admin.peserta.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">Batal</a>
            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">Simpan Data</button>
        </div>
    </form>
</div>
@endsection
