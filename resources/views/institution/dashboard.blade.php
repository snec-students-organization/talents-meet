
<x-institution-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- WELCOME HEADER --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Institution Dashboard</h1>
            <p class="mt-2 text-slate-600">
                Welcome back, <span class="font-semibold text-indigo-600">{{ Auth::user()->name }}</span>! Manage your event registrations and participants from here.
            </p>
        </div>

        {{-- QUICK ACTIONS GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

            {{-- 1. BROWSE & REGISTER --}}
            <a href="{{ route('institution.events.index') }}" 
               class="group bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-indigo-300 transition-all">
                <div class="h-12 w-12 bg-indigo-50 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <span class="text-2xl">📅</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">Browse Events</h3>
                <p class="text-sm text-slate-500 mt-2">View all available events and register your students.</p>
            </a>

            {{-- 2. MY REGISTRATIONS --}}
            <a href="{{ route('institution.events.myRegistrations') }}" 
               class="group bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-emerald-300 transition-all">
                <div class="h-12 w-12 bg-emerald-50 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <span class="text-2xl">✅</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">My Registrations</h3>
                <p class="text-sm text-slate-500 mt-2">Check status of registered events and assigned students.</p>
            </a>

            {{-- 3. PARTICIPANTS --}}
            <a href="{{ route('institution.participants') }}" 
               class="group bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-amber-300 transition-all">
                <div class="h-12 w-12 bg-amber-50 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <span class="text-2xl">👨‍🎓</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 group-hover:text-amber-600 transition-colors">Participants</h3>
                <p class="text-sm text-slate-500 mt-2">Manage student details and download ID cards.</p>
            </a>
        </div>

        {{-- DOWNLOADS SECTION --}}
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-indigo-500/20 rounded-full blur-2xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">📥 Quick Downloads</h2>
                    <p class="text-slate-300 text-sm max-w-xl">
                        Download comprehensive reports and assets for your institution.
                    </p>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('institution.events.download') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg text-sm font-medium transition-all backdrop-blur-sm">
                        <span>📄</span> Event List PDF
                    </a>
                    
                    <a href="{{ route('institution.participants.downloadAll') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 border border-indigo-500/50 rounded-lg text-sm font-medium transition-all shadow-lg shadow-indigo-900/50">
                        <span>🪪</span> All ID Cards
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-institution-layout>
