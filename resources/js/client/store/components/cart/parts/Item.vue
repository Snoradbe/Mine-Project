<template>
    <div class="cart__item">
        <div class="cart__picture">
            <div class="store-product-icon" :style="{'background-image': 'url(\'../img/store/products/' + (item.product.img ? item.product.img : 'default.png') + '\')'}"></div>
        </div>
        <div class="cart__item-info">
            <h6 class="cart__item-name">{{getColumnLangValue('name', item.product)}}</h6>
            <div class="cart__item-desc" v-html="getColumnLangValue('descr', item.product)"></div>
        </div>
        <div class="cart__server">
            <template v-if="item.product.servername">
                <h6 class="cart__item-server">{{Lang.cart.item.server}}</h6>
                {{item.product.servername}}
            </template>
        </div>
        <div class="cart__amount">
            <h6 class="cart__item-amount">{{Lang.cart.item['amount_' + item.product.category.id] ? Lang.cart.item['amount_' + item.product.category.id] : Lang.cart.item.amount}}</h6>
            {{item.amount}} {{pcs()}}
        </div>
        <div class="cart__cost">
            <h6 class="cart__item-cost">{{Lang.cart.item.cost}}</h6>
            <div :class="'cur cur_right ' + (item.product.price_rub ? 'cur_rub' : 'cur_coins')">{{getPrice()}}</div>
        </div>
        <div class="cart__options">
            <span class="material-icons" @click="$emit('update', item)">tune</span>
            <span class="material-icons" @click="$emit('delete', item)">delete</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Item",
        props: ['item'],
        methods: {
            pcs() {
                let word = Lang.words.store.amount['category_' + this.item.product.category.id];
                return this.declOfNum(this.item.amount, word ? word : Lang.words.store.amount.pcs)
            },
            getPrice() {
                let price = this.item.product.price_rub ? this.item.product.price_rub : this.item.product.price_coins
                if (this.item.product.discount && new Date(this.item.product.discount.date_end).getTime() > new Date().getTime()) {
                    price -= Math.floor(price * (this.item.product.discount.discount / 100))
                }
                return price * this.item.amount
            },
        }
    }
</script>
