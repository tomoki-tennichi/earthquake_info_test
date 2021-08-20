<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="/css/styles.css">
    <title>地震情報 - テスト</title>
</head>
<body>

    <header>
        <h1>地震情報 - テスト</h1>
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
            $url_eq = "http://www.data.jma.go.jp/developer/xml/feed/eqvol.xml";    // データ取得先(地震火山: 高頻度)
            /* 
             * 取得したデータ内の、地震に関する情報の<title>
             * 取得したデータ内には火山の情報も含まれている為、取得する項目を指定する
             */
            $title_eq = "震源・震度に関する情報";
            $title_rain = "気象特別警報・警報・注意報";
            $observatory = "大阪管区気象台";    // 取得したいデータを観測している気象台
            $xml_eq = simplexml_load_file($url_eq);
        ?>

        <!-- 地震情報 -->
        <h1><?php echo $title_eq; ?></h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">震源地</th>
                    <th scope="col">都道府県</th>
                    <th scope="col">最大震度</th>
                    <th scope="col">市町村</th>
                    <th scope="col">震度</th>
                    <th scope="col">マグニチュード</th>
                    <th scope="col">メッセージ</th>
                </tr>
            </thead>
            <tbody>
            <?php
            /* リスト、各<entry>下の<title> 表示 */
            $count_eq = 1;
            foreach ($xml_eq->entry as $entry) {
                if ($entry->title == $title_eq) {
                    /* リンクから、各震源・震度に関する情報 の細部の情報を取得 */
                    $xml_child = simplexml_load_file($entry->link['href']);
                    $observation = $xml_child->Body->Intensity->Observation;
                    $eq_data = $xml_child->Body->Earthquake;
                    /* 各都道府県 */
                    foreach ($observation->Pref as $pref) {
                        /* 各地域 */
                        foreach ($pref->Area as $area) {
                            /* 各市町村 */
                            foreach ($area->City as $city) {
                                echo '<tr>';
                                /* # */
                                echo '<th scope="row">' . $count_eq++ . '</th>';
                                /* 震源地 */
                                echo '<td>' . $eq_data->Hypocenter->Area->Name . '</td>';
                                /* 都道府県 */
                                echo '<td>' . $pref->Name . '</td>';
                                /* 最大震度 */
                                echo '<td>' . $pref->MaxInt . '</td>';
                                /* 市町村 */
                                echo '<td>' . $city->Name . '</td>';
                                /* 当該市町村の最大震度 */
                                echo '<td>' . $city->MaxInt . '</td>';
                                /* マグニチュード */
                                echo '<td>' . $eq_data->children('jmx_eb', true)->Magnitude . '</td>';
                                /* メッセージ */
                                echo '<td>' . $xml_child->Head->Headline->Text . '</td>';
                                echo '</tr>';
                            }
                        }
                    }
                }
            }
            ?>
            </tbody>
        </table>
    </main>
</body>
</html>
