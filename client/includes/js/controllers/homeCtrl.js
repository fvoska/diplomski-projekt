angular.module('diplomski-projekt').controller('homeCtrl', function($scope, $http, $timeout, cfpLoadingBar) {
  $scope.start = function() {
    cfpLoadingBar.start();
  };

  $scope.complete = function () {
    cfpLoadingBar.complete();
  };

  $scope.start();

  $(document).ready(function() {
    $http({
      method: 'GET',
      url: config.phpUrl + 'index.php?module=json&action=getSummary'
    })
    .then(function successCallback(response) {
      $scope.numUsers = response.data.count.users;
      $scope.numRequests = response.data.count.requests;
      $scope.numErrors = response.data.count.errors;
      $scope.numErrorsDistinct = response.data.count.errors_distinct;
      $scope.avgProcessingTime = response.data.count.avg_processing_time;
      $scope.complete();
    }, function errorCallback(response) {
      // called asynchronously if an error occurs
      // or server returns response with an error status.
      $scope.complete();
    });
  });
});
