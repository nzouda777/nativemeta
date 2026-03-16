<x-filament-panels::page>
    <form wire:submit="updateFilters">
        {{ $this->form }}
    </form>

    <div class="space-y-6">
        <!-- Cartes de statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Carte Visites -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 6 0 3 3 0 01-6 0M2.458 12C3.732 7.943 7.523 5 7.523 5a3 3 0 013.005 1.923 3.005 3.005 0 011.994 2.181 3.005 3.005 0 01-3.997 2.181L9 19m3 0V5a3 3 0 013-3h3.997"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Visites totales</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $this->getStatistics()['visits']['total'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Carte Visiteurs uniques -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 016-6h2a6 6 0 016 6v1m0 0V3a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2h8a2 2 0 002-2V3a2 2 0 00-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Visiteurs uniques</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $this->getStatistics()['visits']['unique_visitors'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Carte Commandes -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1.5 1.5M5 15h14l8.5 1.5"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Commandes totales</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $this->getStatistics()['orders']['total'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Carte Revenus -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-2 3-2zm0 8c1.657 0 3-.895 3-2s-1.343-2-3-2-3 .895-3 2-2zm9-6a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Revenus totaux</p>
                        <p class="text-2xl font-semibold text-gray-900">€{{ number_format($this->getStatistics()['orders']['revenue'], 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cartes secondaires -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Taux de conversion -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-indigo-100 rounded-full">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002 2zm-6 0a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002 2v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002 2v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Taux de conversion</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $this->getStatistics()['orders']['conversion_rate'] }}%</p>
                    </div>
                </div>
            </div>

            <!-- Panier moyen -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-full">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M3 7h18m-9 4h10m-10 4h14"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Panier moyen</p>
                        <p class="text-2xl font-semibold text-gray-900">€{{ number_format($this->getStatistics()['orders']['avg_value'], 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Visites quotidiennes -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-teal-100 rounded-full">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3v4m6 3v-4m-6-4v4m-6 3v-4m6 3v-4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Visites/jour</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $this->getStatistics()['visits']['avg_daily'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Graphique des commandes -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Évolution des commandes</h3>
                <div class="h-64">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>

            <!-- Graphique des revenus -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Évolution des revenus</h3>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tableaux détaillés -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top formations -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Top formations</h3>
                </div>
                <div class="overflow-hidden">
                    <div class="max-h-96 overflow-y-auto">
                        @forelse($this->getTopCourses() as $course)
                            <div class="px-6 py-4 border-b border-gray-100 hover:bg-gray-50">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $course['title'] }}</p>
                                        <p class="text-sm text-gray-500">{{ $course['sales'] }} ventes</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-gray-900">€{{ number_format($course['revenue'], 2) }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-8 text-center text-gray-500">
                                    Aucune vente sur cette période
                                </div>
                            @endforelse
                    </div>
                </div>
            </div>

            <!-- Commandes récentes -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Commandes récentes</h3>
                </div>
                <div class="overflow-hidden">
                    <div class="max-h-96 overflow-y-auto">
                        @forelse($this->getRecentOrders() as $order)
                            <div class="px-6 py-4 border-b border-gray-100 hover:bg-gray-50">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">#{{ $order['id'] }}</p>
                                        <p class="text-sm text-gray-500">{{ $order['email'] }}</p>
                                        <p class="text-sm text-gray-500">{{ $order['course'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-gray-900">{{ $order['amount'] }}</p>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($order['status'] === 'paid') bg-green-100 text-green-800
                                            @elseif($order['status'] === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $order['status'] }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-8 text-center text-gray-500">
                                    Aucune commande récente
                                </div>
                            @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const chartData = @json($this->getChartData());

            // Graphique des commandes
            new Chart(document.getElementById('ordersChart'), {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Commandes',
                        data: chartData.orders,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Graphique des revenus
            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Revenus (€)',
                        data: chartData.revenue,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '€' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-filament-panels::page>
