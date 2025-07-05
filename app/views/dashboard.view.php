<?php include 'partials/header.view.php' ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">
            Booking Analytics Dashboard
        </h1>
        
        <div x-data="analyticsApp">
            <!-- Controls -->
            <div class="mb-6 flex justify-center space-x-4">
                <select x-model="selectedPeriod" @change="loadAllCharts()" 
                        class="px-4 py-2 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600">
                    <option value="7">Last 7 days</option>
                    <option value="30">Last 30 days</option>
                    <option value="90">Last 90 days</option>
                </select>
                
                <button @click="loadAllCharts()" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Refresh Data
                </button>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Bookings Over Time -->
                <div id="getcl" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        Bookings Over Time
                    </h2>
                    <div class="relative h-64">
                        <canvas id="bookingsTimeChart"></canvas>
                    </div>
                </div>

                <!-- Bookings by Room -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        Bookings by Room
                    </h2>
                    <div class="relative h-64">
                        <canvas id="bookingsRoomChart"></canvas>
                    </div>
                </div>

                <!-- Top Users -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        Top 10 Users
                    </h2>
                    <div class="relative h-64">
                        <canvas id="topUsersChart"></canvas>
                    </div>
                </div>

                <!-- Bookings by Day of Week -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        Bookings by Day of Week
                    </h2>
                    <div class="relative h-64">
                        <canvas id="dayOfWeekChart"></canvas>
                    </div>
                </div>

                <!-- Room Occupancy Rate -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg col-span-1 lg:col-span-2">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        Room Occupancy Rate (%)
                    </h2>
                    <div class="relative h-64">
                        <canvas id="occupancyChart"></canvas>
                    </div>
                </div>

            </div>

            <!-- Loading State -->
            <div x-show="loading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-2 text-gray-900 dark:text-white">Loading analytics...</p>
                </div>
            </div>
        </div>
    </div>

    <script>

