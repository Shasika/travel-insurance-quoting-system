<div class="max-w-3xl mx-auto p-8 bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-lg mt-12">
    <h1 class="text-3xl font-bold text-indigo-700 mb-8 text-center">
        Get Your Travel Insurance Quote
    </h1>

    <form wire:submit.prevent="calculateQuote" class="space-y-8">
        @csrf
        <!-- Destination -->
        <div>
            <label for="destination" class="block text-lg font-medium text-gray-800 mb-2">Destination</label>
            <select
                wire:model="destination"
                id="destination"
                class="block w-full border-gray-300 rounded-lg shadow-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-base p-3"
            >
                <option value="">Select Your Destination</option>
                @foreach (\App\Utils\Constants::DESTINATIONS as $key => $price)
                    <option value="{{ $key }}">{{ $key }} (+${{ $price }})</option>
                @endforeach
            </select>
            @error('destination') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Travel Dates -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="startDate" class="block text-lg font-medium text-gray-800 mb-2">Start Date</label>
                <input
                    type="date"
                    wire:model.lazy="startDate"
                    id="startDate"
                    min="{{ now()->toDateString() }}"
                    class="block w-full border-gray-300 rounded-lg shadow-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-base p-3"
                />
                @error('startDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="endDate" class="block text-lg font-medium text-gray-800 mb-2">End Date</label>
                <input
                    type="date"
                    wire:model="endDate"
                    id="endDate"
                    :disabled="!$wire.startDate"
                    :min="$wire.startDate ? $wire.startDate : ''"
                    class="block w-full border-gray-300 rounded-lg shadow-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-base p-3"
                />
                @error('endDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Coverage Options -->
        <div>
            <span class="block text-lg font-medium text-gray-800 mb-2">Coverage Options</span>
            <div class="flex flex-wrap gap-4">
                <label class="flex items-center space-x-3">
                    <input
                        type="checkbox"
                        wire:model="coverageOptions"
                        value="Medical Expenses"
                        class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    />
                    <span class="text-gray-700">Medical Expenses (+$20)</span>
                </label>
                <label class="flex items-center space-x-3">
                    <input
                        type="checkbox"
                        wire:model="coverageOptions"
                        value="Trip Cancellation"
                        class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    />
                    <span class="text-gray-700">Trip Cancellation (+$30)</span>
                </label>
            </div>
            @error('coverageOptions') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Number of Travelers -->
        <div>
            <label for="numberOfTravelers" class="block text-lg font-medium text-gray-800 mb-2">Number of
                Travelers</label>
            <input
                type="number"
                wire:model="numberOfTravelers"
                id="numberOfTravelers"
                min="1"
                class="block w-full border-gray-300 rounded-lg shadow-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-base p-3"
            />
            @error('numberOfTravelers') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end space-x-4">
            <!-- Submit Button -->
            <button
                type="submit"
                class="px-6 py-3 bg-indigo-600 text-white font-semibold text-lg rounded-lg shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition"
            >
                {{ $quoteId ? 'Update Quote' : 'Calculate Quote' }}
            </button>

            <!-- Reset Button -->
            <button
                type="button"
                wire:click="resetForm"
                class="px-6 py-3 bg-gray-300 text-gray-800 font-semibold text-lg rounded-lg shadow-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition"
            >
                Reset
            </button>
        </div>
    </form>

    <!-- Quote Summary -->
    @if ($quotePrice > 0)
        <div class="mt-8 bg-green-100 p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold text-gray-800">Quote Summary</h2>
            <ul class="mt-4 space-y-2 text-gray-700">
                <li><strong>Destination:</strong> {{ $destination }}</li>
                <li><strong>Start Date:</strong> {{ $startDate }}</li>
                <li><strong>End Date:</strong> {{ $endDate }}</li>
                <li><strong>Coverage Options:</strong> {{ implode(', ', $coverageOptions) }}</li>
                <li><strong>Number of Travelers:</strong> {{ $numberOfTravelers }}</li>
            </ul>
            <p class="mt-6 text-2xl text-green-600 font-bold">Total Price: ${{ $quotePrice }}</p>
        </div>
    @endif
</div>
