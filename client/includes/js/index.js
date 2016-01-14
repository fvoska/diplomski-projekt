var app = angular.module('diplomski-projekt', ['ngRoute', 'chieffancypants.loadingBar', 'ngAnimate']);

// Animations
app.config(function(cfpLoadingBarProvider) {
  cfpLoadingBarProvider.includeSpinner = false;
});

app.config(function($routeProvider, $locationProvider) {
  // Set up routes.
  $routeProvider
    .when('/', {
      navId: 'home',
      templateUrl: config.baseUrl + '/views/home.html',
      title: 'home'
    })
    .when('/users', {
      navId: 'users',
      templateUrl: config.baseUrl + '/views/users.html',
      title: 'users'
    })
    .when('/users/:id', {
      navId: 'users',
      templateUrl: config.baseUrl + '/views/usersDetails.html',
      title: 'usersDetails'
    })
    .when('/users/:id/requests', {
      navId: 'users',
      templateUrl: config.baseUrl + '/views/usersRequests.html',
      title: 'usersRequests',
    })
    .when('/requests', {
      navId: 'requests',
      templateUrl: config.baseUrl + '/views/requests.html',
      title: 'requests',
    })
    .when('/requests/:id', {
      navId: 'requests',
      templateUrl: config.baseUrl + '/views/requestsDetails.html',
      title: 'requestsDetails',
    })
    .when('/errors', {
      navId: 'errors',
      templateUrl: config.baseUrl + '/views/errors.html',
      title: 'errors',
    })
    .when('/errors/group/:group', {
      navId: 'errors',
      templateUrl: config.baseUrl + '/views/errorsGroup.html',
      title: 'errorsGroup',
    })
    .otherwise({ // TODO: 404
      redirectTo: '/'
    });

  // Use the HTML5 History API.
  $locationProvider.html5Mode(true);
});

app.run(['$rootScope', '$route', function($rootScope, $route) {
  // All routes should have access to config and translations.
  $rootScope.config = config;
  $rootScope.trans = trans;

  // Clean route details at route change start.
  $rootScope.$on('$routeChangeStart', function() {
    $rootScope.title_detail = '';
  });

  // Route changed.
  $rootScope.$on('$routeChangeSuccess', function() {
    // Update navbar.
    $rootScope.title = $route.current.title;
    if ($route.current.redirectTo == '/') {
      $('.sidebar-nav').find('.active').removeClass('active');
      $('#home').addClass('active');
    }
    else {
      $('.sidebar-nav').find('.active').removeClass('active');
      $('#' + $route.current.navId).addClass('active');
    }
  });
}]);
