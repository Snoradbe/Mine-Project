<template>
    <tr>
        <td>{{server.name}}</td>

        <td class="status-card__maintenance" v-if="server.maintenance">
            {{Lang.status.under_maintenance}}
        </td>
        <td class="status-card__off" v-else-if="server._offline">
            {{Lang.status.offline}}
        </td>
        <td class="status-card__neutral" v-else-if="server._not_responding">
            {{Lang.status.not_responding}}
        </td>
        <template v-else>
            <td class="status-card__normal" v-if="server.avgtps > 18">
                {{Lang.status.normal_operating}}
            </td>
            <td class="status-card__degraded" v-else-if="server.avgtps > 15">
                {{Lang.status.degraded_performance}}
            </td>
            <td class="status-card__low" v-else>
                {{Lang.status.too_low_performance}}
            </td>
        </template>
    </tr>
</template>

<script>
    export default {
        name: "ServerStatus",
        props: ['server']
    }
</script>
