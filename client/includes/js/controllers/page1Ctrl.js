angular.module('diplomski-projekt').controller('page1Ctrl', function($scope, $http, $timeout, cfpLoadingBar) {
  $scope.start = function() {
    cfpLoadingBar.start();
  };

  $scope.complete = function () {
    cfpLoadingBar.complete();
  }

  $scope.start();

  $(document).ready(function() {
    $timeout(function() {
      $scope.complete();
    }, 250);
  });
});
