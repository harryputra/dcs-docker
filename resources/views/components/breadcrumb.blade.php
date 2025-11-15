<nav aria-label="breadcrumb">
    <ol class="mb-0 breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}" class="text-decoration-none">
                Dashboard
            </a>
        </li>
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$loop->last)
                <li class="breadcrumb-item">
                    <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">
                        {{ $breadcrumb['title'] }}
                    </a>
                </li>
            @else
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $breadcrumb['title'] }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>
