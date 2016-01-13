angular.module('diplomski-projekt').controller('requestsCtrl', function($scope, $rootScope, $location, $routeParams, $http, $timeout, cfpLoadingBar) {
  $scope.start = function() {
    cfpLoadingBar.start();
  };
  $scope.complete = function() {
    cfpLoadingBar.complete();
  }
  $scope.start();

  $scope.requestID = $routeParams.id;
  if ($scope.requestID) {
    $rootScope.title_detail = ' ' + $scope.requestID;
  }

  $(document).ready(function() {
    var location = $location.$$path.split('/');
    switch (location[location.length - 1]) {
      // Requests list view.
      case 'requests':
        break;
      // Details view.
      default:
        // TODO: ajax
        // Header translations.
        $('#dataTables-errors thead th').each(function() {
          var title = $(this).text();
          $(this).html(trans(title));
        });
        // Footer translations.
        $('#dataTables-errors tfoot th').each(function() {
          var title = $(this).text();
          if (title != 'actions') {
            $(this).html('<input type="search" class="form-control input-sm footer-search" placeholder="' + trans('filter_by') + ' \'' + trans(title) + '\'" />');
          }
        });
        // Create DataTable.
        var table = $('#dataTables-errors').DataTable({
          'responsive': true,
          'language': {
            'lengthMenu': trans('lengthMenu'),
            'search': trans('search'),
            'zeroRecords': trans('zeroRecords'),
            'info': trans('info'),
            'infoEmpty': trans('infoEmpty'),
            'infoFiltered': trans('infoFiltered'),
            'processing': trans('processing'),
            'paginate': {
              'first': trans('first'),
              'last': trans('last'),
              'next': trans('next'),
              'previous': trans('previous')
            }
          },
          'processing': true,
          'serverSide': true,
          'ajax': 'getRequestsDetails.php?id=' + $scope.requestID,
          'columns': [{
            'data': 'id'
          }, {
            'data': 'suspicious'
          }, {
            'data': 'type'
          }, {
            'data': 'numOccur'
          }]
        });
        // Set search handler.
        table.columns().every(function() {
          var that = this;
          $('input', this.footer()).on('keydown', function(ev) {
            if (ev.keyCode == 13) {
              that
                .search(this.value)
                .draw();
            }
          });
        });
        break;
    }

    $timeout(function() {
      $scope.complete();
    }, 125);
  });
});
