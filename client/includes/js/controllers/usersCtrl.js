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
  }

  $scope.firstAppear = undefined;
  $scope.numRequests = undefined;
  $scope.ipHistory = undefined;

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
          'ajax': 'getUsers.php',
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
        break;
      // User's requests view.
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
          'ajax': 'getUsersRequests.php?id=' + $scope.userID,
          'columns': [{
            'data': 'id'
          }, {
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
        break;
      // Details view.
      default:
        $http({
          method: 'GET',
          url: 'getUsersDetails.php?id=' + $scope.userID
        })
        .then(function successCallback(response) {
          console.log(response);
          $scope.firstAppear = response.data.first_appear;
          $scope.numRequests = response.data.num_requests;
          $scope.ipHistory = response.data.ip_history;
          // this callback will be called asynchronously
          // when the response is available
        }, function errorCallback(response) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
        });
        // TODO: ajax
        Morris.Donut({
          element: 'morris-donut-chart-error-types',
          data: [{
            label: "Drugo",
            value: 12
          }, {
            label: "Pravopisna",
            value: 30
          }, {
            label: "Gramatička",
            value: 20
          }],
          colors: ['#d9534f']
        });

        Morris.Area({
          element: 'morris-line-chart-activity',
          data: [{
            year: '2006',
            requests: 100
          }, {
            year: '2007',
            requests: 75
          }, {
            year: '2008',
            requests: 50
          }, {
            year: '2009',
            requests: 75
          }, {
            year: '2010',
            requests: 50
          }, {
            year: '2011',
            requests: 75
          }, {
            year: '2012',
            requests: 100
          }],
          xkey: 'year',
          ykeys: ['requests'],
          labels: ['Broj zahtjeva'],
          lineColors: ['#337ab7']
        });
        break;
    }

    $timeout(function() {
      $scope.complete();
    }, 125);
  });
});
