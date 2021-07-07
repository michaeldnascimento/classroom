<?php

/** Esse é um é ação importante, é aqui que gera o token da api de forma automatica.
 * para funcionar é nescessario acessar o diretorio atraves do git, após isso executar o comando "php index2.php"
 * após executar, será gerada uma url para copiar e colar no navegador.
 * após liberar o acesso a api atraves do navegador, sera gerada um codigo na propria url.
 * copiando esse codigo e colando no git, a api vai responder se deu tudo certo, e se sim, o token.php sera gerado automaticamente.
 * concluindo esse processo, o escopo definido já estará liberado.
 *
 * Obs: Nescessario liberar o escopo da api, no console da sua conta conectada.
 *
 * Modificação: o projeto tem duas api, uma responsavel pelo email da comunicação e outro eventos, então foi feito a separação.
 *
 * @author Michael Douglas.
 */


require "../../vendor/autoload.php";

//if (php_sapi_name() != 'cli') {
    //throw new Exception('This application must be run on the command line.');
//}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 * @throws \Google\Exception
 * @throws Exception
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Gmail API PHP Quickstart');
    //$client->setScopes(Google_Service_Gmail::GMAIL_READONLY); // esse escopo é para apenas leitura de dados. Ex: Listar emails da caixa de entrada.
    //$client->setScopes(Google_Service_Classroom::CLASSROOM_COURSES); // esse escopo libera todas as ações de envios de email, leitura, alteração e exclusão.
    $client->setScopes(array(
            Google_Service_Classroom::CLASSROOM_COURSES,
            Google_Service_Classroom::CLASSROOM_COURSEWORK_ME,
            Google_Service_Classroom::CLASSROOM_TOPICS,
            Google_Service_Classroom::CLASSROOM_COURSEWORKMATERIALS,
            Google_Service_Classroom::CLASSROOM_ROSTERS,
            Google_Service_Classroom::CLASSROOM_ANNOUNCEMENTS,
            Google_Service_Classroom::CLASSROOM_COURSEWORK_STUDENTS,
            Google_Service_Classroom::CLASSROOM_GUARDIANLINKS_STUDENTS,
            Google_Service_Classroom::CLASSROOM_PROFILE_EMAILS,
            Google_Service_Classroom::CLASSROOM_STUDENT_SUBMISSIONS_ME_READONLY,
            Google_Service_Classroom::CLASSROOM_PUSH_NOTIFICATIONS)
    );
    $client->setAuthConfig('../../app/api/Credentials/credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = '../../app/api/Token/token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}


// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Classroom($client);

// Print the first 10 courses the user has access to.
$optParams = array(
    'pageSize' => 10
);
$results = $service->courses->listCourses($optParams);
//echo "<pre>";
// print_r($results);

if (count($results->getCourses()) == 0) {
    print "No courses found.<br>\n";
} else {
    print "Courses:<br>\n";
    foreach ($results->getCourses() as $course) {
        //printf("%s (%s)<br>\n", $course->getName(), $course->getId());
        echo "alternateLink         =" . $course['alternateLink'].'<br>';
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
    }
}
