angular.module('diplomski-projekt').controller('requestsCtrl', function($scope, $rootScope, $routeParams, $http, $timeout, cfpLoadingBar) {
  $scope.start = function() {
    cfpLoadingBar.start();
  };

  $scope.complete = function () {
    cfpLoadingBar.complete();
  }

  $scope.start();

  $scope.requestId = $routeParams.id;
  if ($scope.requestId) {
    $rootScope.title_detail = ' ' + $scope.requestId;
  }

  $(document).ready(function() {
    $timeout(function() {
      $scope.complete();
    }, 125);
  });
});
