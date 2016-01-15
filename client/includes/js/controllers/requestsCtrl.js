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
        // Header translations.
        $('#dataTables-requests thead th').each(function() {
          var title = $(this).text();
          $(this).html(trans(title));
        });
        // Footer translations.
        $('#dataTables-requests tfoot th').each(function() {
          var title = $(this).text();
          if (title != 'actions') {
            $(this).html('<input type="search" class="form-control input-sm footer-search" placeholder="' + trans('filter_by') + ' \'' + trans(title) + '\'" />');
          }
        });
        // Create DataTable.
        var table = $('#dataTables-requests').DataTable({
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
          'ajax': 'core/index.php?module=json&action=getRequests',
          'columns': [{
            'data': 'time'
          }, {
            'data': 'processing'
          }, {
            'data': 'numErrors'
          }, {
            'data': 'button'
          }],
          'columnDefs': [{
            'orderable': false,
            'targets': -1,
            'render': function(data, type, full, meta) {
              return '<a href="' + config.baseUrl + '/requests/' + full.id + '" class="btn btn-primary">' + trans('details') + '</a>';
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
      // Details view.
      default:
        // Details.
        $http({
          method: 'GET',
          url: 'core/index.php?module=json&action=getRequestDetails&id=' + $scope.requestID
        })
        .then(function successCallback(response) {
          $scope.requestID = response.data.id;
          $scope.reqUser = response.data.user;
          $scope.reqTime = response.data.time;
          $scope.reqProcessing = response.data.processing_time;
          $scope.reqErrors = response.data.num_errors;
          $scope.wordCount = response.data.word_count;

          $scope.complete();
        }, function errorCallback(response) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
          $scope.complete();
        });

        // Errors.
        // Header translations.
        $('#dataTables-requests-errors thead th').each(function() {
          var title = $(this).text();
          $(this).html(trans(title));
        });
        // Footer translations.
        $('#dataTables-requests-errors tfoot th').each(function() {
          var title = $(this).text();
          if (title != 'actions') {
            $(this).html('<input type="search" class="form-control input-sm footer-search" placeholder="' + trans('filter_by') + ' \'' + trans(title) + '\'" />');
          }
        });
        // Create DataTable.
        var table = $('#dataTables-requests-errors').DataTable({
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
          'ajax': 'core/index.php?module=json&action=getRequestErrors&id=' + $scope.requestID,
          'columns': [{
            'data': 'suspicious'
          }, {
            'data': 'type',
            'render': function (data, type, full, meta) {
              return trans(full.type);
            }
          }, {
            'data': 'numOccur'
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
        break;
    }
  });
});
