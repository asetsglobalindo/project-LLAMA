<div x-data="formHandler()" class="flex flex-col bg-white p-4 lg:p-5 shadow-md gap-2 lg:gap-5 w-full border-1 rounded-2xl">
    <div>
        <h1 class="font-bold text-custom-primary">Asets Business Calculation</h1>
        <p>Optimize Your Investments In Perfect Location</p>
    </div>
    <form method="GET" action="{{ route('filter-space') }}" class="flex flex-col lg:flex-row lg:justify-between gap-4"
        @submit.prevent="validateForm">

        <!-- Min Amount -->
        <div>
            <label for="min_amount" class="block">Min Amount</label>
            <div class="flex items-center">
                <span class="bg-gray-200 text-gray-400 px-3 py-2 border border-gray-300 rounded-l-lg">Rp</span>
                <input type="text" name="min_amount" id="min_amount"
                    class="border border-gray-300 p-2 flex-1 rounded-r-lg" placeholder="e.g 210000"
                    value="{{ request('min_amount') }}" x-model="minAmount" @input="handleInput('minAmount', $event.target.value)">
            </div>
        </div>

        <!-- Max Amount -->
        <div>
            <label for="max_amount" class="block">Max Amount</label>
            <div class="flex items-center">
                <span class="bg-gray-200 text-gray-400 px-3 py-2 border border-gray-300 rounded-l-lg">Rp</span>
                <input type="text" name="max_amount" id="max_amount"
                    class="border border-gray-300 rounded-r-lg p-2 flex-1" placeholder="e.g 3000000"
                    value="{{ request('max_amount') }}" x-model="maxAmount" @input="handleInput('maxAmount', $event.target.value)">
            </div>
        </div>

        <!-- Area -->
        <div>
            <label for="area" class="block">Area</label>
            <input type="text" name="area" id="area" class="border p-2 w-full border-gray-300 rounded-lg"
                placeholder="eg. jakarta pusat" value="{{ request('area') }}">
        </div>

        <!-- Luas -->
        <div>
            <label for="size" class="block">Size (m<sup>2</sup>)</label>
            <select name="size" id="size" class="border p-2 w-full border-gray-300 rounded-lg flex-1">
                <option value="all_size" selected>All size</option>
                <option value="0-20" {{ request('size') == '0-20' ? 'selected' : '' }}>0 - 20</option>
                <option value="20-50" {{ request('size') == '20-50' ? 'selected' : '' }}>20 - 50</option>
                <option value="50-100" {{ request('size') == '50-100' ? 'selected' : '' }}>50 - 100</option>
                <option value="100-200" {{ request('size') == '100-200' ? 'selected' : '' }}>100 - 200</option>
                <option value="200+" {{ request('size') == '200+' ? 'selected' : '' }}>200+</option>
            </select>
        </div>

        {{-- <div>
            <label for="min_luas" class="block">Min Size</label>
            <input type="text" name="min_luas" id="min_luas" class="border p-2 rounded-lg border-gray-300 w-full"
                placeholder="... m2" value="{{ request('min_luas') }}">
        </div>

        <div>
            <label for="max_luas" class="block">Max Size</label>
            <input type="text" name="max_luas" id="max_luas" class="border p-2 rounded-lg border-gray-300 w-full"
                placeholder="... m2" value="{{ request('max_luas') }}">
        </div> --}}

        <!-- Submit Button -->
        <div class="flex text-center lg:items-end">
            <button type="submit" class="bg-custom-primary flex justify-center text-white px-4 py-2 rounded-lg w-full"
                :disabled="loading">
                <template x-if="!loading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M9.5 3A6.5 6.5 0 0 1 16 9.5c0 1.61-.59 3.09-1.56 4.23l.27.27h.79l5 5l-1.5 1.5l-5-5v-.79l-.27-.27A6.52 6.52 0 0 1 9.5 16A6.5 6.5 0 0 1 3 9.5A6.5 6.5 0 0 1 9.5 3m0 2C7 5 5 7 5 9.5S7 14 9.5 14S14 12 14 9.5S12 5 9.5 5" />
                    </svg>
                </template>
                <template x-if="loading">
                    <div class="animate-spin rounded-full h-7 w-7 border-t-2 border-white border-opacity-50"></div>
                </template>
            </button>
        </div>
    </form>
</div>

<script>
    function formHandler() {
        return {
            minAmount: '',
            maxAmount: '',
            loading: false,

            init() {
                // Parse the URL and extract query parameters
                const params = new URLSearchParams(window.location.search);
                this.minAmount = this.formatCurrency(params.get('min_amount') || ''); // Format if present
                this.maxAmount = this.formatCurrency(params.get('max_amount') || ''); // Format if present
            },

            formatCurrency(value) {
                if (!value) return '';
                // Remove any non-numeric characters (except dots for decimals)
                value = value.toString().replace(/[^0-9]/g, '');
                return new Intl.NumberFormat('id-ID').format(value); // Indonesian currency format
            },

            parseCurrency(value) {
                // Remove formatting to get numeric value
                return value.replace(/\./g, '');
            },

            validateForm() {
                // Ensure numeric comparison
                const min = parseFloat(this.parseCurrency(this.minAmount)) || 0;
                const max = parseFloat(this.parseCurrency(this.maxAmount)) || 0;

                if (min > max && max != 0) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops... Something went wrong!",
                        text: "Min amount value can't exceed Max. Please adjust!",
                        width: '50vw'
                    });
                    return false; // Stop submission
                }

                // Trigger loading
                this.loading = true;

                // Before submitting, update the form input fields to use raw numeric values
                const form = this.$el;

                // Set raw numeric values for min_amount and max_amount
                form.querySelector('input[name="min_amount"]').value = this.parseCurrency(this.minAmount);
                form.querySelector('input[name="max_amount"]').value = this.parseCurrency(this.maxAmount);

                // Submit the form
                setTimeout(() => {
                    form.submit();
                }, 1000);

            },

            handleInput(field, value) {
                // Update the value with formatted currency
                this[field] = this.formatCurrency(value);
            }
        };
    }
</script>

