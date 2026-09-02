{{-- ============================================================
     WBI Asset Management — Unified User Profile & Password Modal
     Design System Compliant (DESIGN.md)
     ============================================================ --}}

@auth
@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
@endphp
<div id="wbi-profile-modal" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 opacity-0 pointer-events-none transition-all duration-300 ease-out"
     role="dialog"
     aria-modal="true"
     aria-labelledby="profile-modal-title">

    <!-- Backdrop Overlay -->
    <div class="fixed inset-0 bg-black/40 backdrop-blur-xs transition-opacity duration-300" onclick="closeProfileModal()"></div>

    <!-- Modal Content Card -->
    <div class="relative w-full max-w-lg bg-surface-white rounded-xl border border-border-light shadow-2xl overflow-hidden transform scale-95 transition-all duration-300 z-10 my-auto"
         id="wbi-profile-modal-card">
        
        <!-- Header with Corporate Deep Teal Accent Bar -->
        <div class="h-1.5 bg-primary w-full"></div>

        <div class="p-6 sm:p-7">
            <!-- Modal Top Bar -->
            <div class="flex items-center justify-between pb-4 border-b border-border-light">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow-xs shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 id="profile-modal-title" class="font-display text-base sm:text-lg font-bold text-on-surface leading-tight">
                            Profil Akun Saya
                        </h3>
                        <p class="text-xs text-on-surface-variant leading-none mt-0.5">
                            Informasi identitas &amp; keamanan akun
                        </p>
                    </div>
                </div>

                <!-- Close Button -->
                <button type="button" 
                        onclick="closeProfileModal()" 
                        class="p-1.5 rounded-lg text-on-surface-variant/50 hover:text-on-surface-variant hover:bg-surface-container transition-colors cursor-pointer"
                        aria-label="Tutup Modal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Profile Info Grid (Nama, Role, Departemen, Email) -->
            <div class="mt-5 p-4 rounded-lg bg-surface-container/70 border border-border-light space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    
                    <!-- Nama Lengkap -->
                    <div>
                        <span class="text-[11px] font-medium text-on-surface-variant block uppercase tracking-wider">Nama Lengkap</span>
                        <p class="text-sm font-semibold text-on-surface mt-0.5">{{ auth()->user()->name }}</p>
                    </div>

                    <!-- Role / Hak Akses -->
                    <div>
                        <span class="text-[11px] font-medium text-on-surface-variant block uppercase tracking-wider">Hak Akses / Role</span>
                        <div class="mt-0.5">
                            @php
                                $role = auth()->user()->role;
                                $badgeClasses = match($role) {
                                    'admin' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                    'finance' => 'bg-amber-100 text-amber-800 border-amber-300',
                                    'inventory' => 'bg-teal-100 text-teal-800 border-teal-300',
                                    default => 'bg-slate-100 text-slate-700 border-slate-300',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClasses }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ auth()->user()->role_label }}
                            </span>
                        </div>
                    </div>

                    <!-- Departemen -->
                    <div>
                        <span class="text-[11px] font-medium text-on-surface-variant block uppercase tracking-wider">Departemen / Unit</span>
                        <p class="text-sm font-semibold text-on-surface mt-0.5">
                            @if(auth()->user()->department)
                                {{ auth()->user()->department->name }} 
                                <span class="text-xs font-mono text-on-surface-variant">({{ auth()->user()->department->code }})</span>
                            @else
                                <span class="text-on-surface-variant italic">Seluruh Kampus / Global</span>
                            @endif
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <span class="text-[11px] font-medium text-on-surface-variant block uppercase tracking-wider">Email Terdaftar</span>
                        <p class="text-sm font-semibold text-on-surface mt-0.5 font-mono text-xs">{{ auth()->user()->email }}</p>
                    </div>

                </div>
            </div>

            <!-- Form Ganti Password -->
            <div class="mt-6">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <h4 class="font-display text-sm font-bold text-on-surface">Ganti Password</h4>
                </div>

                <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-4" id="formChangePassword">
                    @csrf
                    @method('PUT')

                    <!-- Password Saat Ini -->
                    <div>
                        <label for="current_password" class="block text-xs font-medium text-on-surface mb-1">
                            Password Saat Ini <span class="text-danger">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   required 
                                   autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="w-full px-3.5 py-2 pr-10 text-sm rounded-md border {{ $errors->has('current_password') ? 'border-danger focus:ring-danger/20' : 'border-outline-variant focus:border-primary-light focus:ring-primary-light/20' }} bg-surface-white text-on-surface focus:outline-none focus:ring-2 transition-colors">
                            <button type="button" 
                                    onclick="togglePasswordField('current_password', this)" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-on-surface-variant/50 hover:text-on-surface-variant transition-colors cursor-pointer"
                                    tabindex="-1">
                                <svg class="w-3.5 h-3.5 eye-icon-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg class="w-3.5 h-3.5 eye-icon-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label for="new_password" class="block text-xs font-medium text-on-surface mb-1">
                            Password Baru <span class="text-danger">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password" 
                                   id="new_password" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Minimal 6 karakter"
                                   class="w-full px-3.5 py-2 pr-10 text-sm rounded-md border {{ $errors->has('password') ? 'border-danger focus:ring-danger/20' : 'border-outline-variant focus:border-primary-light focus:ring-primary-light/20' }} bg-surface-white text-on-surface focus:outline-none focus:ring-2 transition-colors">
                            <button type="button" 
                                    onclick="togglePasswordField('new_password', this)" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-on-surface-variant/50 hover:text-on-surface-variant transition-colors cursor-pointer"
                                    tabindex="-1">
                                <svg class="w-3.5 h-3.5 eye-icon-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg class="w-3.5 h-3.5 eye-icon-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-medium text-on-surface mb-1">
                            Ulangi Password Baru <span class="text-danger">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Ketik ulang password baru"
                                   class="w-full px-3.5 py-2 pr-10 text-sm rounded-md border border-outline-variant bg-surface-white text-on-surface focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors">
                            <button type="button" 
                                    onclick="togglePasswordField('password_confirmation', this)" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-on-surface-variant/50 hover:text-on-surface-variant transition-colors cursor-pointer"
                                    tabindex="-1">
                                <svg class="w-3.5 h-3.5 eye-icon-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg class="w-3.5 h-3.5 eye-icon-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-3 flex items-center justify-end gap-2.5 border-t border-border-light mt-5">
                        <button type="button" 
                                onclick="closeProfileModal()" 
                                class="px-4 py-2 text-xs font-semibold text-on-surface-variant/50 hover:text-on-surface-variant bg-surface-container hover:bg-surface-container-high rounded-md transition-colors cursor-pointer">
                            Tutup
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-xs font-semibold text-white bg-primary hover:bg-primary-light rounded-md shadow-xs transition-colors cursor-pointer inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Password Baru</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openProfileModal() {
        const modal = document.getElementById('wbi-profile-modal');
        const card = document.getElementById('wbi-profile-modal-card');
        if (!modal || !card) return;

        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
        document.body.style.overflow = 'hidden';
    }

    function closeProfileModal() {
        const modal = document.getElementById('wbi-profile-modal');
        const card = document.getElementById('wbi-profile-modal-card');
        if (!modal || !card) return;

        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0', 'pointer-events-none');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
        document.body.style.overflow = '';
    }

    function togglePasswordField(fieldId, btn) {
        const field = document.getElementById(fieldId);
        if (!field || !btn) return;

        const openIcon = btn.querySelector('.eye-icon-open');
        const closedIcon = btn.querySelector('.eye-icon-closed');

        if (field.type === 'password') {
            field.type = 'text';
            if (openIcon) openIcon.classList.add('hidden');
            if (closedIcon) closedIcon.classList.remove('hidden');
        } else {
            field.type = 'password';
            if (openIcon) openIcon.classList.remove('hidden');
            if (closedIcon) closedIcon.classList.add('hidden');
        }
    }

    // Auto-open modal on page load if there are validation errors for password change
    @if($errors->has('current_password') || $errors->has('password'))
        document.addEventListener('DOMContentLoaded', function() {
            openProfileModal();
        });
    @endif

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeProfileModal();
        }
    });
</script>
@endauth
