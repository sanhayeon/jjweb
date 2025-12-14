<?php
   include __DIR__ . "/../includes/header.php";   
?>

<div class="container mt-3">
  <h2>로그인</h2>
  <form action="/auth/auth.php" method="POST">
  <input type="hidden" name="mode" value="login">
  
    <div class="mb-3 mt-3">
      <label for="username">아이디</label>
      <input type="text" class="form-control" id="username" placeholder="아이디" name="username">
    </div>

    <div class="mb-3">
      <label for="password">비밀번호</label>
      <input type="text" class="form-control" id="pasword" placeholder="비밀번호" name="password">
    </div>

    <div class="form-check mb-3">
      <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="remember"> Remember me
      </label>
    </div>
    <button type="submit" class="btn btn-primary" name="submit">로그인</button>
  </form>
	네이버 간편 로그인 
</div>

<?php
   include __DIR__ . "/../includes/footer.php";
?>
