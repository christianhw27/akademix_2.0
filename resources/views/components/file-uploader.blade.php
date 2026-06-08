{{--
    Custom multi-file uploader component.
    Usage: @include('components.file-uploader', ['inputName' => 'attachments[]', 'uploaderId' => 'unique-id'])
--}}
@php
    $uid     = $uploaderId ?? 'uploader';
    $iname   = $inputName  ?? 'attachments[]';
    $maxF    = $maxFiles   ?? 10;
    $maxMb   = $maxMb      ?? 20;
@endphp

<style>
.fu-zone {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 1.5rem 1.25rem 1.25rem;
    background: #f8fafc;
    transition: border-color 0.2s, background 0.2s;
    cursor: default;
}
.fu-zone.drag-over {
    border-color: var(--primary);
    background: #eef2ff;
}
.fu-prompt {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    color: #64748b;
    margin-bottom: 1.25rem;
}
.fu-icon { font-size: 2.25rem; line-height: 1; }
.fu-prompt p { font-size: 13px; text-align: center; margin: 0; }
.fu-add-btn {
    display: inline-flex; align-items: center; gap: 0.45rem;
    background: var(--primary); color: white; border: none;
    border-radius: 8px; padding: 0.55rem 1.1rem; font-size: 13px;
    font-weight: 600; cursor: pointer; transition: opacity 0.15s, transform 0.1s;
}
.fu-add-btn:hover { opacity: 0.88; transform: translateY(-1px); }
.fu-file-list { display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.75rem; }
.fu-file-item {
    display: flex; align-items: center; gap: 0.65rem; background: white;
    border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.65rem;
}
.fu-file-thumb {
    width: 44px; height: 44px; border-radius: 6px; flex-shrink: 0;
    background: #f1f5f9; display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; overflow: hidden;
}
.fu-file-thumb img { width: 100%; height: 100%; object-fit: cover; }
.fu-file-info { flex: 1; min-width: 0; }
.fu-file-name { font-size: 13px; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.fu-file-size { font-size: 11px; color: #94a3b8; margin-top: 2px; }
.fu-file-remove {
    background: #fef2f2; border: 1px solid #fecaca; color: #ef4444; border-radius: 6px;
    width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 14px; font-weight: 700; flex-shrink: 0;
}
.fu-file-remove:hover { background: #fee2e2; }
.fu-counter { font-size: 11.5px; color: #94a3b8; margin-top: 0.4rem; text-align: right; }
</style>

<div class="fu-zone" id="fu-zone-{{ $uid }}">
    {{-- Hidden file input --}}
    <input type="file"
           id="fu-input-{{ $uid }}"
           name="{{ $iname }}"
           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.webp,.zip,.rar"
           multiple
           style="display:none;">

    <div class="fu-prompt">
        <span class="fu-icon">📎</span>
        <p>Seret & lepas file di sini, atau klik tombol di bawah</p>
    </div>

    <div style="text-align: center;">
        <button type="button" class="fu-add-btn" id="fu-btn-{{ $uid }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
            </svg>
            Pilih File
        </button>
    </div>

    <div class="fu-file-list" id="fu-list-{{ $uid }}"></div>
    <div class="fu-counter" id="fu-counter-{{ $uid }}" style="display:none;"></div>
</div>

{{-- Global script loaded only once --}}
@if(!defined('FU_SCRIPT_LOADED'))
@php define('FU_SCRIPT_LOADED', true); @endphp
<script>
window.fuRegistry = window.fuRegistry || {};

function fuFormatBytes(bytes) {
    return bytes > 1048576 ? (bytes/1048576).toFixed(2) + ' MB' : (bytes/1024).toFixed(1) + ' KB';
}

function fuGetIcon(ext) {
    const icons = { jpg:'🖼️', jpeg:'🖼️', png:'🖼️', gif:'🖼️', webp:'🖼️', pdf:'📄', doc:'📝', docx:'📝', xls:'📊', xlsx:'📊', ppt:'📽️', pptx:'📽️', zip:'🗜️', rar:'🗜️' };
    return icons[ext] || '📎';
}

function fuRender(uid) {
    const reg = window.fuRegistry[uid];
    const list = document.getElementById('fu-list-' + uid);
    const counter = document.getElementById('fu-counter-' + uid);
    list.innerHTML = '';

    reg.files.forEach((file, idx) => {
        const ext = file.name.split('.').pop().toLowerCase();
        const isImg = ['jpg','jpeg','png','gif','webp'].includes(ext);

        const item = document.createElement('div');
        item.className = 'fu-file-item';

        const thumb = document.createElement('div');
        thumb.className = 'fu-file-thumb';
        if (isImg) {
            const reader = new FileReader();
            reader.onload = e => { thumb.innerHTML = `<img src="${e.target.result}">`; };
            reader.readAsDataURL(file);
            thumb.innerHTML = '...';
        } else {
            thumb.textContent = fuGetIcon(ext);
        }

        const info = document.createElement('div');
        info.className = 'fu-file-info';
        info.innerHTML = `<div class="fu-file-name">${file.name}</div><div class="fu-file-size">${fuFormatBytes(file.size)}</div>`;

        const rm = document.createElement('button');
        rm.type = 'button';
        rm.className = 'fu-file-remove';
        rm.innerHTML = '&times;';
        rm.onclick = () => {
            reg.files.splice(idx, 1);
            fuSyncInput(uid);
            fuRender(uid);
        };

        item.appendChild(thumb);
        item.appendChild(info);
        item.appendChild(rm);
        list.appendChild(item);
    });

    if (reg.files.length > 0) {
        counter.style.display = 'block';
        counter.textContent = `${reg.files.length} file dipilih (maks. ${reg.maxFiles})`;
    } else {
        counter.style.display = 'none';
    }
}

function fuSyncInput(uid) {
    try {
        const input = document.getElementById('fu-input-' + uid);
        const dt = new DataTransfer();
        window.fuRegistry[uid].files.forEach(f => dt.items.add(f));
        input.files = dt.files;
    } catch (e) {
        console.error("DataTransfer error:", e);
        // Fallback for older browsers: we cannot reliably sync multiple selections.
        // We'll just let the original files array be if DataTransfer fails.
    }
}

window.fuInit = function(uid, maxF, maxMb) {
    window.fuRegistry[uid] = { files: [], maxFiles: maxF, maxMb: maxMb };
    
    const input = document.getElementById('fu-input-' + uid);
    const btn = document.getElementById('fu-btn-' + uid);
    const zone = document.getElementById('fu-zone-' + uid);

    btn.onclick = () => input.click();

    input.onchange = function() {
        const newFiles = Array.from(input.files);
        let added = 0;
        
        newFiles.forEach(file => {
            if (window.fuRegistry[uid].files.length >= maxF) {
                alert('Maksimal ' + maxF + ' file.');
                return;
            }
            if (file.size > maxMb * 1048576) {
                alert(file.name + ' melebihi batas ' + maxMb + ' MB.');
                return;
            }
            const isDup = window.fuRegistry[uid].files.some(f => f.name === file.name && f.size === file.size);
            if (!isDup) {
                window.fuRegistry[uid].files.push(file);
                added++;
            }
        });

        // Try to sync back to the input
        fuSyncInput(uid);
        fuRender(uid);
    };

    // Drag and Drop
    zone.ondragover = e => { e.preventDefault(); zone.classList.add('drag-over'); };
    zone.ondragleave = e => { zone.classList.remove('drag-over'); };
    zone.ondrop = e => {
        e.preventDefault();
        zone.classList.remove('drag-over');
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            // Assign to input files to trigger normal flow
            input.files = e.dataTransfer.files;
            // Manually trigger change event
            const event = new Event('change');
            input.dispatchEvent(event);
        }
    };
};
</script>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.fuInit('{{ $uid }}', {{ $maxF }}, {{ $maxMb }});
    });
</script>
