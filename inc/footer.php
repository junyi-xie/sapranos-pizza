<footer class="site__footer">
        
    <div class="site__footer_wrapper">

        <div class="site__footer_content">

            <nav class="footer__nav">

                <div class="site__social">

                    <a class="site__logo_icon" href="index.php"><img class="site__logo_image" title="Sopranos Pizzabar" src="assets/images/layout/sopranos-logo-footer.png"></a>

                    <?php // <ul class="site__social_links"><li class="site__social_link"><a class="icon__facebook_link" target="_blank" href="#"><i class="fa fa-facebook-f"></i></a></li><li class="site__social_link"><a class="icon__twitter_link" target="_blank" href="#"><i class="fa fa-twitter"></i></a></li><li class="site__social_link"><a class="icon__instagram_link" target="_blank" href="#"><i class="fa fa-instagram"></i></a></li><li class="site__social_link"><a class="icon__pinterest_link" target="_blank" href="#"><i class="fa fa-pinterest"></i></a></li></ul> ?>

                </div>
                
                <ul class="nav__items">

                    <h3 class="nav__header">Explore</h3>

                    <li class="nav__item"><a class="nav__item_link" title="Home" href="index.php">Home</a></li>    

                    <li class="nav__item"><a class="nav__item_link" title="About us" href="about.php">About Us</a></li>                

                    <li class="nav__item"><a class="nav__item_link" title="Store page" href="shop.php">Store Page</a></li>      

                    <li class="nav__item"><a class="nav__item_link" title="Contact" href="contact.php">Contact</a></li>    

                    <li class="nav__item"><a class="nav__item_link" title="Shopping cart" href="cart.php">Shopping Cart</a></li>  

                </ul>

                <ul class="nav__items">

                    <h3 class="nav__header">Visit</h3>

                    <li class="nav__item"><?= $aSopranosBranches['name']; ?></li>    

                    <li class="nav__item"><?= $aSopranosBranches['adres']; ?></li>                

                    <li class="nav__item"><?= $aSopranosBranches['zipcode']."\t".$aSopranosBranches['city']; ?></li> 

                </ul>

                <ul class="nav__items">

                    <h3 class="nav__header">Connect</h3>

                    <li class="nav__item"><a class="nav__item_link" title="<?= $aSopranosBranches['email']; ?>" href="mailto:<?= $aSopranosBranches['email']; ?>"><?= $aSopranosBranches['email']; ?></a></li>    

                    <li class="nav__item"><a class="nav__item_link" title="<?= $aSopranosBranches['phone']; ?>" href="tel:<?= $aSopranosBranches['phone']; ?>"><?= $aSopranosBranches['phone']; ?></a></li>                

                </ul>

                <ul class="nav__items">

                    <h3 class="nav__header">Follow</h3>

                    <li class="nav__item"><a class="icon__twitter_link" title="Twitter" target="_blank" href="#">Twitter</a></li>    

                    <li class="nav__item"><a class="icon__facebook_link" title="Facebook" target="_blank" href="#">Facebook</a></li>    

                    <li class="nav__item"><a class="icon__instagram_link" title="Instagram" target="_blank" href="#">Instagram</a></li>      

                    <li class="nav__item"><a class="icon__pinterest_link" title="pinterest" target="_blank" href="#">Pinterest</a></li>    

                </ul>

                <ul class="nav__items">

                    <h3 class="nav__header">Legal</h3>

                    <li class="nav__item"><a class="nav__item_link" title="Terms of Service" href="http://localhost:8080/sopranos/terms.php">Terms</a></li>    

                    <li class="nav__item"><a class="nav__item_link" title="Privacy" href="http://localhost:8080/sopranos/privacy.php">Privacy</a></li>                

                </ul>
                            
            </nav>

            <div class="footer__legal">
            
                <div class="site__copyright">Copyright &copy; <?php echo date("Y")?> Sopranos Pizzabar. All Rights Reserved.</div>

            </div>

        </div>

    </div>

</footer>