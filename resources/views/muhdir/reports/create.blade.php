<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" dir="rtl">
            {{ __('رفع تقرير شهري') }}
        </h2>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <div class="mb-8 p-4 bg-blue-50 border border-blue-100 rounded-xl flex items-center gap-4">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm text-blue-600 font-bold">إعداد تقرير للطالب:</h4>
                            <p class="text-lg font-black text-blue-900">{{ $student->name }}</p>
                        </div>
                    </div>

                    <form action="{{ route('muhdir.reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                        
                        <div>
                            <label for="title" class="block text-slate-700 font-bold mb-2">عنوان التقرير</label>
                            <input type="text" name="title" id="title" required class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" placeholder="مثال: التقرير الشهري لشهر يناير">
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="month" class="block text-slate-700 font-bold mb-2">الشهر</label>
                                <select name="month" id="month" required class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm">
                                    @foreach(['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'] as $m)
                                        <option value="{{ $m }}">{{ $m }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('month')" class="mt-2" />
                            </div>
                            <div>
                                <label for="year" class="block text-slate-700 font-bold mb-2">السنة</label>
                                <select name="year" id="year" required class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm">
                                    @for($y = date('Y'); $y >= date('Y')-1; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                                <x-input-error :messages="$errors->get('year')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <label for="file" class="block text-slate-700 font-bold mb-2">ملف التقرير (PDF, Word)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>ارفع ملف من جهازك</span>
                                            <input id="file-upload" name="file" type="file" class="sr-only" required>
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF أو Word بحد أقصى 10 ميجا</p>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>

                        <div class="flex justify-start pt-4">
                            <button type="submit" class="bg-blue-600 text-white px-10 py-4 rounded-xl font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/20">
                                إرسال التقرير
                            </button>
                            <a href="{{ route('muhdir.dashboard') }}" class="mr-4 px-10 py-4 text-slate-500 font-bold hover:text-slate-700">تجاهل</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
