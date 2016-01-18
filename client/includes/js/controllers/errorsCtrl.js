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
        $http({
          method: 'GET',
          url: config.phpUrl + 'index.php?module=json&action=getErrorStats'
        })
        .then(function successCallback(response) {
          $scope.errorTypes = response.data.error_types;
          for (var i in $scope.errorTypes) {
            if ($scope.errorTypes.hasOwnProperty(i)) {
              $scope.errorTypes[i].label = trans($scope.errorTypes[i].label);
            }
          }
          $scope.errorCount = response.data.error_count;
          $scope.errorPercentage = $scope.errorCount.error_percentage;
          $scope.avgWordCount = $scope.errorPercentage.avg_word_count;
          $scope.avgErrorCount = $scope.errorPercentage.avg_error_count;

          $scope.frequentErrors = response.data.most_frequent;

          Morris.Donut({
            element: 'morris-donut-chart-error-types-total',
            resize: true,
            data: $scope.errorTypes,
            colors: ['#d9534f'],
            formatter: function (y, data) { return y }
          });
          $scope.complete();
        }, function errorCallback(response) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
          $scope.complete();
        });

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
          'ajax': config.phpUrl + 'index.php?module=json&action=getErrors',
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
      default:
        $http({
          method: 'GET',
          url: config.phpUrl + 'index.php?module=json&action=getErrorGroup&group=' + $scope.group
        })
        .then(function successCallback(response) {
          $scope.type = response.data.type;
          $scope.numOccurReq = response.data.num_occur_req;
          $scope.numOccur = response.data.num_occur;
          $scope.errorReqPercent = (response.data.num_occur_req * 100 / response.data.req_count).toFixed(2) + '%';

          // Header translations.
          $('#dataTables-errors-requests thead th').each(function() {
            var title = $(this).text();
            $(this).html(trans(title));
          });
          // Footer translations.
          $('#dataTables-errors-requests tfoot th').each(function() {
            var title = $(this).text();
            if (title != 'actions') {
              $(this).html('<input type="search" class="form-control input-sm footer-search" placeholder="' + trans('filter_by') + ' \'' + trans(title) + '\'" />');
            }
          });
          // Create DataTable.
          var table = $('#dataTables-errors-requests').DataTable({
            'order': [[ 0, 'desc' ]],
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
            'ajax': config.phpUrl + 'index.php?module=json&action=getErrorsRequests&group=' + $scope.group,
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
        }, function errorCallback(response) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
          $scope.complete();
        });
        break;
        break;
    }
  });
});
