<style>
    @import url('https://fonts.googleapis.com/css2?family=Lilita+One&display=swap');
    .font-display { font-family: 'Lilita One', cursive; letter-spacing: 1px; }
</style>

<nav class="w-full flex justify-center pt-8 pb-4 relative z-50">
    <div class="flex gap-4 items-center">
        
        <a href="{{ route('dashboard') }}" 
           class="bg-[#ffd6f4] text-white font-display text-2xl px-8 py-2.5 rounded-full shadow-md hover:scale-105 transition-transform duration-200 flex items-center justify-center">
            DashBoard
        </a>

        <div x-data="{ open: false }" class="relative">
            
            <button @click="open = ! open" 
                    class="bg-[#635bff] text-white font-display text-2xl px-8 py-2.5 rounded-full shadow-md hover:scale-105 transition-transform duration-200 flex items-center justify-center gap-2">
                {{ explode(' ', Auth::user()->name)[0] }}
                <svg class="w-5 h-5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <div x-show="open" 
                 @click.outside="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute left-1/2 -translate-x-1/2 mt-3 w-max bg-[#635bff] rounded-2xl shadow-xl overflow-hidden flex flex-col"
                 style="display: none;">
                
                <a href="{{ route('profile.edit') }}" 
                   class="px-8 py-2.5 text-white font-display text-xl text-center border-b border-white hover:bg-white/20 transition-colors w-full">
                    Profil
                </a>

                <form method="POST" action="{{ route('logout') }}" class="w-full m-0">
                    @csrf
                    <button type="submit" 
                            class="w-full px-8 py-2.5 text-white font-display text-xl text-center hover:bg-white/20 transition-colors cursor-pointer">
                        Logout
                    </button>
                </form>
            </div>
            
        </div>

    </div>
</nav>