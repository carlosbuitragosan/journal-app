<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-white">
      Calendar
    </h2>
  </x-slot>

  <div class="px-1 sm:px-4 md:px-6 lg:px-8 py-6 text-gray-200">
    <div
      id="calendar"
      class="bg-gray-900 text-white rounded shadow p-4 max-w-4xl mx-auto"
    ></div>
  </div>
  @push('styles')
    <link
      href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css"
      rel="stylesheet"
    />
    <style>
      .fc-col-header {
        background: #4f46e5;
      }
    </style>
  @endpush

  @push('scripts')
    <link
      href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          height: 'auto',
          events: @json(route('journal.entries.json')),
        });

        calendar.render();
      });
    </script>
  @endpush
</x-app-layout>
