<?php

namespace App\Services\Akashic;

use App\Models\Course;
use App\Models\CourseRecom;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserCourse;
use App\Models\UserNotif;
use App\Models\UserSubject;
use App\Services\CourseService;
use App\Services\UserCourseService;
use App\Services\UserService;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Carbon\Carbon;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Webpatser\Uuid\Uuid;


class CourseRecommendationService
{
    private $akashicBaseUrl;

    private $courseService;
    private $userService;
    private $userCourseService;


    public function __construct(CourseService $courseService,
                                UserService $userService,
                                UserCourseService $userCourseService)
    {
        $this->akashicBaseUrl = env('AKASHIC_HOST','http://localhost:5000');

        $this->courseService = $courseService;
        $this->userService = $userService;
        $this->userCourseService = $userCourseService;
    }

    public function add_all_data() {
        $courses = Course::all();
        foreach($courses as $course) {
            $this->add_course($course);
        }

        $subjects = Subject::all();
        foreach ($subjects as $subject) {
            $this->add_subject($subject);
        }

        $users = User::all();
        foreach ($users as $user) {
            $this->add_user($user);
        }

        $userCourses = UserCourse::all();
        foreach ($userCourses as $userCourse) {
            $this->add_user_course($userCourse);
        }

        $courseRecoms = CourseRecom::all();
        foreach ($courseRecoms as $courseRecom) {
            $this->add_course_recom($courseRecom);
        }

        $userNotifs = UserNotif::all();
        foreach ($userNotifs as $userNotif) {
            $this->add_user_notif($userNotif);
        }

        /* RUN ENGINE */
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/run';
        try {
            $response = $client->request('GET', $uri);
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while running engine on course recom example.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            dd((string) $resp->getBody());
        }

        $resp = json_decode($response->getBody());
    }

    public function add_one_user_course($uc) {
        $this->add_user_course($uc);

        /* RUN ENGINE */
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/run';
        try {
            $response = $client->request('GET', $uri);
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while running engine on course recom example. Add one UC.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            dd((string) $resp->getBody());
        }
    }

    public function remove_one_user_course($uc) {
        $this->remove_user_course($uc);

        /* RUN ENGINE */
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/run';
        try {
            $response = $client->request('GET', $uri);
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while running engine on course recom example. Remove one UC.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            dd((string) $resp->getBody());
        }
    }

    public function add_one_user_subject($us) {
        $this->add_user_subject($us);

        /* RUN ENGINE */
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/run';
        try {
            $response = $client->request('GET', $uri);
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while running engine on course recom example. Add one UC.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            dd((string) $resp->getBody());
        }
    }

    public function remove_one_user_subject($us) {
        $this->remove_user_subject($us);

        /* RUN ENGINE */
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/run';
        try {
            $response = $client->request('GET', $uri);
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while running engine on course recom example. Remove one UC.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            dd((string) $resp->getBody());
        }
    }

    public function add_one_course_recom($cr) {
        $this->add_course_recom($cr);

        /* RUN ENGINE */
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/run';
        try {
            $response = $client->request('GET', $uri);
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while running engine on course recom example. Add one CR.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            dd((string) $resp->getBody());
        }
    }

    public function remove_one_course_recom($cr) {
        $this->remove_course_recom($cr);

        /* RUN ENGINE */
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/run';
        try {
            $response = $client->request('GET', $uri);
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while running engine on course recom example. Remove one UC.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            dd((string) $resp->getBody());
        }
    }

    public function add_one_user_notif($un) {
        $this->add_user_notif($un);

        /* RUN ENGINE */
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/run';
        try {
            $response = $client->request('GET', $uri);
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while running engine on course recom example. Add one CR.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            dd((string) $resp->getBody());
        }
    }

    public function remove_one_user_notif($un) {
        $this->remove_user_notif($un);

        /* RUN ENGINE */
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/run';
        try {
            $response = $client->request('GET', $uri);
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while running engine on course recom example. Remove one UC.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            dd((string) $resp->getBody());
        }
    }



