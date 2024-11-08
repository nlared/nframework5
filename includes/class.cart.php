<?
class Cart{
	function notification_menu(){
		foreach($this->items as $item){
			$itemstr.='     		   		<li class="dropdown-item">
                                                <div class="d-flex align-items-start cart-dropdown-item">
                                                    <img src="../assets/images/products/1.jpg" alt="img"
                                                        class="avatar avatar-md br-5 me-3">
                                                    <div class="flex-grow-1">
                                                        <div
                                                            class="d-flex align-items-start justify-content-between mb-0">
                                                            <div class="mb-0 fs-14 fw-semibold">
                                                                <a href="cart.html" class="text-dark">
                                                                '.$item['description'].'
                                                                </a>
                                                            </div>
                                                            <div>
                                                                <span class="fs-15 mb-1">$'.$item['value']*$item['quantity'].'</span>
                                                                <a href="javascript:void(0);"
                                                                    class="header-cart-remove float-end dropdown-item-close"><i
                                                                        class="ti ti-trash"></i></a>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="min-w-fit-content d-flex align-items-start justify-content-between">
                                                            <ul class="header-product-item">
                                                                <li>Quantity: '.$item['quantity'].'</li>
                                                                <li>'.$item['options'].'</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>';
         
		}
		
		return '				<!-- Start::header-element -->
                                <div class="header-element dropdown-center cart-dropdown">
                                    <!-- Start::header-link|dropdown-toggle -->
                                    <a href="javascript:void(0);" class="header-link dropdown-toggle"
                                        data-bs-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon"
                                            viewBox="0 0 24 24">
                                            <path d="M0 0h24v24H0V0z" fill="none" />
                                            <path
                                                d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" />
                                        </svg>
                                        <span class="badge bg-primary rounded-pill header-icon-badge"
                                            id="cart-icon-badge">5</span>
                                    </a>
                                    <!-- End::header-link|dropdown-toggle -->
                                    <!-- Start::main-header-dropdown -->
                                    <div class="main-header-dropdown dropdown-menu dropdown-menu-end"
                                        data-popper-placement="none">
                                        <div class="p-3 border-bottom">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="mb-0 fs-15 fw-semibold">Cart Items</p>
                                                <span class="badge bg-secondary-transparent" id="cart-data">5
                                                    Items</span>
                                            </div>
                                        </div>
                                        <ul class="mb-0 overflow-auto" id="header-cart-items-scroll">
        									'.$itemsstr.'
                                        </ul>
                                        <div class="p-3 empty-header-item border-top">
                                            <div class="d-grid">
                                                <a href="checkout.html" class="btn btn-primary">Proceed to
                                                    checkout</a>
                                            </div>
                                        </div>
                                        <div class="p-5 empty-item d-none">
                                            <div class="text-center">
                                                <span class="avatar avatar-xl rounded-2 bg-warning-transparent">
                                                    <i class="ri-shopping-cart-2-line fs-2"></i>
                                                </span>
                                                <h6 class="fw-bold mb-1 mt-3">Your Cart is Empty</h6>
                                                <a href="shop.html" class="btn btn-primary btn-wave btn-sm m-1"
                                                    data-abc="true">Back to Shop <i
                                                        class="bi bi-arrow-right ms-1"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End::main-header-dropdown -->
                                </div>
                                <!-- End::header-element -->';
	}
}
?>