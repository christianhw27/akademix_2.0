<a href="{{ route('student.dashboard') }}" class="nav-item {{ ($active ?? '') === 'dashboard' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 0.75rem; font-size: 13px; font-weight: 600; letter-spacing: 0.05em;">
    <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="7" height="7"></rect>
        <rect x="14" y="3" width="7" height="7"></rect>
        <rect x="14" y="14" width="7" height="7"></rect>
        <rect x="3" y="14" width="7" height="7"></rect>
    </svg>
    RINGKASAN
</a>
<a href="{{ route('student.materials') }}" class="nav-item {{ ($active ?? '') === 'materials' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 0.75rem; font-size: 13px; font-weight: 600; letter-spacing: 0.05em;">
    <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
        <line x1="16" y1="2" x2="16" y2="6"></line>
        <line x1="8" y1="2" x2="8" y2="6"></line>
        <line x1="3" y1="10" x2="21" y2="10"></line>
    </svg>
    JADWAL
</a>
<a href="{{ route('student.assignments') }}" class="nav-item {{ ($active ?? '') === 'assignments' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 0.75rem; font-size: 13px; font-weight: 600; letter-spacing: 0.05em;">
    <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
    </svg>
    KELAS
</a>
<a href="{{ route('student.attendance') }}" class="nav-item {{ ($active ?? '') === 'attendance' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 0.75rem; font-size: 13px; font-weight: 600; letter-spacing: 0.05em;">
    <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="9 11 12 14 22 4"></polyline>
        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
    </svg>
    KEHADIRAN
</a>
<a href="{{ route('student.grades') }}" class="nav-item {{ ($active ?? '') === 'grades' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 0.75rem; font-size: 13px; font-weight: 600; letter-spacing: 0.05em;">
    <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="20" x2="18" y2="10"></line>
        <line x1="12" y1="20" x2="12" y2="4"></line>
        <line x1="6" y1="20" x2="6" y2="14"></line>
    </svg>
    RAPOR
</a>
