angular.module('exportProgress', []).controller('exportProcessCtrl', function ($scope, $http, $timeout){

	$scope.init = function(url){
		$scope.url = url;
		(function tick() {
			$http.post($scope.url).success(function(data){
				$scope.data = data;
				if (data.reload){
					location.reload();
				}
				if (data.redirect) {
					window.location = data.redirect;
				}
				$timeout(tick, 2000);
			});
		})();
	}
})

