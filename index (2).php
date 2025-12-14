<?php
// 세션 보안 강화
session_start();
// 세션 고정 공격 방지
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// 안전하게 include (경로는 절대 경로 권장)
include __DIR__ . '/../includes/header.php';

$titles = include __DIR__ . '/../includes/titles.php';

// 검색어 길이 제한 (최대 50자)
$search = substr($_GET['q'] ?? '', 0, 50);

// 페이지 번호 검증
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$perPage = 10;
$offset = ($page - 1) * $perPage;

// 검색할 파일 목록 제한 (현재 폴더 내 PHP 파일 중 제외 목록 포함)
$excludeFiles = ['header.php', 'footer.php', basename(__FILE__)];
$allFiles = array_filter(glob("*.php"), function($f) use ($excludeFiles) {
    return !in_array($f, $excludeFiles);
});
sort($allFiles);

$totalPages = ceil(count($allFiles) / $perPage);
$results = [];

// PHP 코드 및 HTML 태그를 모두 제거하는 함수
function remove_php_and_html_tags($text) {
    // 1. PHP 태그 및 코드 제거
    $noPhp = preg_replace('/<\?(?:php)?[\s\S]*?\?>/i', '', $text);
    // 2. HTML 및 주석 태그 제거
    return strip_tags($noPhp);
}

// 검색어 하이라이트 (출력 시 htmlspecialchars 포함)
function highlight($text, $search) {
    if ($search === '') return htmlspecialchars($text);
    return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark>$1</mark>', htmlspecialchars($text));
}
?>

<style>
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

<div class="container-fluid px-4 py-4">
    <h1 class="mb-4">내용 검색</h1>

    <form method="get" class="mb-4" autocomplete="off" novalidate>
        <div class="input-group" style="max-width: 500px; position: relative;">
            <input type="text" id="searchInput" name="q" class="form-control" placeholder="검색어 입력" maxlength="50" value="<?= htmlspecialchars($search, ENT_QUOTES) ?>" />
            <button type="button" id="clearBtn" class="btn btn-outline-secondary" style="display: none; position: absolute; right: 70px; top: 50%; transform: translateY(-50%); padding: 0 8px; font-size: 18px; line-height: 1; border: none; background: transparent; color: #999; cursor: pointer;">✕</button>
            <button type="submit" class="btn btn-primary" style="margin-left: 5px;">검색</button>
        </div>
    </form>

    <?php if ($search !== ''): ?>
        <h4 class="mb-3">"<?= htmlspecialchars($search, ENT_QUOTES) ?>" 검색 결과</h4>

        <?php
        // 검색 수행
        foreach ($allFiles as $file) {
            // 파일 내용을 한 번에 읽기
            $content = @file_get_contents($file);
            if ($content === false) continue; // 파일 읽기 실패 시 무시

            // PHP 코드와 HTML 태그를 모두 제거
            $textOnly = remove_php_and_html_tags($content);

            // 줄 단위로 분리
            $lines = explode("\n", $textOnly);

            $matches = [];
            foreach ($lines as $num => $line) {
                // 공백과 줄바꿈을 제거한 후 검색 (더 정확한 검색을 위해)
                $trimmedLine = trim($line);
                if ($trimmedLine !== '' && stripos($trimmedLine, $search) !== false) {
                    $matches[] = [
                        'line' => $num + 1,
                        'content' => $trimmedLine
                    ];
                }
            }

            if (!empty($matches)) {
                $results[] = [
                    'file' => $file,
                    'matches' => $matches
                ];
            }
        }
        ?>

        <?php if ($results): ?>
            <?php foreach ($results as $result): ?>
                <div class="card mb-3">
                    <div class="card-header bg-light fw-bold">
                        📄 
                        <a href="<?= htmlspecialchars($result['file'], ENT_QUOTES) ?>" target="_blank" rel="noopener noreferrer" class="custom-link">
                            <?= htmlspecialchars($titles[$result['file']] ?? $result['file'], ENT_QUOTES) ?>
                        </a>
                        <small class="text-muted float-end"><?= htmlspecialchars($result['file'], ENT_QUOTES) ?></small>
                    </div>
                    <div class="card-body">
    <?php foreach ($result['matches'] as $match): ?>
        <pre class="mb-2"><code><strong><?= $match['line'] ?>:</strong> <?= highlight(html_entity_decode($match['content'], ENT_QUOTES | ENT_HTML5), $search) ?></code></pre>
    <?php endforeach; ?>

</code></pre>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning">일치하는 결과가 없습니다.</div>
        <?php endif; ?>

    <?php else: ?>
        <h4 class="mb-3">📃목록 (<?= count($allFiles) ?>개 중 <?= $offset + 1 ?>~<?= min($offset + $perPage, count($allFiles)) ?>)</h4>
        <ul class="list-group" style="max-width: 600px;">
<?php
$currentFiles = array_slice($allFiles, $offset, $perPage);
foreach ($currentFiles as $file):
    $title = $titles[$file] ?? $file;

    // 날짜 및 노트번호 추출 (예: 20250716021.php)
    $dateStr = '';
    $noteNumber = '';
    if (preg_match('/^(\d{8})(\d{3})\.php$/', $file, $matches)) {
        $rawDate = $matches[1]; // 20250716
        $noteNumber = $matches[2]; // 021
        // 날짜 형식으로 변환
        $dateStr = date('Y-m-d', strtotime($rawDate));
    }
?>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <div>
            <a href="<?= htmlspecialchars($file, ENT_QUOTES) ?>" target="_blank" rel="noopener noreferrer" class="custom-link">
                <?= htmlspecialchars($title, ENT_QUOTES) ?>
            </a><br>
            <small class="text-muted">
                <?= $dateStr ? "🗓️ {$dateStr}" : '' ?>
                <?= $noteNumber ? " | 📌 {$noteNumber}번째 노트" : '' ?>
            </small>
        </div>
    </li>
<?php endforeach; ?>
</ul>

        <nav class="mt-4">
            <ul class="pagination">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>">← 이전</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>">다음 →</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearBtn');

    function toggleClearBtn() {
        if (searchInput.value.length > 0) {
            clearBtn.style.display = 'inline-block';
        } else {
            clearBtn.style.display = 'none';
        }
    }

    toggleClearBtn();

    searchInput.addEventListener('input', toggleClearBtn);

    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        toggleClearBtn();
        searchInput.focus();
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>