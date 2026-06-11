@extends('layouts.panel')

@section('title', 'Profil Peserta Magang — Peserta')
@section('page_title', 'Profil Peserta Magang')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Data Personal & Dokumen</h1>
        <p class="mt-1 text-sm text-slate-500">Lengkapi profil magang dan unggah dokumen persyaratan Anda di sini.</p>
    </div>
</div>

@if($profile)

@if(empty($profile->nim))
<div class="mb-6 rounded-[14px] bg-rose-50 p-4 border border-rose-200 shadow-sm transition-all">
    <div class="flex items-start gap-3">
        <div class="mt-0.5 rounded-full bg-rose-100 p-1 text-rose-600">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
            <h3 class="text-sm font-bold text-rose-800">Tindakan Diperlukan</h3>
            <p class="mt-1 text-sm text-rose-700 font-medium">Anda belum melengkapi profil! Harap isi data wajib di bawah (seperti NIM/NIS) agar dapat menggunakan fitur absen masuk.</p>
        </div>
    </div>
</div>
@endif

<div class="grid gap-8 lg:grid-cols-3">
    
    {{-- Kolom Form Profil --}}
    <div class="lg:col-span-2">
        <form action="{{ route('peserta.profile.update') }}" method="post" enctype="multipart/form-data" class="space-y-6 rounded-[14px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8 transition-all hover:shadow-md hover:border-slate-300">
            @csrf @method('PUT')
            
            <div class="flex flex-col sm:flex-row items-center gap-6 border-b border-slate-100 pb-8">
                <div id="avatar-wrapper" class="relative h-24 w-24 shrink-0">
                    <div id="avatar-circle" class="h-24 w-24 overflow-hidden rounded-full border-4 border-white shadow-md bg-slate-100 ring-1 ring-slate-200 transition-all" style="position:relative;">
                        @if(auth()->user()->avatar_path)
                            <img id="avatar-current" src="{{ route('storage.file', auth()->user()->avatar_path) }}" class="h-full w-full object-cover">
                        @else
                            <div id="avatar-initial" class="flex h-full w-full items-center justify-center text-3xl font-bold text-blue-300 bg-blue-50">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <img id="avatar-preview" src="" class="h-full w-full object-cover" style="display:none;position:absolute;top:0;left:0;width:100%;height:100%;">
                    </div>
                    {{-- Badge indikator baru dipilih --}}
                    <span id="avatar-badge" class="absolute -bottom-1 -right-1 hidden items-center justify-center rounded-full bg-emerald-500 text-white shadow-md" style="width:24px;height:24px;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </span>
                </div>
                <div class="text-center sm:text-left">
                    <h2 class="text-xl font-bold text-slate-900">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-slate-500 mb-3 font-medium">{{ auth()->user()->email }}</p>
                    <label class="cursor-pointer inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:bg-slate-50 focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Ubah Foto Profil</span>
                        <input id="avatar-input" type="file" name="avatar" class="hidden" accept=".jpg,.jpeg,.png">
                    </label>
                    <div id="avatar-filename" class="hidden mt-2 items-center gap-1.5 text-xs font-semibold text-emerald-600">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span id="avatar-filename-text"></span>
                        <span class="text-slate-400 font-normal">— klik Simpan untuk menerapkan</span>
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <h3 class="font-bold text-slate-800 text-lg mb-5">Data Personal Magang</h3>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">NIM / NIS <span class="text-rose-500">*</span></label>
                        <input name="nim" value="{{ old('nim', $profile->nim) }}" required placeholder="Masukkan nomor induk" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-slate-50 focus:bg-white">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                        <input name="phone" value="{{ old('phone', $profile->phone) }}" required placeholder="08..." class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-slate-50 focus:bg-white">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Program Studi / Jurusan <span class="text-rose-500">*</span></label>
                        <input name="jurusan" value="{{ old('jurusan', $profile->jurusan) }}" required placeholder="Contoh: Teknik Informatika" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-slate-50 focus:bg-white">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Asal Institusi / Kampus <span class="text-rose-500">*</span></label>
                        <input name="institusi" value="{{ old('institusi', $profile->institusi) }}" required placeholder="Contoh: Universitas..." class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-slate-50 focus:bg-white">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat Lengkap <span class="text-rose-500">*</span></label>
                        <textarea name="alamat" rows="3" required placeholder="Alamat domisili saat ini..." class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-slate-50 focus:bg-white">{{ old('alamat', $profile->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-8 mt-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">Unggah Dokumen Magang</h3>
                        <p class="text-sm text-slate-500">Unggah Surat Pengantar / Surat Balasan instansi (format <strong>PDF</strong> saja).</p>
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 mb-5">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Tipe Dokumen</label>
                        <select name="kategori_dokumen" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white">
                            <option value="Surat Pengantar Magang">Surat Pengantar Magang</option>
                            <option value="Surat Balasan Instansi">Surat Balasan Instansi</option>
                            <option value="Laporan Magang">Laporan Magang</option>
                            <option value="Lainnya">Lainnya...</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Dokumen Opsional <span class="text-slate-400 font-normal">(Bila "Lainnya")</span></label>
                        <input name="nama_dokumen" placeholder="Cth: Transkrip Nilai" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white">
                    </div>
                </div>
                
                <div class="flex items-center gap-4 rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 transition-all focus-within:border-blue-500 focus-within:bg-white hover:border-slate-400">
                    <input type="file" name="dokumen" accept=".pdf" class="w-full text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-blue-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-200 transition-colors cursor-pointer">
                </div>
                <p class="mt-1.5 text-xs text-slate-400">Hanya file PDF · Maks. 5MB</p>
            </div>

            <div class="border-t border-slate-100 pt-6 mt-6 flex justify-end">
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-8 py-3 text-sm font-bold text-white shadow-sm hover:focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 hover:bg-blue-700 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Kolom Kanan: Dokumen Terupload --}}
    <div class="lg:col-span-1 border-t border-slate-200 pt-8 mt-4 lg:mt-0 lg:border-t-0 lg:pt-0">
        <h2 class="mb-4 font-bold text-slate-800 text-lg">Arsip Dokumen Anda</h2>
        
        @if($profile->documents->isNotEmpty())
            <div class="flex flex-col gap-3">
                @foreach($profile->documents as $d)
                    <div class="group flex items-start gap-4 rounded-[14px] border border-slate-200 bg-white p-4 shadow-sm transition-all hover:border-slate-300 hover:shadow-md">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-500 ring-1 ring-inset ring-blue-500/10 group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0 pt-0.5">
                            <h4 class="truncate text-sm font-bold text-slate-900 mb-0.5" title="{{ $d->nama }}">{{ $d->nama }}</h4>
                            <p class="text-xs text-slate-500 mb-2 truncate">Diupload: {{ $d->created_at->format('d/m/Y') }}</p>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('storage.file', $d->path) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                                    Lihat Berkas <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                                <form action="{{ route('peserta.profile.document.destroy', $d) }}" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold text-rose-600 hover:text-rose-800 transition-colors" title="Hapus Dokumen">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-[14px] border border-slate-200 border-dashed bg-slate-50 p-8 text-center shadow-sm">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-3">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-700">Arsip Kosong</h3>
                <p class="mt-1 text-xs text-slate-500">Anda belum mengunggah dokumen apapun.</p>
            </div>
        @endif
    </div>
