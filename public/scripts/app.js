'use strict';

angular.module('cs431Project', [
    'ui.bootstrap',
    'ngResource',
    'ngStorage',
    'ngRoute',
    'angular-loading-bar',
    'ngAnimate'    
])
.config(['$routeProvider', '$httpProvider', function ($routeProvider, $httpProvider) {
    $routeProvider
        .when('/', {
            templateUrl: 'partials/home.html',
            controller: 'homeCtrl',
        })
        .when('/student', {
            templateUrl: 'partials/welcome.html',
            controller: 'mainCtrl',
            auth:{
                required: true,
                group: "student"
            }
        })
        .when('/student/courses', {
            templateUrl: 'partials/student_courses.html',
            controller: 'studentCoursesCtrl',
            auth:{
                required: true,
                group: "student"
            }
        })
        .when('/student/sessions', {
            templateUrl: 'partials/student_sessions.html',
            controller: 'studentSessionsCtrl',
            auth:{
                required: true,
                group: "student"
            }
        })
        .when('/student/session/:id', {
            templateUrl: 'partials/student_session.html',
            controller: 'studentSessionCtrl',
            auth:{
                required: true,
                group: "student"
            }
        })
        .when('/student/grades', {
            templateUrl: 'partials/student_grades.html',
            controller: 'studentGradesCtrl',
            auth:{
                required: true,
                group: "student"
            }
        })
        .when('/admin', {
            templateUrl: 'partials/welcome.html',
            controller: 'mainCtrl',
            auth:{
                required: true,
                group: "admin"
            }
        })
        .when('/admin/sessions', {
            templateUrl: 'partials/admin_sessions.html',
            controller: 'adminSessionsCtrl',
            auth:{
                required: true,
                group: "admin"
            }
        })
        .when('/admin/courses', {
            templateUrl: 'partials/admin_courses.html',
            controller: 'adminCoursesCtrl',
            auth:{
                required: true,
                group: "admin"
            }
        })
        .when('/admin/courses/add', {
            templateUrl: 'partials/admin_course_add.html',
            controller: 'adminCourseAddCtrl',
            auth:{
                required: true,
                group: "admin"
            }
        })
        .when('/admin/sessions/add', {
            templateUrl: 'partials/admin_session_add.html',
            controller: 'adminSessionAddCtrl',
            auth:{
                required: true,
                group: "admin"
            }
        })        
        .when('/admin/session/:id', {
            templateUrl: 'partials/admin_session.html',
            controller: 'adminSessionCtrl',
            auth:{
                required: true,
                group: "admin"
            }
        })           
        .when('/admin/course/:id', {
            templateUrl: 'partials/admin_course.html',
            controller: 'adminCourseCtrl',
            auth:{
                required: true,
                group: "admin"
            }
        })  
        .when('/admin/course/:id/session/add', {
            templateUrl: 'partials/admin_session_add.html',
            controller: 'adminSessionAddCtrl',
            auth:{
                required: true,
                group: "admin"
            }
        }) 
        .when('/faculty', {
            templateUrl: 'partials/welcome.html',
            controller: 'mainCtrl',
            auth:{
                required: true,
                group: "faculty"
            }
        })
        .when('/faculty/sessions', {
            templateUrl: 'partials/faculty_sessions.html',
            controller: 'facultySessionsCtrl',
            auth:{
                required: true,
                group: "faculty"
            }
        }) 
        .when('/faculty/session/:id', {
            templateUrl: 'partials/faculty_session.html',
            controller: 'facultySessionCtrl',
            auth:{
                required: true,
                group: "faculty"
            }
        })          
        .when('/faculty/session/:sessionID/grades/:studentID/', {
            templateUrl: 'partials/faculty_session_grades.html',
            controller: 'facultySessionGradesCtrl',
            auth:{
                required: true,
                group: "faculty"
            }
        }) 
        .when('/faculty/courses', {
            templateUrl: 'partials/faculty_courses.html',
            controller: 'facultyCoursesCtrl',
            auth:{
                required: true,
                group: "faculty"
            }
        })  
        .when('/faculty/course/:id', {
            templateUrl: 'partials/faculty_course.html',
            controller: 'facultyCourseCtrl',
            auth:{
                required: true,
                group: "faculty"
            }
        })              
        .when('/login', {
            templateUrl: 'partials/login.html',
            controller: 'authCtrl',
            auth:{
                required: false,
            }
        })
        .when('/unauthorized', {
            templateUrl: 'partials/unauthorized.html',
            controller: 'authCtrl',
            auth:{
                required: false,
            }
        })
        .otherwise({
            redirectTo: '/login'
        });

    $httpProvider.interceptors.push(['$q', '$location', '$localStorage', function($q, $location, $localStorage) {
            return {
                'request': function (config) {
                    config.headers = config.headers || {};
                    if ($localStorage.token) {
                        config.headers.Authorization = 'Bearer ' + $localStorage.token;
                    }
                    return config;
                },
                'responseError': function(response) {
                    if(response.status === 401 || response.status === 403) {
                        $location.path('/login');
                    }
                    return $q.reject(response);
                }
            };
        }]);
    }
]).run(function($rootScope, $location, auth) {
    $rootScope.$on("$routeChangeStart", function(event, nextRoute) {
        if(!auth.isAuthenticated()){
            $location.path('/login');        
        }
        else{
            if(nextRoute.auth){
                if(auth.getUser().role !== nextRoute.auth.group){
                    $location.path('/unauthorized');
                }                    
            }
        }        
    });
})
.filter('firstUpper', function() {
    return function(input, scope) {
        return input ? input.substring(0,1).toUpperCase()+input.substring(1).toLowerCase() : "";
    }
});