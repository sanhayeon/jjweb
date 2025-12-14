<?php
   session_start();
   date_default_timezone_set('Asia/Seoul');

   include __DIR__ . "/../includes/db.php";
   include __DIR__ . "/../includes/header.php";
   if(isset($_POST['mode'])) {
      $mode = $_POST['mode'];
      //echo "POST: ".$mode;
   } else {
      $mode = $_GET['mode'];
      //echo "GET: ".$mode;
   }
   //exit;
   class Auth
   {

      private $conn;
      private $db;
      function __construct($conn,$db)
      {
         //echo "데이터베이스와 연결합니다.";
         $this->conn = $conn;
         $this->db = $db;
         //exit;
      }

	
	
      function login($username,$password)
      {

         #3번까지 4번째부터는 3분간 락상태 유지
         $total_failed_login = 3;
         $lockout_time       = 1;
         $account_locked     = false;

         $data = $this->db->prepare( 'SELECT failed_login, last_login FROM users WHERE username = (:username) LIMIT 1;' );
         $data->bindParam( ':username', $username, PDO::PARAM_STR );
         $data->execute();
         $row = $data->fetch();
         
         if( ( $data->rowCount() == 1 ) && ( $row[ 'failed_login' ] >= $total_failed_login ) )  {
            echo "진입1";
            $last_login = strtotime( $row[ 'last_login' ] );
            $timeout    = $last_login + ($lockout_time * 60);
            $timenow    = time();


            if( $timenow < $timeout ) {
               $account_locked = true;
            }
         }
         
         $data = $this->db->prepare( 'SELECT * FROM users WHERE username = (:username) AND password = (:password) LIMIT 1;' );
         $data->bindParam( ':username', $username, PDO::PARAM_STR);
         $data->bindParam( ':password', $password, PDO::PARAM_STR );
         $data->execute();
         $row = $data->fetch();


		if( ( $data->rowCount() == 1 ) && ( $account_locked == false ) ) {
            echo "진입2";
         $avatar       = $row[ 'avatar' ];
         $failed_login = $row[ 'failed_login' ];
         $last_login   = $row[ 'last_login' ];

         $html .= "<p>Welcome to the password protected area <em>{$user}</em></p>";
         $html .= "<img src=\"{$avatar}\" />";
         echo $html;
         #exit;


         if( $failed_login >= $total_failed_login ) {
            $html .= "<p><em>Warning</em>: Someone might of been brute forcing your account.</p>";
            $html .= "<p>Number of login attempts: <em>{$failed_login}</em>.<br />Last login attempt was at: <em>{$last_login}</em>.</p>";

            echo $html;
         }


         $data = $this->db->prepare( 'UPDATE users SET failed_login = "0" WHERE username = (:username) LIMIT 1;' );
         $data->bindParam( ':username', $username, PDO::PARAM_STR );
         $data->execute();
	#쿠키적용 예
         #setcookie("cookie",$username,time()+(86400*30),"/");
         #setcookie("name","관리자",time()+(86400*30),"/");
		 #세션처리
		 $_SESSION["session"]=$username;
		 $_SESSION["name"]="admin";
		 $_SESSION["password"]=$row['$password'];
		 #generateSessionToken();
		if( isset( $_SESSION[ 'session_token' ] ) ) {
			unset( $_SESSION[ 'session_token' ] );
		}
		$_SESSION[ 'session_token' ] = md5( uniqid() );
		$_SESSION[ 'user_token' ] = $_SESSION[ 'session_token' ];
         echo "<script>alert('로그인 성공');</script>";
         echo "<script>location.href='/'</script>";
		 
		

	} else {
    sleep( rand( 2, 4 ) );

    $data = $this->db->prepare( 'UPDATE users SET failed_login = (failed_login + 1) WHERE username = (:username) LIMIT 1;' );
    $data->bindParam( ':username', $username, PDO::PARAM_STR );
    $data->execute();

    echo "<script>alert('아이디 또는 비밀번호가 일치하지 않습니다.');</script>";
    echo "<script>location.href='/auth/login.php';</script>";
}
      $data = $this->db->prepare( 'UPDATE users SET last_login = now() WHERE username = (:username) LIMIT 1;' );
      $data->bindParam( ':username', $username, PDO::PARAM_STR );
      $data->execute();
         /*
         #step2
         #안전해?
         #아이디 존재 여부만
         $query = "select * from users where username='$username'";
         $result = mysqli_query($this->conn,$query);
         $row = mysqli_fetch_assoc($result);

         if($result && mysqli_num_rows($result) ==1 && $row['failed_login'] >= $total_failed_login )
         {
            echo "<script>alert('당신은 락입니다.');</script>";
            #상태을 그대로 유지.....
            $last_login = strtotime( $row[ 'last_login' ] );
            #strtotime은 무슨 함수인가? 1752558563
            #Parse about any English textual datetime description into a Unix timestamp
            $timeout    = $last_login + ($lockout_time * 60);
            $timenow    = time();

            echo "<hr>";
            echo "<div class='container mt-3'>";
            echo "<h2></h2>";
            echo "<p>";
  
  
            echo "타임아웃 : ".$timeout." | ". date("Y-m-d H:i:s",$timeout)."<br>"; #1752472576
            echo "현재시간 : ".$timenow." | ". date("Y-m-d H:i:s",$timenow)."<br>"; #1752472444

            echo "<a href='/auth/login.php'>이동하기<br></a>";
            echo "</p>";
            echo "</div>";


            if( $timenow < $timeout ) {
               $account_locked = true;
            }

            #time은 무슨 함수인가?
            
            #echo "<script>location.href='/auth/login.php'</script>";
         
         } 
         #아이디와 비밀번호 체크 
         $query = "select * from users where username='$username' and password='$password'";
         //echo $query."<br>";

         $result = mysqli_query($this->conn,$query);
         $row = mysqli_fetch_assoc($result);

         if(mysqli_num_rows($result) == 1 and ( $account_locked == false )) {
            //echo ">>진입";
            $failed_login = $row[ 'failed_login' ];
            $last_login   = $row[ 'last_login' ];
            #3 >= 3
            if( $failed_login >= $total_failed_login ) {
               $html .= "당신의 사이트는 무차별 대입공격 흔적이 있습니다.<br>";
               $html .= "<p>Number of login attempts: <em>{$failed_login}</em>.<br />Last login attempt was at: <em>{$last_login}</em>.</p>";
               echo $html;
               //exit;
            }
            #3>0
            echo "카운트를 리셋합니다.";
            $query = "update users set failed_login = 0 where username='$username'";
            //echo $query;
            $result = mysqli_query($this->conn,$query);
            if($result) {
               #쿠키생성(로그인 유지)
               setcookie("username",$username,time()+(86400*30),"/");
               setcookie("name","관리자",time()+(86400*30),"/");
                  echo "<script>alert('업데이트 완료');</script>";

            } 

            echo "<script>location.href='/'</script>";

         } else {
            sleep( rand( 2, 4 ) );
            //echo "실패";
            $query = "update users set failed_login = failed_login+1 where username='$username'";
            echo "<br>";
            //echo $query;
            $result = mysqli_query($this->conn,$query);

            echo "<hr>";
            echo "<div class='container mt-3'>";
            echo "<h2></h2>";
            echo "<p>";
            echo $query;
            echo "</p>";
            echo "</div>";

         }

            $query = "update users set last_login = now() where username='$username'";
            echo "<br>";
            $result = mysqli_query($this->conn,$query);
         */
         /*
         #step1
         #안전해? 
         #데이터베이스에서 데이터 찾기
         $query = "select * from users where username='$username' and password='$password'";
         echo $query;

         $result = mysqli_query($this->conn,$query);
         if($result && mysqli_num_rows($result) == 1) {
            #echo "데이터가 있음";

            #쿠키생성(로그인 유지)
            setcookie("username",$username,time()+(86400*30),"/");
            setcookie("name","관리자",time()+(86400*30),"/");

            echo "<script>alert('로그인 성공');</script>";
            echo "<script>location.href='/'</script>";

            #세션생성(로그인 유지)


         }  else {
            sleep(rand(0,3));
            echo "<script>alert('로그인 실패');</script>";
            echo "<script>location.href='/auth/login.php'</script>";
         }
         */
         #변수와 일치여부만 확인
         /*
         echo $email;
         if($username == "jema10@naver.com" && $password == "123456") {
            echo "<script>alert('로그인 성공');</script>";
            echo "<script>location.href='/'</script>";
         } else  {
            echo "<script>alert('로그인 실패');</script>";
            echo "<script>location.href='/auth/login.php'</script>";
         }
         
         mysqli_close($this->conn);
         */
      
      
      }


      function logout()
      {
         
         setcookie("cookie", "", time() - 3600,"/");
         setcookie("name", "", time() - 3600,"/");
		 
		 
		 #세션 파괴
		 session_unset();
		 session_destroy();
		 unset( $_SESSION[ 'session_token' ] );
         echo "<script>alert('로그아웃');</script>";
         echo "<script>location.href='/'</script>";
      }

      function signup($username,$password)
      {
         $query = "insert into users(username,password) values('$username','$password')";
         echo "<b>쿼리문</b><br>";
         echo "<hr>";
         echo $query;
         #if (mysqli_query($conn, $query)) {         
         if (mysqli_query($this->conn, $query)) {
           echo "<script>alert('회원가입이 완료 되었습니다.')</script>";
           echo "<script>location.href='/auth/login.php';</script>";
         } else {
           echo "에러발생: " . $sql . "<br>" . mysqli_error($conn);
         }
         
      }
	  /*
	  #step1
	  function changepw($password1,$password2,$password3)
	  { 
		if ($password==$password2) {
			$query= "update users set password = '$password' where username='admin'";
			echo $query;
			$result = mysqli_query($this->conn,$query);
			if ($result) {
				echo "<script>alert('회원정보가 수정되었습니다');</script>";
				echo "<script>location.href='/auth/login.php';</script>";
			}else {
				echo "에러발생:".$sql."<br>".mysqli_error($conn);
			}
		 }else {
			echo "<script>alert('비밀번호가 다르다. 정신차려!!')</script>";
			echo "<script>location.href='/auth/login.php';</script>";
		 }
	
	  }*/
	  
	  #step2
		#HTTP_REFERE : 이전페이지 (http://kelly23.kr/auth/mypage.php인지 확인)
		#stripos() : PHP 함수로, 문자열 안에서 부분 문자열을 대소문자 구분없이 찾는 함수
		# !== false : 값이 있으면
	  /*	function changepw($password,$password2)
      {
         #step1
         
         if($password == $password2) {
            $query = "update users set password='$password' where username='admin'";
            echo "<b>쿼리문</b><br>";
            echo "<hr>";
            echo $query;

            #if (mysqli_query($conn, $query)) {         
            if (mysqli_query($this->conn, $query)) {
              echo "<script>alert('비밀번호가 수정되었습니다.')</script>";
              echo "<script>location.href='/auth/login.php';</script>";
            } else {
              echo "에러발생: " . $sql . "<br>" . mysqli_error($conn);
            }
         } else {
              echo "<script>alert('비밀번호가 다르다. 정신차려!!')</script>";
              echo "<script>location.href='/auth/mypage.php';</script>";
         } 
         
       
         #step2
         #HTTP_REFERE : 이전 페이지 (HTTP_REFERER : http://metaedunet.kr/auth/mypage.php)
         #stripos()함수 : PHP 함수로, 문자열 안에서 부분 문자열을 대소문자 구분 없이 찾는 함수
         # !== false : 있다면
         if( stripos( $_SERVER[ 'HTTP_REFERER' ] ,$_SERVER[ 'SERVER_NAME' ]) !== false ) {
            echo "HTTP_REFERER : ".$_SERVER[ 'HTTP_REFERER']."<br>";
            echo "SERVER_NAME : ".$_SERVER[ 'SERVER_NAME' ]."<br>";
            //exit;
            if($password == $password2) {
               $query = "update users set password='$password' where username='admin'";
               echo "<b>쿼리문</b><br>";
               echo "<hr>";
               echo $query;

               #if (mysqli_query($conn, $query)) {         
               if (mysqli_query($this->conn, $query)) {
                 echo "<script>alert('비밀번호가 수정되었습니다.')</script>";
                 echo "<script>location.href='/auth/login.php';</script>";
               } else {
                 echo "에러발생: " . $sql . "<br>" . mysqli_error($conn);
               }
            } else {
                 echo "<script>alert('비밀번호가 다르다. 정신차려!!')</script>";
                 echo "<script>location.href='/auth/mypage.php';</script>";
            }

         } else  {
               echo "외부공격";
               echo "<script>alert('서버가 아닌 다른곳에서의 요청은 불가능합니다.')</script>";
                echo "<script>location.href='/auth/mypage.php';</script>";
         } */
		#step3
		#입력한 현재 비밀번호가 맞는지 확인
		function changepw($password1,$password2,$password3)
		{ 
		$query="select * from users where username='admin'";
		echo $query;
		$result = mysqli_query($this->conn,$query);
		if (mysqli_num_rows($result)>0) {
			$row=mysqli_fetch_assoc($result);
			$chkPassword=$row['password'];
		}else {
			echo '사용자가 없습니다';
		}
		#맞으면 수정
		if ($password2==$password3 && $chkPassword==$password1) {
			echo '업데이트 성공';
			$query="update users set password='$password3' where username='admin'";
			$result= mysqli_query($this->conn,$query);
		}else {
			echo '비밀번호가 일치하지 않습니다, 또는 업데이트가 실패하였습니다';
		}
         
      }
      


		


   }
   

   $auth = new Auth($conn,$db);

   if($mode == "login") {
      $username=$_POST['username'];
      $password=$_POST['password'];
      $auth->login($username,$password);
   } elseif ($mode == "logout") {
      $auth->logout();
   } elseif ($mode == "signup") {
      $username=$_POST['username'];
      $password=$_POST['password'];
      $auth->signup($username,$password);
   } elseif ($mode == "changepw") {
      #$username=$_SESSION["session"]);
	  $password1=$_POST['password1'];
	  $password2=$_POST['password2'];
	  $password3=$_POST['password3'];
      $auth->changepw($password1,$password2,$password3);	  
   }
   
?>
<?php
   include __DIR__ . "/../includes/footer.php";
?>


