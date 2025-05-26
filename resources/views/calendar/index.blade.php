<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-white">
      Calendar
    </h2>
  </x-slot>

  <div class="px-4 sm:p-6 text-gray-200">
    <div class="overflow-x-auto">
      <div
        id="calendar"
        class="bg-gray-900 text-white rounded shadow p-4 min-w-[700px]"
      ></div>
    </div>
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
      @media (max-width: 640px) {
        .fc-header-toolbar {
          display: flex;
          flex-direction: column;
          gap: 0.5rem;
          align-items: flex-start !important;
        }
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
