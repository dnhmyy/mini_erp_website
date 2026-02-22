<x-app-layout>
    <x-slot name="header">
        Master Cabang
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Daftar Cabang</h3>
            <a href="{{ route('cabang.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-secondary focus:bg-brand-secondary active:bg-brand-secondary focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Cabang
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-6">
            <form action="{{ route('cabang.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <div class="flex-1">
                    <label for="search" class="sr-only">Cari Cabang</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           oninput="this.form.dispatchEvent(new Event('submit'))"
                           placeholder="Cari berdasarkan nama atau alamat..." 
                           class="block w-full rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                </div>
                
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Cari
                </button>
                @if(request('search'))
                <a href="{{ route('cabang.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 transition ease-in-out duration-150">
                    Reset
                </a>
                @endif
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden" id="main-table-wrapper">
            @include('cabang.partials.table')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.querySelector('main form');
            const tableWrapper = document.getElementById('main-table-wrapper');

            if (filterForm && tableWrapper) {
                filterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    updateTable();
                });

                // Handle pagination clicks
                tableWrapper.addEventListener('click', function(e) {
                    const link = e.target.closest('a');
                    if (link && link.href && link.closest('.pagination, nav')) {
                        e.preventDefault();
                        updateTable(link.href);
                    }
                });

                function updateTable(url = null) {
                    const formData = new FormData(filterForm);
                    const params = new URLSearchParams(formData);
                    const fetchUrl = url || `${filterForm.action}?${params.toString()}`;

                    tableWrapper.classList.add('opacity-50', 'pointer-events-none', 'transition-opacity');

                    fetch(fetchUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        tableWrapper.innerHTML = html;
                        tableWrapper.classList.remove('opacity-50', 'pointer-events-none');
                        window.history.pushState({}, '', fetchUrl.replace(/([&?])ajax=1/, ''));
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        tableWrapper.classList.remove('opacity-50', 'pointer-events-none');
                    });
                }
            }
        });
    </script>
</x-app-layout>
