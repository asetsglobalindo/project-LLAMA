<img style="width: 5rem; position: absolute;"
    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTWyp6cQjT-OYOaKx27adwCSvt57YmIfH58PQ&s" alt="">

<div x-data="performanceCalculation()"
    class="flex flex-col mt-8 md:mt-0 bg-white p-4 lg:p-5 shadow-md gap-2 lg:gap-5 w-full border-1 rounded-2xl">

    {{-- Title --}}
    <div>
        <h1 class="font-bold text-custom-primary text-center text-4xl mb-2">Performance Business Calculation</h1>
    </div>

    {{-- Form --}}
    <form id="form-performance" @submit.prevent="fetchData" class="flex flex-col lg:flex-row lg:justify-center gap-4">
        <!-- Month -->
        @php
            use Carbon\Carbon;
            $currentMonth = Carbon::now();
        @endphp

        <div class="lg:w-1/3">
            <label for="month" class="block">Period</label>
            <select name="month" id="month" x-model="month" class="border p-2 w-full border-gray-300 rounded-lg">
                @for ($i = 0; $i < 60; $i++) {{-- Last 5 years --}}
                                @php
                                    $formattedValue = $currentMonth->format('Y/n'); // Example: 2025/2
                                    $formattedText = $currentMonth->format('F Y'); // Example: February 2025
                                @endphp
                                <option value="{{ $formattedValue }}">{{ $formattedText }}</option>
                                @php
                                    $currentMonth->subMonth();
                                @endphp
                @endfor
            </select>
        </div>

        <!-- Tenant Type -->
        <div class="lg:w-1/3">
            <label for="size" class="block">Tenant Type</label>
            <select name="type" id="type" x-model="type" class="border p-2 w-full border-gray-300 rounded-lg">
                <option value="renewal" selected>Renewal</option>
                <option value="existing">Existing</option>
                <option value="new_tenant">New Tenant</option>
            </select>
        </div>

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

    {{-- Result --}}
    <div id="result" x-show="showResult" x-cloak>
        <h1 x-show="(type !== 'existing' || (rawResponse?.data?.revenue_total === 0))" class=" font-bold text-zinc-600
            text-center text-xl mb-2 p-12">We're still gathering data. Check back
            soon!
        </h1>

        <div x-show="type === 'existing' && rawResponse?.data?.revenue_total > 0">
            <div class="flex flex-col justify-around items-center lg:flex-row gap-9 py-12 lg:px-12">
                <div class="flex flex-col items-center justify-center text-center ">
                    <h1 x-text="'Rp ' + rawResponse?.data?.revenue_total_formatted"
                        class="bg-custom-primary text-white text-3xl font-bold rounded-full p-8"></h1>
                    <p class="text-custom-primary text-xl font-semibold pt-3 pb-5-custom">Total Revenue</p>
                </div>

                <div class="table-result">
                    <table class="table-fixed text-center border-separate border-spacing-x-4">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Precentage</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-start">Pertamina Retail</td>
                                <td>93%</td>
                                <td x-text="'Rp ' + rawResponse?.data?.revenue_pertare?.amount_formatted"
                                    class="text-end"></td>
                            </tr>
                            <tr>
                                <td class="text-start">PT. Global Indo Asets</td>
                                <td>7%</td>
                                <td x-text="'Rp ' + rawResponse?.data?.revenue_asets?.amount_formatted"
                                    class="text-end"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sales Area Section -->
            <div class="py-12 lg:px-12">
                <h1 class="font-bold text-custom-primary text-xl">Sales Area Section</h1>
                <div class="flex flex-col justify-around items-center lg:flex-row">
                    <div class="py-6" id="donut-chart"></div>
                    <div class="table-sa">
                        <table class="table-fixed text-center border-separate border-spacing-x-4">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Precentage</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="sales in rawResponse?.data?.sections?.sales_area" :key="sales . name">
                                    <tr class="border border-gray-300">
                                        <td class="text-start" x-text="sales.name"></td>
                                        <td x-text="sales.percentage + '%'"></td>
                                        <td x-text="formatIDR(sales.amount)">
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SPBU Section -->
            <div class="py-12 lg:px-12">
                <h1 class="font-bold text-custom-primary text-xl">SPBU Section</h1>
                <div class="flex flex-col justify-around items-center">
                    <div id="column-chart"></div>
                    <div class="table-sa">
                        <table class="table-fixed w-full text-center border-separate border-spacing-x-4">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Sales Area</th>
                                    <th>Precentage</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="spbu in rawResponse?.data?.sections?.spbu" :key="spbu . name">
                                    <tr class="border border-gray-300">
                                        <td class="text-start" x-text="spbu.name"></td>
                                        <td x-text="spbu.sales_area"></td>
                                        <td x-text="spbu.percentage + '%'"></td>
                                        <td x-text="formatIDR(spbu.amount)">
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tenant Section -->
            <div class="py-12 lg:px-12">
                <h1 class="font-bold text-custom-primary text-xl mb-2">Tenant Section</h1>
                <div class="table-sa">
                    <table class="table-fixed w-full text-center border-separate border-spacing-x-4">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Sales Area</th>
                                <th>SPBU</th>
                                <th>Precentage</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="tenant in rawResponse?.data?.sections?.tenant" :key="tenant . name">
                                <tr class="border border-gray-300">
                                    <td class="text-start" x-text="tenant.name"></td>
                                    <td x-text="tenant.sales_area"></td>
                                    <td class="text-start" x-text="tenant.spbu"></td>
                                    <td x-text="tenant.percentage + '%'"></td>
                                    <td x-text="formatIDR(tenant.amount)">
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>

