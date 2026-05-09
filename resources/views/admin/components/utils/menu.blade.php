@props([
    'title' => '',
    'listMenu' => [],
    'titleClass' => '',
])

@php
    use App\Utilities\Menu;

    $menu = new Menu($listMenu);
    $all = $menu->getAllPermissions();
@endphp

@canany($all)
    @if ($title)
        <li class="menu-title {{ $titleClass }}"><span data-key="t-{{ $title }}">{{ $title }}</span></li>
    @endif

    @foreach ($menu->getListMenu() as $item)
        @php
            $icon = 'ri-apps-2-line';
            if (isset($item['icon']) && $item['icon']) {
                $icon = $item['icon'];
            }
        @endphp

        @if (! $item['children'])
            @canany($item['permissions'])
                <li class="nav-item">
                    <a
                        class="nav-link menu-link {{ Route::is($item['active']) ? 'active' : '' }}"
                        href="{{ $item['route'] }}"
                    >
                        <i class="{{ $icon }}"></i>
                        <span data-key="t-{{ $item['id'] }}">{{ $item['label'] }}</span>
                        @isset($item['badge'])
                            <span class="badge badge-pill bg-danger">{{ $item['badge'] ?? null }}</span>
                        @endisset
                    </a>
                </li>
            @endcanany
        @else
            @canany($menu->getChildrenPermissions($item))
                <li class="nav-item">
                    <a
                        @class([
                            'nav-link menu-link',
                            'collapsed' => ! Route::is(...$menu->getChildrenActiveRoutes($item)),
                        ])
                        href="#sidebar{{ $item['idSidebar'] }}"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="false"
                        aria-controls="sidebar{{ $item['idSidebar'] }}"
                    >
                        <i class="{{ $icon }}"></i>
                        <span data-key="t-{{ $item['id'] }}">{{ $item['label'] }}</span>
                        @isset($item['badge'])
                            <span class="badge badge-pill bg-success me-3">{{ $item['badge'] }}</span>
                        @endisset
                    </a>
                    <div
                        class="collapse menu-dropdown {{ Route::is(...$menu->getChildrenActiveRoutes($item)) ? 'show' : '' }}"
                        id="sidebar{{ $item['idSidebar'] }}"
                    >
                        <ul class="nav nav-sm flex-column">
                            @foreach ($item['children'] as $child)
                                @if (! $child['children'])
                                    @canany($child['permissions'])
                                        <li class="nav-item">
                                            <a
                                                href="{{ $child['route'] }}"
                                                @class(['nav-link', 'active' => Route::is($child['active'])])
                                                data-key="t-{{ $child['id'] }}"
                                            >
                                                {{ $child['label'] }}
                                                @isset($child['badge'])
                                                    <span class="badge badge-pill bg-danger">
                                                        {{ $child['badge'] }}
                                                    </span>
                                                @endisset
                                            </a>
                                        </li>
                                    @endcanany
                                @else
                                    @canany($menu->getChildrenPermissions($child))
                                        <li class="nav-item">
                                            <a
                                                class="nav-link"
                                                href="#sidebar{{ $child['idSidebar'] }}"
                                                data-bs-toggle="collapse"
                                                role="button"
                                                aria-expanded="false"
                                                aria-controls="sidebar{{ $child['idSidebar'] }}"
                                            >
                                                <span data-key="t-{{ $child['id'] }}">{{ $child['label'] }}</span>
                                                @isset($child['badge'])
                                                    <span class="badge badge-pill bg-success me-3">
                                                        {{ $child['badge'] }}
                                                    </span>
                                                @endisset
                                            </a>
                                            <div
                                                class="collapse menu-dropdown {{ Route::is(...$menu->getChildrenActiveRoutes($child)) ? 'show' : '' }}"
                                                id="sidebar{{ $child['idSidebar'] }}"
                                            >
                                                <ul class="nav nav-sm flex-column">
                                                    @foreach ($child['children'] as $grandChild)
                                                        @if (! $grandChild['children'])
                                                            @canany($grandChild['permissions'])
                                                                <li class="nav-item">
                                                                    <a
                                                                        href="{{ $grandChild['route'] }}"
                                                                        @class(['nav-link', 'active' => Route::is($grandChild['active'])])
                                                                        data-key="t-{{ $grandChild['id'] }}"
                                                                    >
                                                                        {{ $grandChild['label'] }}
                                                                        @isset($grandChild['badge'])
                                                                            <span class="badge badge-pill bg-danger">
                                                                                {{ $grandChild['badge'] }}
                                                                            </span>
                                                                        @endisset
                                                                    </a>
                                                                </li>
                                                            @endcanany
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </li>
                                        <!-- end Dashboard Menu -->
                                    @endcanany
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </li>
                <!-- end Dashboard Menu -->
            @endcanany
        @endif
    @endforeach
@endcanany
