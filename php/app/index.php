<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="/css/styles.css">
    <title>地震速報 - テスト</title>
</head>
<body>

    <header>
        <h1>地震速報 - テスト</h1>
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
    <main>

        <?php
            $url = "http://www.data.jma.go.jp/developer/xml/feed/eqvol.xml";    // データ取得先
            /* 
             * 取得したデータ内の、地震に関する情報の<title>
             * 取得したデータ内には火山の情報も含まれている為、取得する項目を指定する
             */
            $target_subject = "震源・震度に関する情報";
            $xml_data = simplexml_load_file($url);
            // var_dump($xml_data);
        ?>
        <!-- リスト、各<entry>下の<title> 表示 -->
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">情報の種類</th>
                    <th scope="col">都道府県</th>
                    <th scope="col">震源地</th>
                    <th scope="col">マグニチュード</th>
                    <th scope="col">メッセージ</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $count = 1;
            foreach($xml_data->entry as $entry) {
                if ($entry->title == $target_subject) {
                    echo '<tr>';
                    echo '<th scope="row">' . $count++ . '</th>';
                    /* 情報の種類 */
                    echo '<td>' . $entry->title . ': </td>';

                    /* リンクから、各震源・震度に関する情報 の細部の情報を取得 */
                    $xml_child = simplexml_load_file($entry->link['href']);

                    /* 都道府県 */
                    echo '<td>' . $xml_child->Body->Intensity->Observation->Pref->Name . '</td>';
                    /* 震源地 */
                    echo '<td>' . $xml_child->Body->Earthquake->Hypocenter->Area->Name . '</td>';
                    /* マグニチュード */
                    echo '<td>' . $xml_child->Body->Earthquake->children('jmx_eb', true)->Magnitude . '</td>';
                    /* メッセージ */
                    echo '<td>' . $xml_child->Head->Headline->Text . '</td>';
                    echo '</tr>';
                }
            }
            ?>
            </tbody>
        </table>

    </main>
</body>
</html>