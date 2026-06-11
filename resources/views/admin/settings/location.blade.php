@extends('layouts.panel')

@section('title', 'Pengaturan Lokasi Absensi — Admin')
@section('page_title', 'Pengaturan Lokasi Absensi')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
    {{-- Leaflet extra controls --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css" />
    <style>
        /* Map custom controls UI */
        .leaflet-bar button {
            background-color: #fff;
            width: 34px;
            height: 34px;
            line-height: 34px;
            display: block;
            text-align: center;
            text-decoration: none;
            color: #475569;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        .leaflet-bar button:hover { background-color: #f8fafc; color: #3b82f6; }
        .leaflet-bar button i { font-size: 14px; }
        
        #search-results {
            position: absolute;
            top: calc(100% + 4px);
            left: 0; right: 0;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
            z-index: 1100;
            max-height: 280px;
            overflow-y: auto;
            display: none;
            padding: 4px 0;
        }
        #search-results::-webkit-scrollbar { width: 5px; }
        #search-results::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        #search-results::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .search-result-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 16px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .search-result-item:hover { background: #f1f5f9; }
        .search-result-item .result-icon { color: #64748b; flex-shrink: 0; margin-top: 2px; }
        .search-result-item .result-name { font-size: 13px; font-weight: 600; color: #1e293b; line-height: 1.3; }
        .search-result-item .result-address { font-size: 11px; color: #94a3b8; margin-top: 2px; line-height: 1.4; }
        #search-loading { display: none; position: absolute; right: 14px; top: 50%; transform: translateY(-50%); }
        @keyframes spin { to { transform: translateY(-50%) rotate(360deg); } }
        #search-loading.visible { display: block; animation: spin 0.7s linear infinite; }
        
        /* Layer Control customization */
        .leaflet-control-layers-toggle { width: 34px !important; height: 34px !important; background-size: 20px 20px !important; }
        .leaflet-control-layers-expanded { border-radius: 12px !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1) !important; padding: 10px !important; font-family: inherit !important; font-size: 12px !important; }
    </style>

    </style>
@endpush

@section('content')
<h1 class="mb-6 text-xl font-bold text-slate-800">📍 Pengaturan Lokasi Absensi</h1>

<div class="grid gap-6 lg:grid-cols-5">

    {{-- Form Panel --}}
    <div class="lg:col-span-2 flex flex-col gap-6">
        
        <div class="rounded-[14px] border border-slate-200/80 bg-white p-6 shadow-sm">
            <h2 class="mb-1 font-semibold text-slate-800">Koordinat Kantor</h2>
            <p class="mb-5 text-sm text-slate-500">Cari nama tempat, klik di peta, atau geser penanda untuk menentukan lokasi kantor.</p>

            <form action="{{ route('admin.setting.location.update') }}" method="POST" id="location-form">
                @csrf

                <div class="mb-4">
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500" for="office_lat">Latitude</label>
                    <input type="number" step="any" name="office_lat" id="office_lat"
                           value="{{ old('office_lat', $officeLat) }}"
                           placeholder="contoh: 0.5085"
                           class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                           required>
                </div>

                <div class="mb-4">
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500" for="office_lng">Longitude</label>
                    <input type="number" step="any" name="office_lng" id="office_lng"
                           value="{{ old('office_lng', $officeLng) }}"
                           placeholder="contoh: 101.4471"
                           class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                           required>
                </div>

                <div class="mb-6">
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500" for="allowed_radius_meters">
                        Radius Toleransi (meter)
                    </label>
                    <div class="relative">
                        <input type="number" name="allowed_radius_meters" id="allowed_radius_meters"
                               value="{{ old('allowed_radius_meters', $radius ?? 100) }}"
                               min="10" max="10000"
                               class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                               required>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-xs text-slate-400">meter</span>
                    </div>
                    <p class="mt-1.5 text-xs text-slate-400">Peserta hanya bisa absen dalam radius ini dari titik kantor.</p>
                </div>

                <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-md shadow-blue-500/20 transition hover:bg-blue-700 hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pengaturan
                </button>
            </form>
        </div>

        {{-- Info Box --}}
        <div class="rounded-[14px] border border-blue-200/80 bg-blue-50 p-5">
            <h3 class="mb-2 flex items-center gap-2 text-sm font-bold text-blue-800">
                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/>
                </svg>
                Cara Menggunakan
            </h3>
            <ul class="space-y-1.5 text-xs text-blue-700">
                <li>🔎 Ketik nama tempat di kotak pencarian di atas peta</li>
                <li>📌 Pilih hasil pencarian atau klik langsung di peta</li>
                <li>↕️ Geser penanda untuk menggeser titik lokasi</li>
                <li>📏 Lingkaran biru = zona radius yang diizinkan</li>
                <li>✅ Peserta hanya bisa absen jika berada di dalam lingkaran</li>
            </ul>
        </div>
    </div>

    {{-- Peta --}}
    <div class="lg:col-span-3">
        <div class="overflow-hidden rounded-[14px] border border-slate-200/80 bg-white shadow-sm">
            <div class="border-b border-slate-100 bg-slate-50/50 px-5 py-4">
                <h2 class="font-semibold text-slate-800">Peta Lokasi Kantor</h2>
                <p class="text-xs text-slate-500 mt-0.5">Cari nama tempat atau klik di peta untuk memilih koordinat</p>
            </div>

            {{-- Search Bar --}}
            <div class="border-b border-slate-100 px-4 py-3 bg-white">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" id="place-search"
                           placeholder="Cari nama tempat… contoh: RS Awal Bros Pekanbaru"
                           autocomplete="off"
                           class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-10 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    {{-- Loading spinner --}}
                    <svg id="search-loading" class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>

                    {{-- Autocomplete Dropdown --}}
                    <div id="search-results"></div>
                </div>
            </div>

            <div id="location-map" style="height: 440px; width: 100%;"></div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    const latInput    = document.getElementById('office_lat');
    const lngInput    = document.getElementById('office_lng');
    const radiusInput = document.getElementById('allowed_radius_meters');
    const searchInput = document.getElementById('place-search');
    const searchResults = document.getElementById('search-results');
    const searchLoading = document.getElementById('search-loading');

    // Default center: Indonesia
    const defaultLat = parseFloat(latInput.value) || -0.7893;
    const defaultLng = parseFloat(lngInput.value) || 113.9213;
    const defaultRadius = parseInt(radiusInput.value) || 100;

    const markerIconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png';
    const shadowUrl = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png';

    // Layers
    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    });

    const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
        maxZoom: 18
    });

    const map = L.map('location-map', {
        center: [defaultLat, defaultLng],
        zoom: latInput.value ? 17 : 5,
        layers: [osm]
    });

    // Layer Control
    const baseLayers = {
        "Peta Jalan": osm,
        "Satelit (Citra)": satellite
    };
    L.control.layers(baseLayers, null, { position: 'topright' }).addTo(map);
    
    // Scale control
    L.control.scale({ imperial: false, position: 'bottomleft' }).addTo(map);

    // Locate Me control
    const LocateControl = L.Control.extend({
        onAdd: function(map) {
            const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
            const button = L.DomUtil.create('button', '', container);
            button.innerHTML = '<i class="fas fa-crosshairs"></i>';
            button.title = "Cari Lokasi Saya";
            button.style.fontSize = '16px';
            
            L.DomEvent.on(button, 'click', function(e) {
                L.DomEvent.stopPropagation(e);
                if (navigator.geolocation) {
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    navigator.geolocation.getCurrentPosition((pos) => {
                        const { latitude, longitude } = pos.coords;
                        placeMarker(latitude, longitude, parseInt(radiusInput.value) || 100, "Lokasi Saya Saat Ini");
                        map.setView([latitude, longitude], 17);
                        button.innerHTML = '<i class="fas fa-crosshairs"></i>';
                    }, () => {
                        alert("Gagal mendapatkan lokasi GPS. Pastikan izin lokasi diberikan.");
                        button.innerHTML = '<i class="fas fa-crosshairs"></i>';
                    });
                }
            });
            return container;
        }
    });
    new LocateControl({ position: 'topleft' }).addTo(map);

    // Custom icon
    const officeIcon = L.icon({
        iconUrl: markerIconUrl,
        shadowUrl: shadowUrl,
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41],
    });

    let marker = null;
    let circle = null;

    function placeMarker(lat, lng, radius, label) {
        if (marker) map.removeLayer(marker);
        if (circle) map.removeLayer(circle);

        latInput.value = lat.toFixed(7);
        lngInput.value = lng.toFixed(7);

        const popupContent = label
            ? `<strong>📍 ${label}</strong><br><span style="font-size:11px;color:#64748b">Klik simpan jika sudah tepat</span>`
            : `<strong>📍 Lokasi Kantor</strong><br><span style="font-size:11px;color:#64748b">Geser untuk pindahkan titik</span>`;

        marker = L.marker([lat, lng], { icon: officeIcon, draggable: true })
            .addTo(map)
            .bindPopup(popupContent);
        
        if (label) marker.openPopup();

        circle = L.circle([lat, lng], {
            radius: radius,
            color: '#3b82f6',
            fillColor: '#3b82f6',
            fillOpacity: 0.12,
            weight: 2,
        }).addTo(map);

        marker.on('dragend', function (e) {
            const pos = e.target.getLatLng();
            placeMarker(pos.lat, pos.lng, parseInt(radiusInput.value) || 100);
        });
    }

    if (latInput.value && lngInput.value) {
        placeMarker(defaultLat, defaultLng, defaultRadius);
    }

    map.on('click', function (e) {
        placeMarker(e.latlng.lat, e.latlng.lng, parseInt(radiusInput.value) || 100);
    });


    radiusInput.addEventListener('input', function () {
        if (marker) {
            const pos = marker.getLatLng();
            placeMarker(pos.lat, pos.lng, parseInt(this.value) || 100);
        }
    });

    // ============================================================
    // Place Search (Nominatim API — OpenStreetMap, free)
    // ============================================================
    let debounceTimer = null;
    let activeIndex   = -1;

    function showResults(items) {
        searchResults.innerHTML = '';
        activeIndex = -1;

        if (!items.length) {
            searchResults.innerHTML = '<div style="padding:14px;text-align:center;font-size:13px;color:#94a3b8;">Tempat tidak ditemukan.</div>';
            searchResults.style.display = 'block';
            return;
        }

        items.forEach((item, i) => {
            const div = document.createElement('div');
            div.className = 'search-result-item';
            div.dataset.index = i;

            // Pecah display_name: bagian pertama = nama, sisanya = alamat
            const parts = item.display_name.split(', ');
            const name    = parts.slice(0, 2).join(', ');
            const address = parts.slice(2).join(', ');

            div.innerHTML = `
                <svg class="result-icon h-4 w-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <div>
                    <div class="result-name">${name}</div>
                    ${address ? `<div class="result-address">${address}</div>` : ''}
                </div>`;

            div.addEventListener('mousedown', function (e) {
                e.preventDefault(); // cegah blur di input
                const lat = parseFloat(item.lat);
                const lng = parseFloat(item.lon);
                const radius = parseInt(radiusInput.value) || 100;

                placeMarker(lat, lng, radius, name);
                map.setView([lat, lng], 17);

                searchInput.value = name;
                searchResults.style.display = 'none';
            });

            searchResults.appendChild(div);
        });

        searchResults.style.display = 'block';
    }

    function hideResults() {
        searchResults.style.display = 'none';
        activeIndex = -1;
    }

    function highlightItem(index) {
        const items = searchResults.querySelectorAll('.search-result-item');
        items.forEach((el, i) => {
            el.style.background = i === index ? '#eff6ff' : '';
        });
    }

    async function doSearch(query) {
        if (query.length < 3) { hideResults(); return; }

        searchLoading.classList.add('visible');
        try {
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=6&accept-language=id`;
            const res  = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            showResults(data);
        } catch (e) {
            searchResults.innerHTML = '<div style="padding:14px;text-align:center;font-size:13px;color:#ef4444;">Gagal memuat hasil pencarian.</div>';
            searchResults.style.display = 'block';
        } finally {
            searchLoading.classList.remove('visible');
        }
    }

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const q = this.value.trim();
        if (!q) { hideResults(); return; }
        debounceTimer = setTimeout(() => doSearch(q), 400);
    });

    // Keyboard navigation
    searchInput.addEventListener('keydown', function (e) {
        const items = searchResults.querySelectorAll('.search-result-item');
        if (!items.length) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIndex = Math.min(activeIndex + 1, items.length - 1);
            highlightItem(activeIndex);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeIndex = Math.max(activeIndex - 1, 0);
            highlightItem(activeIndex);
        } else if (e.key === 'Enter' && activeIndex >= 0) {
            e.preventDefault();
            items[activeIndex]?.dispatchEvent(new MouseEvent('mousedown'));
        } else if (e.key === 'Escape') {
            hideResults();
        }
    });

    searchInput.addEventListener('blur', function () {
        setTimeout(hideResults, 200);
    });

    searchInput.addEventListener('focus', function () {
        if (this.value.trim().length >= 3 && searchResults.innerHTML) {
            searchResults.style.display = 'block';
        }
    });
})();
</script>
@endpush
