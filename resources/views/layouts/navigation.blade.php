<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm"> <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-indigo-600" /> 
                    </a>
                </div>
                
                <div class="hidden space-x-2 sm:-my-px sm:ms-8 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
                
                <div class="hidden space-x-2 sm:-my-px sm:ms-4 sm:flex">
                    <x-dropdown align="left" class="h-full flex items-center">
                        <x-slot name="trigger">
                            <button class="h-16 flex items-center px-3 pt-1 border-b-2 
                                        {{ request()->routeIs(['reimbursements.*', 'cuti.*', 'lembur.*']) ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} 
                                        text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                {{ __('Pengajuan') }}
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Layanan Karyawan') }}
                            </div>
                            <x-dropdown-link :href="route('reimbursements.index')" :active="request()->routeIs('reimbursements.*')">
                                {{ __('Reimbursements') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('cuti.index')" :active="request()->routeIs('cuti.*')">
                                {{ __('Cuti') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('lembur.index')" :active="request()->routeIs('lembur.*')">
                                {{ __('Lembur') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('pengunduran.index')" :active="request()->routeIs('pengunduran.*')">
                                {{ __('Pengunduran Diri') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="hidden space-x-2 sm:-my-px sm:ms-4 sm:flex">
                    <x-nav-link :href="route('master-restaurants.index')" :active="request()->routeIs('master-restaurants.*')">
                        {{ __('Data Restaurant') }}
                    </x-nav-link>
                    <x-nav-link :href="route('lunch-events.index')" :active="request()->routeIs('lunch-events.*')">
                        {{ __('Makan Siang') }} </x-nav-link>
                </div>
                
                @if(Auth::user()->hasRole('admin'))
                <div class="hidden space-x-2 sm:-my-px sm:ms-4 sm:flex">
                    <x-dropdown align="left" class="h-full flex items-center">
                        <x-slot name="trigger">
                            <button class="h-16 flex items-center px-3 pt-1 border-b-2 
                                        {{ request()->routeIs(['admin.roles.*', 'admin.permissions.*', 'admin.master-cuti.*', 'admin.master-assets.*', 'admin.penilaian.*', 'admin.users.*']) ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} 
                                        text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                {{ __('Master') }}
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Administrasi & Data Utama') }}
                            </div>
                            <x-dropdown-link :href="route('admin.dashboard-kontrak')" :active="request()->routeIs('admin.dashboard-kontrak')">
                                {{ __('Dashboard Kontrak') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')">
                                {{ __('Roles') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.permissions.index')" :active="request()->routeIs('admin.permissions.*')">
                                {{ __('Permissions') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.master-cuti.index')" :active="request()->routeIs('admin.master-cuti.*')">
                                {{ __('Master Cuti') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.master-assets.index')" :active="request()->routeIs('admin.master-assets.*')">
                                {{ __('Master Asset') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.penilaian.index')" :active="request()->routeIs('admin.penilaian.*')">
                                {{ __('Penilaian Pegawai') }}
                            </x-dropdown-link>
                            
                            <x-dropdown-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                {{ __('Users') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
                <div class="hidden space-x-2 sm:-my-px sm:ms-4 sm:flex">
                    <x-dropdown align="left" class="h-full flex items-center">
                        <x-slot name="trigger">
                            <button class="h-16 flex items-center px-3 pt-1 border-b-2 
                                        {{ request()->routeIs(['admin.laporan-reimbursements.*']) ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} 
                                        text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                {{ __('Laporan') }}
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Laporan Keuangan') }}
                            </div>
                            <x-dropdown-link :href="route('admin.laporan-reimbursements.index')" :active="request()->routeIs('admin.laporan-reimbursements.*')">
                                {{ __('Daftar Reimbursements') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.laporan-reimbursements.search')" :active="request()->routeIs('admin.laporan-reimbursements.*')">
                                {{ __('Generate Reimbursements') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
                @endif
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-gray-600 bg-gray-50 hover:text-gray-800 focus:outline-none transition ease-in-out duration-150 shadow-sm hover:shadow-md"> <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil Saya') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <div x-data="{ pengajuan_open: false }">
                <button @click="pengajuan_open = ! pengajuan_open" class="w-full flex items-center ps-3 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    {{ __('Pengajuan') }}
                    <div class="ms-auto me-0">
                        <svg class="fill-current h-4 w-4 transform transition duration-150 ease-in-out" :class="{'rotate-180': pengajuan_open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
                <div x-show="pengajuan_open" class="bg-gray-50 border-s border-indigo-500">
                    <x-responsive-nav-link :href="route('reimbursements.index')" :active="request()->routeIs('reimbursements.*')" class="ps-8">
                        {{ __('Reimbursements') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('cuti.index')" :active="request()->routeIs('cuti.*')" class="ps-8">
                        {{ __('Cuti') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('lembur.index')" :active="request()->routeIs('lembur.*')" class="ps-8">
                        {{ __('Lembur') }}
                    </x-responsive-nav-link>
                </div>
            </div>
            
            <x-responsive-nav-link :href="route('master-restaurants.index')" :active="request()->routeIs('master-restaurants.*')">
                {{ __('Data Restaurant') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('lunch-events.index')" :active="request()->routeIs('lunch-events.*')">
                {{ __('Makan Siang') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                {{ __('Profil Saya') }}
            </x-responsive-nav-link>

            @if(Auth::user()->hasRole('admin'))
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <div class="block px-4 py-2 text-xs text-indigo-600 font-semibold">
                        {{ __('Master Data (Admin)') }}
                    </div>
                    <x-responsive-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')">
                        {{ __('Roles') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.permissions.index')" :active="request()->routeIs('admin.permissions.*')">
                        {{ __('Permissions') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.master-cuti.index')" :active="request()->routeIs('admin.master-cuti.*')">
                        {{ __('Master Cuti') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.master-assets.index')" :active="request()->routeIs('admin.master-assets.*')">
                        {{ __('Master Asset') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.penilaian.index')" :active="request()->routeIs('admin.penilaian.*')">
                        {{ __('Penilaian Pegawai') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('Users') }}
                    </x-responsive-nav-link>
                </div>
                
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <div class="block px-4 py-2 text-xs text-indigo-600 font-semibold">
                        {{ __('Laporan (Admin)') }}
                    </div>
                    <x-responsive-nav-link :href="route('admin.laporan-reimbursements.index')" :active="request()->routeIs('admin.laporan-reimbursements.index')">
                        {{ __('Daftar Reimbursements') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.laporan-reimbursements.search')" :active="request()->routeIs('admin.laporan-reimbursements.search')">
                        {{ __('Generate Reimbursements') }}
                    </x-responsive-nav-link>
                </div>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>