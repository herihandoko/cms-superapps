<img
  x-data="{
    mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
    lightLogo: @js(asset('images/logo-superapps-light.png')),
    darkLogo: @js(asset('images/logo-superapps.png')),
  }"
  x-bind:src="mode === 'dark' ? darkLogo : lightLogo"
  @dark-mode-toggled.window="mode = $event.detail"
  alt="{{ config('app.name') }} Logo"
  class="filament-brand-logo w-auto object-contain h-11 dark:h-11"
/>

