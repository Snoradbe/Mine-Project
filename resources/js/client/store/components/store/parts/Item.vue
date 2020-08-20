<template>
    <div :class="{'store-product store-products__item': true, 'active': active, 'disabled': disabled}" @click="open">
        <div class="store-product__info">
            <div class="store-product__name">{{getColumnLangValue('name', product)}} <span v-if="product.amount > 1">(x{{product.amount}})</span></div>
            <div class="store-product__desc" v-html="getColumnLangValue('descr', product)" v-if="getColumnLangValue('descr', product)"></div>
            <div :class="'store-product__price cur cur_right ' + (product.price_rub ? 'cur_rub' : 'cur_coins')">{{getPrice()}}</div>
        </div>
        <div class="store-product__picture">
            <div class="store-product-icon" :style="{'background-image': 'url(\'../img/store/products/' + (product.img ? product.img : 'default.png') + '\')'}"></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Item",
        props: ['product', 'active', 'disabled'],
        methods: {
            getPrice() {
                let price = this.product.price_coins ? this.product.price_coins : this.product.price_rub
                if (this.product.discount && new Date(this.product.discount.date_end).getTime() > new Date().getTime()) {
                    price -= Math.floor(price * (this.product.discount.discount / 100))
                }

                return price
            },
            open() {
                if (!this.disabled) {
                    return this.$emit('open', this.product)
                }
            }
        }
    }
</script>
