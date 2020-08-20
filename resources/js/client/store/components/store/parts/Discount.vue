<template>
    <div :class="{'store-discount': true, active: active}">
        <span class="store-discount__icon material-icons">local_offer</span>
        <span class="store-discount__name">{{getColumnLangValue('name', discount)}}</span>
    </div>
</template>

<script>
    export default {
        name: "Discount",
        props: ['discount', 'active'],
    }
</script>