    #############################################################
    #################### ACTUAL RULES FOR ADDING ################


    private function add_course(Course $course) {
        $uuid = str_replace("-", "", Uuid::generate()->string);
        $rule = (object) [
            "rule-name"     => "Create_new_Course__".$uuid,
            "salience"      => 2000,
            "run-once"      => true,
            "when"          => [],
            "then"          => [
                (object) [
                    "create" => (object) [
                        "model-id"          => "Course",
                        "reflect-on-web"    => false,
                        "data"              => (object) [
                            "id"         => $course->id,
                            "name"       => $course->name,
                            "start_date" => $course->start_date->format('d.m.Y.'),
                            "end_date"   => $course->end_date->format('d.m.Y.')
                        ]
                    ]
                ]
            ]
        ];
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/rules';
        try {
            $response = $client->request('POST',
                $uri,
                [
                    'json' => $rule,
                    'http_errors' => true
                ]
            );
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while adding 'Create_new_Course' rule.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            if ($resp) {
                dd((string) $resp->getBody());
            } else {
                dd("Body is empty");
            }
        }
        return json_decode($response->getBody());
    }

    private function add_subject(Subject $subject) {
        $uuid = str_replace("-", "", Uuid::generate()->string);
        $rule = (object) [
            "rule-name"     => "Create_new_Subject__".$uuid,
            "salience"      => 2000,
            "run-once"      => true,
            "when"          => [],
            "then"          => [
                (object) [
                    "create" => (object) [
                        "model-id"          => "Subject",
                        "reflect-on-web"    => false,
                        "data"              => (object) [
                            "id"    => $subject->id,
                            "name"  => $subject->name
                        ]
                    ]
                ]
            ]
        ];
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/rules';
        try {
            $response = $client->request('POST',
                $uri,
                [
                    'json' => $rule,
                    'http_errors' => true
                ]
            );
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while adding 'Create_new_Subject' rule.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            if ($resp) {
                dd((string) $resp->getBody());
            } else {
                dd("Body is empty");
            }
        }
        return json_decode($response->getBody());
    }


    private function add_user(User $user) {
        $uuid = str_replace("-", "", Uuid::generate()->string);
        $rule = (object) [
            "rule-name"     => "Create_new_User__".$uuid,
            "salience"      => 2000,
            "run-once"      => true,
            "when"          => [],
            "then"          => [
                (object) [
                    "create" => (object) [
                        "model-id"          => "User",
                        "reflect-on-web"    => false,
                        "data"              => (object) [
                            "id"    => $user->id,
                            "name"  => $user->name
                        ]
                    ]
                ]
            ]
        ];
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/rules';
        try {
            $response = $client->request('POST',
                $uri,
                [
                    'json' => $rule,
                    'http_errors' => true
                ]
            );
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while adding 'Create_new_User' rule.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            if ($resp) {
                dd((string) $resp->getBody());
            } else {
                dd("Body is empty");
            }
        }
        return json_decode($response->getBody());
    }

    ############# USER--COURSE READ/WRITE SECTION #####################

    private function add_user_course(UserCourse $userCourse) {
        $uuid = str_replace("-", "", Uuid::generate()->string);
        $rule = (object) [
            "rule-name"     => "Create_new_UserCourse__".$uuid,
            "salience"      => 2000,
            "run-once"      => true,
            "when"          => [],
            "then"          => [
                (object) [
                    "create" => (object) [
                        "model-id"          => "UserCourse",
                        "reflect-on-web"    => false,
                        "data"              => (object) [
                            "id"        => $userCourse->id,
                            "user_id"   => $userCourse->user_id,
                            "course_id" => $userCourse->course_id,
                            'grade'     => $userCourse->grade,
                            'rating'    => $userCourse->rating,
                            'enrolled'  => $userCourse->enrolled,
                        ]
                    ]
                ]
            ]
        ];
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/rules';
        try {
            $response = $client->request('POST',
                $uri,
                [
                    'json' => $rule,
                    'http_errors' => true
                ]
            );
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while adding 'Create_new_UserCourse' rule.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            if ($resp) {
                dd((string) $resp->getBody());
            } else {
                dd("Body is empty");
            }
        }
        return json_decode($response->getBody());
    }

