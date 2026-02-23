<x-guest-layout>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-6" dir="rtl">
        <div class="w-full max-w-[500px] bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-10">
                <!-- Header -->
                <div class="text-center mb-10">
                    <h1 class="text-4xl font-black text-slate-800 mb-2">تسجيل الدخول</h1>
x                </div>

                <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ showPassword: false }">
                    @csrf

                    <!-- Email -->
                    <div class="space-y-2 text-right">
                        <label for="email" class="block text-slate-600 font-bold pr-1">البريد الإلكتروني</label>
                        <div class="relative group">
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full bg-blue-50/50 border border-blue-100 rounded-xl py-4 px-12 text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all text-right"
                                placeholder="ex@gmail.com">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-right" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-2 text-right">
                        <label for="password" class="block text-slate-600 font-bold pr-1">كلمة المرور</label>
                        <div class="relative group">
                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                                class="w-full bg-blue-50/50 border border-blue-100 rounded-xl py-4 px-12 text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all text-right"
                                placeholder="••••••••••••••">

                            <!-- Lock Icon -->
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>

                            <!-- Eye Icon -->
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 hover:text-blue-500 transition-colors focus:outline-none">
                                <svg x-show="!showPassword" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.225 0 2.39.217 3.475.613m1.312 3.53a3 3 0 11-4.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-right" />
                    </div>

                    <!-- Additional Row: Remember & Forget -->
                    <div class="flex items-center justify-between px-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group order-2">
                            <span class="mr-2 text-base text-slate-600 font-bold group-hover:text-slate-800 transition-colors">تذكرني</span>
                            <input id="remember_me" type="checkbox" name="remember" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500/20 transition-all">
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-base text-blue-600 font-bold hover:text-blue-700 transition-all order-1">
                                نسيت كلمة المرور؟
                            </a>
                        @endif
                    </div>

                    <!-- User Type -->
                    <div class="space-y-2 text-right">
                        <label class="block text-slate-600 font-bold pr-1">نوع المستخدم</label>
                        <div class="relative group">
                            <select name="role" required class="w-full bg-white border border-gray-200 rounded-xl py-4 px-12 text-slate-700 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all text-right">
                                <option value="" disabled selected>اختر نوع المستخدم</option>
                                <option value="muhdir">محضر</option>
                                <option value="muraqib">مراقب</option>
                            </select>

                            <!-- Users Icon -->
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>

                            <!-- Chevron Icon -->
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-2 text-right" />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/20 flex items-center justify-between px-6 transition-all duration-300 transform active:scale-[0.98]">
                        <div class="w-8 h-8 flex items-center justify-center bg-blue-500/30 rounded-lg">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 19l-7-7 7-7" />
                            </svg>
                        </div>
                        <span class="text-xl">تسجيل الدخول</span>
                        <div class="w-8"></div> <!-- Spacer to center title -->
                    </button>

                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
