@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{asset('css/store.css')}}?v={{time()}}">
@endsection

@section('content')
    <div class="section-store space-header">
        <div class="container section-store__container">
            <div class="section-store__content">
                <div class="section-store__left">
					<div class="section-store__head">
						<h2 class="section-store__title section-store__title_cart">Shopping Cart</h2>
						<a href="#" class="section-store__back"><span class="material-icons">arrow_back</span> Back to store</a>
					</div>
					<div class="cart section-store__cart">
						<div class="cart__items">
							<div class="cart__item">
								<div class="cart__picture">
									
								</div>
								<div class="cart__info">
									<h6 class="cart__item-name">Emerald Case</h6>
									Premium Perk in every server of mine and something description of product.
								</div>
								<div class="cart__server">
									<h6 class="cart__item-server">Server</h6>
									SlighTech
								</div>
								<div class="cart__amount">
									<h6 class="cart__item-amount">Duration</h6>
									6 Month
								</div>
								<div class="cart__cost">
									<h6 class="cart__item-cost">Cost</h6>
									<div class="cur cur_right cur_coins">1357</div>
								</div>
								<div class="cart__options">
									<span class="material-icons">tune</span>
									<span class="material-icons">delete</span>
								</div>
							</div>
							
							<div class="cart__item">
								<div class="cart__picture">
									
								</div>
								<div class="cart__info">
									<h6 class="cart__item-name">Emerald Case</h6>
									Premium Perk in every server of mine and something description of product.
								</div>
								<div class="cart__server">
									<h6 class="cart__item-server">Server</h6>
									SlighTech
								</div>
								<div class="cart__amount">
									<h6 class="cart__item-amount">Duration</h6>
									6 Month
								</div>
								<div class="cart__cost">
									<h6 class="cart__item-cost">Cost</h6>
									<div class="cur cur_right cur_coins">1357</div>
								</div>
								<div class="cart__options">
									<span class="material-icons">tune</span>
									<span class="material-icons">delete</span>
								</div>
							</div>
							
							<div class="cart__item">
								<div class="cart__picture">
									
								</div>
								<div class="cart__info">
									<h6 class="cart__item-name">Emerald Case</h6>
									Premium Perk in every server of mine and something description of product.
								</div>
								<div class="cart__server">
									<h6 class="cart__item-server">Server</h6>
									SlighTech
								</div>
								<div class="cart__amount">
									<h6 class="cart__item-amount">Duration</h6>
									6 Month
								</div>
								<div class="cart__cost">
									<h6 class="cart__item-cost">Cost</h6>
									<div class="cur cur_right cur_coins">1357</div>
								</div>
								<div class="cart__options">
									<span class="material-icons">tune</span>
									<span class="material-icons">delete</span>
								</div>
							</div>
						</div>
					</div>
                </div>
				<div class="section-store__right">
                    <div class="store-menu">
                        <div class="store-menu__item">
                            <div class="store-menu__left">
                                Shopping cart
                            </div>
                            <div class="store-menu__right">
                                <a href="#" class="">Open</a>
                            </div>
                        </div>

                        <div class="store-menu__item">
                            <div class="store-menu__left">
                                Your balance
                            </div>
                            <div class="store-menu__right">
                                1245
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('bottom')
    
@endsection
