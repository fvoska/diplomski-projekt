angular.module('diplomski-projekt').controller('usersCtrl', function($scope, $rootScope, $compile, $routeParams, $location, $http, $timeout, cfpLoadingBar) {
  $scope.start = function() {
    cfpLoadingBar.start();
  };
  $scope.complete = function() {
    cfpLoadingBar.complete();
  };
  $scope.start();

  $scope.userID = $routeParams.id;
  if ($scope.userID) {
    $rootScope.title_detail = ' ' + $scope.userID;
  }

  function long2ip(ip) {
    //  discuss at: http://phpjs.org/functions/long2ip/
    // original by: Waldo Malqui Silva
    //   example 1: long2ip( 3221234342 );
    //   returns 1: '192.0.34.166'

    if (!isFinite(ip))
      return false;

    return [ip >>> 24, ip >>> 16 & 0xFF, ip >>> 8 & 0xFF, ip & 0xFF].join('.');
  }

  function ip2long(IP) {
    //  discuss at: http://phpjs.org/functions/ip2long/
    // original by: Waldo Malqui Silva
    // improved by: Victor
    //  revised by: fearphage (http://http/my.opera.com/fearphage/)
    //  revised by: Theriault
    //   example 1: ip2long('192.0.34.166');
    //   returns 1: 3221234342
    //   example 2: ip2long('0.0xABCDEF');
    //   returns 2: 11259375
    //   example 3: ip2long('255.255.255.256');
    //   returns 3: false

    var i = 0;
    // PHP allows decimal, octal, and hexadecimal IP components.
    // PHP allows between 1 (e.g. 127) to 4 (e.g 127.0.0.1) components.
    IP = IP.match(
      /^([1-9]\d*|0[0-7]*|0x[\da-f]+)(?:\.([1-9]\d*|0[0-7]*|0x[\da-f]+))?(?:\.([1-9]\d*|0[0-7]*|0x[\da-f]+))?(?:\.([1-9]\d*|0[0-7]*|0x[\da-f]+))?$/i
    ); // Verify IP format.
    if (!IP) {
      return false; // Invalid format.
    }
    // Reuse IP variable for component counter.
    IP[0] = 0;
    for (i = 1; i < 5; i += 1) {
      IP[0] += !! ((IP[i] || '')
        .length);
      IP[i] = parseInt(IP[i]) || 0;
    }
    // Continue to use IP for overflow values.
    // PHP does not allow any component to overflow.
    IP.push(256, 256, 256, 256);
    // Recalculate overflow of last component supplied to make up for missing components.
    IP[4 + IP[0]] *= Math.pow(256, 4 - IP[0]);
    if (IP[1] >= IP[5] || IP[2] >= IP[6] || IP[3] >= IP[7] || IP[4] >= IP[8]) {
      return false;
    }
    return IP[1] * (IP[0] === 1 || 16777216) + IP[2] * (IP[0] <= 2 || 65536) + IP[3] * (IP[0] <= 3 || 256) + IP[4] * 1;
  }

  function cidrToRange(cidr) {
    var range = [2];
    cidr = cidr.split('/');
    if (cidr.length < 2) {
      range[0] = cidr[0];
      range[1] = cidr[0];
      return range;
    }
    var cidr_1 = parseInt(cidr[1]);
    range[0] = long2ip((ip2long(cidr[0])) & ((-1 << (32 - cidr_1))));
    start = ip2long(range[0]);
    range[1] = long2ip( start + Math.pow(2, (32 - cidr_1)) - 1);
    return range;
  }

  var networks = [
    {
      'name': '-',
      'min': '',
      'max': '',
      'mask': ''
    }
  ];

  var dayLabels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

  $scope.nets = networks;

  $(document).ready(function() {
    var location = $location.$$path.split('/');
    var table;
    switch (location[location.length - 1]) {
      // Users list view.
      case 'users':
        // Get networks.
        $http({
          method: 'GET',
          url: config.phpUrl + 'index.php?module=json&action=getNetworks'
        })
        .then(function successCallback(response) {
          var networkMasks = response.data.networks;
          for (var i = 0; i < networkMasks.length; i++) {
            var range = cidrToRange(networkMasks[i].mask);
            networks.push({
              'name': networkMasks[i].name,
              'min': range[0],
              'max': range[1],
              'mask': networkMasks[i].mask
            });
          }
          $scope.complete();
        }, function errorCallback(response) {
          $scope.complete();
        });
        // Header translations.
        $('#dataTables-users thead th').each(function() {
          var title = $(this).text();
          $(this).html(trans(title));
        });
        // Footer translations.
        $('#dataTables-users tfoot th').each(function() {
          var title = $(this).text();
          if (title == 'last_ip') {
            $(this).html('<div class="IPSearchContainer" ng-hide="enteredIPMin || enteredIPMax"><input id="lastIPSerach" ng-model="enteredIP" type="search" class="form-control input-sm footer-search" placeholder="' + trans('filter_by') + ' \'' + trans(title) + '\'" /></div>');
            $(this).append('<div class="IPSearchContainer IPSCs" ng-hide="enteredIP"><span class="IPSearchLabel">' + trans('ip_min') + '</span><input id="IPRangeMin" ng-model="enteredIPMin" type="search" class="form-control input-sm footer-search" placeholder="0.0.0.0" /></div>');
            $(this).append('<div class="IPSearchContainer IPSCs" ng-hide="enteredIP"><span class="IPSearchLabel">' + trans('ip_max') + '</span><input id="IPRangeMax" ng-model="enteredIPMax" type="search" class="form-control input-sm footer-search" placeholder="255.255.255.255" /></div>');
            $(this).append('<div class="IPSearchContainer" ng-hide="enteredIP"><label for="selectNet"><span class="IPSearchLabel">' + trans('ip_net') + '</span></label><select class="form-control" id="selectNet"><option ng-repeat="net in nets" value="{{ net.min }}-{{ net.max }}">{{ net.name }} {{ net.mask }}</option></select></div>');
            $(this).append('<div class="IPSearchContainer" ng-hide="enteredIP"><div class="checkbox SearchChk"><label><input id="ChkLatestIP" type="checkbox" value="" checked>' + trans('only_latest') + '</label></div></div>');
            $compile($('.IPSearchContainer'))($scope);
          } else if (title != 'actions') {
            $(this).html('<input type="search" class="form-control input-sm footer-search" placeholder="' + trans('filter_by') + ' \'' + trans(title) + '\'" />');
          }
        });

        // Create DataTable.
        table = $('#dataTables-users').DataTable({
          'order': [
            [2, 'desc']
          ],
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
          'ajax': {
            'url': config.phpUrl + 'index.php?module=json&action=getUsers',
            'data': function (d) {
              return $.extend({}, d, {
                'ipMin': $('#IPRangeMin').val(),
                'ipMax': $('#IPRangeMax').val(),
                'onlyLatestIP': $('#ChkLatestIP').is(':checked')
              });
            },
            'dataSrc': function(json) {
              $scope.$apply(function() {
                $scope.numRequests = json.count.requests;
                $scope.numErrors = json.count.errors;
                $scope.numErrorsDistinct = json.count.errors_distinct;
                $scope.avgProcessingTime = json.count.avg_processing_time;
              });
              return json.data;
            }
          },
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

        $('#selectNet').change(function() {
          var that = this;
          $scope.$apply(function() {
            $scope.enteredIPMin = $(that).val().split('-')[0];
            $scope.enteredIPMax = $(that).val().split('-')[1];
            setTimeout(function() {
              table.draw();
            }, 100);
          });
        });

        // Set search handler.
        table.columns().every(function() {
          var that = this;
          $('input', this.footer()).on('keydown', function(ev) {
            var id = $(this).attr('id');
            if (id == 'IPRangeMin' || id == 'IPRangeMax') {
              if (ev.keyCode == 13) {
                table.draw();
              }
            }
            else {
              if (ev.keyCode == 13) {
                that
                  .search(this.value)
                  .draw();
              }
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
        table = $('#dataTables-users-requests').DataTable({
          'order': [
            [2, 'desc']
          ],
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
          'ajax': config.phpUrl + 'index.php?module=json&action=getUserRequests&id=' + $scope.userID,
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
            url: config.phpUrl + 'index.php?module=json&action=getUserDetails&id=' + $scope.userID
          })
          .then(function successCallback(response) {
            $scope.firstAppear = response.data.first_appear;
            $scope.numRequests = response.data.request_stats.num_requests;
            $scope.ipHistory = response.data.ip_history;
            $scope.errorTypes = response.data.error_stats;
            var total = 0;
            for (var eti in $scope.errorTypes) {
              if ($scope.errorTypes.hasOwnProperty(eti)) {
                $scope.errorTypes[eti].label = trans($scope.errorTypes[eti].label);
                total += $scope.errorTypes[eti].value;
              }
            }
            $scope.activityMonthly = response.data.usage_stats.monthly;
            $scope.activityDaily = response.data.usage_stats.daily;
            var days = [];
            for (var dli = 0; dli < dayLabels.length; dli++) {
              var hasDay = false;
              var hasDayIndex = 0;
              for (var adi = 0; adi < $scope.activityDaily.length; adi++) {
                if (dayLabels[dli] == $scope.activityDaily[adi].day) {
                  hasDay = true;
                  hasDayIndex = adi;
                  break;
                }
              }
              if (hasDay) {
                days.push({
                  'day': trans($scope.activityDaily[hasDayIndex].day),
                  'requests': $scope.activityDaily[hasDayIndex].requests
                });
              }
              else {
                days.push({
                  'day': trans(dayLabels[dli]),
                  'requests': 0
                });
              }
            }
            $scope.activityHourly = response.data.usage_stats.hourly;
            var hours = [];
            for (var hi = 0; hi < 24; hi++) {
              var hasHour = false;
              var hasHourIndex = 0;
              for (var ahi = 0; ahi < $scope.activityHourly.length; ahi++) {
                if (hi == $scope.activityHourly[ahi].hour) {
                  hasHour = true;
                  hasHourIndex = ahi;
                  break;
                }
              }
              if (hasHour) {
                hours.push($scope.activityHourly[hasHourIndex]);
              }
              else {
                hours.push({
                  'hour': hi,
                  'requests': 0
                });
              }
            }
            $scope.requestStats = response.data.request_stats;
            $scope.errorPercentage = $scope.requestStats.error_percentage;
            $scope.avgWordCount = $scope.errorPercentage.avg_word_count;
            $scope.avgErrorCount = $scope.errorPercentage.avg_error_count;

            if ($scope.errorTypes.length === 0) {
              $('#morris-donut-chart-error-types').css('height', '400px');
              Morris.Donut({
                element: 'morris-donut-chart-error-types',
                resize: true,
                data: [{
                  'label': trans('no_user_errors'),
                  'value': '1'
                }],
                colors: ['#5cb85c'],
                formatter: function(y, data) {
                  return '';
                }
              });
            } else {
              Morris.Donut({
                element: 'morris-donut-chart-error-types',
                resize: true,
                data: $scope.errorTypes,
                colors: ['#d9534f'],
                formatter: function(y, data) {
                  return y + ' (' + (y/total * 100).toFixed(2) + '%)';
                }
              });
            }

            Morris.Area({
              element: 'morris-line-chart-activity',
              resize: true,
              data: $scope.activityMonthly,
              xkey: 'month',
              ykeys: ['requests'],
              labels: [trans('num_requests')],
              lineColors: ['#337ab7']
            });

            $('#selectGraph').change(function() {
              var $this = $(this);
              switch ($this.val()) {
                case 'months':
                  $('#morris-line-chart-activity').empty();
                  Morris.Area({
                    element: 'morris-line-chart-activity',
                    resize: true,
                    data: $scope.activityMonthly,
                    xkey: 'month',
                    ykeys: ['requests'],
                    labels: [trans('num_requests')],
                    lineColors: ['#337ab7']
                  });
                  break;
                case 'days':
                  $('#morris-line-chart-activity').empty();
                  Morris.Area({
                    element: 'morris-line-chart-activity',
                    resize: true,
                    parseTime: false,
                    xLabelMargin: 0,
                    data: days,
                    xkey: 'day',
                    ykeys: ['requests'],
                    labels: [trans('num_requests')],
                    lineColors: ['#337ab7']
                  });
                  break;
                case 'hours':
                  $('#morris-line-chart-activity').empty();
                  Morris.Area({
                    element: 'morris-line-chart-activity',
                    resize: true,
                    parseTime: false,
                    xLabelMargin: 0,
                    data: hours,
                    xkey: 'hour',
                    ykeys: ['requests'],
                    labels: [trans('num_requests')],
                    lineColors: ['#337ab7']
                  });
                  break;
              }
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
