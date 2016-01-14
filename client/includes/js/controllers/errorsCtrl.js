angular.module('diplomski-projekt').controller('errorsCtrl', function($scope, $rootScope, $location, $routeParams, $http, $timeout, cfpLoadingBar) {
  $scope.start = function() {
    cfpLoadingBar.start();
  };
  $scope.complete = function() {
    cfpLoadingBar.complete();
  }
  $scope.start();

  $scope.errorID = $routeParams.id;
  if ($scope.errorID) {
    $rootScope.title_detail = ' ' + $scope.errorID;
  }

  $scope.group = $routeParams.group;
  if ($scope.group) {
    $rootScope.title_detail = ' ' + $scope.group;
  }

  $(document).ready(function() {
    var location = $location.$$path.split('/');
    switch (location[location.length - 1]) {
      // Errors list view.
      case 'errors':
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
          'ajax': 'getErrors.php',
          'columns': [{
            'data': 'suspicious'
          }, {
            'data': 'type',
            'render': function (data, type, full, meta) {
              return trans(full.type);
            }
          }, {
            'data': 'totalNumOccur'
          }, {
            'data': 'button'
          }],
          'columnDefs': [{
            'orderable': false,
            'targets': -1,
            'render': function(data, type, full, meta) {
              return '<a href="' + config.baseUrl + '/errors/group/' + full.suspicious + '" class="btn btn-primary">' + trans('details') + '</a>';
            }
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
        $scope.complete();
        break;
      // Details view by group.
      case 'group':
        break;
      // Details view by id.
      default:
        break;
    }
  });

  setTimeout(function() {
    $scope.complete();
  }, 250);
});
