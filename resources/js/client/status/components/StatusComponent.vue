<template>
    <div class="section-status space-header" v-if="loaded">
        <div class="container">
            <div class="section-status__header">
                <div class="section-status__titles">
                    <h2 class="section-status__title">{{Lang.status.title}}</h2>
                    <h5 class="section-status__subtitle">{{Lang.status.subtitle}}</h5>
                </div>
                <a href="#" class="section-status__report btn btn_outline" @click.prevent="openReportForm" v-if="isAuth">
                    <span class="icon material-icons">flag</span> {{Lang.status.report}}
                </a>
            </div>

            <div class="section-status__content">
                <div class="section-status__left">
                    <div class="status-card">
                        <h2 class="status-card__title">{{Lang.status.dashboard}}</h2>

                        <div class="status-card__separator"></div>

                        <h6 class="status-card__subtitle">{{Lang.status.primary_services}}</h6>
                        <table class="status-card__table" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>{{Lang.status.proxy_gateway}}</td>
                                <td class="status-card__normal" v-if="proxy">
                                    {{Lang.status.normal_operating}}
                                </td>
                                <td class="status-card__off" v-else>
                                    {{Lang.status.offline}}
                                </td>
                            </tr>
                            <server-status :server="servers[server]" :key="server" v-if="servers[server].type === Types.SERVICE_PRIMARY" v-for="server in sorted"></server-status>
                        </table>

                        <h6 class="status-card__subtitle status-card__subtitle_secondary">{{Lang.status.secondary_services}}</h6>
                        <table class="status-card__table" cellpadding="0" cellspacing="0">
                            <server-status :server="servers[server]" :key="server" v-if="servers[server].type === Types.SERVICE_SECONDARY" v-for="server in sorted"></server-status>
                        </table>

                        <div class="status-card__separator"></div>

                        <h6 class="status-card__subtitle">{{Lang.status.game_servers}}</h6>
                        <table class="status-card__table status-card__table_servers" cellpadding="0" cellspacing="0">
                            <server-status :server="servers[server]" :key="server" v-if="servers[server].type === Types.GAME" v-for="server in sorted"></server-status>
                        </table>

                        <div class="status-card__separator"></div>

                        <table class="status-card__table" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>{{Lang.status.players_online}}</td>
                                <td>{{totalOnline}} {{declOfNum(totalOnline, Lang.words.players)}}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="status-card">
                        <h2 class="status-card__title">{{Lang.status.service_servers}}</h2>
                        <template v-if="servers[server].type !== Types.GAME" v-for="server in sorted">
                            <div class="status-card__separator"></div>
                            <server-info :server="servers[server]"></server-info>
                        </template>
                    </div>
                </div>
                <div class="section-status__right">
                    <div class="status-card">
                        <h2 class="status-card__title">{{Lang.status.game_servers}}</h2>
                        <template v-if="servers[server].type === Types.GAME" v-for="server in sorted">
                            <div class="status-card__separator"></div>
                            <server-info :server="servers[server]"></server-info>
                        </template>
                    </div>
                </div>
            </div>
            <a href="#" class="section-status__report section-status__report_bottom btn btn_outline" @click.prevent="openReportForm" v-if="isAuth">
                <span class="icon material-icons">flag</span>
                {{Lang.status.report}}
            </a>
        </div>
        <transition name="item-modal">
            <report-modal :servers="getServerList()" :types="settings.report_types" v-show="modal_report" @close="closeReportForm" @send="sendReport"></report-modal>
        </transition>
    </div>
</template>

