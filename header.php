<header>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-3">
                <a href="index.php" class="branding"><img src="image/indento.svg" alt="Indento"></a>
                <a href="javascript:void(0)" class="hamburgerMenu"><i data-feather="menu"></i></a>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-9 nav-wrapper">
                <a href="javascript:void(0)" class="closeMobleMenu"><i data-feather="x"></i></a>
                <?php
                    if (basename($_SERVER['PHP_SELF']) === 'index.php') {
                        echo '<ul class="navigation">
                                <li><a href="#heroSection">Home</a></li>
                                <li><a href="#featuresSection">Features</a></li>
                                <li><a href="#pricingSection">Pricing Plan</a></li>
                                <li><a href="#contactusSection">Contact Us</a></li>
                            </ul>
                            <ul class="registration">
                                <li><a href="http://indentoapp.com/app/" class="btn btn-primary">Sign In</a></li>
                            </ul>';
                    } else {
                        echo '<ul class="registration fullwidth">
                                <li><a href="index.php">Go Back</a></li>
                                <li><a href="http://indentoapp.com/app/" class="btn btn-primary">Sign In</a></li>
                            </ul>';
                    }
                ?>
            </div>
        </div>
    </div>
</header>