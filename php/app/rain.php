<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="/css/styles.css">
    <title>気象注意情報 - テスト</title>
</head>
<body>

    <header>
        <h1>気象注意情報 - テスト</h1>
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
            $url_rain = "http://www.data.jma.go.jp/developer/xml/feed/extra.xml";  // データ取得先(随時: 高頻度)
            /* 
             * 取得したデータ内の、地震に関する情報の<title>
             * 取得したデータ内には火山の情報も含まれている為、取得する項目を指定する
             */
            $title_eq = "震源・震度に関する情報";
            $title_rain = "気象特別警報・警報・注意報";
            $observatory = "大阪管区気象台";    // 取得したいデータを観測している気象台
            $xml_rain = simplexml_load_file($url_rain);
        ?>

        <!-- 気象特別警報・警報・注意報 -->
        <h1><?php echo $title_rain; ?></h1>
        <!-- リスト -->
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">都道府県</th>
                    <th scope="col">市町村</th>
                    <th scope="col">警報・注意報</th>
                    <th scope="col">相当レベル</th>
                    <th scope="col">状態</th>
                    <th scope="col">都道府県メッセージ</th>
                    <th scope="col">注意</th>
                </tr>
            </thead>
            <tbody>
            <?php
            /* リスト、各<entry>下の<title> 表示 */
            $count_rain = 1;
            foreach($xml_rain->entry as $ent_rain) {

                $pref_msg = $ent_rain->content; // 都道府県メッセージ

                if ($ent_rain->title == $title_rain && $ent_rain->author->name == $observatory) {
                    /* リンクから、気象に関する情報 の細部の情報を取得 */
                    $xml_rain_child = simplexml_load_file($ent_rain->link['href']);
                    $headline = $xml_rain_child->Head->Headline;
                    $pref_rain = $headline->Information[0]->Item[0]->Areas->Area->Name; // 都道府県

                    foreach ($xml_rain_child->Body->Warning[3]->Item as $item) {
                        $city_name = $item->Area->Name; // 市町村
                        foreach ($item->Kind as $kind) {
                            echo '<tr>';
                            /* # */
                            echo '<th scope="row">' . $count_rain++ . '</th>';
                            /* 都道府県 */
                            echo '<td scope="row">' . $pref_rain . '</td>';
                            /* 市町村 */
                            echo '<td scope="row">' . $city_name . '</td>';
                            /* 警報・注意報 */
                            echo '<td scope="row">' . $kind->Name . '</td>';
                            /* 相当レベル */
                            switch ($kind->Name) {
                                case "大雨注意報": 
                                    echo '<td scope="row">レベル２</td>';
                                    break;
                                case "雷注意報": 
                                    echo '<td scope="row">レベル２</td>';
                                    break;
                                case "大雨警報": 
                                    echo '<td scope="row">レベル３</td>';
                                    break;
                                case "大雨特別警報": 
                                    echo '<td scope="row">レベル５</td>';
                                    break;
                                default:
                                    echo '<td scope="row"></td>';
                                    break;
                            }
                            /* 状態 */
                            echo '<td scope="row">' . $kind->Status . '</td>';
                            /* 都道府県メッセージ */
                            echo '<td scope="row">' . $headline->Text . '</td>';
                            /* 注意 */
                            echo '<td scope="row">';
                            if (isset($kind->Attention)) {
                                foreach ($kind->Attention->Note as $note) {
                                    echo $note . ' ';
                                }
                            }
                            echo '</td>';

                            echo '</tr>';
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
