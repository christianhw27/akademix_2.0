{{-- 
    Current attachments list for edit forms (shows existing files with per-file remove checkboxes).
    $attachment: array|null of storage paths
    The form must include a hidden field: <input type="hidden" name="remove_indexes" id="remove_indexes" value="">
--}}
@if($attachment && count((array)$attachment) > 0)
@php $files = (array)$attachment; @endphp
<div style="display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1rem;">
    <div style="font-size: 13px; font-weight: 600; color: var(--on-surface-variant); margin-bottom: 0.25rem;">
        Lampiran Saat Ini ({{ count($files) }} file)
    </div>
    
    <input type="hidden" name="remove_indexes" id="remove_indexes_{{ isset($inputId) ? $inputId : 'main' }}" value="">
    
    @foreach($files as $i => $path)
    @php
        $ext     = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $rawName = basename($path);
        $name    = preg_replace('/^[a-zA-Z0-9]+---/', '', $rawName);
        $url     = Storage::disk('public')->url($path);
        $isImg   = in_array($ext, ['jpg','jpeg','png','gif','webp']);
        $isPdf   = $ext === 'pdf';
        $icon    = match(true) {
            $isImg => '🖼️',
            $isPdf => '📄',
            in_array($ext, ['doc','docx']) => '📝',
            in_array($ext, ['xls','xlsx']) => '📊',
            in_array($ext, ['ppt','pptx']) => '📽️',
            in_array($ext, ['zip','rar'])  => '🗜️',
            default => '📎',
        };
    @endphp
    
    <div id="att-row-{{ isset($inputId) ? $inputId : 'main' }}-{{ $i }}"
         style="display: flex; align-items: center; gap: 0.65rem; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 0.5rem 0.65rem; transition: all 0.2s;">
        
        <div style="width: 44px; height: 44px; border-radius: 6px; flex-shrink: 0; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; overflow: hidden;">
            @if($isImg)
                <img src="{{ $url }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                {{ $icon }}
            @endif
        </div>
        
        <div style="flex: 1; min-width: 0;">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                <div style="font-size: 13px; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ $name }}
                </div>
                <a href="{{ $url }}" target="_blank" style="font-size: 11px; font-weight: 600; color: var(--primary); text-decoration: none; white-space: nowrap;">
                    Lihat File
                </a>
            </div>
            <div id="att-status-{{ isset($inputId) ? $inputId : 'main' }}-{{ $i }}" style="font-size: 11px; color: #94a3b8; margin-top: 2px;">
                File Tersimpan
            </div>
        </div>
        
        <button type="button" 
                id="att-btn-{{ isset($inputId) ? $inputId : 'main' }}-{{ $i }}"
                onclick="toggleRemoveFile('{{ isset($inputId) ? $inputId : 'main' }}', {{ $i }})"
                title="Hapus file ini"
                style="background: #fef2f2; border: 1px solid #fecaca; color: #ef4444; border-radius: 6px; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 14px; font-weight: 700; flex-shrink: 0; transition: all 0.15s; line-height: 1;">
            &times;
        </button>
    </div>
    @endforeach
</div>

<script>
if (typeof toggleRemoveFile !== 'function') {
    window.toggleRemoveFile = function(formId, idx) {
        const hiddenInput = document.getElementById('remove_indexes_' + formId);
        let currentIndexes = hiddenInput.value ? hiddenInput.value.split(',').filter(v => v !== '') : [];
        const row = document.getElementById('att-row-' + formId + '-' + idx);
        const btn = document.getElementById('att-btn-' + formId + '-' + idx);
        const status = document.getElementById('att-status-' + formId + '-' + idx);

        if (!currentIndexes.includes(String(idx))) {
            // Tindakan: Hapus
            currentIndexes.push(String(idx));
            row.style.opacity = '0.6';
            row.style.background = '#fef2f2';
            row.style.borderColor = '#fecaca';
            
            status.textContent = 'Akan dihapus (klik undo untuk batal)';
            status.style.color = '#ef4444';
            status.style.fontWeight = '600';
            
            btn.innerHTML = '↺';
            btn.title = 'Batal hapus file ini';
            btn.style.background = '#e2e8f0';
            btn.style.color = '#475569';
            btn.style.borderColor = '#cbd5e1';
        } else {
            // Tindakan: Batal Hapus (Undo)
            currentIndexes = currentIndexes.filter(v => v !== String(idx));
            row.style.opacity = '1';
            row.style.background = '#f8fafc';
            row.style.borderColor = '#cbd5e1';
            
            status.textContent = 'File Tersimpan';
            status.style.color = '#94a3b8';
            status.style.fontWeight = 'normal';
            
            btn.innerHTML = '&times;';
            btn.title = 'Hapus file ini';
            btn.style.background = '#fef2f2';
            btn.style.color = '#ef4444';
            btn.style.borderColor = '#fecaca';
        }
        hiddenInput.value = currentIndexes.join(',');
    }
}
</script>
@endif
