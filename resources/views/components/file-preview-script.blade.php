<script>
function previewFiles(input, previewId) {
    const container = document.getElementById(previewId);
    container.innerHTML = '';

    if (!input.files || !input.files.length) return;

    const icons = {
        jpg:'🖼️', jpeg:'🖼️', png:'🖼️', gif:'🖼️', webp:'🖼️', svg:'🖼️',
        pdf:'📄', doc:'📝', docx:'📝', xls:'📊', xlsx:'📊',
        ppt:'📽️', pptx:'📽️', zip:'🗜️', rar:'🗜️'
    };

    Array.from(input.files).forEach((file, idx) => {
        const ext     = file.name.split('.').pop().toLowerCase();
        const sizeStr = file.size > 1048576
            ? (file.size / 1048576).toFixed(2) + ' MB'
            : (file.size / 1024).toFixed(1) + ' KB';
        const icon    = icons[ext] || '📎';
        const isImage = ['jpg','jpeg','png','gif','webp','svg'].includes(ext);
        const isPdf   = ext === 'pdf';

        const wrapper = document.createElement('div');
        wrapper.style.cssText = `border:1px solid #e2e8f0; border-radius:8px; overflow:hidden; margin-top:0.6rem; background:#f8fafc;${idx > 0 ? '' : ''}`;

        // Header
        const header = document.createElement('div');
        header.style.cssText = 'padding:0.55rem 0.9rem; background:#f1f5f9; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; gap:0.5rem;';
        header.innerHTML = `<span style="font-size:13px;font-weight:600;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${icon} ${file.name}</span><span style="font-size:12px;color:#64748b;white-space:nowrap;flex-shrink:0;">${sizeStr}</span>`;
        wrapper.appendChild(header);

        const body = document.createElement('div');

        if (isImage) {
            const reader = new FileReader();
            reader.onload = e => {
                body.style.cssText = 'padding:0.75rem; text-align:center; background:white;';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.cssText = 'max-width:100%; max-height:300px; object-fit:contain; border-radius:6px;';
                body.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else if (isPdf) {
            const reader = new FileReader();
            reader.onload = e => {
                body.style.cssText = '';
                const iframe = document.createElement('iframe');
                iframe.src = e.target.result;
                iframe.style.cssText = 'width:100%; height:360px; border:none; display:block;';
                body.appendChild(iframe);
            };
            reader.readAsDataURL(file);
        } else {
            body.style.cssText = 'padding:1rem; text-align:center; color:#64748b; font-size:13px; background:white;';
            body.innerHTML = `<span style="font-size:32px;display:block;margin-bottom:0.4rem;">${icon}</span><strong>${file.name}</strong> siap dikirim.`;
        }

        wrapper.appendChild(body);
        container.appendChild(wrapper);
    });
}

// Keep backward compat alias
function previewFile(input, previewId) { previewFiles(input, previewId); }
</script>
