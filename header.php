<?php
   session_start();
   #print_r($_SESSION);
   $_SESSION[ 'session_token' ] = md5(uniqid());
   #echo $_SESSION[ 'session_token' ];
   $checkIP = $_SERVER['REMOTE_ADDR'];

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>kelly can</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="/static/assets/favicon.ico" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="/static/css/styles.css" rel="stylesheet" />
		<style>
pre code {
  display: block;
  font-family: 'Fira Code', Consolas, Monaco, 'Courier New', monospace;
  font-size: 14px;
  line-height: 1.5;
  background-color: #f0f0f0;
  color: #333;
  padding: 15px;
  border-radius: 6px;
  overflow-x: auto;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  white-space: pre-wrap; /* 필요에 따라 줄바꿈 자동 */
  word-break: break-word;
}
.custom-link {
  color: #007bff;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s ease-in-out;
}
.custom-link:hover, .custom-link:focus {
  color: #0056b3;
  text-decoration: underline;
  outline: none;
}
</style>

<!-- Google Fonts에서 Fira Code 폰트 불러오기 -->
<link href="https://fonts.googleapis.com/css2?family=Fira+Code&display=swap" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/prismjs@1/themes/prism.css" rel="stylesheet" />
    </head>
    <body>
        <div class="d-flex" id="wrapper">
            <!-- Sidebar-->
            <div class="border-end bg-white" id="sidebar-wrapper">
                <div class="sidebar-heading border-bottom bg-light"><a class="list-group-item-action list-group-item"href="/">나는 보안인이다.</a></div>
                <div class="list-group list-group-flush">
				
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/intro/profile.php">소개</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/study/">노트</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/tech/">기술문서</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/packet/">패킷점검</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/server/">서버점검</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/syslog/">syslog</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/forensic/">포렌식</a>

               <?php
                  if (preg_match('/^192\.168\.0\.\d+$/', $checkIP)) {
               ?>
               <a class="list-group-item list-group-item-action list-group-item-light p-3" href="http://backup.kelly23.kr">백업(내부용)</a>
               
			   <?php
               } else {
               ?>

               <a class="list-group-item list-group-item-action list-group-item-light p-3" href="http://kelly23.kr:8097">Elasticsearch(외부용)</a>
               <a class="list-group-item list-group-item-action list-group-item-light p-3" href="http://backup.kelly23.kr:8095">백업(외부용)</a>
                    
               <?php
               }
               ?>

               <?php
                  #if(isset($_COOKIE["cookie"])) {
                  if(isset($_SESSION["session"])) {
               ?>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/logs/">로그</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/status/">상태</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/upload/">업로드</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/search/">검색</a>

                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/check/">보안설정</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="http://backup.kelly23.kr:8095">백업(외부용)</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="http://backup.kelly23.kr">백업(내부용)</a>
               <?php
                  }
               ?>

                </div>
            </div>
            <!-- Page content wrapper-->
            <div id="page-content-wrapper">
                <!-- Top navigation-->
                <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                    <div class="container-fluid">
                        <button class="btn btn-primary" id="sidebarToggle">화면전환</button>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                                <li class="nav-item active"><a class="nav-link" href="/">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="/board/">질문1</a></li><li class="nav-item"><a class="nav-link" href="/board/index2.php">질문2</a></li><li class="nav-item"><a class="nav-link" href="/board/index3.php">질문3</a></li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">관리</a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                        <a class="dropdown-item" href="/auth/login.php">로그인</a>

                                        <?php
                                        #if($_COOKIE["cookie"]) {
				                        if(!isset($_SESSION["session"])){
                                        ?>
                                        <a class="dropdown-item" href="/auth/signup.php">회원가입</a>
                                        <?php
                                             }
                                        ?>
                                        <div class="dropdown-divider"></div>
               <?php
                  #if($_COOKIE["cookie"]) {
				  if(isset($_SESSION["session"])){
               ?>
                           

                                        <a class="dropdown-item" href="/auth/mypage.php">마이페이지</a>
                                        <a class="dropdown-item" href="/auth/Auth.php?mode=withdraw">회원탈퇴</a>
                              <a class="dropdown-item" href="/auth/auth.php?mode=logout">로그아웃</a>
               <?php
                  }
               ?>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>