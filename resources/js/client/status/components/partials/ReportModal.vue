<template>
    <div class="modal status-report" style="display: block" @click.self="$emit('close')">
        <div class="modal__dialog" @click.self="$emit('close')">
            <div class="modal__container">
                <div class="modal__header">
                    <div>
                        <h4 class="modal__title">{{Lang.status.reports.modal.title}}</h4>
                        <h6 class="modal__subtitle">{{Lang.status.reports.modal.subtitle}}</h6>
                    </div>
                </div>
                <div class="modal__body">
                    <form action="#" class="form">
                        <div class="form__item">
                            <label class="form__label status-report__pick-label">{{Lang.status.reports.modal.pick_server}}</label>
                            <select class="select" v-model="server">
                                <option :value="(serv === Lang.status.proxy_gateway ? 'proxy' : serv)" v-for="serv in servers">{{serv}}</option>
                            </select>
                        </div>

                        <div class="form__item">
                            <label class="form__label status-report__pick-label">{{Lang.status.reports.modal.pick_type}}</label>
                            <select class="select" v-model="type">
                                <option :value="typ" v-for="typ in types">{{Lang.status.reports.types[typ]}}</option>
                            </select>
                        </div>

                        <div class="form__item status-report__footer">
                            <div class="status-report__cancel" @click.prevent="$emit('close')">{{Lang.status.reports.modal.btns.cancel}}</div>
                            <button type="submit" class="btn btn_warning" @click.prevent="send">{{Lang.status.reports.modal.btns.send}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ReportModal",
        props: ['servers', 'types'],
        data() {
            return {
                server: 'proxy',
                type: null
            }
        },
        mounted() {
            this.server = 'proxy'
            this.type = this.types[0]
        },
        methods: {
            send() {
                this.$emit('send', this.server, this.type)
            }
        }
    }
</script>
