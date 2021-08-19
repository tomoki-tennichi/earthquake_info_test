<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="/css/styles.css">
    <title>気象警報・注意報 - テスト</title>
</head>
<body>

    <header>
        <h1>気象警報・注意報 - テスト</h1>
    </header>

    <!-- メニュー -->
    <nav class="menu">
        <ul class="nav_list_container">
            <li><a href="">nav 00</a></li>
            <li><a href="">nav 01</a></li>
            <li><a href="">nav 02</a></li>
        </ul>
    </nav>

    <!-- メイン -->
    <main class="p-3">

        <?php
            $url_eq = "http://www.data.jma.go.jp/developer/xml/feed/eqvol.xml";    // データ取得先(随時: 地震火山)
            $url_rain = "http://www.data.jma.go.jp/developer/xml/feed/extra.xml";  // データ取得先(随時: 高頻度)
            /* 
             * 取得したデータ内の、地震に関する情報の<title>
             * 取得したデータ内には火山の情報も含まれている為、取得する項目を指定する
             */
            $title_eq = "震源・震度に関する情報";
            $title_rain = "気象特別警報・警報・注意報";
            $observatory = "大阪管区気象台";    // 取得したいデータを観測している気象台
            $xml_eq = simplexml_load_file($url_eq);
            $xml_rain = simplexml_load_file($url_rain);
            // var_dump($xml_eq); 
        ?>

        <!-- 地震情報 -->
        <h1><?php echo $title_eq; ?></h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <!-- <th scope="col">情報の種類</th> -->
                    <th scope="col">震源地</th>
                    <th scope="col">都道府県</th>
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
                                /* 情報の種類 */
                                // echo '<td>' . $entry->title . '</td>';
                                /* 震源地 */
                                echo '<td>' . $eq_data->Hypocenter->Area->Name . '</td>';
                                /* 都道府県 */
                                echo '<td>' . $pref->Name . '</td>';
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

        <!-- 気象特別警報・警報・注意報 -->
        <h1><?php echo $title_rain; ?></h1>
        <!-- リスト -->
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <!-- <th scope="col">情報の種類</th> -->
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
                    // $body_rain = $xml_rain_child->Body;
                    $pref_rain = $headline->Information[0]->Item[0]->Areas->Area->Name; // 都道府県
                    // $headline->Text;
                    foreach ($xml_rain_child->Body->Warning[3]->Item as $item) {
                        $city_name = $item->Area->Name; // 市町村
                        // $changing_status = $item->ChangingStatus;
                        foreach ($item->Kind as $kind) {

                            echo '<tr>';
                            /* # */
                            echo '<th scope="row">' . $count_rain++ . '</th>';
                            /* 情報の種類 */
                            // echo '<td scope="row">' . $title_rain . '</td>';
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
                            // echo '<td scope="row">' . $pref_msg . '</td>';
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