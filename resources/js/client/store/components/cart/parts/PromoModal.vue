<template>
    <div class="modal cart-modal-promo" style="display: block" @click.self="$emit('close')">
        <div class="modal__dialog" @click.self="$emit('close')">
            <div class="modal__container">
                <div class="modal__header">
                    <div>
                        <h4 class="modal__title">{{Lang.cart.promo_modal.title}}</h4>
                    </div>
                </div>
                <div class="modal__body">
                    <input type="text" class="input" v-model="code">
                    <button type="button" class="btn btn_warning" @click.prevent="checkCode">{{Lang.cart.promo_modal.btn}}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PromoModal",
        data() {
            return {
                code: null
            }
        },
        methods: {
            checkCode() {
                this.$emit('close')
                if (this.code && this.code.trim()) {
                    axios.post('/cart/promo', {'code': this.code})
                        .then(res => {
                            if (res.data.error) {
                                this.$emit('error', res.data.error)
                            } else {
                                alert(res.data.message)
                                this.$emit('activated', res.data.promo, res.data.discount)
                            }
                        })
                }
            }
        }
    }
</script>
