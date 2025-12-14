<?php
   include __DIR__. "/includes/header.php";
?>

<style>
.tables-container {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    margin-top: 2rem;
}

.table-box {
    flex: 1;
    min-width: 300px;
}
table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    overflow: hidden;
}

thead tr {
    background-color: #6366f1;
    color: white;
    text-align: left;
    font-weight: 600;
    font-size: 1rem;
}

tbody tr:nth-child(even) {
    background-color: #f3f4f6;
}

tbody tr:hover {
    background-color: #e0e7ff;
}

th, td {
    padding: 12px 15px;
    border-bottom: 1px solid #e5e7eb;
}

tbody tr:last-child td {
    border-bottom: none;
}
</style>

<div class="container-fluid">
 <div style="white-space: pre-line;">

   
   <div class="tables-container">
      <div class="table-box">
         <strong>가장 많이 접속한 IP</strong>

         <?php
            $logFile = __DIR__ . '/logs/access.log';
            $ipCounts = [];

            if (!file_exists($logFile)) {
               echo "<p>로그 파일이 존재하지 않습니다.</p>";
            } else {
               $handle = fopen($logFile, "r");
               $lines = [];
               if ($handle) {
                  while (($line = fgets($handle)) !== false) {
                     $lines[] = $line;
                     if (preg_match('/^(\d{1,3}(?:\.\d{1,3}){3})/', $line, $matches)) {
                        $ip = $matches[1];
                        if (!isset($ipCounts[$ip])) {
                           $ipCounts[$ip] = 0;
                        }
                        $ipCounts[$ip]++;
                     }
                  }
                  fclose($handle);

                  arsort($ipCounts);
                  $topIPs = array_slice($ipCounts, 0, 5, true);

                  echo "<table>";
                  echo "<thead><tr><th>IP 주소</th><th>접속 횟수</th></tr></thead><tbody>";
                  foreach ($topIPs as $ip => $count) {
                     echo "<tr><td>" . htmlspecialchars($ip) . "</td><td>" . htmlspecialchars($count) . "</td></tr>";
                  }
                  echo "</tbody></table>";

               } else {
                  echo "<p>로그 파일을 열 수 없습니다.</p>";
               }
            }
         ?>
      </div>

      <div class="table-box">
         <strong>최근 접속 IP</strong>

         <?php
            if (isset($lines)) {
               $recentIPs = [];
               $recentData = []; // IP와 시간 저장

               for ($i = count($lines) - 1; $i >= 0 && count($recentIPs) < 5; $i--) {
                  // IP + 시간 추출 정규표현식 (Apache 로그 형식 가정)
                  if (preg_match('/^(\d{1,3}(?:\.\d{1,3}){3}) .*?\[(.*?)\]/', $lines[$i], $matches)) {
                     $ip = $matches[1];
                     $time = $matches[2]; // 시간 문자열

                     if (!in_array($ip, $recentIPs)) {
                        $recentIPs[] = $ip;
                        $recentData[$ip] = $time;
                     }
                  }
               }
               #$recentIPs = array_reverse($recentIPs);

               echo "<table>";
               echo "<thead><tr><th>IP 주소</th><th>접속 시간</th></tr></thead><tbody>";
               foreach ($recentIPs as $ip) {
                  echo "<tr><td>" . htmlspecialchars($ip) . "</td><td>" . htmlspecialchars($recentData[$ip]) . "</td></tr>";
               }
               echo "</tbody></table>";
            }
         ?>
      </div>
   </div>
   
 </div>
</div>

<?php
   include __DIR__. "/includes/footer.php";
?>
