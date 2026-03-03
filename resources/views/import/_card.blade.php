{{--
    Variables:
      $title      – judul kartu
      $subtitle   – deskripsi singkat
      $type       – key tipe import (misal: data_siswa)
      $color      – accent hex color
      $colorBg    – accent background hex
      $stepLabel  – label step (1a, 2b, dst.)
      $delay      – animation-delay CSS value
      $icon       – SVG path string (heroicons outline)
      $warn       – (opsional) pesan peringatan kecil

    CATATAN: partial ini sengaja pakai inline style bukan Tailwind class,
    agar tidak bergantung pada JIT scan saat dev server sudah berjalan.
--}}
<div class="import-card" style="animation-delay:{{ $delay ?? '0s' }};">

    {{-- Accent stripe --}}
    <div style="height:4px;background:{{ $color }};"></div>

    <div style="padding:18px;display:flex;flex-direction:column;gap:14px;flex:1;"
         x-data="{
             file: null,
             dragging: false,
             status: 'idle',
             jobId: null,
             result: null,
             errorMsg: null,
             pollTimer: null,

             setFile(f) {
                 if (!f || !f.name.match(/\.(csv|xlsx|txt)$/i)) return;
                 this.file = f;
                 let dt = new DataTransfer();
                 dt.items.add(f);
                 this.$refs.inp.files = dt.files;
             },

             async submit() {
                 if (!this.file || this.status !== 'idle') return;
                 this.status = 'uploading';

                 const fd = new FormData();
                 fd.append('file', this.file);
                 fd.append('type', '{{ $type }}');
                 fd.append('_token', this.$refs.token.value);

                 try {
                     const res = await fetch('{{ route('import.ajax') }}', {
                         method: 'POST',
                         headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                         body: fd,
                     });

                     if (!res.ok) {
                         const err = await res.json().catch(() => ({}));
                         throw new Error(err.errors?.file?.[0] || err.message || 'Upload gagal.');
                     }

                     const data = await res.json();
                     this.jobId  = data.job_id;
                     this.status = 'queued';
                     this.startPolling();

                 } catch (e) {
                     this.status   = 'failed';
                     this.errorMsg = e.message;
                 }
             },

             startPolling() {
                 this.pollTimer = setInterval(async () => {
                     try {
                         const res  = await fetch('/import/status/' + this.jobId, {
                             headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                         });
                         if (!res.ok) { clearInterval(this.pollTimer); this.status = 'failed'; this.errorMsg = 'Sesi habis, silakan refresh halaman.'; return; }
                         const data = await res.json();

                         if (data.status === 'processing') {
                             this.status = 'processing';
                         } else if (data.status === 'done') {
                             clearInterval(this.pollTimer);
                             this.status = 'done';
                             this.result = data.result;
                         } else if (data.status === 'failed') {
                             clearInterval(this.pollTimer);
                             this.status   = 'failed';
                             this.errorMsg = data.result?.errors?.[0] ?? 'Proses import gagal.';
                         }
                     } catch (e) {
                         // network hiccup — terus polling
                     }
                 }, 2500);
             },

             reset() {
                 clearInterval(this.pollTimer);
                 this.file     = null;
                 this.status   = 'idle';
                 this.jobId    = null;
                 this.result   = null;
                 this.errorMsg = null;
                 if (this.$refs.inp) this.$refs.inp.value = '';
             }
         }">

        {{-- CSRF token untuk fetch --}}
        <input type="hidden" x-ref="token" value="{{ csrf_token() }}">

        {{-- ── Header ────────────────────────────────── --}}
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:10px;background:{{ $colorBg }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="16" height="16" style="color:{{ $color }};" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size:.8125rem;font-weight:700;color:#0f172a;line-height:1.3;">{{ $title }}</p>
                    <p style="font-size:.6875rem;color:#94a3b8;margin-top:2px;line-height:1.3;">{{ $subtitle }}</p>
                </div>
            </div>
            <span style="font-family:'IBM Plex Mono',monospace;font-size:.6rem;font-weight:700;background:{{ $colorBg }};color:{{ $color }};padding:3px 8px;border-radius:7px;flex-shrink:0;white-space:nowrap;letter-spacing:.04em;">
                {{ $stepLabel }}
            </span>
        </div>

        {{-- ── Warning (opsional) ─────────────────────── --}}
        @if(!empty($warn))
            <div style="display:flex;align-items:center;gap:6px;font-size:.6875rem;color:#92400e;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:7px 10px;">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                {{ $warn }}
            </div>
        @endif

        {{-- ── Idle / Uploading: drop zone + tombol ──── --}}
        <template x-if="status === 'idle' || status === 'uploading'">
            <div style="display:flex;flex-direction:column;gap:10px;flex:1;">

                {{-- Drop zone --}}
                <div style="border:2px dashed #e2e8f0;border-radius:11px;min-height:96px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:border-color .15s,background .15s;"
                     :style="dragging ? 'border-color:{{ $color }};background:{{ $colorBg }};' : (file ? 'border-style:solid;border-color:{{ $color }};background:{{ $colorBg }};' : '')"
                     @click="if(status==='idle') $refs.inp.click()"
                     @dragover.prevent="dragging = true"
                     @dragleave.prevent="dragging = false"
                     @drop.prevent="dragging = false; setFile($event.dataTransfer.files[0])">

                    <template x-if="!file">
                        <div style="text-align:center;padding:14px 12px;">
                            <svg width="22" height="22" style="color:#cbd5e1;margin:0 auto 8px;display:block;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p style="font-size:.75rem;font-weight:500;color:#64748b;">
                                Drop file atau <span style="color:{{ $color }};text-decoration:underline;text-underline-offset:2px;cursor:pointer;">browse</span>
                            </p>
                            <p style="font-size:.65rem;color:#cbd5e1;margin-top:3px;font-family:'IBM Plex Mono',monospace;">.csv · .xlsx</p>
                        </div>
                    </template>

                    <template x-if="file">
                        <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;width:100%;animation:pop .15s ease forwards;">
                            <div style="width:30px;height:30px;border-radius:8px;background:{{ $colorBg }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg width="13" height="13" style="color:{{ $color }};" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <p style="font-size:.75rem;font-weight:600;color:#0f172a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" x-text="file.name"></p>
                                <button type="button"
                                        style="font-size:.65rem;color:{{ $color }};margin-top:1px;cursor:pointer;background:none;border:none;padding:0;"
                                        @click.stop="file=null; $refs.inp.value=''">
                                    Ganti file
                                </button>
                            </div>
                            <svg width="14" height="14" style="color:#22c55e;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </template>
                </div>

                <input type="file" accept=".csv,.xlsx,.txt" style="display:none;" x-ref="inp"
                       @change="setFile($event.target.files[0])">

                {{-- Tombol submit --}}
                <button type="button"
                        :disabled="!file || status !== 'idle'"
                        style="width:100%;padding:8px 12px;border-radius:10px;font-size:.75rem;font-weight:700;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:background .15s,color .15s;"
                        :style="(!file || status !== 'idle') ? 'background:#f1f5f9;color:#94a3b8;cursor:not-allowed;' : 'background:{{ $color }};color:#fff;'"
                        @click="submit()">

                    <template x-if="status === 'idle'">
                        <span style="display:flex;align-items:center;gap:5px;">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Import {{ $title }}
                        </span>
                    </template>

                    <template x-if="status === 'uploading'">
                        <span style="display:flex;align-items:center;gap:5px;">
                            <svg class="spin" width="12" height="12" fill="none" viewBox="0 0 24 24">
                                <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Mengunggah...
                        </span>
                    </template>
                </button>
            </div>
        </template>

        {{-- ── Processing: spinner + pesan ─────────────── --}}
        <template x-if="status === 'queued' || status === 'processing'">
            <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;flex:1;gap:10px;padding:20px 0;">
                <svg class="spin" width="28" height="28" fill="none" viewBox="0 0 24 24" style="color:{{ $color }};">
                    <circle style="opacity:.2" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
                    <path style="opacity:.8" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                <p style="font-size:.75rem;font-weight:600;color:#475569;text-align:center;"
                   x-text="status === 'queued' ? 'Menunggu antrian...' : 'Sedang diproses...'"></p>
                <p style="font-size:.65rem;color:#94a3b8;text-align:center;">Halaman ini aman ditinggal</p>
            </div>
        </template>

        {{-- ── Done: tampilkan hasil ─────────────────── --}}
        <template x-if="status === 'done'">
            <div style="border-top:1px solid #f1f5f9;padding-top:12px;display:flex;flex-direction:column;gap:10px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:8px 6px;text-align:center;">
                        <div style="font-size:1.1rem;font-weight:700;color:#16a34a;line-height:1;" x-text="result.inserted"></div>
                        <div style="font-size:.6rem;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-top:3px;">Berhasil</div>
                    </div>
                    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:8px 6px;text-align:center;">
                        <div style="font-size:1.1rem;font-weight:700;color:#ef4444;line-height:1;" x-text="result.errors.length"></div>
                        <div style="font-size:.6rem;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-top:3px;">Error</div>
                    </div>
                </div>

                {{-- Tampilkan pesan error pertama jika tidak ada error_file (exception global) --}}
                <template x-if="result.errors && result.errors.length > 0 && !result.error_file">
                    <div style="display:flex;align-items:flex-start;gap:6px;font-size:.6875rem;color:#991b1b;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:8px 10px;">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink:0;margin-top:1px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span x-text="result.errors[0]" style="word-break:break-word;"></span>
                    </div>
                </template>

                <template x-if="result.error_file">
                    <a :href="'/import/download-error/' + result.error_file"
                       style="display:inline-flex;align-items:center;gap:4px;font-size:.7rem;font-weight:500;color:{{ $color }};text-decoration:underline;text-underline-offset:2px;">
                        <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download error log
                    </a>
                </template>

                <button type="button" @click="reset()"
                        style="width:100%;padding:7px 12px;border-radius:10px;font-size:.7rem;font-weight:600;border:1px solid #e2e8f0;background:#fff;color:#64748b;cursor:pointer;">
                    Import Ulang
                </button>
            </div>
        </template>

        {{-- ── Failed: tampilkan error ───────────────── --}}
        <template x-if="status === 'failed'">
            <div style="border-top:1px solid #f1f5f9;padding-top:12px;display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;align-items:flex-start;gap:6px;font-size:.6875rem;color:#991b1b;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:8px 10px;">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink:0;margin-top:1px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span x-text="errorMsg"></span>
                </div>
                <button type="button" @click="reset()"
                        style="width:100%;padding:7px 12px;border-radius:10px;font-size:.7rem;font-weight:600;border:1px solid #e2e8f0;background:#fff;color:#64748b;cursor:pointer;">
                    Coba Lagi
                </button>
            </div>
        </template>

    </div>
</div>
