<!DOCTYPE html>
<html lang="en" ng-app="diplomski-projekt">
<?php $base_url = "/grupa84/app/"; ?>
<?php //$base_url = "/diplomski/client/"; ?>
<head>
    <base href="<?php echo $base_url; ?>">

    <meta name="google-site-verification" content="NRwDBbCATquAIURcWzzxIZFPnaPnjS811EU_pdWnsEg" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title ng-bind="trans(title) + title_detail + ' - ' + trans('application_title')" ngCloak>Diplomski projekt</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo $base_url; ?>includes/css/bootstrap.min.css" rel="stylesheet">

	<!-- MetisMenu CSS -->
    <link href="<?php echo $base_url; ?>includes/css/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo $base_url; ?>includes/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo $base_url; ?>includes/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Loading Bar -->
    <link href="<?php echo $base_url; ?>includes/css/loading-bar.min.css" rel="stylesheet" type="text/css" />

    <!-- DataTables CSS -->
    <link href="<?php echo $base_url; ?>includes/css/dataTables.bootstrap.css" rel="stylesheet">
    <!--<link href="<?php echo $base_url; ?>includes/css/fixedHeader.dataTables.min.css" rel="stylesheet">-->

    <!-- Morris Charts CSS -->
    <link href="<?php echo $base_url; ?>includes/css/morris.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo $base_url; ?>includes/css/style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.includes/js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery -->
    <script src="<?php echo $base_url; ?>includes/js/libs/jquery.min.js"></script>

    <!-- Angular -->
	<script src="<?php echo $base_url; ?>includes/js/libs/angular.min.js"></script>
    <script src="<?php echo $base_url; ?>includes/js/libs/angular-route.min.js"></script>
    <script src="<?php echo $base_url; ?>includes/js/libs/angular-animate.min.js"></script>
    <script src="<?php echo $base_url; ?>includes/js/libs/loading-bar.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo $base_url; ?>includes/js/libs/bootstrap.min.js"></script>

	<!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo $base_url; ?>includes/js/libs/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo $base_url; ?>includes/js/libs/sb-admin-2.js"></script>

    <!-- DataTables JavaScript -->
    <script src="<?php echo $base_url; ?>includes/js/libs/jquery.dataTables.min.js"></script>
    <script src="<?php echo $base_url; ?>includes/js/libs/dataTables.bootstrap.min.js"></script>
    <!--<script src="<?php echo $base_url; ?>includes/js/libs/dataTables.input.js"></script>-->
    <!--<script src="<?php echo $base_url; ?>includes/js/libs/dataTables.fixedHeader.min.js"></script>-->

    <!-- Morris Charts JavaScript -->
    <script src="<?php echo $base_url; ?>includes/js/libs/raphael.min.js"></script>
    <script src="<?php echo $base_url; ?>includes/js/libs/morris.min.js"></script>

    <!-- Config -->
    <script src="<?php echo $base_url; ?>includes/js/config.js"></script>

    <!-- Translations -->
    <script src="<?php echo $base_url; ?>includes/js/translations.js"></script>

    <!-- Main script -->
    <script src="<?php echo $base_url; ?>includes/js/index.js"></script>

    <!-- Controllers -->
    <script src="<?php echo $base_url; ?>includes/js/controllers/homeCtrl.js"></script>
    <script src="<?php echo $base_url; ?>includes/js/controllers/usersCtrl.js"></script>
    <script src="<?php echo $base_url; ?>includes/js/controllers/requestsCtrl.js"></script>
    <script src="<?php echo $base_url; ?>includes/js/controllers/errorsCtrl.js"></script>

</head>

<body>

    <div id="wrapper" class="ng-cloak" ngCloak>

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">{{ trans('toggle_nav') }}</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo $base_url; ?>index.html">{{trans('application_title')}}</a>
            </div>
            <!-- /.navbar-header -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="<?php echo $base_url; ?>" id="home" class="active"><i class="fa fa-home fa-fw"></i> {{ trans('home') }}</a>
                        </li>
                        <li>
                            <a href="<?php echo $base_url; ?>users" id="users"><i class="fa fa-user fa-fw"></i> {{ trans('users') }}</a>
                        </li>
                        <li>
                            <a href="<?php echo $base_url; ?>requests" id="requests"><i class="fa fa-file-text fa-fw"></i> {{ trans('requests') }}</a>
                        </li>
                        <li>
                            <a href="<?php echo $base_url; ?>errors" id="errors"><i class="fa fa-exclamation-circle fa-fw"></i> {{ trans('errors') }}</a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div ng-view id="contentContainer" class="fader"></div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

</body>

</html>
