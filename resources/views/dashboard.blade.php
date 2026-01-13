@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    {{-- Cards de Estatísticas --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
                <div class="inner">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Total de Clientes</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true">
                    <path
                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z">
                    </path>
                </svg>
                <a href="{{ route('customers.index') }}"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Ver todos <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>{{ $stats['thisMonth'] }}</h3>
                    <p>Novos este Mês</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true">
                    <path
                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z">
                    </path>
                </svg>
                <a href="{{ route('customers.index') }}"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Ver detalhes <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
                <div class="inner">
                    <h3>{{ $stats['today'] }}</h3>
                    <p>Novos Hoje</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true">
                    <path
                        d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z">
                    </path>
                </svg>
                <a href="{{ route('customers.index') }}"
                    class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                    Cadastrar novo <i class="bi bi-plus-circle"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-info">
                <div class="inner">
                    <h3>{{ $stats['states'] }}</h3>
                    <p>Estados Atendidos</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="#"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Cobertura nacional <i class="bi bi-geo-alt"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="row">
        {{-- Gráfico de Cadastros por Mês --}}
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-graph-up me-2"></i>
                        Cadastros por Mês
                    </h3>
                </div>
                <div class="card-body">
                    <div id="customers-chart" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        {{-- Gráfico de Clientes por Estado --}}
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-pie-chart me-2"></i>
                        Clientes por Estado
                    </h3>
                </div>
                <div class="card-body">
                    @if (count($customersByState) > 0)
                        <div id="states-chart" style="height: 300px;"></div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mt-2">Nenhum cliente cadastrado</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Últimos Clientes Cadastrados --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-clock-history me-2"></i>
                        Últimos Cadastros
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('customers.index') }}" class="btn btn-sm btn-primary">
                            Ver todos <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($latestCustomers->count() > 0)
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Telefone</th>
                                    <th>Cidade/UF</th>
                                    <th>Cadastrado em</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($latestCustomers as $customer)
                                    <tr>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->city }}/{{ $customer->state }}</td>
                                        <td>{{ $customer->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mt-2">Nenhum cliente cadastrado ainda</p>
                            <a href="{{ route('customers.index') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg"></i> Cadastrar primeiro cliente
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        crossorigin="anonymous" />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Customers by Month Chart
            const monthlyData = @json($customersByMonth);
            const monthlyChart = new ApexCharts(document.querySelector('#customers-chart'), {
                series: [{
                    name: 'Cadastros',
                    data: monthlyData.map(item => item.count)
                }],
                chart: {
                    height: 300,
                    type: 'area',
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#0d6efd'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                    }
                },
                xaxis: {
                    categories: monthlyData.map(item => item.month)
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        formatter: val => Math.floor(val)
                    }
                },
                tooltip: {
                    y: {
                        formatter: val => val + ' cliente(s)'
                    }
                }
            });
            monthlyChart.render();

            // Customers by State Chart
            @if (count($customersByState) > 0)
                const statesData = @json($customersByState);
                const statesChart = new ApexCharts(document.querySelector('#states-chart'), {
                    series: Object.values(statesData),
                    labels: Object.keys(statesData),
                    chart: {
                        type: 'donut',
                        height: 300
                    },
                    colors: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0', '#6f42c1', '#fd7e14',
                        '#20c997', '#6c757d', '#d63384'
                    ],
                    legend: {
                        position: 'bottom'
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val, opts) {
                            return opts.w.config.labels[opts.seriesIndex] + ': ' + opts.w.config.series[
                                opts.seriesIndex];
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: val => val + ' cliente(s)'
                        }
                    }
                });
                statesChart.render();
            @endif
        });
    </script>
@endpush
