
// Dialog search form.
jQuery( function( $ ) {
    function dialogSearch() {
        var headerSearchIcon = document.getElementsByClassName( 'zip-container' ),
            dialogSearchForm = document.querySelector( '.site-dialog-search' ),
            searchField      = document.querySelector( '.site-dialog-search .search-field' ),
            closeBtn         = document.querySelector( '.site-dialog-search .dialog-search-close-icon' ),
            errorText        = document.querySelector( '.site-dialog-search .dialog-error' );

        if ( ! headerSearchIcon.length || ! dialogSearchForm || ! searchField || ! closeBtn ) {
            return;
        }

        // Disabled field suggestions.
        searchField.setAttribute( 'autocomplete', 'off' );

        // Field must not empty.
        searchField.setAttribute( 'required', 'required' );

        var dialogOpen = function() {
            document.documentElement.classList.add( 'dialog-search-open' );
            document.documentElement.classList.remove( 'dialog-search-close' );

            if ( window.matchMedia( '( min-width: 992px )' ).matches ) {
                searchField.focus();
            }
        };

        var dialogClose = function() {
            document.documentElement.classList.add( 'dialog-search-close' );
            document.documentElement.classList.remove( 'dialog-search-open' );
        };

        for ( var i = 0, j = headerSearchIcon.length; i < j; i++ ) {
            headerSearchIcon[i].addEventListener(
                'click',
                function() {
                    dialogOpen();
                    errorText.innerHTML = '';
                    var zipcodeElement = $(this).find('span.zip-code');

                    var data = {};

                    // Fetch changes that are directly added by calling $thisbutton.data( key, value )
                    $.each(zipcodeElement.data(), function (key, value) {
                        data[key] = value;
                    });

                    // Fetch data attributes in $thisbutton. Give preference to data-attributes because they can be directly modified by javascript
                    // while `.data` are jquery specific memory stores.
                    $.each(zipcodeElement[0].dataset, function (key, value) {
                        data[key] = value;
                    });

                    searchField.value = data.value;

                    // Use ESC key.
                    document.body.addEventListener(
                        'keyup',
                        function( e ) {
                            if ( 27 === e.keyCode ) {
                                dialogSearchForm.submit();
                                dialogClose();
                            }
                        }
                    );

                    dialogSearchForm.addEventListener(
                        'submit',
                        function(e){
                            e.preventDefault();
                            e.stopPropagation();
                            const zipcode = e.target.elements.zipcode;
                            errorText.innerHTML = '';
                            $.ajax({
                                url: gethalal_obj.ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'gethalal_update_zipcode',
                                    zipcode: zipcode.value.trim()
                                },
                                success: function (response) {
                                    if (response.success) {
                                        const data = response.data;
                                        console.log('success', data.post_code, data.zone_name);
                                        zipcodeElement[0].innerHTML = `${data.post_code},${data.zone_name}`;
                                        zipcodeElement[0].setAttribute('data-value', data.post_code);
                                        dialogClose();
                                        return;
                                    }
                                    console.log('failed', response.data.error);
                                    errorText.innerHTML = response.data.error;
                                },
                                error: function( jqxhr, status, exception ) {
                                    console.log('error', exception);
                                    errorText.innerHTML = "Please login";
                                }
                            });
                        }
                    );

                    // Use dialog overlay.
                    dialogSearchForm.addEventListener(
                        'click',
                        function( e ) {
                            if ( this !== e.target ) {
                                return;
                            }

                            dialogClose();
                        }
                    );

                    // Use closr button.
                    closeBtn.addEventListener(
                        'click',
                        function() {
                            dialogClose();
                        }
                    );
                }
            );
        }
    }

    function addToCartAction () {
        if ( typeof wc_add_to_cart_params === 'undefined' ) {
            return false;
        }

        var addToCartBtn = document.getElementsByClassName( 'gethalal_ajax_add_to_cart' );

        for ( var i = 0, j = addToCartBtn.length; i < j; i++ ) {
            addToCartBtn[i].addEventListener(
                'click',
                function (e) {
                    var $thisbutton = $(this);
                    e.preventDefault();
                    e.stopPropagation();

                    if (!$thisbutton.attr('data-product_id') || $thisbutton.hasClass('disable')) {
                        return true;
                    }


                    $thisbutton.removeClass('added');
                    $thisbutton.addClass('loading');

                    // Allow 3rd parties to validate and quit early.
                    if (false === $(document.body).triggerHandler('should_send_ajax_request.adding_to_cart', [$thisbutton])) {
                        $(document.body).trigger('ajax_request_not_sent.adding_to_cart', [false, false, $thisbutton]);
                        return true;
                    }

                    var data = {};

                    // Fetch changes that are directly added by calling $thisbutton.data( key, value )
                    $.each($thisbutton.data(), function (key, value) {
                        data[key] = value;
                    });

                    // Fetch data attributes in $thisbutton. Give preference to data-attributes because they can be directly modified by javascript
                    // while `.data` are jquery specific memory stores.
                    $.each($thisbutton[0].dataset, function (key, value) {
                        data[key] = value;
                    });

                    // Trigger event.
                    $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

                    var ajax_url =  wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart');
                    if(data['quantity'] == 0){
                        ajax_url = gethalal_obj.ajaxurl;
                        data['action'] = 'remove_from_cart';
                    }

                    if($thisbutton.hasClass('is_cart')){
                        data['is_cart'] = true;
                    }

                    $.ajax( {
                        url: ajax_url,
                        type: 'POST',
                        data: data,
                        success: function (response) {
                            if (!response) {
                                return;
                            }

                            if (response.error && response.product_url) {
                                window.location = response.product_url;
                                return;
                            }

                            // Redirect to cart option
                            if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
                                window.location = wc_add_to_cart_params.cart_url;
                                return;
                            }

                            // Trigger event so themes can refresh other areas.
                            var product_id = response.product_id;
                            var quantity = Number(response.quantity);
                            var html = (quantity > 0?`<div data-quantity="${quantity - 1}" data-cart_item_key="${response.cart_item_key}" class="action-minus button product_type_simple add_to_cart_button gethalal_ajax_add_to_cart" data-product_id="${product_id}"><i class="fas fa-minus" style="font-size: 16px"></i></div><div class="action-quantity">${quantity}</div>`:'') +
                                `<div data-quantity="${quantity + 1}" data-cart_item_key="${response.cart_item_key}" class="action-plus button product_type_simple add_to_cart_button gethalal_ajax_add_to_cart" data-product_id="${product_id}"><i class="fas fa-plus" style="font-size: 16px"></i></div>`;

                            if(quantity === 0 && $(`.post-${product_id}.cart_item`).length > 0){
                                $(`.post-${product_id}.cart_item`)[0].remove();
                            } else {
                                var actionContainers = $(`.post-${product_id} .actions-container`);

                                for(var k=0; k<actionContainers.length; k++){
                                    actionContainers[k].innerHTML = html;
                                }
                            }


                            // Top Cart Button Badge
                            var cartBtn = document.getElementsByClassName( 'shopping-bag-button' );
                            if(cartBtn.length){
                                for (const childBtn of cartBtn) {
                                    var allCount = Number(response.all);
                                    var spanTag = childBtn.lastChild;
                                    if(allCount > 0){
                                        html = `<span class="cart-items-text shop-cart-count">${allCount}</span>`;
                                        if(spanTag.tagName === 'SPAN'){
                                            spanTag.innerHTML = allCount;
                                        } else {
                                            childBtn.innerHTML = childBtn.innerHTML + html;
                                        }
                                    } else {
                                        if(spanTag.tagName === 'SPAN'){
                                            spanTag.remove();
                                        }
                                    }
                                }
                            }

                            if($thisbutton.hasClass('is_cart')){
                                location.reload();
                            } else {
                                addToCartAction();
                            }
                        },
                        dataType: 'json'
                    });
                }
            );
        }
    }

    function dialogProductDetail(){
        var dialogOpen = function() {
            document.documentElement.classList.add( 'dialog-open' );
            document.documentElement.classList.remove( 'dialog-close' );
        };

        var dialogClose = function() {
            document.documentElement.classList.add( 'dialog-close' );
            document.documentElement.classList.remove( 'dialog-open' );
        };

        var productContainer = document.getElementsByClassName( 'product-content' );
        for ( var i = 0, j = productContainer.length; i < j; i++ ) {
            if(productContainer[i].parentElement.className.indexOf('not-purchasable') > -1){
                continue;
            }
            productContainer[i].addEventListener(
                'click',
                function (e) {
                    var $thisbutton = $(this).parent();

                    if(e.target.className.indexOf('action-remind') > -1){
                        return false;
                    }
                    e.preventDefault();

                    var data = {};
                    // Fetch changes that are directly added by calling $thisbutton.data( key, value )
                    $.each($thisbutton.data(), function (key, value) {
                        data[key] = value;
                    });

                    $.ajax({
                        type: 'POST',
                        url: gethalal_obj.ajaxurl,
                        data: {
                            'action': 'gethalal_get_product',
                            'product_id': data.id
                        },
                        success: function (response) {
                            if (!response) {
                                return;
                            }

                            dialogOpen();

                            var dialogForm = document.querySelector( '.gethalal-site-dialog' );
                            var dialogContent = document.querySelector( '.gethalal-site-dialog .modal-content' );
                            if(!dialogForm || !dialogContent) {
                                return;
                            }

                            dialogContent.innerHTML = response;

                            addToCartAction();
                            // Use dialog overlay.
                            dialogForm.addEventListener(
                                'click',
                                function( e ) {
                                    if ( this !== e.target ) {
                                        return;
                                    }

                                    dialogClose();
                                }
                            );

                            var closeBtn = document.querySelector( '.gethalal-site-dialog .dialog-close-icon' );
                            // Use closr button.
                            closeBtn.addEventListener(
                                'click',
                                function() {
                                    dialogClose();
                                }
                            );
                        }
                    });
                }
            );

            // Product Detail Page
            if(productContainer[i].className.indexOf('opened') > -1){
                eventFire(productContainer[i], 'click');
            }
        }
    }

    function eventFire(el, etype){
        if (el.fireEvent) {
            el.fireEvent('on' + etype);
        } else {
            var evObj = document.createEvent('Events');
            evObj.initEvent(etype, true, false);
            el.dispatchEvent(evObj);
        }
    }

    function dialogAddress(){
        var dialogOpen = function() {
            document.documentElement.classList.add( 'dialog-open' );
            document.documentElement.classList.remove( 'dialog-close' );
        };

        var dialogClose = function() {
            document.documentElement.classList.add( 'dialog-close' );
            document.documentElement.classList.remove( 'dialog-open' );
        };

        var addressActions = $( '.add-address-action, .address-edit-action' );
        for ( var i = 0, j = addressActions.length; i < j; i++ ) {
            addressActions[i].addEventListener(
                'click',
                function (e) {
                    var $thisbutton = $(this);

                    e.preventDefault();

                    var data = {};
                    // Fetch changes that are directly added by calling $thisbutton.data( key, value )
                    $.each($thisbutton.data(), function (key, value) {
                        data[key] = value;
                    });

                    $.ajax({
                        type: 'POST',
                        url: gethalal_obj.ajaxurl,
                        data: {
                            'action': 'gethalal_get_address',
                            'address_id': data.id
                        },
                        success: function (response) {
                            if (!response) {
                                return;
                            }

                            dialogOpen();

                            var dialogForm = document.querySelector( '.gethalal-site-dialog' );
                            var dialogContent = document.querySelector( '.gethalal-site-dialog .modal-content' );
                            if(!dialogForm || !dialogContent) {
                                return;
                            }

                            dialogContent.innerHTML = response;

                            // Use dialog overlay.
                            dialogForm.addEventListener(
                                'click',
                                function( e ) {
                                    if ( this !== e.target ) {
                                        return;
                                    }

                                    dialogClose();
                                }
                            );

                            checkButtonAction();
                        }
                    });
                }
            );
        }
    }

    function dialogAddPamentMethod(){
        var dialogOpen = function() {
            document.documentElement.classList.add( 'dialog-open' );
            document.documentElement.classList.remove( 'dialog-close' );
        };

        var dialogClose = function() {
            document.documentElement.classList.add( 'dialog-close' );
            document.documentElement.classList.remove( 'dialog-open' );
        };

        var paymentMethodActions = $( '.add-payment-method-action' );
        for ( var i = 0, j = paymentMethodActions.length; i < j; i++ ) {
            paymentMethodActions[i].addEventListener(
                'click',
                function (e) {
                    var $thisbutton = $(this);

                    e.preventDefault();

                    $.ajax({
                        type: 'POST',
                        url: gethalal_obj.ajaxurl,
                        data: {
                            'action': 'gethalal_get_payment_method'
                        },
                        success: function (response) {
                            if (!response) {
                                return;
                            }

                            dialogOpen();

                            var dialogForm = document.querySelector( '.gethalal-site-dialog' );
                            var dialogContent = document.querySelector( '.gethalal-site-dialog .modal-content' );
                            if(!dialogForm || !dialogContent) {
                                return;
                            }

                            dialogContent.innerHTML = response;

                            // Use dialog overlay.
                            dialogForm.addEventListener(
                                'click',
                                function( e ) {
                                    if ( this !== e.target ) {
                                        return;
                                    }

                                    dialogClose();
                                }
                            );
                        }
                    });
                }
            );
        }
    }

    function checkButtonAction(){
        var checkBtns = $( '.set-default-action' );
        for ( var i = 0, j = checkBtns.length; i < j; i++ ) {
            checkBtns[i].addEventListener(
                'click',
                function (e) {
                    var $thisbutton = $(this);
                    e.preventDefault();
                    e.stopPropagation();
                    var input = $thisbutton.find('input[type=hidden]');
                    var icon = $thisbutton.find('.check-btn i');
                    if(input.length && icon.length){
                        if(input[0].value === '1'){
                            input[0].value = 0;
                            icon[0].className = 'far fa-circle';
                        } else {
                            input[0].value = 1;
                            icon[0].className = 'fas fa-check-circle';
                        }
                    }
                }
            );
        }
    }


    function dialogOrderDetail(){
        var dialogOpen = function() {
            document.documentElement.classList.add( 'dialog-open' );
            document.documentElement.classList.remove( 'dialog-close' );
        };

        var dialogClose = function() {
            document.documentElement.classList.add( 'dialog-close' );
            document.documentElement.classList.remove( 'dialog-open' );
        };

        var orderRows = $( '.my_account_orders .order, a.checkout-order-track' );
        for ( var i = 0, j = orderRows.length; i < j; i++ ) {
            orderRows[i].addEventListener(
                'click',
                function (e) {
                    if(e.target.className.indexOf('order-action') > -1){
                        return;
                    }
                    var $thisbutton = $(this);

                    e.preventDefault();

                    var data = {};
                    // Fetch changes that are directly added by calling $thisbutton.data( key, value )
                    $.each($thisbutton.data(), function (key, value) {
                        data[key] = value;
                    });

                    $.ajax({
                        type: 'POST',
                        url: gethalal_obj.ajaxurl,
                        data: {
                            'action': 'gethalal_get_order_detail',
                            'order_id': data.id
                        },
                        success: function (response) {
                            if (!response) {
                                return;
                            }

                            dialogOpen();

                            var dialogForm = document.querySelector( '.gethalal-site-dialog' );
                            var dialogContent = document.querySelector( '.gethalal-site-dialog .modal-content' );
                            if(!dialogForm || !dialogContent) {
                                return;
                            }

                            dialogContent.innerHTML = response;
                            delayLoadScript();

                            // Use dialog overlay.
                            dialogForm.addEventListener(
                                'click',
                                function( e ) {
                                    if ( this !== e.target ) {
                                        return;
                                    }

                                    dialogClose();
                                }
                            );
                        }
                    });
                }
            );
        }
    }

    function delayLoadScript(){
        if($( '.gethalal-order-detail .order-cancel' ).length < 1){
            setTimeout(delayLoadScript, 300);
        } else {
            dialogOrderCancel();
        }
    }

    function dialogOrderCancel(){
        var dialogOpen = function() {
            document.documentElement.classList.add( 'dialog-open' );
            document.documentElement.classList.remove( 'dialog-close' );
        };

        var dialogClose = function() {
            document.documentElement.classList.add( 'dialog-close' );
            document.documentElement.classList.remove( 'dialog-open' );
        };

        var orderCancelBtns = $( '.gethalal-order-detail .order-cancel' );
        for ( var i = 0, j = orderCancelBtns.length; i < j; i++ ) {
            orderCancelBtns[i].addEventListener(
                'click',
                function (e) {
                    var $thisbutton = $(this);

                    e.preventDefault();

                    var data = {};
                    // Fetch changes that are directly added by calling $thisbutton.data( key, value )
                    $.each($thisbutton.data(), function (key, value) {
                        data[key] = value;
                    });

                    $.ajax({
                        type: 'POST',
                        url: gethalal_obj.ajaxurl,
                        data: {
                            'action': 'gethalal_get_cancel_dialog',
                            'order_id': data.id
                        },
                        success: function (response) {
                            if (!response) {
                                return;
                            }

                            dialogOpen();

                            var dialogForm = document.querySelector( '.gethalal-site-dialog' );
                            var dialogContent = document.querySelector( '.gethalal-site-dialog .modal-content' );
                            if(!dialogForm || !dialogContent) {
                                return;
                            }

                            dialogContent.innerHTML = response;

                            // Use dialog overlay.
                            dialogForm.addEventListener(
                                'click',
                                function( e ) {
                                    if ( this !== e.target ) {
                                        return;
                                    }

                                    dialogClose();
                                }
                            );
                        }
                    });
                }
            );
        }
    }

    function dialogDeliveryTime() {
        var headerDeliveryTimeContainer = document.getElementsByClassName( 'delivery-time-container' ),
            dialogDeliveryTimeForm = document.querySelector( '.site-dialog-delivery-time' ),
            closeBtn         = document.querySelector( '.site-dialog-delivery-time .dialog-delivery-time-close-icon' ),
            submitBtn        = document.querySelector( '.site-dialog-delivery-time .delivery-submit' ),
            errorText        = document.querySelector( '.site-dialog-delivery-time .dialog-error' );

        if ( ! headerDeliveryTimeContainer.length || ! dialogDeliveryTimeForm || ! closeBtn ) {
            return;
        }

        var dialogOpen = function() {
            document.documentElement.classList.add( 'dialog-delivery-time-open' );
            document.documentElement.classList.remove( 'dialog-delivery-time-close' );
        };

        var dialogClose = function() {
            document.documentElement.classList.add( 'dialog-delivery-time-close' );
            document.documentElement.classList.remove( 'dialog-delivery-time-open' );
        };

        for ( var i = 0, j = headerDeliveryTimeContainer.length; i < j; i++ ) {
            headerDeliveryTimeContainer[i].addEventListener(
                'click',
                function() {
                    dialogOpen();
                    errorText.innerHTML = '';

                    // Use ESC key.
                    document.body.addEventListener(
                        'keyup',
                        function( e ) {
                            if ( 27 === e.keyCode ) {
                                dialogDeliveryTimeForm.submit();
                                dialogClose();
                            }
                        }
                    );

                    // Use dialog overlay.
                    dialogDeliveryTimeForm.addEventListener(
                        'click',
                        function( e ) {
                            if ( this !== e.target ) {
                                return;
                            }

                            dialogClose();
                        }
                    );

                    dialogDeliveryTimeForm.addEventListener(
                        'submit',
                        function(e){
                            dialogDeliveryTimeForm.elements.dialog_delivery_cycle;
                            dialogClose();
                        }
                    );

                    // Use closr button.
                    closeBtn.addEventListener(
                        'click',
                        function() {
                            dialogClose();
                        }
                    );

                    submitBtn.addEventListener(
                        'click',
                        function() {
                            dialogClose();
                        }
                    );
                }
            );
        }
    }

    function dialogSearchInput() {
        var searchInput = document.querySelector('.search-text-input');
        var searchClear = document.querySelector('.search-clear');
        if(!searchInput){ return; }
        searchInput.addEventListener(
            'input',
            function( e ) {
                var searchText = e.target.value;
                if(searchText.length > 0 && searchClear) {
                    searchClear.classList.add( 'is-searching' );
                } else {
                    searchClear.classList.remove('is-searching');
                }
            }
        );

    }

    ( function() {
        dialogSearch();
        dialogProductDetail();
        addToCartAction();
        dialogAddress();
        // Disable adding payment method with dialog.
        //dialogAddPamentMethod();
        dialogOrderDetail();
        checkButtonAction();
        dialogDeliveryTime();
        dialogSearchInput();
    }());
});
