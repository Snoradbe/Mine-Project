<template>
    <div :class="'alert store-alert store-alert_' + (error.type ? error.type : 'warning')">
        <div class="store-alert__left">
            <div class="store-alert__title">
                <span class="icon material-icons">warning</span>
                <span v-html="error.title ? error.title : error"></span>
            </div>
            <div class="store-alert__text" v-html="error.text" v-if="error.text"></div>
        </div>
        <div class="store-alert__right" v-if="error.btn">
            <a :href="error.link_url ? error.link_url : '#'" class="store-alert__link btn btn_warning">{{error.btn}}</a>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Alert",
        props: ['error']
    }
</script>
