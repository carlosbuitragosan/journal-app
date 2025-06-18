<x-guest-layout>
  <div class="max-w-md mx-auto text-center mt-10">
    <div class="text-center mb-6">
      <h1
        class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 tracking-wide"
      >
        Reflekt
      </h1>
    </div>
    <h1
      class="text-xl font-semibold mb-4 text-gray-800 dark:text-white"
    >
      Email Verification
    </h1>

    <p class="mb-6 text-sm text-gray-600 dark:text-gray-400">
      Please verify your email address by clicking on the link we just
      emailed to you.
    </p>

    @if (session('status') == 'verification-link-sent')
      <div
        class="mb-6 text-sm font-medium text-green-600 dark:text-green-400"
      >
        A new verification link has been sent to your email address.
      </div>
    @endif

    <div class="mt-4">
      <form
        method="POST"
        action="{{ url('/email/verification-notification') }}"
      >
        @csrf
        <x-primary-button>
          {{ __('Resend Verification Email') }}
        </x-primary-button>
      </form>
    </div>

    <div class="mt-6 text-center">
      <form method="POST" action="{{ url('/logout') }}">
        @csrf
        <button
          type="submit"
          class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
        >
          {{ __('Log Out') }}
        </button>
      </form>
    </div>
  </div>
</x-guest-layout>
