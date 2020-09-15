<?php

use Dingo\Api\Routing\Router;


$api = app(Router::class);

$root_group = function (Router $api) {
    $api->group(['namespace' => 'App\Http\Controllers'], function (Router $api) {

        # Auth API
        $api->post('auth/login', 'AuthController@login');

        # Users API
        $api->post('users', 'UserController@create');

        # Load all data into akashic engine
        $api->get('akashic','UserController@load_akashic_data');

        $api->group(['middleware' => 'auth.jwt'], function (Router $api) {


            # Auth API
            $api->post('auth/users/{user_id}/refresh-token', 'AuthController@refreshToken')->where(['user_id' => '[0-9]+']);

            # Users API
            $api->get('users/{user_id}',     'UserController@findById')->where(['user_id' => '[0-9]+']);
            $api->get('users',               'UserController@findAll');
            $api->post('users/{user_id}',    'UserController@changePassword')->where(['user_id' => '[0-9]+']);
            $api->delete('users/{user_id}',  'UserController@delete')->where(['user_id' => '[0-9]+']);

            # Courses API
            $api->post('courses',               'CourseController@create');
            $api->get('courses/{course_id}',    'CourseController@findById')->where(['course_id' => '[0-9]+']);
            $api->get('courses',                'CourseController@findAll');
            $api->put('courses/{course_id}',    'CourseController@update')->where(['course_id' => '[0-9]+']);
            $api->delete('courses/{course_id}', 'CourseController@delete')->where(['course_id' => '[0-9]+']);

            # UserCourses API
            $api->post('users/{user_id}/user-courses',          'UserCourseController@create')->where(['user_id' => '[0-9]+']);
            $api->get('users/{user_id}/user-courses/{uc_id}',   'UserCourseController@findById')->where(['user_id' => '[0-9]+', 'uc_id' => '[0-9]+']);
            $api->get('users/{user_id}/user-courses',           'UserCourseController@findAll')->where(['user_id' => '[0-9]+']);
            $api->put('users/{user_id}/user-courses/{uc_id}',   'UserCourseController@update')->where(['user_id' => '[0-9]+', 'uc_id' => '[0-9]+']);
            $api->delete('users/{user_id}/user-courses/{uc_id}','UserCourseController@delete')->where(['user_id' => '[0-9]+', 'uc_id' => '[0-9]+']);


            # Subjects API
            $api->post('subjects',                'SubjectController@create');
            $api->get('subjects/{subject_id}',    'SubjectController@findById')->where(['subject_id' => '[0-9]+']);
            $api->get('subjects',                 'SubjectController@findAll');
            $api->put('subjects/{subject_id}',    'SubjectController@update')->where(['subject_id' => '[0-9]+']);
            $api->delete('subjects/{subject_id}', 'SubjectController@delete')->where(['subject_id' => '[0-9]+']);


            # UserSubjects API
            $api->post('users/{user_id}/user-subjects',          'UserSubjectController@create')->where(['user_id' => '[0-9]+']);
            $api->get('users/{user_id}/user-subjects/{us_id}',   'UserSubjectController@findById')->where(['user_id' => '[0-9]+', 'us_id' => '[0-9]+']);
            $api->get('users/{user_id}/user-subjects',           'UserSubjectController@findAll')->where(['user_id' => '[0-9]+']);
            $api->put('users/{user_id}/user-subjects/{us_id}',   'UserSubjectController@update')->where(['user_id' => '[0-9]+', 'us_id' => '[0-9]+']);
            $api->delete('users/{user_id}/user-subjects/{us_id}','UserSubjectController@delete')->where(['user_id' => '[0-9]+', 'us_id' => '[0-9]+']);

            # UserRecomentadion API
            $api->post('users/{user_id}/user-recoms',          'CourseRecomController@create')->where(['user_id' => '[0-9]+']);
            $api->get('users/{user_id}/user-recoms/{cr_id}',   'CourseRecomController@findById')->where(['user_id' => '[0-9]+', 'cr_id' => '[0-9]+']);
            $api->get('users/{user_id}/user-recoms',           'CourseRecomController@findAll')->where(['user_id' => '[0-9]+']);
            $api->put('users/{user_id}/user-recoms/{cr_id}',   'CourseRecomController@update')->where(['user_id' => '[0-9]+', 'cr_id' => '[0-9]+']);
            $api->delete('users/{user_id}/user-recoms/{cr_id}','CourseRecomController@delete')->where(['user_id' => '[0-9]+', 'cr_id' => '[0-9]+']);

            # UserNotif API
            $api->post('users/{user_id}/user-notifs',          'UserNotifController@create')->where(['user_id' => '[0-9]+']);
            $api->get('users/{user_id}/user-notifs/{un_id}',   'UserNotifController@findById')->where(['user_id' => '[0-9]+', 'un_id' => '[0-9]+']);
            $api->get('users/{user_id}/user-notifs',           'UserNotifController@findAll')->where(['user_id' => '[0-9]+']);
            $api->put('users/{user_id}/user-notifs/{un_id}',   'UserNotifController@update')->where(['user_id' => '[0-9]+', 'un_id' => '[0-9]+']);
            $api->delete('users/{user_id}/user-notifs/{un_id}','UserNotifController@delete')->where(['user_id' => '[0-9]+', 'un_id' => '[0-9]+']);
        });
    });
};

$api->version('v1', $root_group);

