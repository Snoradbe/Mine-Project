<template>
    <div class="status-server">
        <div class="status-server__titles">
            <div class="status-server__title">{{server.name}}</div>

            <div class="status-server__status status-card__maintenance" v-if="server.maintenance">{{Lang.status.under_maintenance}}</div>
            <div class="status-server__status status-card__off" v-else-if="server._offline">{{Lang.status.offline}}</div>
            <div class="status-server__status" v-else-if="server._not_responding">{{Lang.status.not_responding}}</div>
            <template v-else>
                <div class="status-server__status status-card__normal" v-if="server.avgtps >= 18">{{Lang.status.normal_operating}}</div>
                <div class="status-server__status status-card__degraded" v-else-if="server.avgtps >= 15">{{Lang.status.degraded_performance}}</div>
                <div class="status-server__status status-card__low" v-else>{{Lang.status.too_low_performance}}</div>
            </template>
        </div>

        <div class="status-server__graphic status-server__graphic_na" v-if="server._offline"></div>
        <chart class="status-server__graphic" :chart-data="chartData" :options="chartOptions" :styles="chartStyles" :height="105" v-else></chart>

        <table class="status-server__info" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    {{Lang.status.tps_performance}}
                    <span :class="tpsClass(server.curtps)" v-if="!server._offline">{{server.curtps}} ({{tpsPercent(server.curtps)}}%)</span>
                    <span class="status-card__neutral" v-else>N/A</span>
                </td>
                <td>
                    {{Lang.status.avg}}
                    <span :class="tpsClass(server.avgtps)" v-if="!server._offline">{{server.avgtps}} ({{tpsPercent(server.avgtps)}}%)</span>
                    <span class="status-card__neutral" v-else>N/A</span>
                </td>
                <td>
                    {{Lang.status.uptime}}
                    <span class="status-card__neutral" v-if="!server._offline">{{server.uptime}}</span>
                    <span class="status-card__neutral" v-else>N/A</span>
                </td>
            </tr>
            <tr>
                <td>
                    {{Lang.status.online}}
                    <span class="status-card__neutral" v-if="!server._offline">{{server.players}}/{{server.slots}} {{declOfNum(server.players, Lang.words.players)}}</span>
                    <span class="status-card__neutral" v-else>N/A</span>
                </td>
                <td colspan="2">
                    {{Lang.status.core}}
                    <span class="status-card__neutral" v-if="!server._offline">{{server.core}}</span>
                    <span class="status-card__neutral" v-else>N/A</span>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
    import Chart from "./Chart";
    export default {
        name: "ServerInfo",
        components: {Chart},
        props: ['server'],
        data() {
            return {
                chartStyles: {
                    position: 'relative'
                },
                chartData: {
                    labels: this.server.chart.labels,
                    datasets: [{
                        type: 'line',
                        label: 'TPS',
                        borderColor: [
                            '#ffa700'
                        ],
                        hoverRadius: 8,
                        hitRadius: 3,
                        borderWidth: 2,
                        data: this.server.chart.data
                    }]
                },
                chartOptions: {
                    responsive: true,
                    legend: {
                        display: false
                    },
                    elements: {
                        line: {
                            fill: false,
                        },
                        point: {
                            radius: 0
                        }
                    },
                    layout: {
                        padding: {
                            top: 15,
                            bottom: 5
                        }
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                zeroLineColor: false
                            },
                            ticks: {
                                beginAtZero: true,
                                callback: function(value) {return (parseFloat(value)) + '.0'}
                            }
                        }],
                    }
                }
            }
        },
        watch: {
            server(serv) {
				let data = Object.assign({}, this.chartData)
                data.labels = serv.chart.labels
                data.datasets.data = serv.chart.data
				this.chartData = data
            }
        },
        methods: {
            tpsPercent(tps) {
                return Math.round((tps * 100) / 20, 2)
            },
            tpsClass(tps) {
                if (tps >= 19) {
                    return 'status-card__normal';
                } else if (tps >= 15) {
                    return 'status-card__normal-low';
                }

                return 'status-card__low';
            }
        }
    }
</script>
