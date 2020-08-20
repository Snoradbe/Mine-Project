<template>
    <div :class="{'store-type': true, 'active': active, 'disabled': disabled}">
        <img :src="'/img/store/' + category.id + '.svg'" alt="mine Store" class="store-type__icon">
        <h4 class="store-type__name">{{getColumnLangValue('name', category)}}</h4>
        <span class="store-type__count">{{category.products_count}} {{declOfNum(category.products_count, Lang.words.store.products)}}</span>
    </div>
</template>

<script>
    export default {
        name: "Category",
        props: ['category', 'active', 'disabled'],
    }
</script>
