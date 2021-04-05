<header class="site__header">

    <div class="site__header_content">

        <div class="site__notification">
                
            <div class="notification__message">
                
                <span class="notification__message_text">Hello, how are you?!?</span>

            </div>
            
        </div>

        <div class="site__header_control">

            <a class="site__logo" href="index.php">
                    
                <img class="site__logo_icon" title="Sopranos Pizzabar" src="assets/images/layout/sopranos-logo-header.png">
            
            </a>

        </div>
        
        <nav class="site__menu">
            
            <ul class="menu__list">

                <div class="menu__container">
                
                    <li class="menu__item">

                        <a class="menu__link menu__link-shop--button" title="Start Shopping" href="shop.php">Start Shopping</a>

                    </li>
                    
                    <li class="menu__item">
                    
                        <a class="menu__link menu__link--highlight" title="Login" href="login.php">Login</a>
                    
                    </li>
                    
                    <li class="menu__item js-shopping-cart">

                        <a class="menu__link" title="Shopping Cart" href="cart.php">

                            <div class="menu__link-shopping-cart-container">

                                <div class="ts-icon-cart"><i class="fas fa-shopping-cart"></i></div>

                                <span class="site__shopping-cart-count js-shopping-cart-count"><?= $iShoppingCartCount; ?></span>
                            
                            </div>

                        </a>
                
                    </li>
                
                </div>

            </ul>

        </nav>

    </div>

</header>