<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>CPSC431 Project - Daniel Jordan, Eric Donaldson</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel='stylesheet' ng-href='css/loading-bar.css' type='text/css' media='all' />
    <link href="data:image/x-icon;base64,AAABAAEAEBAQAAEABAAoAQAAFgAAACgAAAAQAAAAIAAAAAEABAAAAAAAgAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAJ6TrAFy+8QCC0vgA7+/vAC6n7ACE0/gAS2RuAKbU5QDAwMAAv+3+ACek6gApoeYAAAAAAAAAAAAAAAAARERERERERABEREREREREAEREREREREQAREREeIRERABJmZSohUREAERERKolVEQAREREozUVRABJmZRDNlFUAEREREQzZRUAREREREM2W1BJmZmZRDNlxUREREREQzZVREREREREMzVJmZmZmURDM0REREREREQzRERERERERAMAAwAAAAMAAAADAAAAAwAAAAMAAAADAAAAAwAAAAMAAAADAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgAA" rel="icon" type="image/x-icon" />
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body ng-app="cs431Project">
<div data-ng-controller="mainCtrl">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" ng-href="#"><img src="img/logo.png"/></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a ng-href="#/{{getRole()}}">Home</a></li>
                    <li data-ng-hide="isLoggedIn()"><a ng-href="#/signin">Login</a></li>
                    <li data-ng-show="isLoggedIn()"><a ng-click="logout()">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-3" ng-show="isLoggedIn()">
                <p class="lead">{{getRole() | firstUpper}} Menu</p>
                <div ng-switch on="getRole()">
                    <div class="list-group" ng-switch-when="faculty">
                        <a ng-class="{active: isActive('/faculty')}" ng-href="#/faculty" class="list-group-item">Faculty Home</a>
                        <a ng-class="{active: isActive('/faculty/sessions')}" ng-href="#/faculty/sessions" class="list-group-item">My Classes</a>
                    </div>
                    <div class="list-group" ng-switch-when="student">
                        <a ng-class="{active: isActive('/student')}" ng-href="#/student" class="list-group-item">Student Home</a>
                        <a ng-class="{active: isActive('/student/sessions')}" ng-href="#/student/sessions" class="list-group-item">Session Enroll</a>
                        <a ng-class="{active: isActive('/student/courses')}" ng-href="#/student/courses" class="list-group-item">My Courses</a>
                        <a ng-class="{active: isActive('/student/grades')}" ng-href="#/student/grades" class="list-group-item">My Grades</a>
                    </div>
                    <div class="list-group" ng-switch-when="admin">
                        <a ng-class="{active: isActive('/admin')}" ng-href="#/admin" class="list-group-item">Admin Home</a>
                        <a ng-class="{active: isActive('/admin/courses')}" ng-href="#/admin/courses" class="list-group-item">View Courses</a>
                        <a ng-class="{active: isActive('/admin/sessions')}" ng-href="#/admin/sessions" class="list-group-item">View Sessions</a>
                    </div>

                    <div class="list-group" ng-switch-default>

                    </div>
                </div>
            </div>
            <div class="col-md-9" ng-view="">
            </div>
        </div>
    </div>
    <div class="container">
        <hr>
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Daniel Jordan, Eric Donaldson. CPSC431 Spring 15</p>
                </div>
            </div>
        </footer>
    </div>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular-route.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular-animate.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular-resource.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.0/ui-bootstrap.min.js"></script>
    <script src='lib/loading-bar.js'></script>
    <script src="lib/bootstrap.min.js"></script>
    <script src="lib/ngStorage.js"></script>
    <script src="scripts/app.js"></script>
    <script src="scripts/controllers.js"></script>
    <script src="scripts/services.js"></script>
</div>
</body>
</html>