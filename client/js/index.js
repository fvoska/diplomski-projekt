var app = angular.module('diplomski-projekt', ['ngRoute']);

app.config(function($routeProvider, $locationProvider) {
  // Set up routes.
  $routeProvider
    .when('/', {
      navId: 'home',
      templateUrl: '/views/home.html',
      title: 'home'
    })
    .when('/page1', {
      navId: 'page1',
      templateUrl: '/views/page1.html',
      title: 'page1'
    })
    .when('/page2', {
      navId: 'page2',
      templateUrl: '/views/page2.html',
      title: 'page2'
    })
    .when('/page3', {
      navId: 'page3',
      templateUrl: '/views/page3.html',
      title: 'page3'
    })
    .otherwise({
      redirectTo: '/'
    });

  // Use the HTML5 History API.
  $locationProvider.html5Mode(true);
});

app.controller('mainCtrl', function($scope) {

});

app.run(['$rootScope', '$route', function($rootScope, $route) {
  $rootScope.trans = trans;
  $rootScope.$on('$routeChangeSuccess', function() {
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
