<!DOCTYPE html>
<html lang="en" ng-app="diplomski-projekt">
<?php //$base_url = "/grupa84/app/"; ?>
<?php $base_url = "/diplomski/client/"; ?>
<head>
    <base href="<?php echo $base_url; ?>">

    <meta name="google-site-verification" content="NRwDBbCATquAIURcWzzxIZFPnaPnjS811EU_pdWnsEg" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title ngCloak>{{ trans(title) }} {{ title_detail }} - {{trans('application_title')}}</title>

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
    <script src="<?php echo $base_url; ?>includes/js/controllers/page2Ctrl.js"></script>
    <script src="<?php echo $base_url; ?>includes/js/controllers/page3Ctrl.js"></script>

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

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>Read All Messages</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-tasks fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-tasks">
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 1</strong>
                                        <span class="pull-right text-muted">40% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                            <span class="sr-only">40% Complete (success)</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 2</strong>
                                        <span class="pull-right text-muted">20% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                            <span class="sr-only">20% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 3</strong>
                                        <span class="pull-right text-muted">60% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                            <span class="sr-only">60% Complete (warning)</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 4</strong>
                                        <span class="pull-right text-muted">80% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                            <span class="sr-only">80% Complete (danger)</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>See All Tasks</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-tasks -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> New Comment
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="pull-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> Message Sent
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-tasks fa-fw"></i> New Task
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>See All Alerts</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?php echo $base_url; ?>login.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

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
                            <a href="#"><i class="fa fa-folder fa-fw"></i> {{ trans('pages23') }}<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
								<li>
									<a href="<?php echo $base_url; ?>page2" id="page2"><i class="fa fa-table fa-fw"></i> {{ trans('page2') }}</a>
								</li>
								<li>
									<a href="<?php echo $base_url; ?>page3" id="page3"><i class="fa fa-table fa-fw"></i> {{ trans('page3') }}</a>
								</li>
                            </ul>
                            <!-- /.nav-second-level -->
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
