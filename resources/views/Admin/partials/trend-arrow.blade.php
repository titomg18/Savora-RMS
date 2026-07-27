{{-- Ikon panah trend kecil: up (hijau), down (merah), flat (garis datar). Dipakai di reports.blade.php --}}
@if ($direction === 'up')
    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <path d="M7 17 17 7"/>
        <path d="M7 7h10v10"/>
    </svg>
@elseif ($direction === 'down')
    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <path d="M7 7 17 17"/>
        <path d="M17 7v10H7"/>
    </svg>
@else
    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <path d="M5 12h14"/>
    </svg>
@endif