<script>
    import ServerStatus from "./partials/ServerStatus";
    import ServerInfo from "./partials/ServerInfo";
    import ReportModal from "./partials/ReportModal";
    export default {
        name: "StatusComponent",
        components: {ReportModal, ServerInfo, ServerStatus},
		props: ['nodeDomain', 'nodeOptions', 'settings', 'isAuth', 'serversSorting'],
        data() {
            return {
				loaded: false,
                servers: {},
                sorted: [],
                proxy: false,
                totalOnline: -1,

				socket: null,

                modal_report: false
            }
        },
        mounted() {
			this.socket = io(this.nodeDomain, this.nodeOptions)
			this.socket.emit('load-data')

			this.socket.on('load-data', data => {
				let now = data.now

				let servers
                try {
				    let sortable = {}
				    let sorted = Object.keys(data.servers).sort((serv1, serv2) => {
				        let sort1 = this.serversSorting[serv1] ? this.serversSorting[serv1] : 0
				        let sort2 = this.serversSorting[serv2] ? this.serversSorting[serv2] : 0
                        return sort2 - sort1
                    })
					this.sorted = sorted
					
                    for (let server of sorted)
                    {
                        sortable[server] = data.servers[server]
                    }
					
                    servers = sortable
                } catch (e) {
                    servers = data.servers
					this.sorted = Object.keys(data.servers)
					console.error(e)
                }
				let online = 0;
				for (let server in servers)
				{
					servers[server].chart = data.charts[server]
					this.loadServerBaseData(servers[server], now)
					online += parseInt(servers[server].players)
				}

				this.servers = servers
				this.proxy = data.proxy_server
				this.totalOnline = online
				this.loaded = true
				loading(false)
			})

			this.socket.on('load-servers', (data) => {
				let now = data.now

				let servers = data.servers
				let online = 0;
				for (let server in servers)
				{
					if (!this.servers[server]) {
						servers[server].chart = {labels: [], data: []}
					} else {
						servers[server].chart = this.servers[server].chart
					}

					if (servers[server].chart.labels.length >= data.chart_seconds) {
						servers[server].chart.labels.splice(0, 1)
						servers[server].chart.data.splice(0, 1)
					}

					if (data.chart[server].data > 20) {
						data.chart[server].data = 20
					}
					servers[server].chart.labels.push(data.chart[server].label)
					servers[server].chart.data.push(data.chart[server].data)

					this.loadServerBaseData(servers[server], now)

					online += parseInt(servers[server].players)
				}

				this.servers = servers
				this.proxy = data.proxy_server
				this.totalOnline = online
			})
        },
        methods: {
			secondsToHms(s) {
				/*let data = {
					h: ((s - s % 3600) / 3600) % 60,
					m: ((s - s % 60) / 60) % 60,
					s: s % 60
				}

				return data.h + Lang.status.h + ' ' + data.m + Lang.status.m + ' ' + data.s + Lang.status.s*/

                let data = {
				    d: this.formatNToNum(Math.floor(s / 86400)),
				    h: this.formatNToNum(Math.floor(s / 3600) % 24),
				    m: this.formatNToNum(Math.floor(s / 60) % 60),
				    s: s % 60
                }

                let result = '';
				if (data.d) {
				    result += data.d + Lang.status.d
                }
				if (data.h) {
				    result += ` ${data.h}${Lang.status.h}`
                }
				if (data.m) {
				    result += ` ${data.m}${Lang.status.m}`
                }
				if (data.s) {
				    result += ` ${data.s}${Lang.status.s}`
                }

				return result
			},
            formatNToNum(n)  {
			    return n > 0 && n < 10 ? '0' + n : n
            },
			loadServerBaseData(server, now) {
				server['last-response'] = server['last-response'] - 3600
				server.maintenance = server.maintenance === 'true'
				server.uptime = this.secondsToHms(server.uptime)
				server._offline = now - server['last-response'] >= 15
				server._not_responding = now - server['last-response'] >= 3
			},
            getServerList() {
			    let servers = [Lang.status.proxy_gateway];
			    for (let server in this.servers)
                {
                    servers.push(server)
                }

			    return servers
            },
            openReportForm() {
			    this.modal_report = true
            },
            closeReportForm() {
			    this.modal_report = false
            },
            sendReport(server, type) {
			    this.closeReportForm()
			    axios.post(this.settings.reports_url, {server: server, type: type})
                    .then(res => {
                        alert(res.data.message)
                    })
            }
        }
    }
</script>

<style scoped>
    /* Появление */
    .item-modal-enter-active {
        animation: item-modal .3s;
    }

    /* Скрывание */
    .item-modal-leave-active {
        animation: item-modal .2s reverse;
    }

    @keyframes item-modal {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }
</style>
