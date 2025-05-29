<div
  x-data="{
    searchQuery: '',
    openModal: null,
    editModalId: null,
    editForm: { title: '', body: '' },
  }"
>
  <x-app-layout>
    <x-slot name="header">
      <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2"
      >
        <input
          type="text"
          placeholder="Search your entries..."
          class="rounded-md border-gray-600 bg-gray-900 text-white shadow-sm text-sm px-3 py-1 w-full sm:w-64"
          x-model="searchQuery"
          aria-label="Search journal entries"
          data-testid="search-input"
        />
      </div>
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
            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900 text-white shadow-sm sm:text-sm"
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
      @forelse ($entries as $entry)
        @php
          $content = strtolower($entry->title . ' ' . $entry->body);
        @endphp

        <div
          x-show="
            ! searchQuery ||
              '{{ strtolower(($entry->title ?? '') . ' ' . ($entry->body ?? '')) }}'.includes(
                searchQuery.toLowerCase(),
              )
          "
          class="border border-gray-600 p-4 rounded relative bg-gray-900"
        >
          <h3 class="text-lg font-bold text-white">
            {{ $entry->title ?? '' }}
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
                window.addEventListener('resize', checkClamped)
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

          <!-- Delete Modal -->
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
              <div class="flex justify-end items-center gap-3">
                <button
                  type="button"
                  class="text-sm text-gray-400 hover:underline"
                  @click="openModal = null"
                >
                  Cancel
                </button>

                <div class="text-sm">
                  <form
                    method="POST"
                    action="{{ url('/journal/' . $entry->id) }}"
                    class="inline"
                  >
                    @csrf
                    @method('DELETE')
                    <button
                      type="submit"
                      class="text-red-500 hover:underline"
                    >
                      Delete
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Edit Modal -->
          <div
            x-show="editModalId === {{ $entry->id }}"
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
                  <label
                    class="block text-sm font-medium text-gray-200"
                  >
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
                  <label
                    class="block text-sm font-medium text-gray-200"
                  >
                    Body
                  </label>
                  <textarea
                    name="body"
                    x-model="editForm.body"
                    rows="4"
                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900 text-white sm:text-sm"
                  ></textarea>
                </div>
                <div class="flex justify-between gap-2">
                  <button
                    type="button"
                    @click="editModalId = null"
                    class="text-gray-400 hover:underline"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    class="text-indigo-500 hover:underline"
                  >
                    Save Changes
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      @empty
        <p class="text-gray-400" data-testid="empty-state">
          You have no journal entries yet.
        </p>
      @endforelse
    </div>
  </x-app-layout>
</div>