    private function remove_user_course(UserCourse $userCourse) {
        $uuid = str_replace("-", "", Uuid::generate()->string);
        $rule = (object) [
            "rule-name"     => "Remove_UserCourse__".$uuid,
            "salience"      => 2000,
            "run-once"      => true,
            "when"          => [
                (object)["?uc<-" => "[UserCourse.id == ".$userCourse->id."]"]
            ],
            "then"          => [
                (object) [
                    "delete" => (object) [
                        "model-id"          => "UserCourse",
                        "reflect-on-web"    => false,
                        "data"              => (object) [
                            "user_id"   => $userCourse->user_id,
                            "id"        => $userCourse->id
                        ]
                    ]
                ]
            ]
        ];
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/rules';
        try {
            $response = $client->request('POST',
                $uri,
                [
                    'json' => $rule,
                    'http_errors' => true
                ]
            );
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while adding 'Remove_UserCourse' rule.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            if ($resp) {
                dd((string) $resp->getBody());
            } else {
                dd("Body is empty");
            }
        }
        return json_decode($response->getBody());
    }

    ############# USER--SUBJECT READ / WRITE SECTION #####################

    private function add_user_subject(UserSubject $userSubject) {
        $uuid = str_replace("-", "", Uuid::generate()->string);
        $rule = (object) [
            "rule-name"     => "Create_new_UserSubject__".$uuid,
            "salience"      => 2000,
            "run-once"      => true,
            "when"          => [],
            "then"          => [
                (object) [
                    "create" => (object) [
                        "model-id"          => "UserSubject",
                        "reflect-on-web"    => false,
                        "data"              => (object) [
                            "id"         => $userSubject->id,
                            "user_id"    => $userSubject->user_id,
                            "subject_id" => $userSubject->subject_id,
                            "rating"     => $userSubject->rating
                        ]
                    ]
                ]
            ]
        ];
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/rules';
        try {
            $response = $client->request('POST',
                $uri,
                [
                    'json' => $rule,
                    'http_errors' => true
                ]
            );
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while adding 'Create_new_UserSubject' rule.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            if ($resp) {
                dd((string) $resp->getBody());
            } else {
                dd("Body is empty");
            }
        }
        return json_decode($response->getBody());
    }


    private function remove_user_subject(UserSubject $userSubject) {
        $uuid = str_replace("-", "", Uuid::generate()->string);
        $rule = (object) [
            "rule-name"     => "Remove_UserSubject__".$uuid,
            "salience"      => 2000,
            "run-once"      => true,
            "when"          => [
                (object)["?us<-" => "[UserSubject.id == ".$userSubject->id."]"]
            ],
            "then"          => [
                (object) [
                    "delete" => (object) [
                        "model-id"          => "UserSubject",
                        "reflect-on-web"    => false,
                        "data"              => (object) [
                            "user_id"   => $userSubject->user_id,
                            "id"        => $userSubject->id
                        ]
                    ]
                ]
            ]
        ];
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/rules';
        try {
            $response = $client->request('POST',
                $uri,
                [
                    'json' => $rule,
                    'http_errors' => true
                ]
            );
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while adding 'Remove_UserSubject' rule.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            if ($resp) {
                dd((string) $resp->getBody());
            } else {
                dd("Body is empty");
            }
        }
        return json_decode($response->getBody());
    }


    ############# USER--RECOM READ / WRITE SECTION #####################

