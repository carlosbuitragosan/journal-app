<x-guest-layout>
  <div
    class="min-h-screen bg-gray-900 text-white flex items-center justify-center px-6"
  >
    <div class="max-w-xl text-center space-y-6">
      <h1 class="text-4xl font-extrabold">Welcome to Reflekt</h1>
      <p class="text-gray-400 text-lg">
        Your private space to capture thoughts, reflect, and grow.
        Simple. Secure. Beautiful.
      </p>
      <div class="flex justify-center gap-4">
        <a
          href="{{ url('/login') }}"
          class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 rounded text-white font-semibold"
        >
          Log in
        </a>
        <a
          href="{{ url('/register') }}"
          class="px-6 py-2 border border-indigo-600 hover:bg-indigo-600 hover:text-white rounded text-indigo-400 font-semibold"
        >
          Register
        </a>
      </div>
    </div>
  </div>
</x-guest-layout>
