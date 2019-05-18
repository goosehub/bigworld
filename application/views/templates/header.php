<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">

        <!-- For Mobile -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">

        <!-- Page Title -->
        <title><?php echo $page_title; ?></title>

        <!-- Google please read this -->
        <meta name="description" content="Big World - Talk with people inside Google Maps">

        <!-- Link to Favicon -->
        <link rel="icon" href="<?=base_url()?>resources/img/favicon.ico">

        <!-- Bootstrap -->
        <link href="<?=base_url()?>resources/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">

        <!-- Font Awesome -->
        <link href="<?=base_url()?>resources/font_awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab|Roboto|Righteous|Open+Sans|Baloo+Bhai|Fira+Mono|Chango" rel="stylesheet">

        <!-- jQuery -->
        <script src="<?=base_url()?>resources/jquery/jquery-3.1.1.min.js"></script>

        <!-- Bootstrap -->
        <script src="<?=base_url()?>resources/bootstrap/js/bootstrap.min.js"></script>

        <!-- JSColor -->
        <script src="<?=base_url()?>resources/jscolor/jscolor.min.js"></script>

        <!-- Embedica -->
        <script src="<?=base_url()?>resources/embedica/embedica.js"></script>

        <!-- Make data available to script -->
        <script>
        var user = <?php echo $user ? json_encode($user) : 'false'; ?>;
        var base_url = '<?=base_url()?>';
        var world_id = <?php echo isset($world) ? $world['id'] : 'false'; ?>;
        </script>

        <!-- Local Script -->
        <script src="<?=base_url()?>resources/script.js?<?php echo time(); ?>"></script>

        <!-- Define as share image -->
        <link rel="image_src" href="<?=base_url()?>resources/logos/screenshot.png" / >
        <meta property='og:image' content='<?=base_url()?>resources/img/screenshot.png'/>

        <!-- Thumbnail -->
        <meta property="og:image" content="<?=base_url()?>resources/img/screenshot.png" />

        <!-- Local Style -->
        <link href="<?=base_url()?>resources/style.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">

    </head>
    <body class="black-background-color">
        <!-- Facebook share -->
        <!-- Shit turned off for now -->
        <div id="fb-root"></div>
        <script>
/*        window.fbAsyncInit = function() {
            FB.init({
                appId      : '523758294469574',
                xfbml      : true,
                version    : 'v2.5'
            });
        };

        (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=523758294469574";
        fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));*/
        </script>