    private function add_course_recom(CourseRecom $courseRecom) {
        $uuid = str_replace("-", "", Uuid::generate()->string);
        $rule = (object) [
            "rule-name" => "Create_new_CourseRecom__".$uuid,
            "salience"  => 2000,
            "run-once"  => true,
            "when"      => [],
            "then"      => [
                (object) [
                    "create" => (object) [
                        "model-id"          => "CourseRecom",
                        "reflect-on-web"    => false,
                        "data"              => (object) [
                            "id"        => $courseRecom->id,
                            "user_id"   => $courseRecom->user_id,
                            "course_id" => $courseRecom->course_id,
                            "ignored"   => $courseRecom->ignored,
                            "accepted"  => $courseRecom->accepted,
                            'priority'  => $courseRecom->priority + 0.00001
                        ]
                    ]
                ]
            ]
        ];
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/rules';
        try {
            $response = $client->request('POST',
                $uri,
                [
                    'json' => $rule,
                    'http_errors' => true
                ]
            );
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while adding 'Create_new_CourseRecom' rule.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            if ($resp) {
                dd((string) $resp->getBody());
            } else {
                dd("Body is empty");
            }
        }
        return json_decode($response->getBody());
    }


    private function remove_course_recom(CourseRecom $courseRecom) {
        $uuid = str_replace("-", "", Uuid::generate()->string);
        $rule = (object) [
            "rule-name" => "Remove_CourseRecom__".$uuid,
            "salience"  => 2000,
            "run-once"  => true,
            "when"      => [
                (object)["?ur<-" => "[CourseRecom.id == ".$courseRecom->id."]"]
            ],
            "then"      => [
                (object) [
                    "delete" => (object) [
                        "model-id"          => "CourseRecom",
                        "reflect-on-web"    => false,
                        "data"              => (object) [
                            "user_id"   => $courseRecom->user_id,
                            "id"        => $courseRecom->id
                        ]
                    ]
                ]
            ]
        ];
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/rules';
        try {
            $response = $client->request('POST',
                $uri,
                [
                    'json' => $rule,
                    'http_errors' => true
                ]
            );
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while adding 'Remove_CourseRecom' rule.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            if ($resp) {
                dd((string) $resp->getBody());
            } else {
                dd("Body is empty");
            }
        }
        return json_decode($response->getBody());
    }


    ############# USER--NOTIF READ / WRITE SECTION #####################

    private function add_user_notif(UserNotif $userNotif) {
        $uuid = str_replace("-", "", Uuid::generate()->string);
        $rule = (object) [
            "rule-name" => "Create_new_UserNotif__".$uuid,
            "salience"  => 2000,
            "run-once"  => true,
            "when"      => [],
            "then"      => [
                (object) [
                    "create" => (object) [
                        "model-id"          => "UserNotif",
                        "reflect-on-web"    => false,
                        "data"              => (object) [
                            "id"        => $userNotif->id,
                            "user_id"   => $userNotif->user_id,
                            "course_id" => $userNotif->course_id,
                            "ignored"   => $userNotif->ignored,
                        ]
                    ]
                ]
            ]
        ];
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/rules';
        try {
            $response = $client->request('POST',
                $uri,
                [
                    'json' => $rule,
                    'http_errors' => true
                ]
            );
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while adding 'Create_new_UserNotif' rule.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            if ($resp) {
                dd((string) $resp->getBody());
            } else {
                dd("Body is empty");
            }
        }
        return json_decode($response->getBody());
    }


    private function remove_user_notif(UserNotif $userNotif) {
        $uuid = str_replace("-", "", Uuid::generate()->string);
        $rule = (object) [
            "rule-name" => "Remove_UserNotif__".$uuid,
            "salience"  => 2000,
            "run-once"  => true,
            "when"      => [
                (object)["?un<-" => "[UserNotif.id == ".$userNotif->id."]"]
            ],
            "then"      => [
                (object) [
                    "delete" => (object) [
                        "model-id"          => "UserNotif",
                        "reflect-on-web"    => false,
                        "data"              => (object) [
                            "user_id"   => $userNotif->user_id,
                            "id"        => $userNotif->id
                        ]
                    ]
                ]
            ]
        ];
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/rules';
        try {
            $response = $client->request('POST',
                $uri,
                [
                    'json' => $rule,
                    'http_errors' => true
                ]
            );
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while adding 'Remove_UserNotif' rule.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            if ($resp) {
                dd((string) $resp->getBody());
            } else {
                dd("Body is empty");
            }
        }
        return json_decode($response->getBody());
    }

}
