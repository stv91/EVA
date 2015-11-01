/******************** SUBMIT FORM *************************************/

function doSubmit($http, url, data) {
	var req = {
      method: 'POST',
      url: url,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      transformRequest: function (obj) {
        var str = [];
        for (var p in obj) {
          var value = obj[p];
          if (typeof value == 'boolean')
            value = 'on';
          if (value)
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(value));
        }

        return str.join("&");
      },
      data: data
    };
    
    return {
        done : function (ok, err) {
            $http(req).success(ok).error(err);
        }
    };
}

/********************* ALERT MANAGER ************************/
function AlertManager() {
    this.alerts = {};
}

AlertManager.prototype.addAlert = function(id, title, desc, type){
    this.alerts[id] = {
        title : title,
        content : desc,
        type : type? type : 'danger',
		container : "#alert-place",
		duration : 3,
    };
};

AlertManager.prototype.showAlert = function ($alert, id) {
    $alert(this.alerts[id]);
};

/*************************************************************************/

function getUrlParams() {
  var query_string = {};
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split("=");
    if (typeof query_string[pair[0]] === "undefined") {
      query_string[pair[0]] = decodeURIComponent(pair[1]);
    }
    else if (typeof query_string[pair[0]] === "string") {
      var arr = [query_string[pair[0]], decodeURIComponent(pair[1])];
      query_string[pair[0]] = arr;
    }
    else {
      query_string[pair[0]].push(decodeURIComponent(pair[1]));
    }
  }
  return query_string;
};

