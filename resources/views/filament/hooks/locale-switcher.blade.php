<div class="fi-locale-switcher flex items-center gap-1 rounded-full border border-primary-500/30 bg-gray-950/40 p-1">
    <a
        href="{{ route('locale.switch', 'ar') }}"
        class="rounded-full px-3 py-1 text-sm font-semibold transition
            {{ app()->getLocale() === 'ar'
                ? 'bg-primary-500 text-gray-950'
                : 'text-primary-300 hover:bg-primary-500/15' }}"
    >
        عربي
    </a>
    <a
        href="{{ route('locale.switch', 'en') }}"
        class="rounded-full px-3 py-1 text-sm font-semibold transition
            {{ app()->getLocale() === 'en'
                ? 'bg-primary-500 text-gray-950'
                : 'text-primary-300 hover:bg-primary-500/15' }}"
    >
        EN
    </a>
</div>
