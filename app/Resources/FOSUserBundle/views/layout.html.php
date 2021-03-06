<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/x-icon" href="<?php echo $view['assets']->getUrl('favicon.png') ?>" />

        <title><?php $view['slots']->output('title', 'AVCC - WeAreAVP') ?></title>
        <script src="<?php echo $view['assets']->getUrl('js/jquery.min.js') ?>"></script>

        <script src="<?php echo $view['assets']->getUrl('js/tinymce/tinymce.min.js') ?>"></script>
        <script src="<?php echo $view['assets']->getUrl('js/tooltip.js') ?>"></script>
        <script src="<?php echo $view['assets']->getUrl('js/popover.js') ?>"></script>
        <script src="<?php echo $view['assets']->getUrl('lightgallery/js/lightslider.js') ?>"></script>


        <!-- Bootstrap core CSS -->
        <?php $view['slots']->start('stylesheets') ?>
        <!--<link href="<?php // echo $view['assets']->getUrl('lightgallery/css/lightgallery.min.css')    ?>" type="text/css" rel="stylesheet" />-->
        <link href="<?php echo $view['assets']->getUrl('lightgallery/css/lightslider.css') ?>" type="text/css" rel="stylesheet" />

        <!--{#						<link href="{{ asset('css/bootstrap.css') }}" type="text/css" rel="stylesheet" />#}-->
        <link href="<?php echo $view['assets']->getUrl('css/metro-bootstrap.min.css') ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo $view['assets']->getUrl('css/metro-bootstrap-responsive.min.css') ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo $view['assets']->getUrl('css/iconFont.min.css') ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo $view['assets']->getUrl('css/chosen.css') ?>" type="text/css" rel="stylesheet" />
        <!--<link href="<?php // echo $view['assets']->getUrl('css/bootstrap.css')              ?>" type="text/css" rel="stylesheet" />-->
        <link href="<?php echo $view['assets']->getUrl('css/modal.css') ?>" type="text/css" rel="stylesheet" />
        <!-- Custom styles for this template -->
        <link href="<?php echo $view['assets']->getUrl('bundles/applicationfront/css/style.css') ?>" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/jquery.mCustomScrollbar.css') ?>" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css" />
        <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/jquery.iviewer.css') ?>" />

        <link href="<?php echo $view['assets']->getUrl('css/basic.css') ?>" type="text/css" rel="stylesheet" />
<!--        <link href="<?php echo $view['assets']->getUrl('css/basic_ie.css') ?>" type="text/css" rel="stylesheet" />-->
        <?php $view['slots']->stop() ?>
        <?php $view['slots']->output('stylesheets') ?>

        <script src="<?php echo $view['assets']->getUrl('js/jquery.maskedinput.js') ?>"></script>
        <script src="<?php echo $view['assets']->getUrl('js/modal.js') ?>"></script>
        <script src="<?php echo $view['assets']->getUrl('js/jquery.dataTables.min.js') ?>"></script>
        <script
            src="//d2wy8f7a9ursnm.cloudfront.net/bugsnag-2.min.js"
            data-apikey="278dc8db47730e343f681cc0df041c33">
        </script>
    </head>

    <body class="metro">
        <?php if ($view->container->getParameter('qa_instance')) { ?>
            <div class="qa-instance">
                <h2 class="qa_heading">QA</h2>
            </div>         
        <?php } ?>
        <header class="bg-dark nav-container">
            <div class="navigation-bar dark fixed-top shadow">
                <div class="navigation-bar-content container">
                    <a href="<?php echo $view['router']->generate('_welcome') ?>" class="element"> AVCC </a>
                    <span class="element-divider"></span>

                    <?php if ($app->getUser()): ?>
                        <a class="element1 pull-menu" href="#"></a>
                        <ul class="element-menu place-right" style="">
                            <li>
                                <a class="dropdown-toggle" href="#">Welcome, <?php echo $app->getUser()->getName(); ?>&nbsp;&nbsp;</a>
                                <ul class="dropdown-menu dark" data-role="dropdown">
                                    <li><a href="<?php echo $view['router']->generate('fos_user_profile_show') ?>">Profile</a></li>
                                    <li><a href="<?php echo $view['router']->generate('fos_user_change_password') ?>">Change Password</a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo $view['router']->generate('fos_user_security_logout') ?>">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                        <?php if ($view['security']->isGranted('ROLE_MANAGER')): ?>
                            <a class="element1 pull-menu" href="#"></a>
                            <ul class="element-menu place-right" style="">
                                <li>
                                    <a class="dropdown-toggle" href="#"><span class="icon-cog"></span> Settings
                                    </a>
                                    <ul class="dropdown-menu dark" data-role="dropdown">
                                        <li><a href="<?php echo $view['router']->generate('users') ?>">Users</a></li>
                                        <?php if ($view['security']->isGranted('ROLE_SUPER_ADMIN')): ?>
                                            <li><a href="<?php echo $view['router']->generate('organizations') ?>">Organization</a></li>
                                        <?php endif; ?>
                                        <li><a href="<?php echo $view['router']->generate('field_settings') ?>">Field Settings</a></li>
                                        <li><a href="<?php echo $view['router']->generate('field_settings_backup') ?>">Enable Backup</a></li>
                                        <li><a class="" href="<?php echo $view['router']->generate('projects') ?>">Projects</a></li>
                                        <?php if ($view['security']->isGranted('ROLE_SUPER_ADMIN')): ?>
                                            <li><a class="" href="<?php echo $view['router']->generate('help_guide') ?>">Help Guide</a></li>
                                            <li><a class="" href="<?php echo $view['router']->generate('terms_of_service') ?>">Terms of Service</a></li>
                                            <?php if ($view->container->getParameter('enable_stripe')): ?>
                                                <li><a class="" href="<?php echo $view['router']->generate('plan') ?>">Plans</a></li>
                                                <li><a class="" href="<?php echo $view['router']->generate('subscription') ?>">Subscriptions</a></li>
                                                <?php
                                            endif;
                                        endif;
                                        ?>
                                        <?php if (!$view['security']->isGranted('ROLE_SUPER_ADMIN') && $view['security']->isGranted('ROLE_ADMIN')): ?>
                                            <li>
                                                <a class="" href="<?php echo $view['router']->generate('account') ?>">Account</a>
                                            </li>
                                        <?php endif ?>
                                    </ul>
                                </li>
                            </ul>
                        <?php endif; ?>
                        <?php if ($view['security']->isGranted('ROLE_SUPER_ADMIN')): ?>
                            <a class="element1 pull-menu" href="#"></a>
                            <ul class="element-menu place-right" style="">
                                <li>
                                    <a class="dropdown-toggle" href="#"><span class=""></span> Vocabularies
                                    </a>
                                    <ul class="dropdown-menu dark content mCustomScrollbar" data-role="dropdown" style="height: 480px;">
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_colors') ?>">Colors</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_aciddetectionstrips') ?>">Acid Detection Strips</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_bases') ?>">Bases</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_mediatypes') ?>">Media Types</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_formats') ?>">Formats</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_cassettessizes') ?>">Cassette Sizes</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_commercial') ?>">Commercial</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_diskdiameters') ?>">Disk Diameter</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_formatversions') ?>">Format Version</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_framerates') ?>">Frame Rates</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_mediadiameters') ?>">Media diameters</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_monostereo') ?>">Mono Stereo</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_noicereduction') ?>">Noise Reduction</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_printtypes') ?>">Print Types</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_recordingspeed') ?>">Recording Speed</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_recordingstandards') ?>">Recording Standards</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_reelcore') ?>">Reel Core</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_reeldiameters') ?>">Reel Diameters</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_slides') ?>">Sides</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_sounds') ?>">Sound</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_tapethickness') ?>">Tape Thickness</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_tracktypes') ?>">Track Types</a></li>
                                        <li><a href="<?php echo $view['router']->generate('vocabularies_pcollection') ?>">Parent Collection</a></li>
                                    </ul>
                                </li>
                            </ul>
                        <?php endif ?>

                        <?php if (!$view['security']->isGranted('ROLE_SUPER_ADMIN')): ?>
                            <a class="element1 pull-menu" href="#"></a>
                            <ul class="element-menu place-right" style="">
                                <li>
                                    <a class="" href="<?php echo $view['router']->generate('help_guide_list') ?>">Help Guide</a>
                                </li>
                            </ul>
                        <?php endif;
                        ?>
                        <a class="element1 pull-menu" href="#"></a>
                        <ul class="element-menu place-right" style="">
                            <li>
                                <a class="" href="<?php echo $view['router']->generate('report') ?>">Reports</a>

                            </li>
                        </ul>
                        <a class="element1 pull-menu" href="#"></a>
                        <ul class="element-menu place-right" style="">
                            <li>
                                <a class="" href="<?php echo $view['router']->generate('record_list') ?>">Records</a>

                            </li>
                        </ul>


                    <?php endif ?>
                </div>
            </div>
        </header>

        <div class="container" id="container" style="margin-top:20px;margin-bottom:20px;">
            <?php if ($app->getUser() && $app->getUser()->getOrganizations()): ?>
                <?php if ($app->getUser()->getOrganizations()->getIsPaid() == 0): ?>
                                                                                                                            <!--<p>Want more than 2500 records? Upgrade now by contacting avcc@avpreserve.com</p>-->
                <?php endif ?>
            <?php endif ?>
            <?php foreach ($app->getSession()->getFlashBag()->all() as $type => $messages): ?>
                <?php foreach ($messages as $message): ?>
                    <div class="flash-<?php echo $type; ?> text-success">
                        <?php echo $message; ?>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <?php $view['slots']->output('body') ?>

        </div> <!-- /container -->

        <?php $view['slots']->start('javascripts') ?>

        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/jquery.widget.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/jquery.mousewheel.js') ?>"></script>
        <script id="metro-js" type="text/javascript" src="<?php echo $view['assets']->getUrl('js/metro.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/chosen.jquery.js') ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/jquery.mCustomScrollbar.concat.min.js') ?>"></script>
        <script type="text/javascript" src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/jquery.iviewer.js') ?>"></script>

        <?php if ($view->container->getParameter('ga_tracking')) { ?>
            <script>
                (function (i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                            m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
                ga('create', '<?php echo $view->container->getParameter('ga_tracking'); ?>', 'auto');
                ga('send', 'pageview');
            </script>
        <?php } ?>
        <script type="text/javascript">
            setTimeout(function () {
                $('.text-success').hide();
            }, 5000);
            $(document).ready(function () {
                $('.formats_dd').chosen();
            });

        </script>

        <?php $view['slots']->stop() ?>
        <?php $view['slots']->output('javascripts') ?>
        <?php $view['slots']->output('view_javascripts') ?>
        <script type="text/javascript" id="cookieinfo" src="//cookieinfoscript.com/js/cookieinfo.min.js" data-cookie="CookieInfoScript" data-text-align="center" data-linkmsg="Check our Privacy Policy." data-moreinfo="/privacy-policy" data-close-text="Got it!">
        </script>
    </body>
</html>
