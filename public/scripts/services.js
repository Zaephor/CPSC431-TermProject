'use strict';
angular.module('cs431Project')
.factory('api', ['$http', '$localStorage','$resource', function($http, $localStorage, $resource) {
    $http.defaults.useXDomain = true;
    var baseUrl = "http://cpsc431.discard.xyz"; 
    return {
        auth: $resource(baseUrl+'/api/login', {}, {
            login: {
                method: "POST",
                isArray: false
            }                   
        }),
        studentSessions: $resource(baseUrl+'/api/student/sessions', {}, {
            get: {
                method: "GET",
                isArray: false
            }                   
        }),  
        studentSession: $resource(baseUrl+'/api/student/session/:sessionID', {
            sessionID: '@id'
        }, {
            get: {
                method: "GET",
                isArray: false
            }            
        }),         
        studentEnroll: function(id){
            var resource = $resource(baseUrl+'/api/student/enroll/:sessionID', {
                sessionID: id
            }, {
                post: {
                    method: "POST",
                    isArray: false, 
                }            
            })
            return resource.post()
        },
        studentCourses: $resource(baseUrl+'/api/student/sessions', {}, {
            get: {
                method: "GET",
                isArray: false
            }                   
        }),  
        studentAssignments: $resource(baseUrl+'/api/student/assignments', {}, {
            get: {
                method: "GET",
                isArray: false
            }                   
        }), 
        studentAssignment: $resource(baseUrl+'/api/student/assignment/:assignment', {
            assignment: '@assignment'
        }, {
            put: {
                method: "PUT",
                isArray: false
            },
            get: {
                method: "GET",
                isArray: false
            }
        }),
        studentSyllabus: $resource(baseUrl+'/api/student/session/:sessionID/syllabus', {
            sessionID: '@sessionID'
        }, {
            get: {
                method: "GET",
                headers: {
                    accept: 'application/pdf'
                },
                responseType: 'arraybuffer',
                cache: true,
                transformResponse: function (data) {
                    var pdf;
                    if (data) {
                        pdf = new Blob([data], {
                            type: 'application/pdf'
                        });
                    }
                    return {
                        response: pdf
                    };
                }
            }
        }),
        adminSessions: $resource(baseUrl+'/api/admin/sessions', {}, {
            get: {
                method: "GET",
                isArray: false
            }                   
        }),  
        adminAddCourse: $resource(baseUrl+'/api/admin/course/add', {}, {
            add: {
                method: "PUT",
                isArray: false
            }                   
        }),
        adminAddSession: $resource(baseUrl+'/api/admin/session/add', {}, {
            add: {
                method: "PUT",
                isArray: false
            }                   
        }),
        adminCourses: $resource(baseUrl+'/api/admin/courses', {}, {
            get: {
                method: "GET",
                isArray: false
            }                   
        }),
        adminDepartments: $resource(baseUrl+'/api/admin/departments/all', {}, {
            get: {
                method: "GET",
                isArray: false
            }                   
        }),        
        adminFaculty: $resource(baseUrl+'/api/admin/faculty/all', {}, {
            get: {
                method: "GET",
                isArray: false
            }                   
        }),        
        adminCourseDelete: $resource(baseUrl+'/api/admin/course/:courseID/delete', {
            courseID: '@id'
        }, {
            delete: {
                method: "DELETE",
                isArray: false
            }          
        }), 
        adminSessionDelete: $resource(baseUrl+'/api/admin/session/:sessionID/delete', {
            sessionID: '@id'
        }, {
            delete: {
                method: "DELETE",
                isArray: false
            }          
        }), 
        adminSession: $resource(baseUrl+'/api/admin/session/:sessionID', {
            sessionID: '@id'
        }, {
            get: {
                method: "GET",
                isArray: false
            },
            modify: {
                method: "POST",
                isArray: false
            }      
        }),       
        adminCourse: $resource(baseUrl+'/api/admin/course/:courseID', {
            courseID: '@id'
        }, {
            get: {
                method: "GET",
                isArray: false
            },
            modify: {
                method: "POST",
                isArray: false
            }         
        }),
        facultySessions: $resource(baseUrl+'/api/faculty/sessions', {}, {
            get: {
                method: "GET",
                isArray: false
            }                   
        }),
        facultySession: $resource(baseUrl+'/api/faculty/session/:sessionID', {
            sessionID: '@id'
        }, {
            get: {
                method: "GET",
                isArray: false
            }
        }),
        facultySessionStudentGrades: $resource(baseUrl+'/api/faculty/grades/:studentID/:sessionID', {
            sessionID: '@sessionID',
            studentID: '@studentID'
        }, {
            get: {
                method: "GET",
                isArray: false
            }
        }),        
        facultyGrades: $resource(baseUrl+'/api/faculty/assignment/:assignmentID/modify', {
            assignmentID: '@assignmentID'
        }, {
            modify: {
                method: "PUT",
            }        
        }),         
        facultySessionStudents: $resource(baseUrl+'/api/faculty/session/:sessionID/students', {
            sessionID: '@id'
        }, {
            get: {
                method: "GET",
                isArray: false
            }
        }),        
        facultyCourse: $resource(baseUrl+'/api/faculty/course/:courseID', {
            courseID: '@id'
        }, {
            get: {
                method: "GET",
                isArray: false
            }
        })               
    };
}])
.factory('auth', ['$localStorage', function($localStorage) {
    return {
        getToken: function(){
            return $localStorage.token;
        },
        isAuthenticated: function(){
            return $localStorage.token ? true : false;
        },
        setUser: function(token){
            $localStorage.token = token;
        },
        getUser: function(){
            var user = {};
            var token = $localStorage.token;
            if(typeof token !== 'undefined'){
                user = JSON.parse(window.atob(token.split('.')[1]))
            }
            return user.user;
        },
        logout: function(success){
            delete $localStorage.token;
            success();
        }
    };
}]);