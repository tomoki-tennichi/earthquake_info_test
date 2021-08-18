<?php
    // namespace Libraries;
    // require_once "./vendor/autoload.php";
    // require_once "./Libraries/Feed.php";

    /* 他のXMLファイルにアクセスするAPI */
    // https://www.php.net/manual/ja/simplexml.examples-basic.php
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <title>Manager_Login</title>
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
            // $inst = new Feed;
            $url = "http://www.data.jma.go.jp/developer/xml/feed/eqvol.xml";
            // $atom = $inst->loadAtom($url);              // 上記URL内側、<feed> を指している
            $target_subject = "震源・震度に関する情報";     // 
            // var_dump($atom);
            // var_dump($atom->title);
            $data
        ?>
        <!-- リスト、各<entry>下の<title> 表示 -->
        <ul>
        <?php
        
            // foreach($atom->entry as $entry) {
            //     if ($entry->title == $target_subject) {
            //         echo '<div>';
            //         echo '<span>' . $entry->title . ': </span>';

            //         if (isset($entry->link)) {
            //             echo '<a>' . $entry->link['href'] . '</a>';
            //             echo '[D00]';
            //             $inst_child = new Feed;
            //             $url_child = $entry->link['href'];
            //             $atom_child = $inst_child->loadAtom($url_child);
            //             echo $atom_child->Headline->Text;
            //             echo '[D01]';
            //         }
                    
            //         echo '</div>';
            //     }
            // }
        ?>
        </ul>

        <ul>
            <li>test 00</li>
            <li>test 01</li>
            <li>test 02</li>
            <li>test 03</li>
            <li>test 04</li>
        </ul>
    </main>
    
</body>
</html>