<x-guest-layout>

    {{-- HEADER SECTION --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-20">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-5xl font-bold mb-4 tracking-wide">
                SNEC Talents’ Meet
            </h1>
            <p class="text-xl opacity-90">
                Explore Results • Events • Participants • Colleges
            </p>

            <div class="mt-8">
                <a href="/login"
                   class="px-8 py-3 bg-white text-blue-700 font-semibold rounded shadow hover:bg-gray-100">
                    Login
                </a>
            </div>
        </div>
    </div>

    {{-- STREAM RESULTS SECTION --}}
    <div class="max-w-6xl mx-auto py-16 px-6">
        <h2 class="text-3xl font-bold mb-6 text-center">View Results by Stream</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <a href="/results/sharia"
               class="p-6 bg-white rounded shadow hover:shadow-lg transition text-center border">
                <h3 class="text-xl font-bold mb-2">Sharia</h3>
                <p class="text-gray-600 text-sm">View Results</p>
            </a>

            <a href="/results/sharia_plus"
               class="p-6 bg-white rounded shadow hover:shadow-lg transition text-center border">
                <h3 class="text-xl font-bold mb-2">Sharia Plus</h3>
                <p class="text-gray-600 text-sm">View Results</p>
            </a>

            <a href="/results/she"
               class="p-6 bg-white rounded shadow hover:shadow-lg transition text-center border">
                <h3 class="text-xl font-bold mb-2">SHE</h3>
                <p class="text-gray-600 text-sm">View Results</p>
            </a>

            <a href="/results/she_plus"
               class="p-6 bg-white rounded shadow hover:shadow-lg transition text-center border">
                <h3 class="text-xl font-bold mb-2">SHE Plus</h3>
                <p class="text-gray-600 text-sm">View Results</p>
            </a>

            <a href="/results/life"
               class="p-6 bg-white rounded shadow hover:shadow-lg transition text-center border">
                <h3 class="text-xl font-bold mb-2">Life</h3>
                <p class="text-gray-600 text-sm">View Results</p>
            </a>

            <a href="/results/life_plus"
               class="p-6 bg-white rounded shadow hover:shadow-lg transition text-center border">
                <h3 class="text-xl font-bold mb-2">Life Plus</h3>
                <p class="text-gray-600 text-sm">View Results</p>
            </a>

            <a href="/results/bayyinath"
               class="p-6 bg-white rounded shadow hover:shadow-lg transition text-center border">
                <h3 class="text-xl font-bold mb-2">Bayyinath</h3>
                <p class="text-gray-600 text-sm">View Results</p>
            </a>

            <a href="/results/general"
               class="p-6 bg-white rounded shadow hover:shadow-lg transition text-center border">
                <h3 class="text-xl font-bold mb-2">General</h3>
                <p class="text-gray-600 text-sm">View Results</p>
            </a>

        </div>
    </div>

    {{-- ABOUT SECTION --}}
    <div class="bg-gray-50 py-16">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-4">About the Event</h2>
            <p class="text-gray-700 text-lg leading-relaxed">
                SNEC Talents’ Meet aims to bring together the brightest students 
                across multiple streams. This platform creates opportunities to showcase 
                academic excellence, creativity, and leadership in a competitive environment.
            </p>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="bg-gray-800 text-gray-300 py-6 text-center">
        <p class="text-sm">© {{ date('Y') }} SNEC Talents’ Meet. All Rights Reserved.</p>
    </footer>

</x-guest-layout>
