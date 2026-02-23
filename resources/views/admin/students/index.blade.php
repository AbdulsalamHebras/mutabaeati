<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('إدارة الطلاب') }}
            </h2>
            <a href="{{ route('admin.students.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                إضافة طالب جديد
            </a>
        </div>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-100 text-emerald-700 rounded-lg border border-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="py-3 px-4 font-bold text-slate-700 font-bold">اسم الطالب</th>
                                <th class="py-3 px-4 font-bold text-slate-700 font-bold">المحضر المكلف</th>
                                <th class="py-3 px-4 font-bold text-slate-700 font-bold">تاريخ الإضافة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr class="border-b border-gray-50 hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $student->name }}</td>
                                    <td class="py-3 px-4">{{ $student->muhdir->name ?? 'غير محدد' }}</td>
                                    <td class="py-3 px-4">{{ $student->created_at->format('Y/m/d') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-10 text-center text-gray-400">لا يوجد طلاب مضافين بعد</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
