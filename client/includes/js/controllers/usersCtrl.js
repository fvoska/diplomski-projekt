angular.module('diplomski-projekt').controller('usersCtrl', function($scope, $rootScope, $routeParams, $location, $http, $timeout, cfpLoadingBar) {
  $scope.start = function() {
    cfpLoadingBar.start();
  };
  $scope.complete = function() {
    cfpLoadingBar.complete();
  }
  $scope.start();

  $scope.userID = $routeParams.id;
  if ($scope.userID) {
    $rootScope.title_detail = ' ' + $scope.userID;
  };

  $(document).ready(function() {
    var location = $location.$$path.split('/');
    switch (location[location.length - 1]) {
      // Users list view.
      case 'users':
        // Header translations.
        $('#dataTables-users thead th').each(function() {
          var title = $(this).text();
          $(this).html(trans(title));
        });
        // Footer translations.
        $('#dataTables-users tfoot th').each(function() {
          var title = $(this).text();
          if (title != 'actions') {
            $(this).html('<input type="search" class="form-control input-sm footer-search" placeholder="' + trans('filter_by') + ' \'' + trans(title) + '\'" />');
          }
        });
        // Create DataTable.
        var table = $('#dataTables-users').DataTable({
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
          'ajax': 'core/index.php?module=json&action=getUsers',
          'columns': [{
            'data': 'id'
          }, {
            'data': 'first_appear'
          }, {
            'data': 'num_requests'
          }, {
            'data': 'last_ip'
          }, {
            'data': 'button'
          }],
          'columnDefs': [{
            'orderable': false,
            'targets': -1,
            'render': function(data, type, full, meta) {
              return '<a href="' + config.baseUrl + '/users/' + full.id + '" class="btn btn-primary">' + trans('details') + '</a>';
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
      // User's requests view.
      case 'requests':
        // Header translations.
        $('#dataTables-users-requests thead th').each(function() {
          var title = $(this).text();
          $(this).html(trans(title));
        });
        // Footer translations.
        $('#dataTables-users-requests tfoot th').each(function() {
          var title = $(this).text();
          if (title != 'actions') {
            $(this).html('<input type="search" class="form-control input-sm footer-search" placeholder="' + trans('filter_by') + ' \'' + trans(title) + '\'" />');
          }
        });
        // Create DataTable.
        var table = $('#dataTables-users-requests').DataTable({
          'order': [[ 2, 'desc' ]],
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
          'ajax': 'core/index.php?module=json&action=getUserRequests&id=' + $scope.userID,
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
        $http({
          method: 'GET',
          url: 'core/index.php?module=json&action=getUserDetails&id=' + $scope.userID
        })
        .then(function successCallback(response) {
          $scope.firstAppear = response.data.first_appear;
          $scope.numRequests = response.data.request_stats.num_requests;
          $scope.ipHistory = response.data.ip_history;
          $scope.errorTypes = response.data.error_stats;
          for (var i in $scope.errorTypes) {
            if ($scope.errorTypes.hasOwnProperty(i)) {
              $scope.errorTypes[i].label = trans($scope.errorTypes[i].label);
            }
          }
          $scope.activityMonthly = response.data.usage_stats.monthly;
          $scope.requestStats = response.data.request_stats;
          $scope.errorPercentage = $scope.requestStats.error_percentage;
          $scope.avgWordCount = $scope.errorPercentage.avg_word_count;
          $scope.avgErrorCount = $scope.errorPercentage.avg_error_count;

          if ($scope.errorTypes.length == 0) {
            $('#morris-donut-chart-error-types').css('height', '400px')
            Morris.Donut({
              element: 'morris-donut-chart-error-types',
              resize: true,
              data: [
                {
                  'label': trans('no_user_errors'),
                  'value': '1'
                }
              ],
              colors: ['#5cb85c'],
              formatter: function (y, data) { return '' }
            });
          }
          else {
            Morris.Donut({
              element: 'morris-donut-chart-error-types',
              resize: true,
              data: $scope.errorTypes,
              colors: ['#d9534f'],
              formatter: function (y, data) { return y }
            });
          }

          Morris.Area({
            element: 'morris-line-chart-activity-monthly',
            resize: true,
            data: $scope.activityMonthly,
            xkey: 'month',
            ykeys: ['requests'],
            labels: [trans('num_requests')],
            lineColors: ['#337ab7']
          });
          $scope.complete();
        }, function errorCallback(response) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
          $scope.complete();
        });
        break;
    }
  });
});
