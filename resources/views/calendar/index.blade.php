<x-app-layout>
  <div class="px-4 sm:p-6 text-gray-200">
    <div class="overflow-x-auto">
      <div
        id="calendar"
        class="bg-gray-900 text-white rounded shadow p-4 min-w-[700px]"
      ></div>

      <!-- Calendar Entry Modal -->
      <div
        id="calendarModal"
        x-data="calendarModal"
        @open-entry.window="open($event.detail)"
        x-show="showModal"
        x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
      >
        <div
          class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg w-full sm:max-w-lg space-y-4 max-h-[90vh] overflow-y-auto"
        >
          <!-- View Mode -->
          <template x-if="!isEditing && !confirmingDelete">
            <div>
              <h2
                x-text="entry.title || 'Untitled Entry'"
                class="text-lg font-bold mb-4 text-gray-800 dark:text-white"
              ></h2>
              <p
                x-text="entry.body"
                class="text-gray-600 dark:text-gray-300 whitespace-pre-line break-words mb-6"
              ></p>
              <div class="flex justify-between gap-2">
                <div class="flex gap-2">
                  <button
                    @click="isEditing = true"
                    class="text-sm text-blue-500 hover:underline"
                  >
                    Edit
                  </button>
                  <button
                    @click="confirmingDelete = true"
                    class="text-sm text-red-500 hover:underline"
                  >
                    Delete
                  </button>
                </div>
                <button
                  @click="close()"
                  class="text-sm text-gray-300 hover:underline"
                >
                  Close
                </button>
              </div>
            </div>
          </template>

          <!-- Edit Mode -->
          <template x-if="isEditing">
            <form
              :action="`{{ url('journal') }}/${entry.id}`"
              method="POST"
              class="space-y-4"
            >
              @csrf
              @method('PATCH')
              <div>
                <label class="block text-sm text-gray-200">
                  Title
                </label>
                <input
                  type="text"
                  name="title"
                  x-model="entry.title"
                  class="w-full mt-1 rounded-md bg-gray-900 text-white border-gray-600"
                />
              </div>
              <div>
                <label class="block text-sm text-gray-200">
                  Body
                </label>
                <textarea
                  name="body"
                  rows="4"
                  x-model="entry.body"
                  class="w-full mt-1 rounded-md bg-gray-900 text-white border-gray-600"
                ></textarea>
              </div>
              <div class="flex justify-between gap-2">
                <button
                  type="button"
                  @click="isEditing = false"
                  class="text-sm text-gray-400 hover:underline"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  class="text-indigo-600 hover:underline"
                >
                  Save Changes
                </button>
              </div>
            </form>
          </template>

          <!-- Delete Confirmation -->
          <template x-if="confirmingDelete">
            <form
              :action="`{{ url('journal') }}/${entry.id}`"
              method="POST"
              class="space-y-4"
            >
              @csrf
              @method('DELETE')
              <p class="text-gray-300">
                Are you sure you want to delete this entry?
              </p>
              <div class="flex justify-between">
                <button
                  type="button"
                  @click="confirmingDelete = false"
                  class="text-sm text-gray-400 hover:underline"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  class="text-sm text-red-600 hover:underline"
                >
                  Delete
                </button>
              </div>
            </form>
          </template>
        </div>
      </div>
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
        #calendar {
          min-width: 900px;
        }

        .fc-header-toolbar {
          display: flex;
          flex-direction: column;
          gap: 0.5rem;
          align-items: flex-start !important;
        }
      }

      .fc-event {
        cursor: pointer;
      }
    </style>
  @endpush

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
    <script>
      document.addEventListener('alpine:init', () => {
        Alpine.data('calendarModal', () => ({
          showModal: false,
          isEditing: false,
          confirmingDelete: false,
          entry: { id: null, title: '', body: '' },
          open(entry) {
            this.entry = entry;
            this.showModal = true;
          },
          close() {
            this.showModal = false;
            this.isEditing = false;
            this.confirmingDelete = false;
          },
        }));
      });

      document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          height: 'auto',
          events: @json(route('journal.entries.json')),
          eventClick: function (info) {
            fetch(
              `{{ url('/api/journal-entry/') }}/${info.event.id}`,
            )
              .then((res) => res.json())
              .then((entry) => {
                window.dispatchEvent(
                  new CustomEvent('open-entry', { detail: entry }),
                );
              })
              .catch((err) => {
                console.error('Error fetching journal entry:', err);
              });
          },
        });

        calendar.render();
      });
    </script>
  @endpush
</x-app-layout>
