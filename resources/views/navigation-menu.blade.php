{{-- resources/views/navigation-menu.blade.php - Jetstream Navigation --}}
@props(['primaryColor' => '#153E7E'])

@php
  // تحديد الصفحة الرئيسية حسب نوع المستخدم
  $homeRoute = 'front.home';
  $isAdmin = Auth::check() && method_exists(Auth::user(), 'is_admin') && Auth::user()->is_admin;
  if ($isAdmin && Route::has('admin.dashboard')) {
    $homeRoute = 'admin.dashboard';
  }
@endphp

<nav x-data="{ open: false }" class="border-b border-gray-200" style="background-color: {{ $primaryColor }};">
    {{-- شريط علوي لسطح المكتب --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" dir="rtl">
        <div class="flex h-16 justify-between">

            {{-- يمين الشريط: شعار + اسم اللوحة --}}
            <div class="flex items-center gap-3">
                <a href="{{ route($homeRoute) }}" class="flex items-center no-underline">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M3 12l9-9 9 9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 21V9h6v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="ms-2 text-white text-sm md:text-base font-semibold">
                      {{ $isAdmin ? 'لوحة التحكم' : 'جماركي' }}
                    </span>
                </a>

                {{-- روابط داخلية --}}
                <div class="hidden space-x-8 rtl:space-x-reverse lg:-my-px lg:ms-8 lg:flex">
                    <x-nav-link :href="route($homeRoute)" :active="request()->routeIs($homeRoute)" class="text-white hover:text-white/90">
                        الرئيسية
                    </x-nav-link>

                    @if(Route::has('export.calculator'))
                      <x-nav-link :href="route('export.calculator')" :active="request()->routeIs('export.calculator')" class="text-white hover:text-white/90">
                          حاسبة التصدير
                      </x-nav-link>
                    @endif

                    @if(Route::has('export.quotes.index'))
                      <x-nav-link :href="route('export.quotes.index')" :active="request()->routeIs('export.quotes.*')" class="text-white hover:text-white/90">
                          عروض التصدير
                      </x-nav-link>
                    @endif
                </div>
            </div>

            {{-- يسار الشريط: قائمة المستخدم / الفريق --}}
            <div class="hidden lg:flex lg:items-center lg:ms-6">

                {{-- تبديل الفريق (إن كانت ميزة الفرق مفعّلة) --}}
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="left" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-white/10 px-3 py-2 text-sm leading-4 text-white transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/70">
                                        {{ Auth::user()->currentTeam->name ?? 'فريقي' }}
                                        <svg class="ms-2 -me-0.5 h-4 w-4 text-white/90" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    إدارة الفريق
                                </div>

                                <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                    إعدادات الفريق
                                </x-dropdown-link>

                                @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                    <x-dropdown-link href="{{ route('teams.create') }}">
                                        فريق جديد
                                    </x-dropdown-link>
                                @endcan

                                <div class="border-t border-gray-200 my-2"></div>

                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    تبديل الفريق
                                </div>
                                @foreach (Auth::user()->allTeams() as $team)
                                    <x-switchable-team :team="$team" component="dropdown-link" />
                                @endforeach
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                {{-- قائمة الحساب --}}
                <div class="ms-3 relative">
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex rounded-full border-2 border-white/40 transition focus:outline-none focus:ring-2 focus:ring-white/70">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-white/10 px-3 py-2 text-sm leading-4 text-white transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/70">
                                        {{ Auth::user()->name }}
                                        <svg class="ms-2 -me-0.5 h-4 w-4 text-white/90" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                إدارة الحساب
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                الملف الشخصي
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    API Tokens
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200 my-2"></div>

                            {{-- تسجيل الخروج --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}"
                                                 onclick="event.preventDefault(); this.closest('form').submit();">
                                    تسجيل الخروج
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            {{-- زر القائمة للجوال --}}
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center rounded-md p-2 text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/70"
                        aria-label="القائمة">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- قائمة الجوال القابلة للطي --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden" dir="rtl" style="background-color: {{ $primaryColor }};">
        <div class="pt-2 pb-3 space-y-1 border-t border-white/20">
            <x-responsive-nav-link :href="route($homeRoute)" :active="request()->routeIs($homeRoute)" class="text-white hover:bg-white/10">
                الرئيسية
            </x-responsive-nav-link>

            @if(Route::has('export.calculator'))
              <x-responsive-nav-link :href="route('export.calculator')" :active="request()->routeIs('export.calculator')" class="text-white hover:bg-white/10">
                  حاسبة التصدير
              </x-responsive-nav-link>
            @endif

            @if(Route::has('export.quotes.index'))
              <x-responsive-nav-link :href="route('export.quotes.index')" :active="request()->routeIs('export.quotes.*')" class="text-white hover:bg-white/10">
                  عروض التصدير
              </x-responsive-nav-link>
            @endif
        </div>

        {{-- قسم الحساب --}}
        <div class="pt-4 pb-1 border-t border-white/20">
            <div class="px-4 flex items-center gap-3">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <img class="h-10 w-10 rounded-full object-cover border border-white/40" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                @endif
                <div>
                    <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-white/80">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.show')" class="text-white hover:bg-white/10">
                    الملف الشخصي
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link :href="route('api-tokens.index')" class="text-white hover:bg-white/10">
                        API Tokens
                    </x-responsive-nav-link>
                @endif

                {{-- فرق العمل على الجوال --}}
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-white/20 my-2"></div>
                    <div class="px-4 text-xs text-white/80">الفرق</div>

                    <x-responsive-nav-link :href="route('teams.show', Auth::user()->currentTeam->id)" class="text-white hover:bg-white/10">
                        إعدادات الفريق
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link :href="route('teams.create')" class="text-white hover:bg-white/10">
                            فريق جديد
                        </x-responsive-nav-link>
                    @endcan

                    <div class="px-4 text-xs text-white/80">تبديل الفريق</div>
                    @foreach (Auth::user()->allTeams() as $team)
                        <x-switchable-team :team="$team" component="responsive-nav-link" />
                    @endforeach
                @endif

                {{-- تسجيل الخروج --}}
                <form method="POST" action="{{ route('logout') }}" class="border-t border-white/20 mt-2">
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); this.closest('form').submit();"
                                           class="text-white hover:bg-white/10">
                        تسجيل الخروج
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
