<?php
# Obviously don't use root/root in production :)
$username = "root";
$password = "root";
$host     = "localhost";
$db       = "speedrite";
$conn_string = "mysql:host=" . $host . ";dbname=" . $db;

try {
    # Connect to database
    $pdo_db = new PDO($conn_string, $username, $password);
    $pdo_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # Default is ERRMODE_SILENT

    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        # Query DB to check if entry_code value already exists
        $pdo_ecExists = $pdo_db->prepare('SELECT main.entry_code FROM main WHERE main.entry_code = :entry_code'); # LIMIT 1?
        $pdo_ecExists->execute(array('entry_code' => $_GET['entry_code']));
        $result = $pdo_ecExists->fetchColumn(); # Gets a single column value from a single row(or all rows)?

        # Return the result
        if ($result) {
            echo "The entry_code '" . $_GET['entry_code'] ."' is already in use!" ;
        } else { # $result is null
            echo "The entry_code '" . $_GET['entry_code'] ."' is avaliable!" ;
        }
    } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
        # Stores the submitted form data
        $pdo_storeEntry = $pdo_db->prepare('
            INSERT INTO `main` (
                `entry_code`,
                `name`,
                `email`,
                `phone`,
                `store`,
                `model_purchased`,
                `serial_number`,
                `nplate_guess`
            )
            VALUES (
                :entry_code,
                :name,
                :email,
                :phone,
                :store,
                  (SELECT id`
                  FROM `model`
                  WHERE`name` = :model_purchased),
                :serial_number,
                :nplate_guess
            )
        ');

        $pdo_storeEntry->execute(array(
            ':entry_code'      => $_POST['entry_code'],
            ':name'            => $_POST['name'],
            ':email'           => $_POST['email'],
            ':phone'           => $_POST['phone'],
            ':store'           => $_POST['store'],
            ':model_purchased' => $_POST['model_purchased'],
            ':serial_number'   => $_POST['serial_number'],
            ':nplate_guess'    => $_POST['nplate_guess']
        ));

        echo "Stored data successfully.";
    }
    # close the connection
    $pdo_db = null;

} catch (PDOException $e) {
    echo "I'm sorry, Dave. I'm afraid I can't do that." . "<br/>";
    echo 'ERROR: ' . $e->getMessage();
}