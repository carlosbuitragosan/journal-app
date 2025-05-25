<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-white">
      Your Journal Entries
    </h2>
  </x-slot>

  <div class="p-6 text-gray-200 space-y-6">
    <!-- Entry Form -->
    <form
      method="POST"
      action="{{ url('/journal') }}"
      class="space-y-4 mb-6"
    >
      @csrf
      <div>
        <label
          for="title"
          class="block text-sm font-medium text-gray-200"
        >
          Title
        </label>
        <input
          type="text"
          name="title"
          id="title"
          class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        />
      </div>
      <div>
        <label
          for="body"
          class="block text-sm font-medium text-gray-200"
        >
          Entry
        </label>
        <textarea
          name="body"
          id="body"
          rows="5"
          class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900 text-white shadow-sm sm:text-sm"
          required
        ></textarea>
      </div>
      <div>
        <button
          type="submit"
          class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-md font-semibold text-white hover:bg-indigo-700"
        >
          Save Entry
        </button>
      </div>
    </form>

    <!-- Journal Entries -->
    <div
      x-data="{
        openModal: null,
        editModalId: null,
        editForm: { title: '', body: '' },
        init() {
          this.editModalId = null
        },
      }"
      class="space-y-6"
    >
      @forelse ($entries as $entry)
        <div
          class="border border-gray-600 p-4 rounded relative bg-gray-900"
        >
          <h3 class="text-lg font-bold text-white">
            {{ $entry->title }}
          </h3>
          <p class="text-sm text-gray-400">
            {{ $entry->created_at->diffForHumans() }}
          </p>
          <div
            x-data="{
              expanded: false,
              isClamped: false,
              checkClamped() {
                const el = this.$refs.entryText
                if (! el) return
                this.isClamped = el.scrollHeight > el.clientHeight
              },
            }"
            x-init="
              $nextTick(() => {
                checkClamped()
                window.addEventListener('resize', () => checkClamped())
              })
            "
            class="mt-2"
          >
            <p
              x-ref="entryText"
              :class="expanded ? 'text-gray-200 break-words' : 'text-gray-200 break-words line-clamp-4'"
              class="transition-all"
            >
              {{ $entry->body }}
            </p>
            <button
              x-show="isClamped"
              @click="expanded = !expanded"
              class="mt-2 text-sm text-indigo-400 hover:underline focus:outline-none"
            >
              <span x-show="!expanded">Show more</span>
              <span x-show="expanded">Show less</span>
            </button>
          </div>

          <!-- Actions -->
          <div class="mt-4 flex gap-4">
            <button
              @click="openModal = {{ $entry->id }}"
              class="text-sm text-red-500 hover:underline"
            >
              Delete
            </button>
            <button
              @click="
                editModalId = {{ $entry->id }};
                editForm.title = '{{ addslashes($entry->title) }}';
                editForm.body = '{{ addslashes($entry->body) }}'
              "
              class="text-sm text-blue-400 hover:underline"
            >
              Edit
            </button>
          </div>

          <!-- Confirmation Modal -->
          <div
            x-show="openModal === {{ $entry->id }}"
            x-transition
            x-cloak
            class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50"
          >
            <div
              class="bg-white dark:bg-gray-800 p-6 rounded-md shadow-xl w-full max-w-md"
            >
              <h2
                class="text-lg font-semibold text-gray-800 dark:text-white mb-3"
              >
                Delete entry?
              </h2>
              <p
                class="text-sm text-gray-600 dark:text-gray-300 mb-6"
              >
                Are you sure you want to delete this journal entry?
                This action can't be undone.
              </p>
              <div class="flex justify-end space-x-3">
                <button
                  type="button"
                  class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded hover:bg-gray-300 dark:hover:bg-gray-600"
                  @click="openModal = null"
                >
                  Cancel
                </button>
                <form
                  method="POST"
                  action="{{ url('/journal/' . $entry->id) }}"
                >
                  @csrf
                  @method('DELETE')
                  <button
                    type="submit"
                    class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700"
                  >
                    Delete
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      @empty
        <p class="text-gray-400">You have no journal entries yet.</p>
      @endforelse

      <!-- Edit Entry Modal -->
      <div
        x-show="editModalId"
        x-transition
        x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
      >
        <div
          class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg max-w-lg w-full"
        >
          <h2
            class="text-lg font-bold mb-4 text-gray-800 dark:text-white"
          >
            Edit Entry
          </h2>
          <form
            :action="`{{ url('/journal') }}/${editModalId}`"
            method="POST"
            class="space-y-4"
          >
            @csrf
            @method('PATCH')
            <div>
              <label class="block text-sm font-medium text-gray-200">
                Title
              </label>
              <input
                type="text"
                name="title"
                x-model="editForm.title"
                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900 text-white sm:text-sm"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-200">
                Body
              </label>
              <textarea
                name="body"
                x-model="editForm.body"
                rows="4"
                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900 text-white sm:text-sm"
              ></textarea>
            </div>
            <div class="flex justify-end gap-2">
              <button
                type="button"
                @click="editModalId = null"
                class="px-3 py-1 bg-gray-300 hover:bg-gray-400 rounded text-gray-800"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 rounded text-white"
              >
                Save Changes
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
