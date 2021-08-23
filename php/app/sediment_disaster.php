<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="/css/styles.css">
    <title>土砂災害情報 - テスト</title>
</head>
<body>

    <header>
        <h1>土砂災害情報 - テスト</h1>
    </header>

    <!-- メニュー -->
    <nav class="menu">
        <ul class="nav_list_container">
            <li><a href="/">地震</a></li>
            <li><a href="/rain.php">大雨</a></li>
            <li><a href="/sediment_disaster.php">土砂災害</a></li>
            <li><a href="/tornado.php">竜巻</a></li>
        </ul>
    </nav>

    <!-- メイン -->
    <main class="p-3">

        <?php
            $url_weather = "http://www.data.jma.go.jp/developer/xml/feed/extra.xml";  // データ取得先(随時: 高頻度)
            $target_title = "土砂災害警戒情報";
            // 取得したいデータを観測している気象台
            $xml_weather = simplexml_load_file($url_weather);
        ?>

        <!-- 土砂災害情報 -->
        <h1><?php echo $target_title; ?></h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">都道府県</th>
                    <th scope="col">市町村</th>
                    <th scope="col">程度</th>
                    <th scope="col">状態</th>
                    <th scope="col">時刻</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $count = 1;
                foreach ($xml_weather->entry as $ent) {
                    if ($ent->title == $target_title) {
                        
                        $xml_child = simplexml_load_file($ent->link['href']);

                        $body = $xml_child->Body;
                        $time = $xml_child->Head->ReportDateTime;   // 時刻
                        $pref_name =  $body->TargetArea->Name;      // 都道府県

                        foreach ($body->Warning->Item as $item) {
                            echo '<tr>';
                            /* # */
                            echo '<td>' . $count++ . '</td>';
                            /* 都道府県 */
                            echo '<td>' . $pref_name . '</td>';
                            /* 市町村 */
                            echo '<td>' . $item->Area->Name . '</td>';
                            /* 程度 */
                            echo '<td>' . $item->Kind->Name . '</td>';
                            /* 状態 */
                            echo '<td>' . $item->Kind->Status . '</td>';
                            /* 時刻 */
                            echo '<td>' . $time . '</td>';
                            echo '</tr>';
                        }
                    }
                }
            ?>
            </tbody>
        </table>
    </main>
</body>
</html>
