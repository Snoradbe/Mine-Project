<template>
    <div :class="{'store-server': true, active: active}">
        <h2 class="store-server__abbr">{{getServerAbbr()}}</h2>
        <span class="store-server__name">{{server.name}}</span>
    </div>
</template>

<script>
    export default {
        name: "Server",
        props: ['server', 'active'],
        methods: {
            getServerAbbr() {
                return this.server.name.replace(/[^A-Z]+/g, '').substr(0, 2)
            }
        }
    }
</script>
