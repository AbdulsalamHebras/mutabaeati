<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة التحكم - المحضر') }}
        </h2>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-100 text-emerald-700 rounded-lg border border-emerald-200 text-right">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 text-right">طلابي المكلف بهم</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($students as $student)
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                        </svg>
                                    </div>
                                    <span class="text-xs bg-blue-100 text-blue-700 font-bold px-2 py-1 rounded-md">طالب</span>
                                </div>
                                <h4 class="text-lg font-black text-slate-800 mb-4 text-right">{{ $student->name }}</h4>
                                
                                <a href="{{ route('muhdir.reports.create', $student->id) }}" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2 hover:bg-blue-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    رفع تقرير شهري
                                </a>
                            </div>
                        @empty
                            <div class="col-span-full py-20 text-center text-gray-400">
                                لا يوجد طلاب مكلفين بك حالياً
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
