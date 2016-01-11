var app = angular.module('diplomski-projekt', ['ngRoute', 'chieffancypants.loadingBar', 'ngAnimate'])
.config(function(cfpLoadingBarProvider) {
  cfpLoadingBarProvider.includeSpinner = false;
});
app.animation('.fader', function() {
  return {
    enter: function(element, done) {
      element.css('display', 'none');
      element.fadeIn(250, done);
      return function() {
        element.stop();
      }
    },
    leave: function(element, done) {
      element.fadeOut(250, done)
      return function() {
        element.stop();
      }
    }
  }
});

app.config(function($routeProvider, $locationProvider) {
  // Set up routes.
  $routeProvider
    .when('/', {
      navId: 'home',
      templateUrl: config.baseUrl + '/views/home.html',
      title: 'home'
    })
    .when('/page1', {
      navId: 'page1',
      templateUrl: config.baseUrl + '/views/page1.html',
      title: 'page1'
    })
    .when('/page2', {
      navId: 'page2',
      templateUrl: config.baseUrl + '/views/page2.html',
      title: 'page2'
    })
    .when('/page3', {
      navId: 'page3',
      templateUrl: config.baseUrl + '/views/page3.html',
      title: 'page3'
    })
    .otherwise({
      redirectTo: config.baseUrl + '/'
    });

  // Use the HTML5 History API.
  $locationProvider.html5Mode(true);
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
