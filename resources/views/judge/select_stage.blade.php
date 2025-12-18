@extends('layouts.judge')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden transform transition-all">
        
        {{-- CARD HEADER --}}
        <div class="judge-gradient p-8 text-center text-white relative overflow-hidden">
            <div class="relative z-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl mb-4 group-hover:scale-110 transition-transform">
                    <span class="text-3xl">📍</span>
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight">Select Stage</h1>
                <p class="text-indigo-100/80 text-sm font-medium mt-1 uppercase tracking-widest">Judge Assignment</p>
            </div>
            
            {{-- Abstract Ornaments --}}
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-indigo-900/20 rounded-full blur-2xl"></div>
        </div>

        {{-- FORM BODY --}}
        <div class="p-8">
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-xl text-sm font-bold flex items-center gap-3">
                    <span>⚠️</span>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('judge.set_stage') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="stage_number" class="block text-xs font-bold text-slate-400 uppercase tracking-[0.15em] mb-2 px-1">Stage Number</label>
                    <div class="relative group">
                        <select name="stage_number" 
                                id="stage_number"
                                class="w-full pl-4 pr-10 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl text-slate-800 font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all appearance-none cursor-pointer" 
                                required>
                            <option value="">Choose your stage...</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ session('judge_stage') == $i ? 'selected' : '' }}>Stage {{ $i }}</option>
                            @endfor
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full py-4 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-extrabold text-lg shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center gap-3 group">
                    Continue to Dashboard
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </form>
        </div>

        {{-- FOOTER INFO --}}
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 text-center">
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed">
                Assigning a stage allows you to<br>filter and score events for that location.
            </p>
        </div>
    </div>
</div>
@endsection
