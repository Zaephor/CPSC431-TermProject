'use strict';
angular.module('cs431Project')
    .controller('mainCtrl', ['$rootScope', '$scope', '$location', 'api', 'auth', function($rootScope, $scope, $location, api, auth) {
        $scope.user = auth.getUser();
        $scope.isLoggedIn = function(){
            return auth.isAuthenticated() ? true : false
        }
        $scope.getRole = function(){
            if(auth.getUser() == undefined){
                return false;
            }
            else{
                return auth.getUser().role;                
            }
        }
        $scope.logout = function(){
            auth.logout(function() {
                $location.path('/login');
            }, function() {
                $rootScope.error = 'Failed to logout';
            });
        }
        $scope.isActive = function(path) {
            if(path == $location.path()){
                return true;
            }
            return false;
        };
    }])
    .controller('homeCtrl', ['$rootScope', '$scope', '$location', 'api', 'auth', function($rootScope, $scope, $location, api, auth) {
        if(auth.isAuthenticated()){
            $location.path("/"+auth.getUser().role);    
        }
        else{
            $location.path('/login');
        }
    }])        
    .controller('authCtrl', ['$rootScope', '$scope', '$location', 'api', 'auth', function($rootScope, $scope, $location, api, auth) {
        $scope.login = function(){
            var formData = {
                email: $scope.email,
                password: $scope.password
            }
            api.auth.login(formData).$promise.then(function(res){
                auth.setUser(res.token);
                $location.path("/"+auth.getUser().role);    
            }, function(error){
                alert("Failed to login"); 
            })
        }
    }])
    .controller('studentSessionsCtrl', ['$rootScope', '$scope', '$location', 'api', 'auth', function($rootScope, $scope, $location, api, auth) {
        $scope.getSessions = function(){
            $scope.error = "";
            api.studentSessions.get().$promise.then(function(res){
                $scope.courses = res.data;
                $scope.enrolledCourses = $scope.getCourses();
            })
            .catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })            
        }
        $scope.getCourses = function(){ 
            $scope.error = "";
            var courses = [];
            api.studentCourses.get().$promise.then(function(res){
                var sessions = [];
                res.data.forEach(function(course){   
                    course.sessions.forEach(function(session){
                        if(session.students.length > 0){
                            sessions.push(session);
                        }
                    })
                    if(sessions.length > 0){
                        courses.push(course.id);
                    }
                    sessions = [];
                })
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })
            return courses;    
        }
        $scope.getSessions();
        $scope.enrolledCourses = $scope.getCourses();
        var isEnrolled = function(id){
            if($scope.enrolledCourses.indexOf(id) > -1){
                return true
            }
            else{
                return false
            }
        }
        $scope.enroll = function(session, course){
            if(isEnrolled(course)){
                alert("Error: You are already enrolled in a session for this course");
                return false;
            }
            else{
                api.studentEnroll(session).$promise
                .then(function(res){
                    $scope.enrolledCourses.push(course);
                }).catch(function(){
                    alert("Error, please try again");
                })  
            }
            return true;
        }
    }])
    .controller('studentSessionCtrl', ['$rootScope', '$scope', '$location','$routeParams', 'api', 'auth', function($rootScope, $scope, $location, $routeParams, api, auth) {
        $scope.token = auth.getToken();
        $scope.getSession = function(){
            $scope.error = "";
            api.studentSession.get({
                sessionID: $routeParams.id
            }).$promise
            .then(function(res){
                $scope.session = res.data;
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })            
        }
        $scope.getSession();
    }])
    .controller('studentCoursesCtrl', ['$rootScope', '$scope', '$location', 'api', 'auth', function($rootScope, $scope, $location, api, auth) {
        $scope.getCourses = function(){ 
            $scope.error = "";
            api.studentCourses.get().$promise.then(function(res){
                $scope.courses = [];
                var temp = {};
                var sessions = [];
                res.data.forEach(function(course){
                    temp = {
                        code: course.code,
                        department: course.department,
                        title: course.title,
                        unitval: course.unitval,
                        description: course.description,
                        id: course.id
                    }     
                    course.sessions.forEach(function(session){
                        if(session.students.length > 0){
                            sessions.push(session);
                        }
                    })
                    if(sessions.length > 0){
                        temp.sessions = sessions; 
                        $scope.courses.push(temp);
                    }
                    sessions = [];
                    temp={};
                })
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })  
        }
        $scope.getCourses();
    }])
    .controller('studentGradesCtrl', ['$rootScope', '$scope', '$location', 'api', 'auth', function($rootScope, $scope, $location, api, auth) {
        api.studentAssignments.get().$promise.then(function(res){
            $scope.assignments = [];
            res.data.forEach(function(course){
                if(course.assignments.length){
                    $scope.assignments.push(course);
                }
            })
        })
    }])
    .controller('adminSessionsCtrl', ['$rootScope', '$scope', '$location', 'api', 'auth', function($rootScope, $scope, $location, api, auth) {
        $scope.getSessions = function(){
            $scope.error = "";
            api.adminSessions.get().$promise.then(function(res){
                $scope.courses = res.data;
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })            
        }
        $scope.getSessions();
    }])
    .controller('adminCoursesCtrl', ['$rootScope', '$scope', '$location', 'api', 'auth', function($rootScope, $scope, $location, api, auth) {
        $scope.getCourses = function(){
            $scope.error = "";
            api.adminCourses.get().$promise.then(function(res){
                $scope.courses = res.data
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })              
        }
        $scope.getCourses();
    }])
    .controller('adminSessionCtrl', ['$rootScope', '$scope', '$location','$routeParams', 'api', 'auth', function($rootScope, $scope, $location, $routeParams, api, auth) {
        $scope.getSession = function(){
            $scope.error = "";
            api.adminSession.get({
                sessionID: $routeParams.id
            }).$promise
            .then(function(res){
                $scope.session = res.data;
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })            
        }
        $scope.deleteSession = function(id){
            api.adminSessionDelete.delete({
                sessionID: id
            }).$promise
            .then(function(res){
                if(res.status == 200){
                    $location.path('/admin/sessions');
                }
            }).catch(function(){
                alert("Error, please try again");
            })                       
        }
        $scope.getSession();
    }]) 
    .controller('adminCourseCtrl', ['$rootScope', '$scope', '$location', '$routeParams', 'api', 'auth', function($rootScope, $scope, $location, $routeParams, api, auth) {
        $scope.getCourse = function(){
            $scope.error = "";
            api.adminCourse.get({
                courseID: $routeParams.id
            }).$promise
            .then(function(res){
                $scope.course = res.data;
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })            
        }
        $scope.deleteCourse = function(){
            $scope.error = "";
            api.adminCourseDelete.delete({
                courseID: $routeParams.id
            }).$promise
            .then(function(res){
                if(res.status == 200){
                    $location.path('/admin/courses');
                }    
            }).catch(function(){
                alert("Error, please try again");
            })            
        }
        $scope.getCourse();
    }]) 
    .controller('adminCourseAddCtrl', ['$rootScope', '$scope', '$location', '$routeParams', 'api', 'auth', function($rootScope, $scope, $location, $routeParams, api, auth) {
        $scope.getDepartments = function(){
            $scope.error = "";
            api.adminDepartments.get().$promise
            .then(function(res){
                $scope.departments = res.data
                console.log(res)
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })            
        }
        $scope.course = {};
        $scope.addCourse = function(){
            return api.adminAddCourse.add({
                "department_id": $scope.course.department,
                "title": $scope.course.title,
                "description": $scope.course.description,
                "code": $scope.course.number,
                "unitval": $scope.course.units
            }).$promise
            .then(function(res){
                if(res.status == 200){
                    return true;                  
                }
            }).catch(function(){
                alert("Failed to add new course. Please try again");
            })
        }
        $scope.getDepartments();
    }]) 
    .controller('adminSessionAddCtrl', ['$rootScope', '$scope', '$location', '$routeParams', 'api', 'auth', function($rootScope, $scope, $location, $routeParams, api, auth) {
        $scope.session = {};
        $scope.getProfessors = function(){
            $scope.error = "";
            api.adminFaculty.get().$promise
            .then(function(res){
                $scope.professors = res.data
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })            
        }
        $scope.getCourse = function(){
            $scope.error = "";
            api.facultyCourse.get({
                courseID: $routeParams.id
            }).$promise
            .then(function(res){
                $scope.course = res.data;
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })            
        }
        $scope.addSession = function(){
            return api.adminAddSession.add({
                "course_id": $routeParams.id,
                "professor_id": $scope.session.professor,
                "begins_on": $scope.session.startDate,
                "ends_on": $scope.session.endDate,
                "room": $scope.session.room
            }).$promise
            .then(function(res){
                if(res.status == 200){
                    return true;
                }
            }).catch(function(){
                alert("Failed to add new course. Please try again");
            })
        }
        $scope.getCourse();
        $scope.getProfessors();
    }]) 
    .controller('facultySessionsCtrl', ['$rootScope', '$scope', '$location', 'api', 'auth', function($rootScope, $scope, $location, api, auth) {
        $scope.getSessions = function(){
            $scope.error = "";
            api.facultySessions.get().$promise.then(function(res){
                $scope.courses = [];
                res.data.forEach(function(course){
                    if(course.sessions.length){
                        $scope.courses.push(course);
                    }
                })
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })            
        }
        $scope.getSessions();
    }])
    .controller('facultySessionCtrl', ['$rootScope', '$scope', '$location','$routeParams', 'api', 'auth', function($rootScope, $scope, $location, $routeParams, api, auth) {
        $scope.getSession = function(){
            $scope.error = "";
            api.facultySession.get({
                sessionID: $routeParams.id
            }).$promise
            .then(function(res){
                $scope.session = res.data;
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })            
        }
        $scope.getStudents = function(){
            $scope.error = "";
            api.facultySessionStudents.get({
                sessionID: $routeParams.id
            }).$promise
            .then(function(res){
                $scope.students = res.data.students;
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })            
        }
        $scope.getSession();
        $scope.getStudents();
    }]) 
    .controller('facultySessionGradesCtrl', ['$http','$rootScope', '$scope', '$location','$routeParams', 'api', 'auth', function($http, $rootScope, $scope, $location, $routeParams, api, auth) {
        $scope.getGrades = function(){
            $scope.error = "";
            api.facultySessionStudentGrades.get({
                sessionID: $routeParams.sessionID,
                studentID: $routeParams.studentID                
            }).$promise
            .then(function(res){
                $scope.assignments = res.data;
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })            
        }
        $scope.getGrades();
        $scope.submitScore = function(id, score){
            if(score == null){
                score = 0;
            }
            return api.facultyGrades.modify({
                assignmentID: id
            },{
                "score": score
            }).$promise
            .then(function(res){
                if(res.data.score == score){
                    return true;
                }
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })  
        }
    }])            
    .controller('facultyCourseCtrl', ['$rootScope', '$scope', '$location', '$routeParams', 'api', 'auth', function($rootScope, $scope, $location, $routeParams, api, auth) {
        $scope.getCourse = function(){
            $scope.error = "";
            api.facultyCourse.get({
                courseID: $routeParams.id
            }).$promise
            .then(function(res){
                $scope.course = res.data;
            }).catch(function(){
                $scope.error = "Error: HTTP Call Failed. Please try again"
            })            
        }
        $scope.getCourse();
    }]);  