</div>

@else
<div class="rounded-[14px] border border-rose-200 bg-rose-50 p-6 text-center shadow-sm max-w-xl">
    <h2 class="text-lg font-bold text-rose-800">Ups...</h2>
    <p class="mt-1 text-rose-700 font-medium">Terjadi kesalahan, Data Magang Anda tidak tersedia di database. Mohon lapor ke Admin.</p>
</div>
@endif

@push('scripts')
<script>
    (function () {
        var input = document.getElementById('avatar-input');
        var preview = document.getElementById('avatar-preview');
        var initial = document.getElementById('avatar-initial');
        var badge = document.getElementById('avatar-badge');
        var filenameBox = document.getElementById('avatar-filename');
        var filenameText = document.getElementById('avatar-filename-text');
        var avatarCircle = document.getElementById('avatar-wrapper').querySelector('div');

        if (!input) return;

        input.addEventListener('change', function () {
            var file = this.files[0];
            if (!file) return;

            // Validasi tipe file
            var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert('Hanya file JPG, JPEG, atau PNG yang diperbolehkan.');
                this.value = '';
                return;
            }

            // Validasi ukuran (maks 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimum 5MB.');
                this.value = '';
                return;
            }

            // Tampilkan nama file
            filenameText.textContent = file.name.length > 28 ? file.name.substring(0, 25) + '...' : file.name;
            filenameBox.style.display = 'flex';

            // Tampilkan preview
            var reader = new FileReader();
            reader.onload = function (e) {
                if (initial) initial.style.display = 'none';
                preview.src = e.target.result;
                preview.style.display = 'block';
                preview.style.position = '';
            };
            reader.readAsDataURL(file);

            // Tampilkan badge centang
            badge.style.display = 'flex';

            // Hijaukan ring avatar
            avatarCircle.classList.remove('ring-slate-200');
            avatarCircle.classList.add('ring-emerald-400');
        });
    })();
</script>
@endpush
@endsection
