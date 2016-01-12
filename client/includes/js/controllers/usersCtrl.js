angular.module('diplomski-projekt').controller('usersCtrl', function($scope, $rootScope, $routeParams, $http, $timeout, cfpLoadingBar) {
  $scope.start = function() {
    cfpLoadingBar.start();
  };

  $scope.complete = function () {
    cfpLoadingBar.complete();
  }

  $scope.start();

  $scope.userId = $routeParams.id;
  if ($scope.userId) {
    $rootScope.title_detail = $scope.userId;
  }

  $(document).ready(function() {
    $timeout(function() {
      $scope.complete();
    }, 125);
  });
});
