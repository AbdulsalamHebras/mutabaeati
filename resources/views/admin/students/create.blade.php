<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" dir="rtl">
            {{ __('إضافة طالب جديد') }}
        </h2>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <form action="{{ route('admin.students.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="name" class="block text-slate-700 font-bold mb-2">اسم الطالب</label>
                            <input type="text" name="name" id="name" required class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label for="muhdir_id" class="block text-slate-700 font-bold mb-2">المحضر المكلف</label>
                            <select name="muhdir_id" id="muhdir_id" required class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm">
                                <option value="" disabled selected>اختر المحضر</option>
                                @foreach($muhdirs as $muhdir)
                                    <option value="{{ $muhdir->id }}">{{ $muhdir->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('muhdir_id')" class="mt-2" />
                        </div>

                        <div class="flex justify-start">
                            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition-colors">
                                حفظ الطالب
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
