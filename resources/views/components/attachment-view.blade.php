{{-- 
    Reusable attachment display (read-only, for show/detail pages).
    $attachment: array of storage paths OR null
    $label: section heading
--}}
@if($attachment && count((array)$attachment) > 0)
@php $files = (array)$attachment; @endphp
<div style="border: 1px solid var(--outline); border-radius: var(--radius); overflow: hidden; background: #f8fafc; margin-top: 1rem;">
    <div style="padding: 0.65rem 1rem; background: #f1f5f9; border-bottom: 1px solid var(--outline); display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 13px; font-weight: 600; color: var(--on-surface);">
            📎 {{ $label ?? 'Lampiran' }} ({{ count($files) }} file)
        </span>
    </div>

    @foreach($files as $i => $path)
    @php
        $ext      = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $rawName  = basename($path);
        $fileName = preg_replace('/^[a-zA-Z0-9]+---/', '', $rawName);
        $fileUrl  = Storage::disk('public')->url($path);
        $isImage  = in_array($ext, ['jpg','jpeg','png','gif','webp']);
        $isPdf    = $ext === 'pdf';
        $icon     = match(true) {
            $isImage              => '🖼️',
            $isPdf                => '📄',
            in_array($ext, ['doc','docx']) => '📝',
            in_array($ext, ['xls','xlsx']) => '📊',
            in_array($ext, ['ppt','pptx']) => '📽️',
            in_array($ext, ['zip','rar'])  => '🗜️',
            default               => '📎',
        };
    @endphp

    <div style="{{ $i > 0 ? 'border-top: 1px solid var(--outline);' : '' }}">
        <!-- File header bar -->
        <div style="padding: 0.5rem 1rem; display: flex; justify-content: space-between; align-items: center; background: white;">
            <span style="font-size: 13px; font-weight: 500; color: var(--on-surface);">
                {{ $icon }} <span style="font-family: monospace; font-size: 12px; color: var(--primary);">{{ $fileName }}</span>
            </span>
            <a href="{{ $fileUrl }}" target="_blank" download
               style="font-size: 12px; font-weight: 600; color: var(--primary); text-decoration: none; white-space: nowrap;">
                ⬇ Unduh
            </a>
        </div>

        @if($isImage)
            <div style="padding: 0.75rem 1rem; text-align: center; background: #fafafa; border-top: 1px solid #f1f5f9;">
                <img src="{{ $fileUrl }}" alt="{{ $fileName }}"
                     style="max-width: 100%; max-height: 400px; object-fit: contain; border-radius: var(--radius); cursor: pointer;"
                     onclick="window.open('{{ $fileUrl }}', '_blank')">
            </div>
        @elseif($isPdf)
            <div style="border-top: 1px solid #f1f5f9;">
                <iframe src="{{ $fileUrl }}" style="width: 100%; height: 420px; border: none; display: block;" title="{{ $fileName }}"></iframe>
            </div>
        @else
            <div style="padding: 1rem 1rem; background: #fafafa; border-top: 1px solid #f1f5f9; text-align: center; font-size: 13px; color: var(--on-surface-variant);">
                Preview tidak tersedia.
                <a href="{{ $fileUrl }}" target="_blank" style="color: var(--primary); font-weight: 600;">Buka File</a>
            </div>
        @endif
    </div>
    @endforeach
</div>
@endif
