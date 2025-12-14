 <?php
   #step1(예전방식)
   
   $servername = "localhost";
   $username = "kelly23";
   $password = "123456";
   $db = "kelly23";
   // Create connection
   $conn = mysqli_connect($servername, $username, $password,$db);

   // Check connection
   if (!$conn) {
     die("연결 실패: " . mysqli_connect_error());
   }
   //echo "연결 성공";
   
   #step2(MySQLi 방식)
   /*
   $servername = "localhost";
   $username = "kelly23";
   $password = "password";

   // Create connection
   $conn = new mysqli($servername, $username, $password);

   // Check connection
   if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
   }
   echo "Connected successfully";
   */

   #step3(PDO[PHP Data Objects] 방식)
   $servername = "localhost";
   $username = "kelly23";
   $password = "123456";

   try {
     $db = new PDO("mysql:host=$servername;dbname=kelly23", $username, $password);
     // set the PDO error mode to exception
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     //echo "PDO 연결성공";
   } catch(PDOException $e) {
     echo "PDO 연결실패: " . $e->getMessage();
   }
   
   
   
   /*
   $db = new PDO('mysql:host=' . $_DVWA[ 'db_server' ].';dbname=' . $_DVWA[ 'db_database' ].';port=' . $_DVWA['db_port'] . ';charset=utf8', $_DVWA[ 'db_user' ], $_DVWA[ 'db_password' ]);
   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
   */
?> 