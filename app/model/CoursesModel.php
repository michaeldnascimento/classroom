<?php

namespace app\model;

use app\api\Connections\Connection;
use Google\Model;
use Google\Service\Classroom;
use Google_Service_Classroom;
use Google_Service_Classroom_Student;
use Google_Service_Classroom_Teacher;
use Google_Service_Exception;

class CoursesModel
{

    private $client;
    /**
     * @var Google_Service_Classroom
     */

    public function __construct()
    {
        $connection = new Connection();
        $this->client = $connection->get_client();
    }

    public function GoogleServerClassroom()
    {
        return new Google_Service_Classroom($this->client);
    }

    public function listCourses()
    {

        $pageToken = NULL;
        $courses = array();

        do {
            $params = array(
                'pageSize' => 100,
                'pageToken' => $pageToken
            );
            $response = $this->GoogleServerClassroom()->courses->listCourses($params);
            $courses = array_merge($courses, $response->courses);
            $pageToken = $response->nextPageToken;
        } while (!empty($pageToken));

        if (count($courses) == 0) {
            return "No courses found.<br>\n";
        } else {
            //echo "<pre>";
            //print_r($courses);
            //foreach ($courses as $course) {
                //printf("%s (%s)<br>\n", $course->name, $course->id);
            //}
            return $courses;
        }
    }

    /**
     * @throws Google_Service_Exception
     */
    public function consultCourse()
    {

    $courseId = '368344528510';

    try {
        $course = $this->GoogleServerClassroom()->courses->get($courseId);
        // printf("Course '%s' found.\n", $course->name);
        printf("%s (%s)<br>\n", $course->getName(), $course->getId(), $course->getAlternateLink( ));

        echo "alternateLink         =" . $course->getAlternateLink( ).'<br>';
        echo "calendarId            =" . $course['calendarId'].'<br>';
        echo "courseGroupEmail      =" . $course['courseGroupEmail'].'<br>';
        echo "courseState           =" . $course['courseState'].'<br>';
        echo "creationTime          =" . $course['creationTime'].'<br>';
        echo "description           =" . $course['description'].'<br>';
        echo "descriptionHeading    =" . $course['descriptionHeading'].'<br>';
        echo "enrollmentCode        =" . $course['enrollmentCode'].'<br>';
        echo "id                    =" . $course['id'].'<br>';
        echo "name                  =" . $course['name'].'<br>';
        echo "ownerId               =" . $course['ownerId'].'<br>';
        echo "room                  =" . $course['room'].'<br>';
        echo "section               =" . $course['section'].'<br>';
        echo "teacherGroupEmail     =" . $course['teacherGroupEmail'].'<br>';
        echo "updateTime            =" . $course['updateTime'].'<br>';
        echo "====================================================<br>";

        //return $course;

    } catch (Google_Service_Exception $e) {
        if ($e->getCode() == 404) {
            printf("Course with ID '%s' not found.\n", $courseId);
        } else {
            throw $e;
        }
    }

    }

    /**
     * @throws Google_Service_Exception
     */
    public function addProfessor()
    {

        $courseId = '368344528510';
        $teacherEmail = 'rogerio.santos@fm.usp.br';
        $teacher = new Google_Service_Classroom_Teacher(array(
            'userId' => $teacherEmail
        ));

        try {

            $teacher = $this->GoogleServerClassroom()->courses_teachers->create($courseId, $teacher);

            return ("User ". $teacher->profile->name->fullName. " was added as a teacher to the course with ID ". $courseId);

        } catch (Google_Service_Exception $e) {
            if ($e->getCode() == 409) {
                return ("User ". $teacherEmail. " is already a member of this course.");
            } else {
                throw $e;
            }
        }

    }

    /**
     * @throws Google_Service_Exception
     */
    public function addStudent()
    {

        $courseId = '368344528510';
        $enrollmentCode = 'abcdef';
        $studentEmail = 'rogerio.santos@fm.usp.br';
        $student = new Google_Service_Classroom_Student(array(
            'userId' => $studentEmail
        ));
        $params = array(
            'enrollmentCode' => $enrollmentCode
        );
        try {
            $student = $this->GoogleServerClassroom()->courses_students->create($courseId, $student, $params);
            printf("User '%s' was enrolled  as a student in the course with ID '%s'.\n",
                $student->profile->name->fullName, $courseId);
        } catch (Google_Service_Exception $e) {
            if ($e->getCode() == 409) {
                return "You are already a member of this course.";
            } else {
                throw $e;
            }
        }

    }


}
