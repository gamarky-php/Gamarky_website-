<div class="bg-white shadow rounded-lg p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ __('auth.verify_phone') }}</h3>
            <p class="text-sm text-gray-600 mt-1">
                @if(Auth::user()->phone_verified_at)
                    <span class="text-green-600 font-medium">✓ {{ __('auth.phone_verified') }}</span>
                    <span class="text-gray-500 block mt-1">{{ Auth::user()->phone }}</span>
                @else
                    <span class="text-amber-600 font-medium">⚠ {{ __('auth.phone_not_verified') }}</span>
                @endif
            </p>
        </div>
        
        @if(Auth::user()->phone_verified_at)
            <div class="bg-green-100 rounded-full p-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        @else
            <div class="bg-amber-100 rounded-full p-3">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        @endif
    </div>

    @if (session('status') == 'sms-sent')
        <div class="bg-green-100 text-green-800 p-3 rounded-lg mb-4 text-sm">
            ✓ {{ __('auth.sms_sent') }}
        </div>
    @endif

    @if (session('status') == 'phone-verified')
        <div class="bg-green-100 text-green-800 p-3 rounded-lg mb-4 text-sm">
            ✓ {{ __('auth.phone_verified_success') }}
        </div>
    @endif

    @if ($errors->has('phone') || $errors->has('code'))
        <div class="bg-red-100 text-red-800 p-3 rounded-lg mb-4 text-sm">
            {{ $errors->first('phone') ?: $errors->first('code') }}
        </div>
    @endif

    @if(!Auth::user()->phone_verified_at)
        <div class="space-y-4">
            {{-- Send verification code --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.phone_number') }}</label>
                <form method="POST" action="{{ route('phone.send') }}" class="flex gap-2">
                    @csrf
                    <input 
                        type="tel"
                        name="phone" 
                        value="{{ old('phone', Auth::user()->phone) }}"
                        required 
                        placeholder="+966501234567" 
                        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ltr">
                    <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition-colors">
                        {{ __('auth.send_code') }}
                    </button>
                </form>
                <p class="text-xs text-gray-500 mt-1">{{ __('auth.example_phone') }}</p>
            </div>

            {{-- Enter verification code --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.verification_code') }}</label>
                <form method="POST" action="{{ route('phone.verify.submit') }}" class="flex gap-2">
                    @csrf
                    <input 
                        type="text"
                        name="code" 
                        required 
                        maxlength="6"
                        placeholder="{{ __('auth.code_placeholder') }}" 
                        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 text-center text-lg tracking-widest ltr">
                    <button type="submit" class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium transition-colors">
                        {{ __('auth.verify_code') }}
                    </button>
                </form>
                <p class="text-xs text-gray-500 mt-1">{{ __('auth.code_valid_for') }}</p>
            </div>
        </div>
    @else
        <div class="bg-gray-50 rounded-lg p-4 text-center">
            <p class="text-sm text-gray-600">
                {{ __('auth.phone_verified_on') }}
                <span class="font-medium text-gray-900">{{ Auth::user()->phone_verified_at->format('Y-m-d H:i') }}</span>
            </p>
            
            {{-- Change number button (optional) --}}
            <form method="POST" action="{{ route('phone.send') }}" class="mt-3">
                @csrf
                <input type="hidden" name="phone" value="{{ Auth::user()->phone }}">
                <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    {{ __('auth.resend_code') }}
                </button>
            </form>
        </div>
    @endif
</div>