document.addEventListener('alpine:init', () => {
    Alpine.data('analyticsApp', () => ({
        selectedPeriod: '30',
        loading: false,
        charts: {},

        async init() {
            // Wait for Chart.js to be available
            await this.waitForChart();
            await this.loadAllCharts();
        },

        async waitForChart() {
            // Wait for Chart to be available
            while (typeof Chart === 'undefined') {
                await new Promise(resolve => setTimeout(resolve, 100));
            }
        },

        async loadAllCharts() {
            this.loading = true;
            try {
                // Check if Chart is available
                if (typeof Chart === 'undefined') {
                    throw new Error('Chart.js is not loaded');
                }

                await Promise.all([
                    this.loadBookingsOverTime(),
                    this.loadBookingsByRoom(),
                    this.loadTopUsers(),
                    this.loadBookingsByDay(),
                    this.loadOccupancyRate()
                ]);
            } catch (error) {
                console.error('Error loading charts:', error);
            } finally {
                this.loading = false;
            }
        },

        async loadBookingsOverTime() {
            try {
                console.log('Dark mode:', document.documentElement.classList.contains('dark'));
                console.log(document.getElementById("getcl").classList);
                const response = await fetch(`analytics/getBookingsByDateRange/${this.selectedPeriod}`);
                const data = await response.json();
                
                const ctx = document.getElementById('bookingsTimeChart').getContext('2d');
                
                if (this.charts.bookingsTime) {
                    this.charts.bookingsTime.destroy();
                }

                this.charts.bookingsTime = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: Object.keys(data),
                        datasets: [{
                            label: 'Daily Bookings',
                            data: Object.values(data),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: {
                                color: document.documentElement.classList.contains('dark') ? '#EEF1F4' : '#391C41'
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                color: document.documentElement.classList.contains('dark') ? '#EEF1F4' : '#391C41'
                                },
                                grid: {
                                color: document.documentElement.classList.contains('dark') ?  '#e5e7eb' : '#374151'
                                }
                            },
                            x: {
                                ticks: {
                                color: document.documentElement.classList.contains('dark') ? '#EEF1F4' : '#391C41'
                                },
                                grid: {
                                color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error loading bookings over time:', error);
            }
        },

                async loadBookingsByRoom() {
                    try {
                        const response = await fetch(`analytics/getBookingsByRoom/${this.selectedPeriod}`);
                        const data = await response.json();
                        
                        const ctx = document.getElementById('bookingsRoomChart').getContext('2d');
                        
                        if (this.charts.bookingsRoom) {
                            this.charts.bookingsRoom.destroy();
                        }

                        this.charts.bookingsRoom = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: Object.keys(data),
                                datasets: [{
                                    data: Object.values(data),
                                    backgroundColor: [
                                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                                        '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                                    ]
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                        color: document.documentElement.classList.contains('dark') ? '#EEF1F4' : '#391C41'
                                        }
                                    }
                                }
                            }
                        });
                    } catch (error) {
                        console.error('Error loading bookings by room:', error);
                    }
                },

                async loadTopUsers() {
                    try {
                        const response = await fetch(`analytics/getBookingsByUser/${this.selectedPeriod}`);
                        const data = await response.json();
                        
                        const ctx = document.getElementById('topUsersChart').getContext('2d');
                        
                        if (this.charts.topUsers) {
                            this.charts.topUsers.destroy();
                        }

                        this.charts.topUsers = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: Object.keys(data),
                                datasets: [{
                                    label: 'Number of Bookings',
                                    data: Object.values(data),
                                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                                    borderColor: 'rgb(34, 197, 94)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                indexAxis: 'y',
                                plugins: {
                                    legend: {
                                        labels: {
                                        color: document.documentElement.classList.contains('dark') ?  '#EEF1F4' : '#391C41'
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                        color: document.documentElement.classList.contains('dark') ? '#EEF1F4' : '#391C41'
                                        },
                                        grid: {
                                        color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                                        }
                                    },
                                    x: {
                                        ticks: {
                                        color: document.documentElement.classList.contains('dark') ? '#EEF1F4' : '#391C41'
                                        },
                                        grid: {
                                        color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                                        }
                                    }
                                }
                            }
                        });
                    } catch (error) {
                        console.error('Error loading top users:', error);
                    }
                },

                async loadBookingsByDay() {
                    try {
                        const response = await fetch(`analytics/getBookingsByDayOfWeek/${this.selectedPeriod}`);
                        const data = await response.json();
                        
                        const ctx = document.getElementById('dayOfWeekChart').getContext('2d');
                        
                        if (this.charts.dayOfWeek) {
                            this.charts.dayOfWeek.destroy();
                        }

                        this.charts.dayOfWeek = new Chart(ctx, {
                            type: 'radar',
                            data: {
                                labels: Object.keys(data),
                                datasets: [{
                                    label: 'Bookings by Day',
                                    data: Object.values(data),
                                    fill: true,
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    borderColor: 'rgb(255, 99, 132)',
                                    pointBackgroundColor: 'rgb(255, 99, 132)',
                                    pointBorderColor: '#fff',
                                    pointHoverBackgroundColor: '#fff',
                                    pointHoverBorderColor: 'rgb(255, 99, 132)'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        labels: {
                                        color: document.documentElement.classList.contains('dark') ? '#EEF1F4' : '#391C41'
                                        }
                                    }
                                },
                                scales: {
                                    r: {
                                        angleLines: {
                                        color: document.documentElement.classList.contains('dark') ?  '#e5e7eb' : '#374151'
                                        },
                                        grid: {
                                        color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                                        },
                                        pointLabels: {
                                        color: document.documentElement.classList.contains('dark') ? '#EEF1F4' : '#391C41'
                                        },
                                        ticks: {
                                        color: document.documentElement.classList.contains('dark') ? '#EEF1F4' : '#391C41'
                                        }
                                    }
                                }
                            }
                        });
                    } catch (error) {
                        console.error('Error loading bookings by day:', error);
                    }
                },

                async loadOccupancyRate() {
                    try {
                        const response = await fetch(`analytics/getOccupancyRate/${this.selectedPeriod}`);
                        const data = await response.json();
                        
                        const ctx = document.getElementById('occupancyChart').getContext('2d');
                        
                        if (this.charts.occupancy) {
                            this.charts.occupancy.destroy();
                        }

                        this.charts.occupancy = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: Object.keys(data),
                                datasets: [{
                                    label: 'Occupancy Rate (%)',
                                    data: Object.values(data),
                                    backgroundColor: Object.values(data).map(value => 
                                        value >= 80 ? 'rgba(239, 68, 68, 0.8)' :
                                        value >= 60 ? 'rgba(245, 158, 11, 0.8)' :
                                        'rgba(34, 197, 94, 0.8)'
                                    ),
                                    borderColor: Object.values(data).map(value => 
                                        value >= 80 ? 'rgb(239, 68, 68)' :
                                        value >= 60 ? 'rgb(245, 158, 11)' :
                                        'rgb(34, 197, 94)'
                                    ),
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        labels: {
                                        color: document.documentElement.classList.contains('dark') ? '#EEF1F4' : '#391C41'
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100,
                                        ticks: {
                                        color: document.documentElement.classList.contains('dark') ? '#EEF1F4' : '#391C41',
                                            callback: function(value) {
                                                return value + '%';
                                            }
                                        },
                                        grid: {
                                        color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                                        }
                                    },
                                    x: {
                                        ticks: {
                                        color: document.documentElement.classList.contains('dark') ? '#EEF1F4' : '#391C41'
                                        },
                                        grid: {
                                        color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                                        }
                                    }
                                }
                            }
                        });
                    } catch (error) {
                        console.error('Error loading occupancy rate:', error);
                    }
                
                }
            
            }))
        
    
});

    </script>

<?php include 'partials/footer.view.php' ?>