<script>
    function performanceCalculation() {
        return {
            month: `${new Date().getFullYear()}/${new Date().getMonth() + 1}`,
            type: 'renewal',
            loading: false,
            showResult: false,
            rawResponse: '',

            formatIDR(amount) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 2
                }).format(amount);
            },

            async fetchData() {
                this.loading = true;
                this.showResult = false;

                // Construct API URL with query parameters
                const apiUrl = `https://api.asets.co.id/api/revenue/${this.month}`;

                try {
                    const response = await fetch(apiUrl, {
                        method: 'GET', // Change to 'POST' if required
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });

                    this.rawResponse = await response.json(); // Store the full response as an object

                    if (this.rawResponse.success) {
                        this.showResult = true;
                        this.renderChart(); // Call chart render after data is fetched successfully
                    } else {
                        this.rawResponse = { error: "API response unsuccessful" };
                    }
                } catch (error) {
                    this.rawResponse = 'Error fetching data';
                    console.error(error);
                }

                this.loading = false;
            },

            // Generate a random hex color
            generateRandomColor() {
                const letters = '0123456789ABCDEF';
                let color = '#';
                for (let i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            },

            // Pie Chart - Sales Area Section
            getChartOptions() {
                // Extract values from sales_area data
                const salesAreaAmounts = this.rawResponse.data.sections.sales_area.map(item => item.amount);
                const salesAreaNames = this.rawResponse.data.sections.sales_area.map(item => item.name);

                // Generate random colors for each item
                const randomColorsSalesArea = salesAreaAmounts.map(() => this.generateRandomColor());

                return {
                    series: salesAreaAmounts,
                    colors: randomColorsSalesArea,
                    chart: {
                        height: 320,
                        width: "100%",
                        type: "donut",
                    },
                    stroke: {
                        colors: ["transparent"],
                        lineCap: "",
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontFamily: "Poppin, sans-serif",
                                        offsetY: 20,
                                    },
                                    total: {
                                        showAlways: true,
                                        show: true,
                                        label: "Total",
                                        fontFamily: "Poppin, sans-serif",
                                        formatter: function (w) {
                                            const sum = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                            return 'Rp ' + sum;
                                        },
                                    },
                                    value: {
                                        show: true,
                                        fontFamily: "Poppin, sans-serif",
                                        offsetY: -20,
                                        formatter: function (value) {
                                            return "Rp " + value;
                                        },
                                    },
                                },
                                size: "80%",
                            },
                        },
                    },
                    grid: {
                        padding: {
                            top: -2,
                        },
                    },
                    labels: salesAreaNames,
                    dataLabels: {
                        enabled: false,
                    },
                    legend: {
                        position: "bottom",
                        fontFamily: "Poppin, sans-serif",
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                return "Rp " + value;
                            },
                        },
                    },
                    xaxis: {
                        labels: {
                            formatter: function (value) {
                                return "Rp " + value;
                            },
                        },
                        axisTicks: {
                            show: false,
                        },
                        axisBorder: {
                            show: false,
                        },
                    },
                };
            },

            // Column Chart - SPBU SECTION
            SPBUChart() {
                // Extract values from SPBU
                const SPBUAmounts = this.rawResponse.data.sections.spbu.map(item => item.amount);
                const SPBUNames = this.rawResponse.data.sections.spbu.map(item => item.name);

                // Generate random colors for each item
                const randomColorsSPBU = SPBUAmounts.map(() => this.generateRandomColor());

                // Dynamically set series based on extracted data
                const series = SPBUAmounts.map((amount, index) => ({
                    name: SPBUNames[index], // Dynamically set the name from SPBUNames
                    color: randomColorsSPBU[index], // Dynamically set the color from randomColorsSPBU
                    data: [
                        { x: "Revenue", y: amount }, // Dynamically set the y value from SPBUAmounts
                    ],
                }));

                return {
                    series: series,
                    chart: {
                        type: "bar",
                        height: "320px",
                        fontFamily: "Poppins, sans-serif",
                        toolbar: {
                            show: false,
                        },
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: "70%",
                            borderRadiusApplication: "end",
                            borderRadius: 8,
                        },
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        style: {
                            fontFamily: "Poppins, sans-serif",
                        },
                    },
                    states: {
                        hover: {
                            filter: {
                                type: "darken",
                                value: 1,
                            },
                        },
                    },
                    stroke: {
                        show: true,
                        width: 0,
                        colors: ["transparent"],
                    },
                    grid: {
                        show: false,
                        strokeDashArray: 4,
                        padding: {
                            left: 2,
                            right: 2,
                            top: -14,
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    legend: {
                        show: false,
                    },
                    xaxis: {
                        floating: false,
                        labels: {
                            show: true,
                            style: {
                                fontFamily: "Poppins, sans-serif",
                                cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400',
                            },
                        },
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false,
                        },
                    },
                    yaxis: {
                        show: false,
                    },
                    fill: {
                        opacity: 1,
                    },
                };
            },

            renderChart() {
                // Render Donut Chart
                if (document.getElementById("donut-chart") && typeof ApexCharts !== 'undefined') {
                    const chart = new ApexCharts(document.getElementById("donut-chart"), this.getChartOptions());
                    chart.render();
                }

                // Render Column Chart
                if (document.getElementById("column-chart") && typeof ApexCharts !== 'undefined') {
                    const chart = new ApexCharts(document.getElementById("column-chart"), this.SPBUChart());
                    chart.render();
                }
            },
        };
    }
</script>