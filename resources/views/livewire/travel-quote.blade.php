<div class="max-w-3xl mx-auto p-2 bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-lg mt-2">
    <h1 class="text-2xl font-bold text-indigo-700 mb-6 text-center">
        Get Your Travel Insurance Quote
    </h1>

    <form wire:submit.prevent="calculateQuote" class="space-y-6">
        @csrf
        <!-- Destination -->
        <div>
            <label for="destination" class="block text-base font-medium text-gray-800 mb-1">Destination</label>
            <select
                wire:model="destination"
                id="destination"
                class="block w-full border-gray-300 rounded-lg shadow-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
            >
                <option value="">Select Your Destination</option>
                @foreach (\App\Utils\Constants::DESTINATIONS as $key => $price)
                    <option value="{{ $key }}">{{ $key }} (+${{ $price }})</option>
                @endforeach
            </select>
            @error('destination') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Travel Dates -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="startDate" class="block text-base font-medium text-gray-800 mb-1">Start Date</label>
                <input
                    type="date"
                    wire:model.lazy="startDate"
                    id="startDate"
                    min="{{ now()->toDateString() }}"
                    class="block w-full border-gray-300 rounded-lg shadow-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
                />
                @error('startDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="endDate" class="block text-base font-medium text-gray-800 mb-1">End Date</label>
                <input
                    type="date"
                    wire:model="endDate"
                    id="endDate"
                    :disabled="!$wire.startDate"
                    :min="$wire.startDate ? $wire.startDate : ''"
                    class="block w-full border-gray-300 rounded-lg shadow-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
                />
                @error('endDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Coverage Options -->
        <div>
            <span class="block text-base font-medium text-gray-800 mb-1">Coverage Options</span>
            <div class="flex flex-wrap gap-2">
                <label class="flex items-center space-x-2">
                    <input
                        type="checkbox"
                        wire:model="coverageOptions"
                        value="Medical Expenses"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    />
                    <span class="text-gray-700 text-sm">Medical Expenses (+$20)</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input
                        type="checkbox"
                        wire:model="coverageOptions"
                        value="Trip Cancellation"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    />
                    <span class="text-gray-700 text-sm">Trip Cancellation (+$30)</span>
                </label>
            </div>
            @error('coverageOptions') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Number of Travelers -->
        <div>
            <label for="numberOfTravelers" class="block text-base font-medium text-gray-800 mb-1">Number of
                Travelers</label>
            <input
                type="number"
                wire:model="numberOfTravelers"
                id="numberOfTravelers"
                min="1"
                class="block w-full border-gray-300 rounded-lg shadow-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
            />
            @error('numberOfTravelers') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <!-- Submit Button -->
            <button
                type="submit"
                class="px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition"
            >
                {{ $quoteId ? 'Update Quote' : 'Calculate Quote' }}
            </button>

            <!-- Reset Button -->
            <button
                type="button"
                wire:click="resetForm"
                class="px-4 py-2 bg-gray-300 text-gray-800 font-semibold text-sm rounded-lg shadow-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition"
            >
                Reset
            </button>
        </div>
    </form>

    <!-- Quote Summary -->
    <!-- Quote Summary -->
    @if ($quotePrice > 0)
        <div class="mt-6 bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-lg border border-green-300">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <svg class="w-6 h-6 text-green-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Quote Summary
            </h2>
            <ul class="mt-4 space-y-2">
                <li class="flex justify-between items-center">
                    <span class="font-medium text-gray-700">Destination:</span>
                    <span class="text-gray-900 font-semibold">{{ $destination }}</span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="font-medium text-gray-700">Start Date:</span>
                    <span class="text-gray-900 font-semibold">{{ $startDate }}</span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="font-medium text-gray-700">End Date:</span>
                    <span class="text-gray-900 font-semibold">{{ $endDate }}</span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="font-medium text-gray-700">Coverage Options:</span>
                    <span class="text-gray-900 font-semibold">{{ implode(', ', $coverageOptions) }}</span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="font-medium text-gray-700">Number of Travelers:</span>
                    <span class="text-gray-900 font-semibold">{{ $numberOfTravelers }}</span>
                </li>
            </ul>
            <div class="mt-6 flex justify-between items-center bg-green-200 p-4 rounded-lg shadow-inner">
                <span class="text-xl font-bold text-gray-700">Total Price:</span>
                <span class="text-2xl text-green-700 font-extrabold">${{ $quotePrice }}</span>
            </div>
        </div>
    @endif

</div>
